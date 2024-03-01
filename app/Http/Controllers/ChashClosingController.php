<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Payment;
use App\Spend;

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

        $spendContributions = Spend::selectRaw('YEAR(date) AS year, SUM(amount) AS amount')
            ->groupBy('year')
            ->where('impact', 'capital')
            ->orderBy('year', 'desc')->get();

        $payments = Payment::selectRaw('YEAR(date) AS year, SUM(interest_amount) AS amount, SUM(must) AS must')
            ->groupBy('year')
            ->where('state', 'activo')
            ->orderBy('year', 'desc')->get();

        $spendInterests = Spend::selectRaw('YEAR(date) AS year, SUM(amount) AS amount')
            ->groupBy('year')
            ->where('impact', 'interés')
            ->orderBy('year', 'desc')->get();

        // Determinar los minimos y maximos de las ultimas posiciones
        $max = $contributions[0]->year > $payments[0]->year ? $contributions[0]->year : $payments[0]->year;
        $lastCon = $contributions->count() - 1;
        $lastPay = $payments->count() - 1;
        $min = $contributions[$lastCon]->year < $payments[$lastPay]->year ? $contributions[$lastCon]->year : $payments[$lastPay]->year;

        $contributions = json_decode(json_encode($contributions));
        $spendContributions = json_decode(json_encode($spendContributions));
        $payments = json_decode(json_encode($payments));
        $spendInterests = json_decode(json_encode($spendInterests));

        $data = [];

        do {
            $keyCon = array_search($max, array_column($contributions, 'year'));
            $con = $keyCon !== false ? $contributions[$keyCon] : null;

            $keySpendCon = array_search($max, array_column($spendContributions, 'year'));
            $conSpend = $keySpendCon !== false ? $spendContributions[$keySpendCon] : null;

            $keyPay = array_search($max, array_column($payments, 'year'));
            $pay = $keyPay !== false ? $payments[$keyPay] : null;

            $keySpendInt = array_search($max, array_column($spendInterests, 'year'));
            $paySpend = $keySpendInt !== false ? $spendInterests[$keySpendInt] : null;

            array_push(
                $data,
                [
                    'year' => $max,
                    'contribution' => ($con !== null ? $con->amount : 0) - ($conSpend !== null ? $conSpend->amount : 0),
                    'interest' => ($pay !== null ? $pay->amount : 0) - ($paySpend !== null ? $paySpend->amount : 0),
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

        $spendCons = Spend::selectRaw('MONTH(date) AS month, SUM(amount) AS amount')
            ->groupBy('month')
            ->where('impact', 'capital')
            ->whereYear('date', $year)
            ->orderBy('month', 'desc')->get();

        $payments = Payment::selectRaw('MONTH(date) AS month, SUM(interest_amount) AS amount, SUM(must) AS must')
            ->groupBy('month')
            ->where('state', 'activo')
            ->whereYear('date', $year)
            ->orderBy('month', 'desc')->get();

        $spendInts = Spend::selectRaw('MONTH(date) AS month, SUM(amount) AS amount')
            ->groupBy('month')
            ->where('impact', 'interés')
            ->whereYear('date', $year)
            ->orderBy('month', 'desc')->get();

        // Determinar los minimos y maximos de las ultimas posiciones
        $max = $contributions[0]->month > $payments[0]->month ? $contributions[0]->month : $payments[0]->month;
        $lastCon = $contributions->count() - 1;
        $lastPay = $payments->count() - 1;
        $min = $contributions[$lastCon]->month < $payments[$lastPay]->month ? $contributions[$lastCon]->month : $payments[$lastPay]->month;

        $contributions = json_decode(json_encode($contributions));
        $spendCons = json_decode(json_encode($spendCons));
        $payments = json_decode(json_encode($payments));
        $spendInts = json_decode(json_encode($spendInts));

        $data = [];

        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        do {

            $keyCon = array_search($max, array_column($contributions, 'month'));
            $keyConSpend = array_search($max, array_column($spendCons, 'month'));
            $keyPay = array_search($max, array_column($payments, 'month'));
            $keyPaySpend = array_search($max, array_column($spendInts, 'month'));

            if ($keyCon !== false || $keyPay !== false) {

                $con = $keyCon !== false ? $contributions[$keyCon] : null;
                $conSpend = $keyConSpend !== false ? $spendCons[$keyConSpend] : null;
                $pay = $keyPay !== false ? $payments[$keyPay] : null;
                $paySpend = $keyPaySpend !== false ? $spendInts[$keyPaySpend] : null;

                array_push(
                    $data,
                    [
                        'month' => $months[$max - 1],
                        'contribution' => ($con !== null ? $con->amount : 0) - ($conSpend !== null ? $conSpend->amount : 0),
                        'interest' => ($pay !== null ? $pay->amount : 0) - ($paySpend !== null ? $paySpend->amount : 0),
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
