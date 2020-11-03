<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use Illuminate\Http\Request;

class MidiaController extends Controller
{
    public function index() {
        //
    }

    public function create() {
        return view('arquivo.add_imagem');
    }

    public function store(Request $request) {
        //
    }

    public function show(Midia $midia) {
        //
    }

    public function edit(Midia $midia) {
        //
    }

    public function update(Request $request, Midia $midia) {
        //
    }

    public function destroy(Midia $midia) {
        //
    }

    public function baixar() {
        // $file = \Storage::disk('public')->get()
    }

    
}
