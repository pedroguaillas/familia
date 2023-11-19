<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Person;

class LoanRenewalController extends Controller
{
    public function index2(Loan $loan)
    {
        //Se requiere de person para mostrar el nombre en la cabecera
        $person = $loan->person;
        //Se requiere de guarantor para mostrar el nombre en la cabecera
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();

        $loan_renewals = $loan->loan_renewals;

        return view('loan_renewals.index', compact('loan', 'person', 'guarantor', 'loan_renewals'));
    }
}
