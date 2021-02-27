<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Payment;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

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
        $date_end = new \DateTime($request->get('date_end'));

        if ($date_end != null) {
            $monthstart = $date_start->format('m');
            $monthend = $date_end->format('m');
            /* return response()->json(['inicio' => (int)$monthstart, 'Fin' => (int)$monthend]); */
            $payments = array();
            for ($i = (int)$monthstart; $i < (int)$monthend; $i++) {
                $payment = [
                    'interest_amount' => $request->get('interest_amount'),
                    'capital' => 0,
                    'must' => 0,
                    'date' => $request->get('date_start')
                ];
                array_push($payments, $payment);
            }
            $loan = Loan::findOrFail($request->loan_id);
            $loan->payments()->createMany($payments);
        }

        /*  $payment = new Payment;

        $payment->loan_id = $request->loan_id;
        $payment->interest_amount = $request->interest_amount;
        $payment->must = $request->must;
        $payment->date = $request->date;

        $payment->save();
 */
        return redirect()->route('prestamos.pagos', $request->loan_id)->with('mensaje', 'Se agrego con éxito los pago');
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
        //
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
