<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Person;
use App\Payment;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade as PDF;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Loan $loan)
    {
        //Se requiere de person para mostrar el nombre en la cabecera
        $person = $loan->person;
        //Se requiere de guarantor para mostrar el nombre en la cabecera
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();
        // $payments = $loan->payments;
        $payments = DB::table('payments')
            ->where([
                'loan_id' => $loan->id,
                // 'state' => 'activo'
            ])
            ->orderBy('date', 'asc')
            // ->orderBy('interest_amount')
            ->get();

        return view('payments.index', compact('person', 'guarantor', 'loan', 'payments'));
    }

    public function report(Loan $loan)
    {
        //Se requiere de person para mostrar el nombre en la cabecera
        $person = $loan->person;
        //Se requiere de guarantor para mostrar el nombre en la cabecera
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();
        // $payments = $loan->payments;
        $payments = Payment::where([
            'loan_id' => $loan->id,
            'state' => 'activo'
        ])
            ->orderBy('date', 'asc')
            ->get();

        $title = 'PAGOS';

        $pdf = PDF::loadView('payments.report', compact('person', 'guarantor', 'loan', 'payments', 'title'));

        (new PdfController())->loadTempleate($pdf);

        return $pdf->stream('pagosprestamo.pdf');
    }

    public function amortizationTable(Loan $loan)
    {
        //Se requiere de person para mostrar el nombre en la cabecera
        $person = $loan->person;
        //Se requiere de guarantor para mostrar el nombre en la cabecera
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();
        // $payments = $loan->payments;
        $payments = Payment::where('loan_id', $loan->id)
            ->orderBy('date', 'asc')
            ->get();

        $title = 'TABLA DE AMORTIZACIÓN';

        $pdf = PDF::loadView('payments.report', compact('person', 'guarantor', 'loan', 'payments', 'title'));

        (new PdfController())->loadTempleate($pdf);

        return $pdf->stream('pagosprestamo.pdf');
    }

    public function voucher(Payment $payment)
    {
        $pdf = PDF::loadView('payments.voucher', compact('payment'));
        $pdf->setPaper('a6');

        return $pdf->stream('pago.pdf');
    }

    public function store(Request $request)
    {
        $loan = Loan::find($request->loan_id);

        if ($loan->method === 'inicio') {
            $date_start = new \DateTime($request->get('date_start'));
            if (!is_null($request->get('date_end'))) {
                $date_end = new \DateTime($request->get('date_end'));
                $monthstart = $date_start->format('m');
                $monthend = $date_end->format('m');
                $payments = array();
                $carbon = new Carbon($date_start, new DateTimeZone('America/Guayaquil'));
                for ($i = $monthstart; $i <= $monthend; $i++) {
                    $payment = [
                        'debt' => $request->get('debt'),
                        'interest_amount' => $request->get('interest_amount'),
                        'capital' => 0,
                        'must' => 0,
                        'date' => $carbon->toDateTimeString(),
                        'observation' => $request->get('observation')
                    ];
                    array_push($payments, $payment);
                    $carbon->addMonth();
                }

                $loan->payments()->createMany($payments);
            } else {
                $payment = [
                    'debt' => $request->get('debt'),
                    'interest_amount' => $request->get('interest_amount'),
                    'capital' => $request->get('capital'),
                    'must' => $request->get('must'),
                    'date' => $request->get('date_start'),
                    'observation' => $request->get('observation')
                ];
                $loan->payments()->create($payment);
            }
        } else {
            $payment = Payment::find($request->payment_id);

            // 1. Si el monto del pago es igual al monto del formulario solo pasar a ACTIVO el pago
            if ($request->capital > $payment->capital) {
                // 2. a. Extraer todos los pagos inactivos ordenados por la fecha
                $payments = Payment::where([
                    'state' => 'inactivo',
                    'loan_id' => $loan->id,
                ])
                    ->orderBy('date', 'asc')->get();

                // 2. b. 1. Crear un SUMADOR
                $sum = $payment->capital;
                $i = 1;
                $array = [];
                // 2. b. Recorrer los pagos
                while ($i < $payments->count() && $sum + $payments[$i]->capital <= $request->capital) {
                    // 2. b. 2. Verificar que el SUMADOR < al monto del formulario
                    $sum += $payments[$i]->capital;
                    $array[] = $payments[$i]->id;
                    $i++;
                }

                if ($request->capital > $sum) {
                    $array[] = $payments[$i]->id;

                    $loan->amount = $payment->debt - $request->capital;
                    $loan->period = $payments->count() - $i;
                    $loan->date = $request->date_start;
                    // De los pagos inactivos solo no elimino el pago a modificar
                    Payment::where('id', '<>', $payment->id)
                        ->where('state', 'inactivo')->delete();
                    // Si el monto es mayo a 0 debe generar la nueva tabla
                    if ($loan->amount) {
                        (new LoanController())->loadAmortizacion($loan);
                    }
                } else {
                    // Si es IGUAL Solo eliminar registros de pagos
                    Payment::whereIn('id', $array)->delete();
                }
            }

            $payment->update([
                'state' => 'activo',
                'debt' => $request->get('debt'),
                'interest_amount' => $request->get('interest_amount'),
                'capital' => $request->get('capital'),
                'must' => $request->get('must'),
                'date' => $request->get('date_start'),
                'observation' => $request->get('observation')
            ]);
        }

        return redirect()->route('prestamo.pagos', $request->loan_id)->with('mensaje', 'Se agrego con éxito los pagos');
    }

    public function show(Payment $payment)
    {
        return response()->json(['payment' => $payment]);
    }

    public function interestCalculate(Loan $loan)
    {
        $debt = 0;
        $capital = 0;
        $interest = 0;
        $payment_id = 0;

        $day = (int)substr($loan->date, 8, 2);

        // Inicio method Inicio
        if ($loan->method === 'inicio') {
            $debt = $loan->amount - Payment::where([
                ['state', 'like', 'activo'],
                ['loan_id', '=', $loan->id]
            ])
                ->groupBy('loan_id')->sum('capital');

            $interest = round($debt * $loan->interest_percentage * 0.01, 2);
            // Fin method Inicio
        } else {
            // Inicio amortizacion

            // Obtener todos los pagos pero inactivos
            $payments = Payment::where('loan_id', $loan->id)
                // ordenados por monto de interes
                ->orderBy('interest_amount', 'desc')
                ->where('state', 'inactivo')->get();

            // Si hay pagos inactivos
            if ($payments->count()) {

                // Determinar el pago que se debe cobrar
                $payment = $payments->first();

                $payment_id = $payment->id;
                $capital = $payment->capital;
                $debt = $payment->debt;
                // $day = substr($payment->date, 0, 10);
                $interest = $payment->interest_amount;
            }

            // Fin amortizacion
        }

        return response()->json([
            'capital' => round($capital, 2),
            'debt' => $debt,
            'day' => $day,
            'interest' => $interest,
            'method' => $loan->method,
            'payment_id' => $payment_id
        ]);
    }

    public function liquidacionCalculate(Loan $loan)
    {
        $interest = 0;
        $payment_id = 0;

        $day = (int)substr($loan->date, 8, 2);

        $debt = $loan->amount - Payment::where('state', 'activo')
            ->where('loan_id', $loan->id)->sum('capital');

        // Inicio method Inicio
        if ($loan->method === 'inicio') {

            $interest = round($debt * $loan->interest_percentage * 0.01, 2);
            // Fin method Inicio

        } else {
            // Inicio amortizacion

            // Obtener todos los pagos pero inactivos
            $payments = Payment::where('loan_id', $loan->id)
                // ordenados por monto de interes
                ->orderBy('interest_amount', 'desc')
                ->where('state', 'inactivo')->get();

            // Si hay pagos inactivos
            if ($payments->count()) {

                // Determinar el pago que se debe cobrar
                $payment = $payments->first();

                $payment_id = $payment->id;

                $interest = $payment->interest_amount;
            }

            // Fin amortizacion
        }

        return response()->json([
            'debt' => round($debt, 2),
            'day' => $day,
            'interest' => $interest,
            'method' => $loan->method,
            'payment_id' => $payment_id
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());

        return redirect()->route('prestamo.pagos', $payment->loan_id)->with('success', 'Se ha modificado un pago');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
    }
}
