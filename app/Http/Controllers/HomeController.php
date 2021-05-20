<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Payment;
use App\Person;
use Carbon\Carbon;
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
        $countactions = Person::select(DB::raw('SUM(actions) AS sum_actions'))
            // $countmembers = Person::select(DB::raw('COUNT(id) AS count'))
            ->groupBy('type')
            ->where([
                ['state', '=', 'activo'],
                ['type', '=', 'socio']
                // ])->get()->first();
            ])->get()->first()->sum_actions;
        // $countmembers = $person->count;
        // $actions = $person->sum_actions;
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

        $total = $interest;

        foreach ($contributions as $c) {
            $total += $c->sum;
        }

        // Selecciona los montos prestamos
        $sql = "SELECT l.amount as total_borrowed, ";
        // Suma los capitales devueltos si ese pago esta activo
        $sql .= "(SELECT SUM(p.capital) FROM payments AS p WHERE p.loan_id = l.id AND p.state = 'activo') AS total_returned ";
        // Selecciona todos los registros de prestamos
        $sql .= "FROM loans AS l ";
        // Condiciona que el prestamos este activo
        $sql .= "WHERE l.state = 'activo'";

        $amounts_borrowed = DB::select($sql);

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

    //Reporte general
    public function report()
    {
        $current_contributions = null;
        $current_interest = 0;

        $this->querys($current_contributions, $current_interest);

        $general_contributions = Contribution::select('type', DB::raw('SUM(amount) AS sum'))
            ->groupBy('type')
            ->where('state', 'activo')
            ->orderBy('type', 'DESC')->get();

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
            ->orderBy('type', 'DESC')->get();

        $interest = Payment::select(DB::raw('SUM(interest_amount) AS sum'))
            ->where('state', 'activo')
            // ->where([
            //     ['state', '=', 'activo'],
            //     ['date', '<', $date->format('Y-m-d')]
            // ])
            ->get()->first()->sum;
    }
}
