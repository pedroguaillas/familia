<?php

namespace App\Http\Controllers;

use App\Directive;
use App\Loan;
use App\Payment;
use App\Person;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LoanController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = \DB::table('loans')
            ->select(
                'loans.id',
                'loans.amount',
                'loans.interest_percentage',
                'loans.date',
                'people.first_name',
                'people.last_name',
                \DB::raw('sum(payments.capital) as sum_capital_paid')
            )
            ->join('people', 'people.id', 'loans.person_id')
            ->leftJoin('payments', 'payments.loan_id', 'loans.id')
            ->groupBy('loans.id', 'loans.amount', 'loans.interest_percentage', 'loans.date', 'people.first_name', 'people.last_name')
            ->where('loans.state', 'activo')
            ->orderBy('loans.date')->get();

        return view('loans.index', compact('loans'));
    }

    public function pdf()
    {
        $loans = \DB::table('loans')
            ->select(
                'loans.id',
                'loans.amount',
                'loans.interest_percentage',
                'loans.date',
                'people.first_name',
                'people.last_name',
                \DB::raw('sum(payments.capital) as sum_capital_paid')
            )
            ->join('people', 'people.id', 'loans.person_id')
            ->leftJoin('payments', 'payments.loan_id', 'loans.id')
            ->groupBy('loans.id', 'loans.amount', 'loans.interest_percentage', 'loans.date', 'people.first_name', 'people.last_name')
            ->where('loans.state', 'activo')
            ->orderBy('loans.date')->get();

        $loans = json_decode(json_encode($loans), true);

        $pdf = PDF::loadView('loans.report', compact('loans'));
        (new PdfController())->loadTempleate($pdf);

        return $pdf->stream('prestamos.pdf');
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
        return redirect()->route('loans.index')->with('success', 'Se registro un nuevo préstamo');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        //Usuda para mostrar renovacion de credito
        $person = $loan->person;

        $payments = $loan->payments;

        $loan->debt = 0;

        foreach ($payments as $item) {
            if ($item->state === 'activo') {
                $loan->debt += $item->capital;
            }
        }

        $today = Carbon::now();

        return response()->json(['loan' => $loan, 'person' => $person]);
    }

    public function solicitude(Loan $loan)
    {
        //Se requiere de guarantor para mostrar el nombre del garante si existe
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();

        // Presidente
        $directive = Directive::all()->first()->person;

        $pdf = PDF::loadView('loans.solicitude', compact('loan', 'guarantor', 'directive'));

        return $pdf->stream('solicitud_prestamo.pdf');
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

        return redirect()->route('loans.index')->with('success', 'Se actualizo un préstamo.');
    }

    public function renovation(Request $request, Loan $loan)
    {
        $old_loan_amount = $loan->amount;
        $loan->amount = $request->amount + $loan->amount;
        $loan->date = $request->date;
        $loan->interest_percentage = $request->interest_percentage;

        if ($loan->save()) {

            $loan->loan_renewals()->create([
                'amount' => $loan->amount,
                'interest_percentage' => $loan->interest_percentage,
                'date' => $loan->date
            ]);

            if ($request->interest_amount > 0) {

                $payments = $loan->payments;

                $old_debt = 0;

                foreach ($payments as $item) {
                    if ($item->state === 'activo') {
                        $old_debt += $item->capital;
                    }
                }

                $loan->payments()->create([
                    'debt' => $old_loan_amount - $old_debt,
                    'date' => $request->date,
                    'interest_amount' => $request->interest_amount
                ]);
            }
        }

        return redirect()->route('loans.index')->with('success', 'Se renovo el crédito de ' . $loan->person->first_name . ' ' . $loan->person->last_name);
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

        return redirect()->route('loans.index')->with('danger', 'Se elimino un préstamo');
    }
}
