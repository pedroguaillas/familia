<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Payment;
use Illuminate\Http\Request;
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
            'amount.min' => 'El monto debe ser mayor a 0',
            'interest_percentage.min' => 'El interés debe ser mayo a 0.05',
            'period.min' => 'El N° pagos minimo debe ser minimo 1',
        ]);

        if ($validator->fails()) {
            return redirect('préstamo/{' . $loan->id . '}/renovación')
                ->withErrors($validator)
                ->withInput();
        }

        // Eliminar los pagos inactivos del prestamo
        Payment::where([
            'state' => 'inactivo',
            'loan_id' => $loan->id
        ])->delete();

        $loan->interest_percentage = $request->interest_percentage;
        // Suma directamente el capital porque ya elimino los pagos inactivos del prestamo
        $amount = $loan->amount + $request->amount;
        $loan->amount += $request->amount - Payment::where('loan_id', $loan->id)->sum('capital');
        $loan->method = $request->method;
        $loan->period = $request->period;
        $loan->type = $request->type;
        $loan->date = $request->date;

        (new LoanController())->loadAmortizacion($loan);

        // Actualizar datos del prestamo con los nuevos datos
        $loan->amount = $amount;
        // Se contabiliza de nuevo una vez creado la nueva tabla de amortizacion
        $loan->period = Payment::where('loan_id', $loan->id)->count();
        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Se renovo un préstamo');
    }
}
