<template>            
    <div>       
        <div class="row mb-2">
            <div class="col-lg-12">
                <div class="text-right">
                    <b-button v-if="totalRows == 0 && opcion_busqueda == 'RUC'" size="sm" variant="success" v-b-modal.add-empresa>
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
                <a href="#" @click="mostrarComprobante(row.item)">{{ row.item.razon_social }}</a>                
            </template>              
            <template v-slot:cell(editar)="row">                
                <inertia-link 
                    v-if="row.item.dni == row.item.dni"
                    class="btn btn-warning btn-sm"
                    href="#"
                >
                    <b-icon icon="pencil-square"></b-icon>
                </inertia-link>           
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
        <b-modal id="add-empresa" ref="modal" title="Nueva Empresa" @cancel="resetearEmpresa" @ok="registrarEmpresa">            
            <b-form>
                <b-row>
                    <b-col cols="6">
                        <b-form-group id="input-group-1" label="RUC:" label-for="input-1">
                            <b-form-input
                                class="text-center"
                                id="input-1"
                                v-model="empresa.ruc"
                                type="text"   
                                readonly                                                                                             
                            ></b-form-input>
                            <span v-if="errors.ruc" class="text-danger">{{ errors.ruc[0] }}</span>
                        </b-form-group>
                    </b-col>
                    <b-col cols="6">
                        <b-form-group id="input-group-3" label="Email:" label-for="input-3">
                            <b-form-input
                                id="input-3"
                                v-model="empresa.email"
                                type="text"
                                placeholder="Ingrese correo eletrónico"                                                                 
                            ></b-form-input>
                            <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
                        </b-form-group>
                    </b-col>                    
                </b-row>
                <b-row>
                    <b-col cols="12">
                        <b-form-group id="input-group-2" label="Razón social:" label-for="input-2">
                            <b-form-input
                                id="input-2"
                                v-model="empresa.razon_social"
                                type="text"
                                placeholder="Ingrese razón social"                                                                   
                            ></b-form-input>
                            <span v-if="errors.razon_social" class="text-danger">{{ errors.razon_social[0] }}</span>
                        </b-form-group>
                    </b-col>                                                             
                </b-row>                                                 
                <b-row>                    
                    <b-col cols="12">
                        <b-form-group id="input-group-4" label="Dirección:" label-for="input-4">
                            <b-form-input
                                id="input-4"
                                v-model="empresa.direccion"
                                type="text"
                                placeholder="Ingrese domicilio legal"                                                                 
                            ></b-form-input>
                            <span v-if="errors.direccion" class="text-danger">{{ errors.direccion[0] }}</span>
                        </b-form-group>
                    </b-col>                        
                </b-row>                                                 
            </b-form>        
            <template v-slot:modal-footer="{ ok, cancel }">
                <b-button size="sm" variant="danger" @click="cancel()">Cancelar</b-button>
                <b-button size="sm" variant="primary" @click.prevent="ok()">Registrar</b-button>
            </template>
        </b-modal>     
    </div>    
</template>
<script>
const axios = require("axios");

export default {
    name: "cobros.buscarusario.empresas",
    props: ["opcion_busqueda", "filtro"],    
    data() {
        return {
            app_url: this.$root.app_url,                                 
            fields: [
                { key: "ruc", label: "RUC", class: "text-center" },
                { key: "data", label: "RAZÓN SOCIAL" },
                { key: "email", label: "EMAIL" },
                { key: "editar", label: "" },
            ],      
            empresa: {
                ruc: '',
                razon_social: '',
                email: '',
                direccion: ''
            },                
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],
            errors: []                                
        };
    },
    watch: {
        'empresa.ruc': async function (val) {                
            if (val.length == 11) {                 

                try {
                    const response = await axios.get(`${this.app_url}/api_ruc/${val}`)
                    if (response.data) {                                                                                                         
                            this.empresa.razon_social = response.data.razonSocial
                            this.empresa.direccion = response.data.direccion
                    }
                } catch (error) {
                    console.log(error)
                }                                    
            }                
        }
    }, 
    created() {
        if (this.opcion_busqueda === 'RUC') {
            this.empresa.ruc = this.filtro
        }
    },
    methods: {
        refreshTable() {
            this.$refs.tbl_usuarios.refresh();
        },
        myProvider(ctx) {
            //this.toggleBusy()
            let params = {
                'tipo_usuario': 'EMPRESA',
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
                if (this.totalRows == 1 && this.opcion_busqueda === 'RUC'){
                    this.$inertia.get(route('comprobantes.crear_empresa'), {                
                    'empresa' : usuarios[0],                
            })
                }
                return usuarios || [];
            });
        },             
        mostrarComprobante(empresa) {       
            this.$inertia.get(route('comprobantes.crear_empresa'), {                
                'empresa' : empresa,                
            })
        }, 
        registrarEmpresa(bvModalEvt) {                 
            bvModalEvt.preventDefault()               
            this.errors = [];           
            
            axios.post(`${this.app_url}/registrarEmpresa`, this.empresa)
                .then(response => {                                                  
                    this.resetearEmpresa()    
                    this.$nextTick(() => {
                        this.$bvModal.hide("add-empresa");
                    });
                    if (!response.data.error) {
                        this.$bvToast.toast(response.data.successMessage, {
                            title: `Registrar empresa`,
                            variant: 'success',
                            solid: true
                        })                          
                    }
                    else {
                        this.$bvToast.toast(response.data.errorMessage, {
                            title: `Registrar empresa`,
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
        resetearEmpresa(){                
            this.empresa.razon_social = '';
            this.empresa.email = '';
            this.empresa.direccion = '';    
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