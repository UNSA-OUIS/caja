<template>            
    <div>            
        <div class="row mb-2">
            <div class="col-lg-12">
                <div class="text-right">
                    <b-button v-if="totalRows == 0 && opcion_busqueda === 'DNI'" size="sm" variant="success" v-b-modal.add-particular @click="buscar_sunat">
                        Nuevo
                    </b-button>                    
                </div>
            </div>
        </div>       
        <b-table
            ref="tbl_usuarios"
            show-empty
            striped
            hover
            bordered
            small
            responsive
            stacked="md"
            :items="myProvider"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"                
            empty-text="No hay usuarios para mostrar"
            empty-filtered-text="No hay usuarios que coincidan con su búsqueda."
            
        >                
            <template v-slot:cell(data)="row">                
                <a href="#" @click="mostrarComprobante(row.item)">{{ row.item.apellidos }}, {{ row.item.nombres }}</a>                
            </template>   
            <template v-slot:cell(editar)="row">                
                <b-button
                    v-if="row.item.dni == row.item.dni"
                    class="btn btn-warning btn-sm"
                    v-b-modal.add-particular
                    @click="open_edit_modal(row.item)"
                >
                    <b-icon icon="pencil-square"></b-icon>
                </b-button>
                <!-- <inertia-link 
                    v-if="row.item.dni == row.item.dni"
                    class="btn btn-warning btn-sm"
                    href="#"
                > 
                </inertia-link>-->
            </template>                               
        </b-table>
        <b-row>
            <b-col class="mr-auto">
                <span class="font-weight-bold">Total: </span> {{ totalRows }} registros                    
            </b-col>
            <b-col class="ml-auto">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>     
        <b-modal id="add-particular" ref="modal" title="Nuevo Particular" @cancel="resetearParticular" @ok="registrarParticular" >            
            <b-form>
                <b-row>
                    <b-col cols="6">
                        <b-form-group id="input-group-1" label="DNI:" label-for="input-1">
                            <b-form-input
                                class="text-center"
                                id="input-1"
                                v-model="particular.dni"
                                type="text"   
                                :readonly="this.modal_accion=='Crear'"                                                                                             
                            ></b-form-input>
                            <span v-if="errors.dni" class="text-danger">{{ errors.dni[0] }}</span>
                        </b-form-group>
                    </b-col>
                    <b-col cols="6">
                        <b-form-group id="input-group-2" label="Email:" label-for="input-2">
                            <b-form-input
                                id="input-2"
                                v-model="particular.email"
                                type="text"
                                placeholder="Ingrese correo electrónico"                                                                   
                            ></b-form-input>
                            <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
                        </b-form-group>
                    </b-col>                        
                </b-row>
                <b-row>
                    <b-col cols="6">
                        <b-form-group id="input-group-3" label="Nombres:" label-for="input-3">
                            <b-form-input
                                id="input-3"
                                v-model="particular.nombres"
                                type="text"
                                placeholder="Ingrese Nombres"
                                :readonly="this.modal_accion=='Crear'"                                                                 
                            ></b-form-input>
                            <span v-if="errors.nombres" class="text-danger">{{ errors.nombres[0] }}</span>
                        </b-form-group>
                    </b-col>
                    <b-col cols="6">
                        <b-form-group id="input-group-4" label="Apellidos:" label-for="input-4">
                            <b-form-input
                                id="input-4"
                                v-model="particular.apellidos"
                                type="text"
                                placeholder="Ingrese apellidos"                                                                 
                                :readonly="this.modal_accion=='Crear'"                                                                 
                            ></b-form-input>
                            <span v-if="errors.apellidos" class="text-danger">{{ errors.apellidos[0] }}</span>
                        </b-form-group>
                    </b-col>                        
                </b-row>                                                 
            </b-form>        
            <template v-slot:modal-footer="{ ok, cancel }">
                <b-button size="sm" variant="danger" @click="cancel()">Cancelar</b-button>
                <b-button size="sm" variant="primary" v-if="modal_accion=='Crear'" @click.prevent="ok()">Registrar</b-button>
                <b-button size="sm" variant="warning" v-if="modal_accion=='Editar'" @click="editarParticular()">Editar</b-button>
            </template>
        </b-modal>    
    </div>    
</template>
<script>
const axios = require("axios");

export default {
    name: "cobros.buscarusario.particulares",
    props: ["opcion_busqueda", "filtro"],    
    data() {
        return {
            app_url: this.$root.app_url,
            alumno: {},                        
            fields: [
                { key: "dni", label: "DNI", class: "text-center" },
                { key: "data", label: "APELLIDOS Y NOMBRES" },
                { key: "email", label: "EMAIL" },
                { key: "editar", label: "" },
            ],                      
            particular: {
                dni: '',
                nombres: '',
                apellidos: '',
                email: ''
            },
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],
            errors: [],
            modal_readonly: false,
            modal_accion: ''                        
        };
    },
     
    created() {
        if (this.opcion_busqueda === 'DNI') {
            this.particular.dni = this.filtro
        }
    },
    methods: {
        refreshTable() {
            this.$refs.tbl_usuarios.refresh();
        },
        myProvider(ctx) {
            //this.toggleBusy()
            let params = {
                'tipo_usuario': 'PARTICULAR',
                'opcion_busqueda': this.opcion_busqueda,
                'filtro': this.filtro,
                'page': ctx.currentPage,
                'size': ctx.perPage            
            }            
            const promise = axios.get(`${this.app_url}/buscarUsuario`, { params })

            return promise.then(response => {                
                //this.toggleBusy()                               
                const usuarios = response.data.data
                this.totalRows = response.data.total                    
                if (this.totalRows == 1 && this.opcion_busqueda === 'DNI'){
                    this.$inertia.get(route('comprobantes.crear_particular'), {                
                        'particular' : usuarios[0],                
                    })
                }
                return usuarios || [];
            });
        },
        async buscar_sunat(){
            this.modal_accion='Crear'
            if (this.particular.dni.length == 8) {      
                await axios.get(`${this.app_url}/api_dni/${this.particular.dni}`)
                .then(response =>{
                    if(response.data.success!==null){
                        this.particular.nombres = response.data.nombres
                        let apPaterno = response.data.apellidoPaterno
                        let apMaterno = response.data.apellidoMaterno
                        this.particular.apellidos = `${apPaterno} ${apMaterno}` 
                    }
                })
                .catch(function(error){
                    console.log("error!! "+error)
                })

                                                
            }
        }, 
        open_edit_modal(part_para_edit){
            this.modal_accion='Editar';
            this.particular={...part_para_edit}
        },                     
        mostrarComprobante(particular) {       
            this.$inertia.get(route('comprobantes.crear_particular'), {                
                'particular' : particular,                
            })
        },
        editarParticular(){
            console.log(this.particular);
            this.errors = [];           
            
             axios.post(`${this.app_url}/actualizarParticular`, this.particular)
                .then(response => {
                    console.log(response)                                                  
                        
                    this.$nextTick(() => {
                        this.$bvModal.hide("add-particular");
                        this.$refs.tbl_usuarios.refresh();
                        
                    });
                    if (!response.data.error) {
                        
                        this.$bvToast.toast(response.data.successMessage, {
                            title: `Editar particular`,
                            variant: 'success',
                            solid: true
                        })                          
                    }
                    else {
                        this.$bvToast.toast(response.data.errorMessage, {
                            title: `Editar particular`,
                            variant: 'danger',
                            solid: true
                        })
                    }                        
                }).catch(error => {           
                             
                    if (error.response.status==422) {
                        this.errors = error.response.data.errors;                      
                    }                    
                });
        },  
        registrarParticular(bvModalEvt) {                 
            bvModalEvt.preventDefault()               
            this.errors = [];           
            
            axios.post(`${this.app_url}/registrarParticular`, this.particular)
                .then(response => {                                                  
                    this.resetearParticular()    
                    this.$nextTick(() => {
                        this.$bvModal.hide("add-particular");
                        this.$refs.tbl_usuarios.refresh();
                    });
                    if (!response.data.error) {
                        this.$bvToast.toast(response.data.successMessage, {
                            title: `Registrar particular`,
                            variant: 'success',
                            solid: true
                        })                          
                    }
                    else {
                        this.$bvToast.toast(response.data.errorMessage, {
                            title: `Registrar particular`,
                            variant: 'danger',
                            solid: true
                        })
                    }                        
                }).catch(error => {                    
                    if (error.response.status==422) {
                        this.errors = error.response.data.errors;                      
                    }                    
                });                         
        },    
        resetearParticular(){                
            this.particular.nombres = '';
            this.particular.apellidos = '';
            this.particular.email = '';    
            this.errors = []               
        },  
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        toggleBusy() {
            this.isBusy = !this.isBusy
        },
    }
};
</script>