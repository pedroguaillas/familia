<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Loan;
use App\Payment;
use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Cuenta la cantidad de socios que tiene la caja
        $countmembers = Person::select(DB::raw('COUNT(id) AS count'))
            ->groupBy('type')
            ->where([
                ['state', '=', 'activo'],
                ['type', '=', 'socio']
            ])->get()->first()->count;

        // // Selecciona las persona que tienen creditos que aun no ha terminado de pagar
        // $people = Loan::join('payments AS p', 'p.loan_id', 'loans.id')
        //     ->select('person_id')
        //     // Agrupa por prestamos para seleccionar las prestamos que aun no ha terminado de pagar
        //     ->groupBy('loans.id', 'person_id')
        //     ->havingRaw('SUM(p.capital) <> SUM(loans.amount)')
        //     ->where('p.state', 'activo')->get();

        // $countdebtors = Person::select(DB::raw('COUNT(id) AS count'))
        //     ->whereIn('id', $people)->get()->first()->count;

        $contributions = null;
        $interest = 0;

        $this->querys($contributions, $interest);

        $total = $contributions[0]->sum + $contributions[1]->sum + $interest;

        $amounts_borrowed = Loan::join('payments AS p', 'p.loan_id', 'loans.id')
            ->select('loans.amount as total_borrowed')
            ->groupBy('loans.id', 'total_borrowed')
            ->havingRaw('SUM(p.capital) <> total_borrowed')
            ->where('p.state', 'activo')->get();

        $total_borrowed = 0;
        // El contador de la siguiente linea no siempre va se real
        // Siempre y cuando la persona tenga dos creditos pero eso nunca va ha darse
        $countdebtors = 0;

        foreach ($amounts_borrowed as $item) {
            $total_borrowed += $item->total_borrowed;
            $countdebtors++;
        }

        return view('start', compact('countmembers', 'countdebtors', 'total', 'total_borrowed'));
        // return view('home');
    }

    //Reporte general
    public function report()
    {

        $current_contributions = null;
        $current_interest = 0;

        $this->querys($current_contributions, $current_interest);

        $general_contributions = Contribution::select('type', DB::raw('SUM(amount) AS sum'))
            ->groupBy('type')
            ->where('state', 'activo')->get();

        $general_interest = Payment::select(DB::raw('SUM(interest_amount) AS sum'))
            ->where('state', 'activo')->get()->first()->sum;

        return response()->json([
            'current_contributions' => $current_contributions,
            'current_interest' => $current_interest,
            'general_contributions' => $general_contributions,
            'general_interest' => $general_interest
        ]);
    }

    //Reporte temporal
    public function reportcurrent($person_id)
    {
        $contributions = null;
        $interest = 0;

        $this->querys($contributions, $interest);

        $actions = Person::select(DB::raw('SUM(actions) AS sum'))
            ->where('state', 'activo')->get()->first()->sum;

        $amount_current = $contributions[0]->sum + $contributions[1]->sum + $interest;

        $amount = $amount_current / $actions;

        $person_actions = Person::findOrFail($person_id)->actions;

        return response()->json([
            'amount' => round($amount, 2),
            'person_actions' => $person_actions
        ]);
    }

    //Reporte temporal
    private function querys(&$contributions, &$interest)
    {
        $date = Carbon::now();
        $contributions = Contribution::select('type', DB::raw('SUM(amount) AS sum'))
            ->groupBy('type')
            ->where([
                ['state', '=', 'activo'],
                ['date', '<', $date->format('Y-m-d')]
            ])
            ->orderBy('type')->get();

        $interest = Payment::select(DB::raw('SUM(interest_amount) AS sum'))
            ->where([
                ['state', '=', 'activo'],
                ['date', '<', $date->format('Y-m-d')]
            ])->get()->first()->sum;
    }
}
