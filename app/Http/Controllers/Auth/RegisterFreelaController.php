<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterFreelaController extends Controller{
    //




    /**
     * Formulário para criar novo usuário Comprador.
     *
     */
    protected function formFreela(){
        return view('auth.registro.freela');
    }


}
