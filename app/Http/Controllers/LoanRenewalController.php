<?php

namespace App\Http\Controllers;

use App\Loan;
use App\LoanRenewal;
use App\Person;
use Illuminate\Http\Request;

class LoanRenewalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2(Loan $loan)
    {
        //Se requiere de person para mostrar el nombre en la cabecera
        $person = $loan->person;
        //Se requiere de guarantor para mostrar el nombre en la cabecera
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();

        $loan_renewals = $loan->loan_renewals;

        return view('loan_renewals.index', compact('loan', 'person', 'guarantor', 'loan_renewals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LoanRenewal  $loanRenewal
     * @return \Illuminate\Http\Response
     */
    public function show(LoanRenewal $loanRenewal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LoanRenewal  $loanRenewal
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanRenewal $loanRenewal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LoanRenewal  $loanRenewal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanRenewal $loanRenewal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LoanRenewal  $loanRenewal
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanRenewal $loanRenewal)
    {
        //
    }
}
