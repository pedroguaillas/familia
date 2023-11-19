<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Directive;
use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class PersonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $people = Person::where('state', 'activo')->get();
        $people = json_decode(json_encode($people));

        $directive = Directive::all()->first();
        $directive->person;

        return view('people.index', compact('people', 'directive'));
    }

    public function indexJson()
    {
        $people = Person::where('state', 'activo')->get();
        return response()->json(['people' => $people]);
    }

    public function store(Request $request)
    {
        try {
            $person =  Person::create($request->all());
            return redirect()->route('people.index')->with('info', (($request->type === 'socio') ? 'Socio' : 'Persona particular') . ' agregado con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorcode = $e->errorInfo[1];
            if ($errorcode === 1062) {
                return redirect()->route('people.index')->with('warning', 'Ya se ha registrado una persona con la cédula ' . $request->identification_card);
            }
        }
    }

    public function purchaseActions(Request $request)
    {
        $person = Person::findOrFail($request->person_id);
        $person->actions = $request->quantity_action_purchase + 1;

        if ($person->save()) {
            $date = Carbon::now();
            Contribution::create([
                'person_id' => $request->person_id,
                'amount' => $request->amount_to_pay,
                'date' => $date->format('Y-m-d'),
                'observation' => $request->observation
            ]);
        }

        return redirect()->route('contributions.index')->with('success', 'Se registro la compra de acciones del socio ' . $person->first_name . ' ' . $person->last_name);
    }

    public function show(Person $person)
    {
        return response()->json(['person' => $person]);
    }

    public function update(Request $request, Person $person)
    {
        $person->update($request->all());

        return redirect()->route('people.index')->with('info', 'Datos del personal actualizado.');
    }

    public function destroy(Person $person)
    {
        // Para anular un socio se requiere lo siguiente
        // Calcular el valor a devolver al socio saliente
        // Multiplicar el numero de acciones por el valor de la accion
        // A ese valor reducir los $50

        // Inactivo porque se va eliminar
        $person->state = 'inactivo';
        // Poner la identificacion = null
        // porque si regresar a ser socio, no permitiria ya que se volveria aparecer ese ID
        $person->identification_card = null;
        $person->save();
    }

    public function report($type)
    {
        $people = Person::where([
            'type' => $type,
            'state' => 'activo'
        ])->get();

        $pdf = PDF::loadView('people.report',  compact('people'));
        (new PdfController())->loadTempleate($pdf);
        return $pdf->stream('reporte_socios.pdf');
    }
}
