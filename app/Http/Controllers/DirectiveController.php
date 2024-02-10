<?php

namespace App\Http\Controllers;

use App\Directive;
use Illuminate\Http\Request;

class DirectiveController extends Controller
{
    public function update(Request $request, Directive $directive)
    {
        $directive->update($request->all());

        return redirect()->route('people.index')->with('info', 'Se modifico el presidente');
    }
}
