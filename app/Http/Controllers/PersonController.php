<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Directive;
use App\Person;
use App\Spend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->only('identification_card', 'val_contribution', 'phone', 'mail'), [
            'identification_card' => 'unique:people|digits:10',
            'val_contribution' => 'required_if:type,socio|numeric',
            'phone' => 'nullable|digits:10',
            'mail' => 'nullable|email',
        ], [
            'identification_card' => [
                'unique' => 'Ya existe un persona con esa cédula.',
                'digits' => 'La cédula solo debe contener 10 dígitos.'
            ],
            'val_contribution' => [
                'required_if' => 'Si la persona es socio es requerido, el valor de la acción.',
                'numeric' => 'El valor de la acción debe ser numérico.'
            ],
            'phone' => [
                'nullable' => 'No es requerido el teléfono.',
                'digits' => 'El teléfono solo debe contener 10 dígitos.'
            ],
            'mail' => [
                'nullable' => 'No es requerido el correo.',
                'email' => 'El correo no contiene un formato de correo electrónico.'
            ],
        ]);

        if ($validator->fails()) {
            return redirect('people')
                ->withErrors($validator)
                ->withInput();
        }

        $inputs = $request->except('val_contribution');
        $inputs += ['actions' => 1];
        $person = Person::create($inputs);

        if ($request->type === 'socio') {
            $carbon = Carbon::now();

            $person->contributions()->create([
                'amount' => $request->val_contribution,
                'date' => $carbon->format('Y-m-d'),
                'type' => 'mensual',
                'state' => 'activo',
                'observation' => 'Registro de un nuevo socio'
            ]);
        }

        return redirect()->route('people.index')->with('info', (($request->type === 'socio') ? 'Socio' : 'Persona particular') . ' agregado con éxito.');
    }

    public function purchaseActions(Request $request)
    {
        $person = Person::findOrFail($request->person_id);
        $person->actions += $request->quantity_action_purchase;

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

    public function destroy(Request $request, Person $person)
    {
        if ($person->type === 'socio') {
            // Para anular un socio se requiere lo siguiente
            $contributions = null;
            $interest = 0;

            // Calcular el valor de cada accion
            (new HomeController())->querys($contributions, $interest);

            // Cantidad de acciones en la caja
            $actions = Person::selectRaw('SUM(actions) AS sum')
                ->where('state', 'activo')->get()->first()->sum;

            // Valor entre los aportes e intereses
            $amount_current = $contributions[0]->sum + $contributions[1]->sum + $interest;

            $amount = $amount_current / $actions;
            // Multiplicar el numero de acciones por el valor de la accion
            // A ese valor reducir los $50
            $val_gasto = $amount * $request->action_delete - 50;

            $carbon = Carbon::now();

            Spend::create([
                'name' => 'Devolución por salida del socio',
                'amount' => $val_gasto,
                'date' => $carbon->format('Y-m-d'),
                'observation' => $person->identification_card . ' ' . $person->first_name . ' ' . $person->last_name,
                // 'state' => 'activo',
                'impact' => 'capital'
            ]);

            if ($request->action_delete === $person->actions) {

                // Inactivo porque se va eliminar
                $person->state = 'inactivo';
                // Poner la identificacion = null
                // porque si regresar a ser socio, no permitiria ya que se volveria aparecer ese ID
                $person->identification_card = null;
            } else {
                // Si solo va reducir sus acciones 
                $person->actions -= $request->action_delete;
            }
        } else {
            // Eliminar una persona que no es socio
            $person->state = 'inactivo';
            $person->identification_card = null;
        }

        $person->save();

        return redirect()->route('people.index')->with('info', 'Se ' . ($request->action_delete === $person->actions ? 'elimino un socio' : 'redujo las acciones de') . ' un socio.');
    }

    public function report($type)
    {
        $people = Person::where([
            'type' => $type,
            'state' => 'activo'
        ])->get();

        $pdf = PDF::loadView('people.report', compact('people'));
        (new PdfController())->loadTempleate($pdf);
        return $pdf->stream('reporte_socios.pdf');
    }
}
