<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $person = Person::all();
        return view('people', compact('person'));
    }

    public function indexJson()
    {
        $people = Person::where('state', 'activo')->get();
        return response()->json(['people' => $people]);
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

        $person = new Person;

        $person->first_name = $request->first_name;
        $person->last_name = $request->last_name;
        $person->identification_card = $request->identification_card;
        $person->phone = $request->phone;
        $person->email = $request->email;

        $person->save();
        return redirect()->route('people.index')->with('mensaje', 'Socio Agregado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $person = Person::findOrFail($id);
        return view('editPeople', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dato = Person::findOrFail($id);


        $dato->first_name = $request->first_name;
        $dato->last_name = $request->last_name;
        $dato->identification_card = $request->identification_card;
        $dato->phone = $request->phone;
        $dato->email = $request->email;

        $dato->save();
        return redirect()->route('people.index')->with('mensaje', 'Datos del socio actulizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        //
    }


    public function personReport()
    {
        $personReport = Person::all();
        $pdf = PDF::loadView('peopleReport',  compact('personReport'));

        return $pdf->stream('reporte_socios.pdf');
    }
    public function delete($id)
    {
        $registro = Person::findOrFail($id);
        $registro->delete();
        return response()->json(['status' => 'Registro eliminado.']);
    }
}
