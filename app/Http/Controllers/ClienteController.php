<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();

        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cpf' => 'required|unique:clientes|max:11',
            'nome' => 'required',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'endereco' => 'required|array',
            'endereco.endereco' => 'required',
            'endereco.estado' => 'required',
            'endereco.cidade' => 'required',
        ]);
    
        $endereco = Endereco::create($request->input('endereco'));
    
        $clienteData = $request->except('endereco');
        $clienteData['endereco_id'] = $endereco->id;
        
        $cliente = Cliente::create($clienteData);
    
        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);

        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'cpf' => 'required|unique:clientes,cpf,'.$cliente->id.'|max:11',
            'nome' => 'required',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'endereco_id' => 'required'
        ]);

        $cliente->update($request->all());

        return response()->json($cliente);
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return response()->json(null, 204);
    }
}