<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $personas = Persona::orderBy('id', 'desc')->paginate(2);
        } else {
            $personas = Persona::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(2);
        }

        return [
            'pagination' => [
                'total' => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page' => $personas->perPage(),
                'last_page' => $personas->lastPage(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function store(Request $request)
    {
        $personas = new Persona();
        $personas->nombre = $request->nombre;
        $personas->tipo_documento = $request->tipo_documento;
        $personas->num_documento = $request->num_documento;
        $personas->direccion = $request->direccion;
        $personas->telefono = $request->telefono;
        $personas->email = $request->email;
        $personas->save();
    }

    public function update(Request $request)
    {
        $personas = Persona::findOrFail($request->id);
        $personas->nombre = $request->nombre;
        $personas->tipo_documento = $request->tipo_documento;
        $personas->num_documento = $request->num_documento;
        $personas->direccion = $request->direccion;
        $personas->telefono = $request->telefono;
        $personas->email = $request->email;
        $personas->save();
    }
}
