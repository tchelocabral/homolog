<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryFormat;
use Session;

class DeliveryFormatController extends Controller
{
    protected $request;
    protected $deliveryFormat;

    public function __construct(Request $request, DeliveryFormat $deliveryFormat) {

        $this->request = $request;
        $this->deliveryFormat = $deliveryFormat;
        $this->middleware('auth');
        $this->middleware('permission:lista-tipo-job');
        $this->middleware('permission:cria-tipo-job', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-tipo-job', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-tipo-job', ['only' => ['destroy']]);
    }

    public function index() {
        
        $deliverysformat = DeliveryFormat::all();
        return view('deliveryformat.lista', compact('deliverysformat'));
    }

    public function create() {

        return view('deliveryformat.novo');
    }

    public function store(Request $request) {

        // dd($request);

        $validator = $this->validate($request, [
            'nome' => 'required|unique:delivery_format,nome',
        ]);

        try{
            
            \DB::beginTransaction();

            //dd($request->request);
            
            $deliveryformat = DeliveryFormat::create($request->except(['_token']));
            $deliveryformat->save();

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content',  __('messages.Novo Delivery Format incluído com sucesso!'));
            $request->session()->flash('message.erro', '');

            return redirect()->route('deliveryformat.show', encrypt($deliveryformat->id));

        }catch (\Exception $exception){
            
            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content',  __('messages.O Delivery Format não pôde ser incluído.'));
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('deliveryformat.index');
    }


    public function show($id) {

        $id = decrypt($id);
        //$tasks = Task::orderBy('nome', 'asc')->get();
        $deliveryformat = DeliveryFormat::find($id);

        $deliveryformat->podeApagar = ($deliveryformat->jobs && count($deliveryformat->jobs) > 0) ? false : true;
        
        $deliveryformat->lista_tipos_troca =null;
        if(!$deliveryformat->podeApagar)
        {
            $deliveryformat->lista_tipos_troca = DeliveryFormat::where('id','!=', $id)->get();
        }

        return \View::make('deliveryformat.detalhes', compact('deliveryformat'));
    }

    public function edit($id) {
        $id = decrypt($id);
        //
        $deliveryformat          = DeliveryFormat::find($id);

        return \View::make('deliveryformat.edit', compact(['deliveryformat']));
    }

    public function update(Request $request, $id) {

        $id = decrypt($id);
        //dd($request);

        try{

            $deliveryformat = DeliveryFormat::find($id);

            $deliveryformat->fill($request->all());
           
            $deliveryformat->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content',  __('messages.O Delivery Format foi atualizado com sucesso!'));
            $request->session()->flash('message.erro', '');

            return redirect()->route('deliveryformat.index');

        }catch (\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content',  __('messages.O Delivery Format não pôde ser atualizado.'));
            $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id) {

        $id = decrypt($id);
        try{
            
            $deliveryformat = DeliveryFormat::findOrFail($id);
            $deliveryformat->delete();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content',  __('messages.O Delivery Format excluído com sucesso!'));
            Session::flash('message.erro', '');


            return redirect()->route('deliveryformat.index');

        } catch (\Exception $exception){

            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content',  __('messages.O Delivery Format não pôde ser excluído.'));
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->route('deliveryformat.index');
        }
    }

      /* Retorna os dados de um Tipo de Job */
      public function dados(Request $request) {
        
        if($request->ajax()){
            return DeliveryFormat::get()->find($request->get('id'));
        } else {
            return true;
        }
    }
    
}
