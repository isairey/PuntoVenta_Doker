<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleIngreso;
use App\Models\Ingreso;
use Carbon\Carbon;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante',
                    'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id','desc')->paginate(2);
        } else {
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante',
                    'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado','personas.nombre','users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%' . $buscar . '%')->orderBy('ingresos.id', 'desc')->paginate(2);
        }

        return [
            'pagination' => [
                'total' => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page' => $ingresos->perPage(),
                'last_page' => $ingresos->lastPage(),
                'from' => $ingresos->firstItem(),
                'to' => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }

    public function store(Request $request)
    {   
        try {
            DB::beginTransaction();
            
            $myTime = Carbon::now('America/Mexico_City');
            
            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $request->fecha_hora;
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = $request->estado;
            $ingreso->save();

            //registramos detalles de ingreso 
            $detalles = $request->data;
            foreach($detalles as $ep => $det){
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
            }
            
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        
    }

    public function desactivar(Request $request)
    {
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->condicion = 'Anulado';
        $ingreso->save();
    }
}
