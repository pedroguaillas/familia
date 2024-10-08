<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Person;
use App\Directive;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class ContributionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contributions = $this->list();

        return view('contributions/index', compact('contributions'));
    }

    private function list()
    {
        $contributions = Person::selectRaw('people.id as person_id,first_name,last_name,actions,SUM(amount) as amount,SUM(must) as must')
            ->leftJoin('contributions', 'people.id', 'person_id')
            ->groupBy('people.id', 'first_name', 'last_name', 'actions')
            ->where([
                ['people.state', 'activo'],
                ['people.type', 'socio']
            ])
            ->get();

        return $contributions;
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

        return redirect()->route('aporte.historial', $request->person_id)->with('success', 'Se agrego con éxito los aportes');
    }

    public function storeMasive(Request $request)
    {
        $contributions = $request->get('contributions');
        $contributions = json_decode($contributions);

        $array = [];
        foreach ($contributions as $contribution) {
            if ($contribution->amount > 0) {
                $contribution->type = (string)$request->get('type');
                array_push($array, $contribution);
            }
        }

        $array = json_decode(json_encode($array), true);

        \DB::table('contributions')->insert($array);

        return response()->json(['msm' => 'Bien desde el servidor']);
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
            $pdf = PDF::loadView('contributions.historialreport', compact('person', 'contributions', 'amount'));
            (new PdfController())->loadTempleate($pdf);
            return $pdf->stream('historial_de_aportes.pdf');
        } else {
            return view('contributions/history', compact('person', 'contributions', 'amount'));
        }
    }

    public function show(Contribution $contribution)
    {
        return response()->json(['contribution' => $contribution]);
    }

    public function update(Request $request, Contribution $contribution)
    {
        $contribution->update($request->all());

        return redirect()->route('aporte.historial', $contribution->person->id)->with('success', 'Se modifico un aporte');
    }

    public function destroy(Contribution $contribution)
    {
        $contribution->delete();

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

        // Presidente
        $directive = Directive::all()->first()->person;

        $pdf = PDF::loadView('contributions/solicitude', compact('person', 'directive'));

        return $pdf->stream('solicitud_compra_acciones.pdf');
    }
}
