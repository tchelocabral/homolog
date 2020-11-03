<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use URL;

class TermController extends Controller
{
    

    public function termosDeUso(Request $request){

        // $url_termo = 'storage/docs/freelance_ff_terms_en.pdf';

        // return response()->file($url_termo);

        return view('docs.termos');

    }


}
