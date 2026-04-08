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
                <i class="fa fa-align-justify"></i> Listado de Pesonas
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="persona in arrayPersonas" :key="persona.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('persona','actualizar',persona)">
                                    <i class="icon-pencil"></i>
                                </button> 
                            </td>
                            <td v-text="persona.nombre"></td>
                            <td v-text="persona.tipo_documento"></td>
                            <td v-text="persona.num_documento"></td>
                            <td v-text="persona.direccion"></td>
                            <td v-text="persona.telefono"></td>
                            <td v-text="persona.email"></td>
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
                descripcion : '',
                arrayPersonas: [],
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
            listarPersona(page,buscar,criterio){
                let me = this;
                var url = '/cliente?page='+page+'&buscar='+buscar+'&criterio='+criterio;
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
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarPersona(page,buscar,criterio);
            },
            abrirModal(modelo, accion, data =[]){
                console.log("method openModal EJECUTE");
                switch(modelo){
                    case 'persona':
                        {
                            switch(accion){
                                case 'registrar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Registrar Cliente';
                                        this.descripcion = '';
                                        this.nombre = '';
                                        this.descripcion = '';
                                        this.tipo_documento ='DNI';
                                        this.num_documento = '';
                                        this.direccion  = '';
                                        this.telefono = '';
                                        this.email = '';
                                        this.errorMsgPersona = 0;
                                        this.tipoAccion = 1;
                                        break ;

                                    }
                                case 'actualizar' :
                                    {
                                        this.modal = 1;
                                        this.tituloModal = "Actualizar Cliente";
                                        this.nombre = data['nombre'];
                                        this.tipoAccion = 2;
                                        this.persona_id = data['id'];
                                        this.tipo_documento = data['tipo_documento'];
                                        this.num_documento = data['num_documento'];
                                        this.direccion  = data['direccion'];
                                        this.telefono = data['telefono'];
                                        this.email = data['email'];
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
            },
            registrarPersona(){
                console.log("Hola Registrando persona");
                if(this.validarPersona()){
                    return;
                }
                let me = this;
                axios.post('/cliente/registrar', {
                    'nombre' : me.nombre,
                    'tipo_documento' :this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email
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
                axios.put('/cliente/actualizar', {
                    'nombre' : me.nombre,
                    'tipo_documento' :this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'id' : this.persona_id
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

            }
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
