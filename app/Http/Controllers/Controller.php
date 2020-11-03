<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use \Crypt;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    # Os Controllers precisam retirar ascentos de Strings de nomes, etc.
    public static function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }


    /**
     * Função de Upload de Arquivo.
     * Tem como base a pasta storage/app/public/public
     * Retira o caminho da pasta public que foi adicionada.
     *
     * @param [file] $file - to upload
     * @param [text] $caminho - path to store
     * @param boolean $timestamp - add timestamp on file_name 
     * @return boolean fez o upload, retorna o caminho do arquivo salvo junto com o nome e a extensão
     */
    public static function upload($file, $caminho, $timestamp = true){
        
        # Verifica arquivo e se é válido
        // dd($file->isValid());
        if($file && $file->isValid()){
            
            #se adiciona timestamp
            $ts = $timestamp ? str_replace([' ', ':', '-'], '', \Carbon\Carbon::now()->toDateTimeString()) . '_' : '';
            
            # monta o caminho da pasta
            $pasta_midias = 'public' . DIRECTORY_SEPARATOR . $caminho;

            # retirar acentos e espaços do nome do arquivo
            $nome = $ts . Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
            
            # salva arquivo na pasta
            $upload = $file->storeAs($pasta_midias, $nome);
            
            # retira 'public/' do caminho do arquivo para salvar no banco de dados
            $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);
            
            # se fez o upload, retorna o caminho do arquivo salvo junto com o nome e a extensão
            // dd($upload);
            if($upload){
                return $pasta_midias . DIRECTORY_SEPARATOR . $nome;
            }
        }
        return false;
    }



    /**
     * Redireciona em caso de usuário com nova senha
     *
     * @return boolean
     */
    public static function isNovaSenha(){
        // dd(\Auth::user());
        $rotas_liberadas = [ 
            'user.nova.senha',
            'logout',
            'user.gravar.senha',
            'login',
            'efetua.login'
        ];
        $rota_liberada = in_array(\Route::current()->getName(), $rotas_liberadas)  ;
        if (\Auth::check() && \Auth::user()->nova_senha && !$rota_liberada) {
            return redirect()->route('user.nova.senha');
        }
    }



}
