<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterVendedorController extends Controller{


    /**
     * Formulário para criar novo usuário Vendedor.
     *
     */
    protected function formVendedor(){
        return view('auth.registro.vendedor');
    }

}
