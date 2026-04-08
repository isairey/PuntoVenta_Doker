
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
                <i class="fa fa-align-justify"></i> Listado de Ingresos
                <button type="button" class="btn btn-secondary"  @click="mostrarDetalle()">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <template v-if="listado">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="tipo_comprobante">tipo_comprobante</option>
                                <option value="num_comprobante">num comprobante</option>
                                <option value="fecha_hora">fecha hora</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Proveedor</th>
                                <th>Tipo Comprobante</th>
                                <th>serie comprobante</th>
                                <th>numero comprobante</th>
                                <th>Fecha Hora</th>
                                <th>Total</th>
                                <th>Impuestos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" @click="abrirModal('ingreso','actualizar',ingreso)">
                                        <i class="icon-eye"></i>
                                    </button> 
                                    &nbsp;
                                    <template v-if="ingreso.estado == 'Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)" >
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="ingreso.usuario"></td>
                                <td v-text="ingreso.nombre"></td>
                                <td v-text="ingreso.tipo_comprobante"></td>
                                <td v-text="ingreso.serie_comprobante"></td>
                                <td v-text="ingreso.num_comprobante"></td>
                                <td v-text="ingreso.fecha_hora"></td>
                                <td v-text="ingreso.total"></td>
                                <td v-text="ingreso.impuesto"></td>
                                <td v-text="ingreso.estado"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
            </template>
            <template v-else>
            <div class="card-body">
                <div class="form-group row border">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="">Proveedor</label>
                            <v-select 
                                @search="selectProveedor"
                                label="nombre"
                                :options="arrayProveedor"
                                placeholder="Buscar Proveedor"
                                @input="getDatosProveedor"
                                >
                            </v-select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="">Impuesto</label>
                        <input type="text" class="form-control" v-model="impuesto">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Tipo Comprobante</label>
                            <select name="" id="" class="form-control" v-model="tipo_comprobante">
                                <option value="0">Seleccione</option>
                                <option value="BOLETA">Boleta</option>
                                <option value="FACTURA">Factura</option>
                                <option value="TICKET">Ticket</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Serie Comprobante</label>
                            <input type="text" name="" id="" class="form-control" v-model="serie_comprobante">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Numero Comprobante</label>
                            <input type="text" class="form-control" v-model="num_comprobante">
                        </div>
                    </div>
                </div>
                <div class="form-group row border">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Artículo <span style="color:red" v-show="idarticulo==0">(* Seleccione)</span> </label>
                            <div class="form-inline">
                                <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese Articulo">
                                <button class="btn btn-primary">...</button>
                                <input type="text" readonly class="form-control" v-model="articulo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Precio <span style="color:red" v-show="precio==0"> (*Ingrese)</span> </label>
                            <input type="text" value="0" step="any" class="form-control" v-model="precio">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Cantidad <span style="color:red" v-show="cantidad==0" >(*Ingrese)</span></label>
                            <input type="number" value="0" class="form-control" v-model="cantidad">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i> </button>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group row border">
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Articulo</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayDetalle.length">
                                <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                    <td>
                                        <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                            <i class="icon-close"></i>
                                        </button>
                                    </td>
                                    <td v-text="detalle.articulo"></td>
                                    <td>
                                        <input v-model="detalle.precio" type="number" value="2" class="form-control">
                                    </td>
                                    <td>
                                        <input v-model="detalle.cantidad" type="number" value="3" class="form-control">
                                    </td>
                                    <td> {{detalle.precio * detalle.cantidad}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right"><strong>Total parcial</strong></td>
                                    <td>$ {{totalParcial = (total - totalImpuesto).toFixed(2) }} </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right"><strong>Total Impuesto</strong></td>
                                    <td>$ {{totalImpuesto = ((total*impuesto)/(1+impuesto)).toFixed(2) }} </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right"><strong>Total neto</strong></td>
                                    <td>$ {{total=calcularTotal}} </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <td colspan="5">NO HAY ARTICULOS AGREGADOS</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrrar</button>
                        <button type="button" class="btn btn-primary" @click="registrarIngreso()"> Registrar compra</button>
                    </div>
                </div>
            </div>
            </template>
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
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion == 1"  class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                    <button type="button" v-if="tipoAccion == 2"  class="btn btn-primary" @click="actualizarPersona()" >Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin del modal-->
</main>
</template>

<script>
import 'vue-select/dist/vue-select.css';
import vSelect from 'vue-select'

//Vue.component('v-select', vSelect)
//const vSelect = require('vue-select').default;
    const axios = require('axios').default;
    //const vSelect = require('vue-select').default;
    
    export default {
        data (){
            return {
                ingreso_id : '',
                idproveedor : 0,
                nombre : '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto: '0.16',
                total : 0.0,
                totalImpuesto : 0.0,
                totalParcial : 0.0,
                arrayIngreso : '',
                arrayProveedor : [],
                arrayDetalle : [],
                listado : 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMsgIngreso : [],
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
                criterio : 'num_comprobante',
                buscar : '',
                contacto : '',
                telefono_contacto : '',
                idarticulo: 0,
                precio : 0,
                cantidad : 0,
                arrayArticulo : [],
                codigo : '',
                articulo : ''
            }
        },
        components:{
            vSelect
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
            },
            calcularTotal: function(){
                var resultado = 0;
                for (let i = 0; i < this.arrayDetalle.length; i++) {
                    resultado = resultado+(this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad);
                    
                }
                return resultado;
            }
        },
        methods : {
            listarIngreso(page,buscar,criterio){
                let me = this;
                var url = '/ingreso?page='+page+'&buscar='+buscar+'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
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
            selectProveedor(search,loading){ 
                let me = this;
                loading(true);
                var url = '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    
                    var respuesta = response.data;
                    //q:search
                    me.arrayProveedor = respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            getDatosProveedor(val1){ 
                let me  = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },
            buscarArticulo(){
                let me = this;
                var url = '/articulo/buscarArticulo?filtro='+me.codigo;
                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayArticulo  = respuesta.articulos;
                    if(me.arrayArticulo.length>0){
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                    }else{
                        me.articulo  = 'No existe erticulo';
                        me.idarticulo = 0; 
                    } 
                }).catch(function (error){
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarIngreso(page,buscar,criterio);
            },
            encuentra(id){
                var sw = false;
                for (let index = 0; index < this.arrayDetalle.length; index++) {
                    if(this.arrayDetalle[index].idarticulo == id){
                        sw = true;
                    }
                }
                return sw;
            },
            agregarDetalle(){
                let me = this;
                if(me.idarticulo == 0 || me.cantidad == 0 || me.precio == 0){

                }else{
                    if(me.encuentra(me.idarticulo)){ 
                        //alert("sorprise MDFKR");
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire(
                            'Cancelar',
                            'Este aarticulo ya se encuentra registrado',
                            'error'
                            );
                    }else {
                            me.arrayDetalle.push({
                            idarticulo : me.idarticulo,
                            articulo : me.articulo,
                            cantidad : me.cantidad,
                            precio : me.precio
                        });
                        me.codigo = "";
                        me.idarticulo = 0;
                        me.articulo = "";
                        me.cantidad = 0;
                        me.precio = 0;
                    }
                    

                }
                
            },
            eliminarDetalle(id){
                let me = this;
                me.arrayDetalle.splice(id,1);
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
            mostrarDetalle(){
                this.listado = 0;
            },
            ocultarDetalle(){
                this.listado = 1;
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
            this.listarIngreso('1',this.buscar,this.criterio);
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
@media(min-width: 600px){
    .btnagregar{
        margin-top: 2rem;
    }
}
</style>
