<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Person;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class ContributionController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contributions = $this->list();

        return view('contributions/index', compact('contributions'));
    }

    private function list()
    {
        $contributions = DB::table('people')
            ->select(
                'people.id as person_id',
                'people.first_name',
                'people.last_name',
                'people.actions',
                DB::raw('SUM(contributions.amount) as amount')
            )->leftJoin('contributions', 'people.id', 'contributions.person_id')
            ->groupBy(
                'people.id',
                'people.first_name',
                'people.last_name',
                'people.actions'
            )
            ->where([
                ['people.state', 'activo'],
                ['people.type', 'socio']
            ])
            ->get();
        // $contributions = json_decode(json_encode($contributions), true);

        return $contributions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function create2(Request $request)
    {
        $date = new \DateTime($request->date);

        if ($request->type === 'mensual') {
            $contributions = Contribution::select('contributions.person_id')
                ->where([
                    'state' => 'activo',
                    'type' => 'mensual'
                ])
                ->whereMonth('contributions.date', $date->format('m'))
                ->whereYear('contributions.date', $date->format('Y'))
                ->get();
        } else {
            $contributions = Contribution::select('contributions.person_id')
                ->where([
                    'state' => 'activo',
                    'type' => 'anual'
                ])
                ->whereYear('contributions.date', $date->format('Y'))
                ->get();
        }

        $people = Person::where([
            ['state', 'activo'],
            ['type', 'socio']
        ])
            ->whereNotIn('id', $contributions)->get();

        $date = $request->date;
        $type = $request->type;

        return view('contributions/create', compact('people', 'date', 'type'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $person = Person::findOrFail($request->get('person_id'));
        $contribution = $request->only(['date', 'amount', 'must', 'type']);

        if ($request->type === 'mensual' && !is_null($request->get('date_end'))) {
            $date_start = new \DateTime($request->get('date'));
            $date_end = new \DateTime($request->get('date_end'));
            $monthstart = $date_start->format('m');
            $monthend = $date_end->format('m');
            $contributions = array();
            $carbon = new Carbon($date_start, new DateTimeZone('America/Guayaquil'));
            for ($i = $monthstart; $i <= $monthend; $i++) {
                $contribution['date'] = $carbon->toDateTimeString();
                array_push($contributions, $contribution);
                $carbon->addMonth();
            }
            $person->contributions()->createMany($contributions);
        } else {
            $contribution['observation'] = $request->observation;
            $person->contributions()->create($contribution);
        }

        return redirect()->route('aportes.historial', $request->person_id)->with('success', 'Se agrego con éxito los aportes');
    }

    public function storeMasive(Request $request)
    {
        $contributions = $request->get('contributions');
        $contributions = json_decode($contributions);
        foreach ($contributions as $contribution) {
            $contribution->type = (string)$request->get('type');
        }

        $contributions = json_decode(json_encode($contributions), true);

        DB::table('contributions')->insert($contributions);

        return response()->json(['msm' => 'bien desde el servidor', 'contributions' => $contributions]);
    }

    public function history($person_id)
    {
        return $this->historyview($person_id);
    }

    public function historypdf($person_id)
    {
        return  $this->historyview($person_id, true);
    }

    private function historyview($person_id, $pdf = false)
    {
        $person = Person::findOrFail($person_id);
        $amount = 0;
        $contributions = Contribution::where([
            'person_id' => $person_id,
            'state' => 'activo'
        ])
            ->orderBy('date', 'DESC')->get();
        for ($i = 0; $i < count($contributions); $i++) {
            $amount += $contributions[$i]->amount;
        }

        $contributions = json_decode(json_encode($contributions));

        if ($pdf) {
            $pdf = PDF::loadView('contributions.reporthistorial', compact('person', 'contributions', 'amount'));
            (new PdfController())->loadTempleate($pdf);
            return $pdf->stream('historial_de_aportes.pdf');
        } else {
            return view('contributions/history', compact('person', 'contributions', 'amount'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function show(Contribution $contribution)
    {
        return response()->json(['contribution' => $contribution]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function edit(Contribution $contribution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contribution $contribution)
    {
        $contribution->update($request->all());

        return redirect()->route('aportes.historial', $contribution->person->id)->with('success', 'Se modifico un aporte');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contribution $contribution)
    {
        $contribution->state = 'inactivo';
        $contribution->save();

        return response()->json(['msm' => "Se elimino un aporte"]);
    }

    public function report()
    {
        $contributions = $this->list();

        $pdf = PDF::loadView('contributions/report',  compact('contributions'));
        (new PdfController())->loadTempleate($pdf);

        return $pdf->stream('reporte_aportes.pdf');
    }

    public function solicitude(int $person_id)
    {
        $person = Person::findOrFail($person_id);
        $pdf = PDF::loadView('contributions/solicitude', compact('person'));

        return $pdf->stream('solicitud_compra_acciones.pdf');
    }
}
