<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Payment;
use App\Person;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = DB::table('loanS')
            ->select(
                'loans.id',
                'loans.amount',
                'loans.interest_percentage',
                'loans.date',
                'people.first_name',
                'people.last_name',
                DB::raw('sum(payments.capital) as sum_capital_paid')
            )
            ->join('people', 'people.id', 'loans.person_id')
            ->leftJoin('payments', 'payments.loan_id', 'loans.id')
            ->groupBy('loans.id', 'loans.amount', 'loans.interest_percentage', 'loans.date', 'people.first_name', 'people.last_name')
            ->where('loans.state', 'activo')
            ->orderBy('loans.date')->get();

        $loans = json_decode(json_encode($loans), true);

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $people = Person::where('state', 'activo')->get();
        return view('loans.create', compact('people'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Loan::create($request->all());
        return redirect()->route('loans.index')->with('success', 'Se registro un nuevo prestamo');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        //
    }

    public function showPdf()
    {
        $pdf = PDF::loadView('loans.print_unit');

        return $pdf->stream('prestamo.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //Se requiere de person para mostrar el nombre en el formulario
        $person = Person::findOrFail($loan->person_id);
        //Se requiere de guarantor para mostrar el nombre del garante si existe
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();

        return view('loans.edit', compact('loan', 'person', 'guarantor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'introduction' => 'required',
        //     'location' => 'required',
        //     'cost' => 'required'
        // ]);
        $loan->update($request->all());

        return redirect()->route('loans.index')->with('success', 'Se actualizo un registro con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $loan->state = 'inactivo';
        $loan->save();

        $update = Payment::where('loan_id', $loan->id)
            ->update(['state' => 'inactivo']);

        return redirect()->route('loans.index')->with('danger', 'Se elimino un prestamo');
    }
}
