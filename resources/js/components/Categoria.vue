<template>
   <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Listado de Categorías
                <button type="button" class="btn btn-secondary"  @click="abrirModal('categoria','registrar')">
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
                            <input type="text" v-model="buscar" @keyup.enter="listarCategoria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarCategoria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="categoria in arrayCategorias" :key="categoria.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('categoria','actualizar',categoria)">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="categoria.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                 <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                                
                            </td>
                            <td v-text="categoria.nombre"></td>
                            <td v-text="categoria.descripcion"></td>
                            <td>
                                <div v-if="categoria.condicion">
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
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                <span class="help-block">(*) Ingrese el nombre de la categoría</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                            <div class="col-md-9">
                                <input type="email" v-model="descripcion" class="form-control" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="form-group row div-error" v-show="errorCategoria" >
                            <div class="text-center text-error">
                                <div v-for="error in errorMsgCategoria" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion == 1"  class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
                    <button type="button" v-if="tipoAccion == 2"  class="btn btn-primary" @click="actualizarCategoria()" >Actualizar</button>
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
    export default {
        data (){
            return {
                nombre : '',
                descripcion : '',
                arrayCategorias: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCategoria : 0,
                errorMsgCategoria : [],
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
                buscar : ''
            }
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
            listarCategoria(page,buscar,criterio){
                let me = this;
                var url = '/categoria?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCategorias = respuesta.categorias.data;
                    me.pagination = respuesta.pagination;
                    // handle success
                    //me.arrayCategorias = response.data;
                    console.log("LISTAR CATEGORIASSS");
                    console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarCategoria(page,buscar,criterio);
            },
            abrirModal(modelo, accion, data =[]){
                console.log("method openModal EJECUTE");
                switch(modelo){
                    case 'categoria':
                        {
                            switch(accion){
                                case 'registrar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Registrar categoria';
                                        this.descripcion = '';
                                        this.nombre = '';
                                        this.tipoAccion = 1;
                                        break ;

                                    }
                                case 'actualizar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = "Actualizar categoria";
                                        this.nombre = data['nombre'];
                                        this.descripcion = data['descripcion'];
                                        this.tipoAccion = 2;
                                        this.categoriaID = data['id'];
                                        break;
                                    }

                            }
                        }
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.descripcion = '';
            },
            registrarCategoria(){
                console.log("Hola Registrando categoria");
                if(this.validarCategoria()){
                    return;
                }
                let me = this;
                axios.post('/categoria/registrar', {
                    'nombre' : me.nombre,
                    'descripcion' : me.descripcion
                })
                .then(function (response) {
                    console.log("respuesta de registrar categoria");
                    console.log(response);
                    me.cerrarModal();
                    me.listarCategoria('1','','nombre');
                })
                .catch(function (error) {
                    console.log("ERROR al registrar categoria");
                    console.log(error);
                });
            },
            actualizarCategoria(){
                if(this.validarCategoria()){
                    return;
                }
                let me = this;
                axios.put('/categoria/actualizar', {
                    'nombre' : me.nombre,
                    'descripcion' : me.descripcion,
                    'id' : me.categoriaID
                })
                .then(function (response) {
                    console.log(response);
                    me.cerrarModal();
                    me.listarCategoria('1','','nombre');
                })
                .catch(function (error) {
                    console.log("ERROR al Actualizar categoria");
                    console.log(error);
                });
            },
            desactivarCategoria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Està seguro de querer desactivar la categoria?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'SI, desactivar!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/categoria/desactivar', {
                        'id' : id
                    })
                    .then(function (response) {
                        me.listarCategoria('1','','nombre');
                    })
                    .catch(function (error) {
                        console.log("ERROR al Actualizar categoria");
                        console.log(error);
                    });
                    swalWithBootstrapButtons.fire(
                    'Desactivado!',
                    'la categoria fue desactivada con exito.',
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
            activarCategoria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Està seguro de querer activar la categoria?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'SI, activar!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/categoria/activar', {
                        'id' : id
                    })
                    .then(function (response) {
                        me.listarCategoria('1','','nombre');
                    })
                    .catch(function (error) {
                        console.log("ERROR al Actualizar categoria");
                        console.log(error);
                    });
                    swalWithBootstrapButtons.fire(
                    'Activado!',
                    'la categoria fue activada con exito.',
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
            validarCategoria(){
                this.errorCategoria = 0;
                this.errorMsgCategoria = [];

                if(!this.nombre){
                    this.errorMsgCategoria.push("EL NOMBRE DE LA CATEGORIA ES OBLIGATORIO");
                    this.errorCategoria = 1;
                    console.log("Nombre categoria vacio");
                } 
                return this.errorCategoria;

            }
        },
        mounted() {
            console.log('Component mounted Example.');
            this.listarCategoria('1',this.buscar,this.criterio);
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
