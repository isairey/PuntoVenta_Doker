<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $articulos = Articulo::join('categorias','articulos.idCategoria','=','categorias.id')
            ->select('articulos.id','articulos.idCategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id', 'desc')->paginate(2);
        } else {
            $articulos = Articulo::join('categorias','articulos.idCategoria','=','categorias.id')
            ->select('articulos.id','articulos.idCategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%' . $buscar . '%')
            ->orderBy('articulos.id', 'desc')->paginate(2);
        }

        return [
            'pagination' => [
                'total'         =>$articulos ->total(),
                'current_page'  =>$articulos ->currentPage(),
                'per_page'      =>$articulos ->perPage(),
                'last_page'     =>$articulos ->lastPage(),
                'from'          =>$articulos ->firstItem(),
                'to'            =>$articulos ->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }

    public function buscarArticulo(Request $request){
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=',$filtro)
        ->select('id','nombre')->orderBy('nombre','asc')->take(1)->get();
        return ['articulos' => $articulos];
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $articulo = new Articulo();
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->save();
    }

  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $articulo = Articulo::findOrFail($request->id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->save();
    }

    /**
     * Activa  una categoria
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar(Request $request)
    {
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = 1;
        $articulo->save();
    }

    /**
     * Desactiva  una categoria
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar(Request $request)
    {
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = 0;
        $articulo->save();
    }
}
