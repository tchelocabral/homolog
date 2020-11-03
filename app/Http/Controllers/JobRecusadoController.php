<?php

namespace App\Http\Controllers;

use App\Models\JobRecusado;
use App\Models\Job;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class JobRecusadoController extends Controller
{
    protected $request;
    protected $jobRecusado;


    public function __construct(Request $request, JobRecusado $jobRecusado) { 
        
        $this->request = $request;
        $this->jobRecusado = $jobRecusado;
        $this->middleware('auth');
        $this->middleware('permission:lista-job');
        $this->middleware('permission:cria-job', ['only' => ['create','store', 'storeAvulso']]);
        $this->middleware('permission:atualiza-job', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-job', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $jobs = JobRecusado::all();

        return view('jobRecusado.lista', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show(JobRecusado $jobRecusado)
    {
        //
    }

}
