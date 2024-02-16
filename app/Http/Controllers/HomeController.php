<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Loan;
use App\Payment;
use App\Person;
use App\Spend;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Cuenta la cantidad de socios que tiene la caja
        $countactions = Person::selectRaw('SUM(actions) AS sum_actions')
            // $countmembers = Person::select(DB::raw('COUNT(id) AS count'))
            ->groupBy('type')
            ->where([
                ['state', '=', 'activo'],
                ['type', '=', 'socio']
            ])->get()->first()->sum_actions;

        $contributions = null;
        $interest = 0;

        $this->querys($contributions, $interest);

        $total = $interest;

        foreach ($contributions as $c) {
            $total += $c->sum;
        }

        $spendCapital = Spend::selectRaw('SUM(amount) AS amount')
            ->where('impact', 'capital')
            ->first()->amount;

        // Reducir a los aportes los gastos de capital
        $total -= $spendCapital;

        $amounts_borrowed = Loan::selectRaw('loans.amount As total_borrowed, SUM(p.capital) AS total_returned')
            ->leftJoin('payments AS p', function ($query) {
                $query->on('loan_id', 'loans.id')
                    ->where('p.state', 'activo');
            })->where('loans.state', 'activo')
            ->groupBy('loans.id')->get();

        // Sumador de la deuda
        $total_borrowed = 0;
        // Contador de las personas que tienen prestamos
        $countdebtors = 0;

        foreach ($amounts_borrowed as $item) {
            // Condiciona que el monto devuelto este menor que el monto del prestamo
            if ($item->total_returned < $item->total_borrowed) {
                // La deuda es el monto del prestamo menos el monto devuelto
                $total_borrowed += $item->total_borrowed - $item->total_returned;
                $countdebtors++;
            }
        }
        $total = round($total / $countactions, 2);

        return view('start', compact('countactions', 'countdebtors', 'total', 'total_borrowed'));
        // return view('home');
    }

    public function manual()
    {
        $filename = 'app/manual.pdf';
        $path = storage_path($filename);

        return response()->make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    //Reporte general
    public function report()
    {
        $current_contributions = null;
        $current_interest = 0;

        $this->querys($current_contributions, $current_interest);

        $general_contributions = Contribution::selectRaw('type, SUM(amount + must) AS sum')
            ->groupBy('type')
            ->where('state', 'activo')
            ->orderBy('type', 'DESC')->get();

        $general_interest = Payment::selectRaw('SUM(interest_amount + must) AS sum')
            ->where('state', 'activo')->get()->first()->sum;

        // Gastos
        $spend = DB::table('spends')->selectRaw('SUM(amount) AS amount')
            ->where('impact', 'interés')
            ->first()->amount;

        $spendCapital = Spend::selectRaw('SUM(amount) AS amount')
            ->where('impact', 'capital')
            ->first()->amount;

        return response()->json([
            'current_contributions' => $current_contributions,
            'current_interest' => $current_interest,
            'general_contributions' => $general_contributions,
            'general_interest' => $general_interest - $spend,
            'spend_capital' => $spendCapital,
        ]);
    }

    //Reporte temporal
    public function reportcurrent($person_id)
    {
        $contributions = null;
        $interest = 0;

        $this->querys($contributions, $interest);

        $actions = Person::selectRaw('SUM(actions) AS sum')
            ->where('state', 'activo')->get()->first()->sum;

        $spendCapital = Spend::selectRaw('SUM(amount) AS amount')
            ->where('impact', 'capital')
            ->first()->amount;

        $amount_current = $contributions[0]->sum + $contributions[1]->sum + $interest - $spendCapital;

        $amount = $amount_current / $actions;

        $person = null;

        if ($person_id > 0) {
            $person = Person::find($person_id);
        }

        return response()->json([
            'amount' => round($amount, 2),
            'person' => $person,
            'person_actions' => $person !== null ? $person->actions : 0
        ]);
    }

    //Reporte temporal
    public function querys(&$contributions, &$interest)
    {
        $date = Carbon::now();
        $contributions = Contribution::selectRaw('type, SUM(amount + must) AS sum')
            ->groupBy('type')
            ->where([
                ['state', '=', 'activo'],
                ['date', '<=', $date->format('Y-m-d')]
            ])
            ->orderBy('type', 'DESC')->get();

        $interest = Payment::selectRaw('SUM(interest_amount + must) AS sum')
            ->where('state', 'activo')
            ->where([
                ['state', '=', 'activo'],
                ['date', '<=', $date->format('Y-m-d')]
            ])
            ->get()->first()->sum;

        // Reducir a los intereses los gastos pero solo activos
        $interest -= Spend::selectRaw('SUM(amount) AS amount')
            ->where('impact', 'interés')
            ->first()->amount;
    }
}
