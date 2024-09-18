<?php

namespace App\Http\Controllers;

use App\Directive;
use App\Loan;
use App\Payment;
use App\Person;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $date = new Carbon('now -1 month');

        $loans = Loan::selectRaw('loans.id,amount,interest_percentage,loans.date,method,first_name,last_name, SUM(payments.capital) AS sum_capital_paid')
            ->join('people', 'people.id', 'person_id')
            // ->leftJoin('payments', 'loan_id', 'loans.id')
            ->leftJoin('payments', function ($join) {
                $join->on('loans.id', 'loan_id')
                    // Le suma los pagos activos los inactivos son los que solo se usan para la tabla de amortización
                    ->where('payments.state', 'activo');
            })
            ->groupBy('loans.id', 'amount', 'interest_percentage', 'loans.date', 'method')
            ->where('loans.state', 'activo')
            // ->where(function ($query) use ($date) {
            //     $query->whereDate('loans.date', ' < ', 'DATE(2022-04-25)')
            //         ->orWhere('loans.state', 'activo');
            // })
            // Nueva restrincion para mostrar solo los prestamos que falta concluir los pagos
            ->havingRaw('sum_capital_paid IS NULL OR sum_capital_paid < amount')
            // ->orHavingRaw($date->toDateString() . ' < DATE(updated_at)')
            ->orderBy('loans.date')->get();

        return view('loans.index', compact('loans'));
    }

    public function pdf()
    {
        $loans = Loan::selectRaw('loans.id,amount,interest_percentage,loans.date,method,first_name,last_name, sum(payments.capital) as sum_capital_paid, sum(payments.interest_amount) as interest_amount, sum(payments.must) as must')
            ->join('people', 'people.id', 'person_id')
            // ->leftJoin('payments', 'loan_id', 'loans.id')
            ->leftJoin('payments', function ($join) {
                $join->on('loans.id', 'loan_id')
                    ->where('payments.state', 'activo');
            })
            ->groupBy('loans.id', 'loans.amount', 'loans.interest_percentage', 'loans.date', 'people.first_name', 'people.last_name')
            ->where('loans.state', 'activo')
            ->orderBy('loans.date')->get();

        $loans = json_decode(json_encode($loans), true);

        $pdf = PDF::loadView('loans.report', compact('loans'));
        (new PdfController())->loadTempleate($pdf);

        return $pdf->stream('prestamos.pdf');
    }

    public function create()
    {
        $people = Person::where('state', 'activo')->get();
        return view('loans.create', compact('people'));
    }

    public function store(Request $request)
    {
        // Falta validar el garante si debe seleccionar
        $validator = Validator::make($request->all(), [
            'person_id' => 'required',
            'amount' => 'required',
            'interest_percentage' => 'required',
            'date' => 'required',
            'type' => 'required',
            'period' => 'required',
            'method' => 'required'
        ], [
            'person_id.required' => 'Debe seleccionar el solicitante',
            'amount.required' => 'El monto es requerido',
            'interest_percentage.required' => 'El interés es requerido',
            'date.required' => 'La fecha es requerido',
            'type.required' => 'El pago es requerido',
            'period.required' => 'El periodo es requerido',
            'method.required' => 'La tabla es requerido',
        ]);

        if ($validator->fails()) {
            return redirect('loans/create')
                ->withErrors($validator)
                ->withInput();
        }

        $loan = Loan::create($request->all());

        // Generar tabla de amortizacion
        $this->loadAmortizacion($loan);

        return redirect()->route('loans.index')->with('success', 'Se registro un nuevo préstamo');
    }

    public function loadAmortizacion(Loan $loan)
    {
        $interes = $loan->interest_percentage * 0.01;
        $deudainicial = $loan->amount;
        $interescal = $loan->amount * $interes;
        $capital = 0;
        $pago = 0;

        if ($loan->method === 'variable') {
            $capital = round($loan->amount / $loan->period, 2);
            // Ajuste de amortización variable
            if ($capital * $loan->period < $loan->amount) {
                $capital += 0.01;
            }
            $pago = $interescal + $capital;
        } else {
            // Pago con dos decimales para convertirle en fijo durante todo el periodo
            $pago = round($interescal / (1 - pow(1 + $interes, -$loan->period)), 2);
            $capital = $pago - $interescal;
        }

        $deudafinal = $deudainicial - $capital;

        $month = '';

        switch ($loan->type) {
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

        $date = Carbon::createFromFormat('Y-m-d', $loan->date);
        $array = [];

        $sumCapital = 0;

        for ($i = 0; $i < $loan->period; $i++) {
            if ($i > 0) {

                $deudainicial = $deudafinal;
                $interescal = $deudainicial * $interes;

                if ($loan->method === 'variable') {
                    $pago = $interescal + $capital;
                } else {
                    $capital = $pago - $interescal;
                }

                $deudafinal = $deudainicial - $capital;
            }

            $date->addMonth($month);

            $sumCapital += round($capital, 2);

            // Ajuste de amortización FIJA
            if ($i === $loan->period - 1 && $sumCapital < $loan->amount) {
                $capital += $loan->amount - $sumCapital;
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
    }

    public function show(Loan $loan)
    {
        //Usada para mostrar renovacion de credito
        $person = $loan->person;

        $payments = $loan->payments;

        $loan->debt = 0;

        foreach ($payments as $item) {
            if ($item->state === 'activo') {
                $loan->debt += $item->capital;
            }
        }

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

    public function edit(Loan $loan)
    {
        //Se requiere de person para mostrar el nombre en el formulario
        $person = Person::findOrFail($loan->person_id);
        //Se requiere de guarantor para mostrar el nombre del garante si existe
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();

        return view('loans.edit', compact('loan', 'person', 'guarantor'));
    }

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

    public function destroy(Loan $loan)
    {
        // Antes pasaba a inactivo
        // Ahora se elimina de manera física
        Payment::where('loan_id', $loan->id)->delete();

        $loan->delete();

        return redirect()->route('loans.index')->with('danger', 'Se elimino un préstamo');
    }
}
