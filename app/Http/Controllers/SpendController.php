<?php

namespace App\Http\Controllers;

use App\Spend;
use Illuminate\Http\Request;

class SpendController extends Controller
{
    public function index()
    {
        $spends = Spend::all();
        return view('spends.index', compact('spends'));
    }

    public function store(Request $request)
    {
        Spend::create($request->all());
        return redirect()->route('spends.index')->with('info', 'Se registro un nuevo gasto');
    }

    public function show(Spend $spend)
    {
        return response()->json(['spend' => $spend]);
    }

    public function update(Request $request, Spend $spend)
    {
        $spend->update($request->all());

        return redirect()->route('spends.index')->with('info', 'Se actualizo un gasto');
    }

    public function destroy(Spend $spend)
    {
        $spend->delete();

        return redirect()->route('spends.index')->with('danger', 'Se elimino un gasto');
    }
}
