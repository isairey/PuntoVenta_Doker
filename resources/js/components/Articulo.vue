<template>
   <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Listado de Categorías
                <button type="button" class="btn btn-secondary"  @click="abrirModal('articulo','registrar')">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarArticulo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="articulo in arrayArticulos" :key="articulo.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('articulo','actualizar',articulo)">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="articulo.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                 <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="articulo.codigo"></td>
                            <td v-text="articulo.nombre"></td>
                            <td v-text="articulo.nombre_categoria"></td>
                            <td v-text="articulo.precio_venta"></td>
                            <td v-text="articulo.stock"></td>
                            <td v-text="articulo.descripcion"></td>
                            <td>
                                <div v-if="articulo.condicion">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-warning">Inactivo</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                        <li class="page-item " v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)" >Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" :class="{'mostrar':modal}"  aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal" ></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                            <div class="col-md-9">
                                <select class="form-control"  v-model="idCategoria">
                                    <option value="0" disable> Seleccione </option>
                                    <option v-for="categoria in arrayCategorias" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                </select>     
                                <span class="help-block">(*) seleccione el nombre de la categoría</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                            <div class="col-md-9">
                                <input type="text" v-model="codigo" class="form-control" placeholder="Codigo de barras">
                                <barcode :value="codigo" :options="{format : 'EAN-13'}">
                                    Show this if the rendering fails.
                                </barcode>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre Articulo</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                <span class="help-block">(*) Ingrese el nombre de la categoría</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Precio de Venta</label>
                            <div class="col-md-9">
                            <input type="number" v-model="precio_venta" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                            <div class="col-md-9">
                            <input type="number" v-model="stock" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                            <div class="col-md-9">
                                <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese Descripcion">
                            </div>
                        </div>
                        <div class="form-group row div-error" v-show="errorArticulo" >
                            <div class="text-center text-error">
                                <div v-for="error in errorMsgArticulo" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion == 1"  class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                    <button type="button" v-if="tipoAccion == 2"  class="btn btn-primary" @click="actualizarArticulo()" >Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
</main>
</template>

<script>
    const axios = require('axios').default;
    import VueBarcode from 'vue-barcode';
    export default {
        data (){
            return {
                articulo_id: 0,
                idCategoria : 0,
                nombre_categoria: '',
                codigo : '',
                nombre : '',
                precio_venta: 0,
                stock: 0,
                descripcion : '',
                arrayArticulos: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorArticulo : 0,
                errorMsgArticulo : [],
                categoriaID : 0,
                pagination:{
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                arrayCategorias: []
            }
        },
        components: {
            'barcode': VueBarcode
        },
        computed:{
            isActived : function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return []; 
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2 );
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from ++;
                }
                return pagesArray;
            }
        },
        methods : {
            listarArticulo(page,buscar,criterio){  
                let me = this;
                var url = '/articulo?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArticulos = respuesta.articulos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            selectCategoria(){
                let me = this;
                var url = '/categoria/selectCategoria';
                axios.get(url).then(function (response) {
                     var respuesta = response.data;
                    me.arrayCategorias = respuesta.categorias;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .then(function () {
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarArticulo(page,buscar,criterio);
            },
            abrirModal(modelo, accion, data =[]){
                console.log("method openModal EJECUTE");
                switch(modelo){
                    case 'articulo':
                        {
                            switch(accion){
                                case 'registrar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Registrar articulo';
                                        this.idCategoria = 0;
                                        this.nombre_categoria = '';
                                        this.codigo = '';
                                        this.precio_venta = 0;
                                        this.descripcion = '';
                                        this.nombre = '';
                                        this.tipoAccion = 1;
                                        this.stock = 0;
                                        break ;

                                    }
                                case 'actualizar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = "Actualizar articulo";
                                        this.nombre = data['nombre'];
                                        this.descripcion = data['descripcion'];
                                        this.tipoAccion = 2;
                                        this.articulo_id = data['id'];
                                        this.idCategoria = data['idCategoria'];
                                        this.codigo = data['codigo'];
                                        this.stock = data['stock'];
                                        this.precio_venta = data['precio_venta'];
                                        break;
                                    }

                            }
                        }
                }
                this.selectCategoria();
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.idCategoria = 0;
                this.nombre_categoria = '';
                this.codigo = '';
                this.nombre = '';
                this.precio_venta = '';
                this.stock = 0;
                this.descripcion = '';
                this.errorArticulo = '';
            },
            registrarArticulo(){
                if(this.validarArticulo()){
                    return;
                }
                let me = this;
                axios.post('/articulo/registrar', {
                    'idcategoria' : this.idCategoria,
                    'codigo' : this.codigo,
                    'nombre' : me.nombre,
                    'stock': this.stock,
                    'precio_venta' : this.precio_venta,
                    'descripcion' : me.descripcion
                })
                .then(function (response) {
                    console.log("respuesta de registrar categoria");
                    console.log(response);
                    me.cerrarModal();
                    me.listarArticulo('1','','nombre');
                })
                .catch(function (error) {
                    console.log("ERROR al registrar categoria");
                    console.log(error);
                });
            },
            actualizarArticulo(){
                if(this.validarArticulo()){
                    return;
                }
                let me = this;
                axios.put('/articulo/actualizar', {
                    'id' : this.articulo_id,
                    'idcategoria' : this.idCategoria,
                    'codigo' : this.codigo,
                    'nombre' : me.nombre,
                    'stock': this.stock,
                    'precio_venta' : this.precio_venta,
                    'descripcion' : me.descripcion
                })
                .then(function (response) {
                    console.log(response);
                    me.cerrarModal();
                    me.listarArticulo('1','','nombre');
                })
                .catch(function (error) {
                    console.log("ERROR al Actualizar articulo");
                    console.log(error);
                });
            },
            desactivarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Està seguro de querer desactivar el articulo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'SI, desactivar!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/articulo/desactivar', {
                        'id' : id
                    })
                    .then(function (response) {
                        me.listarArticulo('1','','nombre');
                    })
                    .catch(function (error) {
                        console.log("ERROR al Actualizar articulo");
                        console.log(error);
                    });
                    swalWithBootstrapButtons.fire(
                    'Desactivado!',
                    'El articulo fue desactivado con exito.',
                    'success'
                    )
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelar',
                    'tu registro no fue desactivado :)',
                    'error'
                    )
                }
                })
            },
            activarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Està seguro de querer activar el articulo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'SI, activar!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/articulo/activar', {
                        'id' : id
                    })
                    .then(function (response) {
                        me.listarArticulo('1','','nombre');
                    })
                    .catch(function (error) {
                        console.log("ERROR al Actualizar el Ariculo");
                        console.log(error);
                    });
                    swalWithBootstrapButtons.fire(
                    'Activado!',
                    'El articulo fue activado con exito.',
                    'success'
                    )
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelar',
                    'tu registro no fue activado :)',
                    'error'
                    )
                }
                })
            },
            validarArticulo (){
                this.errorArticulo = 0;
                this.errorMsgArticulo = [];

                if(!this.nombre){
                    this.errorMsgArticulo.push("EL NOMBRE DEL ARTICULO ES OBLIGATORIO");
                    this.errorArticulo = 1;
                } 
                if(this.idCategoria == 0){
                    this.errorMsgArticulo.push("SELECCIONE UNA CATEGORIA");
                    this.errorArticulo = 1;
                } 
                if(!this.stock){
                    this.errorMsgArticulo.push("EL STOCK TIENE QUE SER UN NUMERO POSITIVO ENTERO");
                    this.errorArticulo = 1;
                }
                if(!this.precio_venta){
                    this.errorMsgArticulo.push("EL PRECIO DEL ARTICULO ES OBLIGATORIO   ");
                    this.errorArticulo = 1;
                }
                return this.errorArticulo;

            }
        },
        mounted() {
            console.log('Component mounted Example.');
            this.listarArticulo('1',this.buscar,this.criterio);
        }
    }
</script>
<style>
.modal-content{
	width : 100% !important;
	Position : absolute !important;
}
.mostrar {
	display : list-item !important;
	opacity : 1 !important;
	Position : absolute !important;
	background-color: #3c29297a !important;
}
.div-error{
    display: flex;
    justify-content: center;
}
.text-error{
    color: red !important;
    font-weight: bold;
}
</style>
