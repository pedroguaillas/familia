<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Payment;
use App\Person;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2($id)
    {
        $loan = Loan::findOrFail($id);
        $person = $loan->person;
        $payments = $loan->payments;

        return view('payments.index', compact('person', 'loan', 'payments'));
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
        $date_start = new \DateTime($request->get('date_start'));
        if (!is_null($request->get('date_end'))) {
            $date_end = new \DateTime($request->get('date_end'));
            $monthstart = $date_start->format('m');
            $monthend = $date_end->format('m');
            $payments = array();
            $carbon = new Carbon($date_start, new DateTimeZone('America/Guayaquil'));
            for ($i = $monthstart; $i <= $monthend; $i++) {
                $payment = [
                    'interest_amount' => $request->get('interest_amount'),
                    'capital' => 0,
                    'must' => 0,
                    'date' => $carbon->toDateTimeString()
                ];
                array_push($payments, $payment);
                $carbon->addMonth();
            }
            $loan = Loan::findOrFail($request->loan_id);
            $loan->payments()->createMany($payments);
        } else {
            $payment = [
                'interest_amount' => $request->get('interest_amount'),
                'capital' => $request->get('capital'),
                'must' => $request->get('must'),
                'date' => $request->get('date_start')
            ];
            $loan = Loan::findOrFail($request->loan_id);
            $loan->payments()->create($payment);
        }
        return redirect()->route('prestamos.pagos', $request->loan_id)->with('mensaje', 'Se agrego con éxito los pago');
    }

    public function delete($id)
    {

        $registro = Payment::findOrFail($id);
        $registro->delete();
        return response()->json(['status' => 'Registro eliminado']);

        //Campeonato::destroy($id);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
    }

    public function interestCalculate($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        $payment = Payment::select(DB::raw('SUM(capital) as paid'))
            ->where([
                ['state', 'like', 'activo'],
                ['loan_id', '=', $loan_id]
            ])
            ->groupBy('loan_id')->first();
        /*  $toPay = $loan->amount - $payments[0]->paid; */
        $toPay = 0;
        if ($payment !== null) {
            $toPay = $loan->amount - $payment->paid;
        } else {
            $toPay = $loan->amount;
        }

        return response()->json(['toPay' => $toPay * $loan->interest_percentage * 0.01]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
