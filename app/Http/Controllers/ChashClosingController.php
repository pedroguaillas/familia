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

        return view('reports.year', compact('contributions', 'payments'));
    }

    public function month($year)
    {
        $contributions = Contribution::selectRaw('MONTH(date) AS month, SUM(amount) AS amount, SUM(must) AS must')
            ->groupBy('month')
            ->where('state', 'activo')
            ->whereYear('date', $year)
            ->get();

        $payments = Payment::selectRaw('MONTH(date) AS month, SUM(interest_amount) AS amount, SUM(must) AS must')
            ->groupBy('month')
            ->where('state', 'activo')
            ->whereYear('date', $year)
            ->get();

        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        return view('reports.month', compact('contributions', 'payments', 'year', 'months'));
    }
}
