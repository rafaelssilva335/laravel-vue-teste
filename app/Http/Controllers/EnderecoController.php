<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function getCidadesPorEstado($estado)
    {
        $cidades = Endereco::select('cidade')->where('estado', $estado)->groupBy('cidade')->get();
        return response()->json($cidades);
    }
    
    public function getEstados()
    {
        $estados = Endereco::select('estado')->groupBy('estado')->get();
        return response()->json($estados);
    }
}
