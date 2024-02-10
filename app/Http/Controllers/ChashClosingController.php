<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Payment;

class ChashClosingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function year()
    {
        $contributions = Contribution::selectRaw('YEAR(date) AS year, SUM(amount) AS amount, SUM(must) AS must')
            ->groupBy('year')
            ->where('state', 'activo')
            ->orderBy('year', 'desc')->get();

        $payments = Payment::selectRaw('YEAR(date) AS year, SUM(interest_amount) AS amount, SUM(must) AS must')
            ->groupBy('year')
            ->where('state', 'activo')
            ->orderBy('year', 'desc')->get();

        // Determinar los minimos y maximos de las ultimas posiciones
        $max = $contributions[0]->year > $payments[0]->year ? $contributions[0]->year : $payments[0]->year;
        $lastCon = $contributions->count() - 1;
        $lastPay = $payments->count() - 1;
        $min = $contributions[$lastCon]->year < $payments[$lastPay]->year ? $contributions[$lastCon]->year : $payments[$lastPay]->year;

        $contributions = json_decode(json_encode($contributions));
        $payments = json_decode(json_encode($payments));

        $data = [];

        do {
            $keyCon = array_search($max, array_column($contributions, 'year'));
            $con = $keyCon !== false ? $contributions[$keyCon] : null;

            $keyPay = array_search($max, array_column($payments, 'year'));
            $pay = $keyPay !== false ? $payments[$keyPay] : null;

            array_push(
                $data,
                [
                    'year' => $max,
                    'contribution' => $con !== null ? $con->amount : 0,
                    'interest' => $pay !== null ? $pay->amount : 0,
                    'must' => ($con !== null ? $con->must : 0) + ($pay !== null ? $pay->must : 0),
                ]
            );

            $max--;
        } while ($max >= $min);

        $data = json_decode(json_encode($data));

        return view('reports.year', compact('data'));
    }

    public function month($year)
    {
        $contributions = Contribution::selectRaw('MONTH(date) AS month, SUM(amount) AS amount, SUM(must) AS must')
            ->groupBy('month')
            ->where('state', 'activo')
            ->whereYear('date', $year)
            ->orderBy('month', 'desc')->get();

        $payments = Payment::selectRaw('MONTH(date) AS month, SUM(interest_amount) AS amount, SUM(must) AS must')
            ->groupBy('month')
            ->where('state', 'activo')
            ->whereYear('date', $year)
            ->orderBy('month', 'desc')->get();

        // Determinar los minimos y maximos de las ultimas posiciones
        $max = $contributions[0]->month > $payments[0]->month ? $contributions[0]->month : $payments[0]->month;
        $lastCon = $contributions->count() - 1;
        $lastPay = $payments->count() - 1;
        $min = $contributions[$lastCon]->month < $payments[$lastPay]->month ? $contributions[$lastCon]->month : $payments[$lastPay]->month;

        $contributions = json_decode(json_encode($contributions));
        $payments = json_decode(json_encode($payments));

        $data = [];

        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        do {

            $keyCon = array_search($max, array_column($contributions, 'month'));
            $keyPay = array_search($max, array_column($payments, 'month'));

            if ($keyCon !== false || $keyPay !== false) {

                $con = $keyCon !== false ? $contributions[$keyCon] : null;
                $pay = $keyPay !== false ? $payments[$keyPay] : null;

                array_push(
                    $data,
                    [
                        'month' => $months[$max - 1],
                        'contribution' => $con !== null ? $con->amount : 0,
                        'interest' => $pay !== null ? $pay->amount : 0,
                        'must' => ($con !== null ? $con->must : 0) + ($pay !== null ? $pay->must : 0),
                    ]
                );
            }

            $max--;
        } while ($max >= $min);

        $data = json_decode(json_encode($data));

        return view('reports.month', compact('data', 'year'));
    }
}
