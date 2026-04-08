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
                <i class="fa fa-align-justify"></i> Listado de Usuarios
                <button type="button" class="btn btn-secondary"  @click="abrirModal('persona','registrar')">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="num_documento">Num Documento</option>
                                <option value="email">Email</option>
                                <option value="telefono">Telefono</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarPersona(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Tipo documento</th>
                            <th>Numero</th>
                            <th>Direccion</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="persona in arrayPersonas" :key="persona.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('persona','actualizar',persona)">
                                    <i class="icon-pencil"></i>
                                </button> 
                                &nbsp;
                                <template v-if="persona.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(persona.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                 <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activarUsuario(persona.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="persona.nombre"></td>
                            <td v-text="persona.tipo_documento"></td>
                            <td v-text="persona.num_documento"></td>
                            <td v-text="persona.direccion"></td>
                            <td v-text="persona.telefono"></td>
                            <td v-text="persona.email"></td>
                            <td v-text="persona.usuario"></td>
                            <td v-text="persona.rol"></td>
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
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">
                                <span class="help-block">(*) Ingrese el nombre de la persona</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Tipo documento</label>
                            <div class="col-md-9">
                                <select  v-model="tipo_documento" class="form-control">
                                    <option value="DNI">DNI</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                    <option value="Credencial">Credencial</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Numero Documento</label>
                            <div class="col-md-9">
                                <input type="text" v-model="num_documento" class="form-control" placeholder="Numero del documento">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                            <div class="col-md-9">
                                <input type="text" v-model="telefono" class="form-control" placeholder="Nombre de la persona">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Email</label>
                            <div class="col-md-9">
                                <input type="text" v-model="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Roles</label>
                            <div class="col-md-9">
                                <select class="form-control"  v-model="idrol">
                                    <option value="0">Seleccione un Rol </option>
                                    <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre" ></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Usuario (*)</label>
                            <div class="col-md-9">
                                <input type="text" v-model="usuario" class="form-control" placeholder="Nombre de usuario">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Password (*)</label>
                            <div class="col-md-9">
                                <input type="password" v-model="password" class="form-control" placeholder="password">
                            </div>
                        </div>
                        <div class="form-group row div-error" v-show="errorPersona" >
                            <div class="text-center text-error">
                                <div v-for="error in errorMsgPersona" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion == 1"  class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                    <button type="button" v-if="tipoAccion == 2"  class="btn btn-primary" @click="actualizarPersona()" >Actualizar</button>
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
                persona_id : '',
                nombre : '',
                tipo_documento : '',
                num_documento : '',
                direccion : '',
                telefono : '',
                email : '',
                usuario: '',
                password : '',
                idrol : 0,
                descripcion : '',
                arrayPersonas: [],
                arrayRol : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPersona : 0,
                errorMsgPersona : [],
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
                contacto : '',
                telefono_contacto : '',
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
            listarPersona(page,buscar,criterio){
                let me = this;
                var url = '/user?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonas = respuesta.personas.data;
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
            selectRol(){
                let me = this;
                var url = '/rol/selectRol';
                axios.get(url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayRol = respuesta.roles;
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
                me.listarPersona(page,buscar,criterio);
            },
            abrirModal(modelo, accion, data =[]){
                this.selectRol();
                switch(modelo){
                    case 'persona':
                        {
                            switch(accion){
                                case 'registrar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Registrar Usuario';
                                        this.descripcion = '';
                                        this.nombre = '';
                                        this.descripcion = '';
                                        this.tipo_documento ='DNI';
                                        this.num_documento = '';
                                        this.direccion  = '';
                                        this.telefono = '';
                                        this.email = '';
                                        this.usuario = '';
                                        this.tipoAccion = 1;
                                        this.password = '';
                                        this.idrol = 0;
                                        break ;

                                    }
                                case 'actualizar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = "Actualizar Usuario";
                                        this.nombre = data['nombre'];
                                        this.tipoAccion = 2;
                                        this.persona_id = data['id'];
                                        this.tipo_documento = data['tipo_documento'];
                                        this.num_documento = data['num_documento'];
                                        this.direccion  = data['direccion'];
                                        this.telefono = data['telefono'];
                                        this.email = data['email'];
                                        this.usuario = data['usuario'];
                                        this.password = data['password'];
                                        this.idrol = data['idrol'];
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
                this.tipo_documento ='';
                this.num_documento = '';
                this.direccion  = '';
                this.telefono = '';
                this.email = '';
                this.errorMsgPersona = 0;
                this.usuario = '';
                this.password = '';
                this.idrol = 0;
            },
            registrarPersona(){
                if(this.validarPersona()){
                    return;
                }
                let me = this;
                axios.post('/user/registrar', {
                    'nombre' : me.nombre,
                    'tipo_documento' :this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarPersona('1','','nombre');
                })
                .catch(function (error) {
                    console.log("ERROR al registrar Persona");
                    console.log(error);
                });
            },
            actualizarPersona(){
                if(this.validarPersona()){
                    return;
                }
                let me = this;
                axios.put('/user/actualizar', {
                    'nombre' : me.nombre,
                    'tipo_documento' :this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'id' : this.persona_id,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol
                })
                .then(function (response) {
                    console.log(response);
                    me.cerrarModal();
                    me.listarPersona('1','','nombre');
                })
                .catch(function (error) {
                    console.log("ERROR al Actualizar Persona");
                    console.log(error);
                });
            },
            validarPersona(){
                this.errorPersona = 0;
                this.errorMsgPersona = [];

                if(!this.nombre){
                    this.errorMsgPersona.push("EL NOMBRE DE LA PERSONA ES OBLIGATORIO");
                    this.errorPersona = 1;
                } 
                return this.errorPersona;

            },
             desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Està seguro de querer desactivar el usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'SI, desactivar!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/user/desactivar', {
                        'id' : id
                    })
                    .then(function (response) {
                        me.listarPersona('1','','nombre');
                    })
                    .catch(function (error) {
                        
                    });
                    swalWithBootstrapButtons.fire(
                    'Desactivado!',
                    'El usuario fue  desactivado con exito.',
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
            activarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Està seguro de querer activar el Usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'SI, activar!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/user/activar', {
                        'id' : id
                    })
                    .then(function (response) {
                        me.listarPersona('1','','nombre');
                    })
                    .catch(function (error) {
                        console.log("ERROR al Actualizar usuario");
                        console.log(error);
                    });
                    swalWithBootstrapButtons.fire(
                    'Activado!',
                    'El usuario fue actiado con exito.',
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
        },
        mounted() {
            console.log('Component mounted Example.');
            this.listarPersona('1',this.buscar,this.criterio);
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
