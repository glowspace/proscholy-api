<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\External;

class ExternalController extends Controller
{
    public function __construct()
    {

    }

    public function index(){
        $externals = External::all();
        return view('admin.external.index', compact('externals'));
    }

    public function create(){
        return view('admin.external.create');
    }

    public function store(Request $request){
        // TODO: make this line working
        // Externals::create($request->all());

        $external       = new External();
        $external->type = $request["type"];
        $external->url = $request['url'];
        $external->save();

        return redirect()->route('admin.external.create');
    }

    public function edit(External $external)
    {
        return view('admin.external.edit', compact('external'));
    }

    public function destroy(External $external){
        // TODO: find if a External model that had been linked to this External has no dependencies anymore
        // in the case delete this one as well

        $external->delete();
    }

    public function update(Request $request, External $external)
    {
        $external->update($request->all());
        return redirect()->route('admin.external.index');
    }
}
