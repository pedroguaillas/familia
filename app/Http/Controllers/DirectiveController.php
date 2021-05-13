<?php

namespace App\Http\Controllers;

use App\Directive;
use Illuminate\Http\Request;

class DirectiveController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Directive  $directive
     * @return \Illuminate\Http\Response
     */
    public function show(Directive $directive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Directive  $directive
     * @return \Illuminate\Http\Response
     */
    public function edit(Directive $directive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Directive  $directive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Directive $directive)
    {
        $directive->update($request->all());

        return redirect()->route('people.index')->with('info', 'Se modifico el presidente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Directive  $directive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Directive $directive)
    {
        //
    }
}
