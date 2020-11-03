<?php

namespace App\Http\Controllers;

use App\Models\Seguidor;
use Illuminate\Http\Request;

class SeguidorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        try{

            // Criar relação de Seguir
            $validate = $this->validate($request, [
                'user_id_seguir' => 'int',
                'user_id_seguindo' => 'int',
            ]);


            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Seguindo!');
            $request->session()->flash('message.erro', '');


        }catch (\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Não foi possível criar relacionamento.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        } 
        return redirect('app/clientes');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function show(Seguidor $seguidor) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function edit(Seguidor $seguidor) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seguidor $seguidor) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seguidor $seguidor) {
        //
    }

}
