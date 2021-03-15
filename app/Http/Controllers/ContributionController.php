<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Person;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $contributions = json_decode(json_encode($contributions), true);
        return view('contributions/index', compact('contributions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contributions = DB::table('people')
            ->select(
                'people.id',
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
        $contributions = json_decode(json_encode($contributions), true);
        return view('contributions/create', compact('contributions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contributions = $request->get('contributions');
        $date = Carbon::now();
        $contributions = json_decode($contributions);
        foreach ($contributions as $contribution) {
            $contribution->date = $date->toDateTimeString();
            $contribution->type = (string)$request->get('type');
        }

        $contributions = json_decode(json_encode($contributions), true);

        DB::table('contributions')->insert($contributions);

        return response()->json(['msm' => 'bien desde el servidor', 'contributions' => $contributions]);
    }

    public function history($person_id)
    {
        $person = Person::findOrFail($person_id);
        $amount = 0;
        $contributions = $person->contributions;
        for ($i = 0; $i < count($contributions); $i++) {
            $amount += $contributions[$i]->amount;
        }

        return view('contributions/history', compact('person', 'contributions', 'amount'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function show(Contribution $contribution)
    {
        //
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
    public function update(Request $request, $id)
    {
        dd($id);
        $contribution = Contribution::findOrFail($id);
        $person_id = $contribution->person->id;
        $contribution->date = $request->date;
        $contribution->amount = $request->amount;
        $contribution->save();
        return redirect()->route('aportes.historial', $person_id)->with('success', 'Se actualizo un registro con exito ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contribution  $contribution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contribution $contribution)
    {
        //
    }
}
