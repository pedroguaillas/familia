<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class RenovationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Loan $loan)
    {
        $cantPagados = Payment::where([
            'loan_id' => $loan->id,
            'state' => 'activo'
        ])->count();

        $pagado = Payment::where([
            'loan_id' => $loan->id,
            'state' => 'activo'
        ])->sum('capital');

        return view('loans.renovacioncredito', compact('loan', 'cantPagados', 'pagado'));
    }

    public function update(Request $request, Loan $loan)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'amount' => 'min:0.01',
            'interest_percentage' => 'min:0.5',
            'period' => 'min:1',
        ], [
            'amount.required' => 'El monto debe ser mayor a 0',
            'interest_percentage.required' => 'El interés debe ser mayo a 0.05',
            'period.required' => 'El N° pagos minimo debe ser minimo 1',
        ]);

        if ($validator->fails()) {
            return redirect('loans/create')
                ->withErrors($validator)
                ->withInput();
        }

        // Eliminar los pagos inactivos
        Payment::where('state', 'inactivo')->delete();

        // Registrar nuevos pagos inactivos
        // Generar tabla de amortizacion
        // Registrar en tabla de pagos como inactivos
        $interes = $request->interest_percentage * 0.01;
        $newMount = $loan->amount + $request->amount - Payment::where('loan_id', $loan->id)->sum('capital');
        $deudainicial = $newMount;
        $interescal = $newMount * $interes;
        $capital = 0;
        $pago = 0;

        if ($request->method === 'variable') {
            $capital = round($newMount / $request->period, 2);
            // Ajuste de amortización variable
            if ($capital * $request->period < $newMount) {
                $capital += 0.01;
            }
            $pago = $interescal + $capital;
        } else {
            // Pago con dos decimales para convertirle en fijo durante todo el periodo
            $pago = round($interescal / (1 - pow(1 + $interes, -$request->period)), 2);
            $capital = $pago - $interescal;
        }

        $deudafinal = $deudainicial - $capital;

        $month = '';

        switch ($request->type) {
            case 'mensual':
                $month = 1;
                break;
            case 'trimestral':
                $month = 3;
                break;
            case 'semestral':
                $month = 6;
                break;
            case 'anual':
                $month = 12;
                break;
        }

        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $array = [];

        $sumCapital = 0;

        for ($i = 0; $i < $request->period; $i++) {
            if ($i > 0) {

                $deudainicial = $deudafinal;
                $interescal = $deudainicial * $interes;

                if ($request->method === 'variable') {
                    $pago = $interescal + $capital;
                } else {
                    $capital = $pago - $interescal;
                }

                $deudafinal = $deudainicial - $capital;
            }

            $date->addMonth($month);

            $sumCapital += round($capital, 2);

            // Ajuste de amortización fija
            if ($i === $request->period - 1 && $sumCapital < $newMount) {
                $capital += $newMount - $sumCapital;
                $interescal = $pago - $capital;
            }

            $array[] = [
                'debt' => $deudainicial,
                'interest_amount' => $interescal,
                'capital' => $capital,
                'date' => $date . '',
                'state' => 'inactivo'
            ];
        }

        $loan->payments()->createMany($array);

        // Actualizar datos del prestamo con los nuevos datos
        $loan->amount += $request->amount;
        $loan->interest_percentage = $request->interest_percentage;
        $loan->type = $request->type;
        $loan->period = Payment::where('loan_id', $loan->id)->count();
        $loan->method = $request->method;
        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Se renovo un préstamo');
    }
}
