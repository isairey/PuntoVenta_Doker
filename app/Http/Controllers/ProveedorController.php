<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email','proveedores.contacto','proveedores.telefono_contacto')
            ->orderBy('personas.id','desc')->paginate(2);
        } else {
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email','proveedores.contacto','proveedores.telefono_contacto')
            ->where('personas.'.$criterio, 'like', '%' . $buscar . '%')->orderBy('personas.id', 'desc')->paginate(2);
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

    public function selectProveedor(Request $request){
        $filtro = $request->filtro;
        $proveedores = Proveedor::join('personas','proveedores.id','=','personas.id')
        ->where('personas.nombre','like','%'.$filtro.'%')
        ->orWhere('personas.num_documento','like','%'.$filtro.'%')
        ->select('personas.id','personas.nombre','personas.num_documento')
        ->orderBy('personas.nombre','asc')->get();

        return ['proveedores'=>$proveedores];
    }

    public function store(Request $request)
    {   
        try {
            //code...
            DB::beginTransaction();
            
            $personas = new Persona();
            $personas->nombre = $request->nombre;
            $personas->tipo_documento = $request->tipo_documento;
            $personas->num_documento = $request->num_documento;
            $personas->direccion = $request->direccion;
            $personas->telefono = $request->telefono;
            $personas->email = $request->email;
            $personas->save();

            $proveedor = new Proveedor();
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->id = $personas->id;
            $proveedor->save();
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        
    }

    public function update(Request $request)
    {
        try {
            //code...
            DB::beginTransaction();

            $proveedor = Proveedor::findOrFail($request->id);
            $personas  = Persona::findOrFail($proveedor->id);
            
            $personas->nombre = $request->nombre;
            $personas->tipo_documento = $request->tipo_documento;
            $personas->num_documento = $request->num_documento;
            $personas->direccion = $request->direccion;
            $personas->telefono = $request->telefono;
            $personas->email = $request->email;
            $personas->save();

            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
