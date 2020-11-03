<?php

namespace App\Http\Controllers;

use App\Models\ResourceFile;
use Illuminate\Http\Request;

class ResourceFileController extends Controller
{
    //
    public function __construct(Request $request) {

        $this->middleware('auth');
        //$this->middleware('permission:cria-tutorial', ['only' => ['create','store', 'edit','update','destroy']]);
    }


    public function index() {
        // Retorna os usuários cadastrados
        $resources = ResourceFile::get();

        $editar_resource = false;

        $user = \Auth::user();

        if($user->isDev() || $user->isAdmin())
        {
            $editar_resource = true;
        }


        return view('resources.arquivos.lista', compact('resources', 'editar_resource'));
    }
}
