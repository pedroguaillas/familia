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

    public function index2($id)
    {
        $loan = Loan::findOrFail($id);
        //Se requiere de person para mostrar el nombre en la cabecera
        $person = $loan->person;
        //Se requiere de guarantor para mostrar el nombre en la cabecera
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();
        // $payments = $loan->payments;
        $payments = \DB::table('payments')
            ->where([
                'loan_id' => $id,
                'state' => 'activo'
            ])
            ->orderBy('date', 'asc')
            ->orderBy('capital')
            ->get();

        return view('payments.index', compact('person', 'guarantor', 'loan', 'payments'));
    }

    public function report(Loan $loan)
    {
        // $loan = Loan::findOrFail($id);
        //Se requiere de person para mostrar el nombre en la cabecera
        $person = $loan->person;
        //Se requiere de guarantor para mostrar el nombre en la cabecera
        $guarantor = Person::where('id', $loan->guarantor_id)->get()->first();
        // $payments = $loan->payments;
        $payments = \DB::table('payments')
            ->where([
                'loan_id' => $loan->id,
                'state' => 'activo'
            ])
            ->orderBy('date', 'asc')
            ->get();

        $pdf = PDF::loadView('payments.report', compact('person', 'guarantor', 'loan', 'payments'));

        (new PdfController())->loadTempleate($pdf);

        return $pdf->stream('pagosprestamo.pdf');
    }

    public function voucher(Payment $payment)
    {
        $pdf = PDF::loadView('payments.voucher', compact('payment'));
        $pdf->setPaper('a6');
        // (new PdfController())->loadTempleate($pdf);

        return $pdf->stream('pago.pdf');
    }

    public function store(Request $request)
    {
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
            $loan = Loan::findOrFail($request->loan_id);
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
            $loan = Loan::findOrFail($request->loan_id);
            $loan->payments()->create($payment);
        }

        return redirect()->route('prestamo.pagos', $request->loan_id)->with('mensaje', 'Se agrego con éxito los pago');
    }

    public function show(Payment $payment)
    {
        return response()->json(['payment' => $payment]);
    }

    public function interestCalculate($loan_id)
    {
        $debt = 0;
        $capital = 0;
        $day = '';
        $interest = 0;

        $loan = Loan::findOrFail($loan_id);

        // Inicio method Inicio
        if ($loan->method === 'inicio') {
            $payment = Payment::select(\DB::raw('SUM(capital) as paid'))
                ->where([
                    ['state', 'like', 'activo'],
                    ['loan_id', '=', $loan_id]
                ])
                ->groupBy('loan_id')->first();

            if ($payment !== null) {
                $debt = $loan->amount - $payment->paid;
            } else {
                $debt = $loan->amount;
            }
            $day = (int)substr($loan->date, 8, 2);
            $interest = round($debt * $loan->interest_percentage * 0.01, 2);
            // Fin method Inicio
        } else {
            // Inicio amortizacion

            // Obterner todos los pagos
            $payments = Payment::where('loan_id', $loan_id)
                ->orderBy('capital')->get();

            // Determinar el pago que se debe cobrar
            $payment = $payments->first();

            $capital = $payment->capital;
            $debt = $loan->amount;
            $day = 0;
            $interest = $payment->interest_amount;

            // Fin amortizacion
        }

        return response()->json([
            'capital' => $capital,
            'debt' => $debt,
            'day' => $day,
            'interest' => $interest
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
        // $payment->state = 'inactivo';
        // $payment->save();
    }
}
