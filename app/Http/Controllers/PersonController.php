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
        $people = Person::where('state', 'activo')->get();
        return view('people.index', compact('people'));
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
        try {
            $person =  Person::create($request->all());
            return redirect()->route('people.index')->with('mensaje', (($request->type === 'socio') ? 'Socio' : 'Persona particular') . ' agregado con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorcode = $e->errorInfo[1];
            if ($errorcode === 1062) {
                return redirect()->route('people.index')->with('mensaje', 'Ya se ha registrado una persona con la cédula ' . $request->identification_card);
            }
        }
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
        return view('people.editPeople', compact('person'));
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
        $person->update([
            ['state' => 'inactivo']
        ]);

        return redirect()->route('people.index')->with('danger', 'Se elimino ');
    }


    public function report($type)
    {
        $people = Person::where('type', $type)->get();

        $pdf = PDF::loadView('people.report',  compact('people'));
        return $pdf->stream('reporte_socios.pdf');
    }

    public function delete($id)
    {
        $person = Person::findOrFail($id);
        $person->state = 'inactivo';
        $person->save();


        return response()->json(['mensaje', 'se elimino']);
    }
}
