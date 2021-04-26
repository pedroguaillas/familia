<?php

namespace App\Http\Controllers;

use App\Spend;
use Illuminate\Http\Request;

class SpendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spends = Spend::all();
        return view('spends.index', compact('spends'));
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
        Spend::create($request->all());
        return redirect()->route('spends.index')->with('info', 'Se registro un nuevo gasto');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Spend  $spend
     * @return \Illuminate\Http\Response
     */
    public function show(Spend $spend)
    {
        return response()->json(['spend' => $spend]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Spend  $spend
     * @return \Illuminate\Http\Response
     */
    public function edit(Spend $spend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Spend  $spend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spend $spend)
    {
        $spend->update($request->all());

        return redirect()->route('spends.index')->with('info', 'Se actualizo un gasto');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Spend  $spend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spend $spend)
    {
        $spend->delete();

        return redirect()->route('spends.index')->with('danger', 'Se elimino un gasto');
    }
}
