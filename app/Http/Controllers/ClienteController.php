<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Endereco;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('endereco')->paginate(5);

        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cpf' => 'required|unique:clientes|max:11',
            'nome' => 'required',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'endereco' => 'required',
            'estado' => 'required',
            'cidade' => 'required',
        ]);
    
        $enderecoData = [
            'endereco' => $request->endereco,
            'estado' => $request->estado,
            'cidade' => $request->cidade,
        ];
    
        $endereco = Endereco::create($enderecoData);
    
        $clienteData = $request->except(['endereco', 'estado', 'cidade']);
        $clienteData['endereco_id'] = $endereco->id;
        $cliente = Cliente::create($clienteData);
    
        return response()->json($cliente, 201);
    }

    private function updateEndereco(Request $request, $id)
    {
        $endereco = Endereco::find($id);
    
        $request->validate([
            'endereco' => 'required',
            'estado' => 'required',
            'cidade' => 'required',
        ]);
    
        $endereco->update([
            'endereco' => $request->endereco,
            'estado' => $request->estado,
            'cidade' => $request->cidade,
        ]);
    
        return response()->json($endereco);
    }

    public function show($id)
    {
        $cliente = Cliente::with('endereco')->findOrFail($id);

        return $cliente;
    }

    public function search(Request $request)
    {
        $clientes = Cliente::query();
    
        if ($request->nome) {
            $clientes->where('nome', 'like', '%' . $request->nome . '%');
        }
    
        if ($request->cpf) {
            $clientes->where('cpf', 'like', '%' . $request->cpf . '%');
        }
    
        if ($request->data_nascimento) {
            $clientes->where('data_nascimento', '=', $request->data_nascimento);
        }
    
        if ($request->sexo) {
            $clientes->where('sexo', '=', $request->sexo);
        }
    
        if ($request->endereco) {
            $clientes->whereHas('endereco', function ($query) use ($request) {
                $query->where('endereco', 'like', '%' . $request->endereco . '%');
            });
        }
    
        if ($request->estado) {
            $clientes->whereHas('endereco', function ($query) use ($request) {
                $query->where('estado', '=', $request->estado);
            });
        }
    
        if ($request->cidade) {
            $clientes->whereHas('endereco', function ($query) use ($request) {
                $query->where('cidade', 'like', '%' . $request->cidade . '%');
            });
        }

        return $clientes->with('endereco')->paginate(5);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'cpf' => 'required|max:11|unique:clientes,cpf,' . $cliente->id,
            'nome' => 'required',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'endereco' => 'required',
            'estado' => 'required',
            'cidade' => 'required',
        ]);
    
        // Verificar se o CPF pertence ao mesmo cliente que está realizando a edição
        if ($cliente->cpf !== $request->cpf) {
            $cpfEmUso = Cliente::where('cpf', $request->cpf)->exists();
            if ($cpfEmUso) {
                return response()->json(['error' => 'Este CPF já está sendo utilizado por outro cliente'], 422);
            }
        }
        // dd($cliente);
        $this->updateEndereco($request, $cliente->endereco_id);
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