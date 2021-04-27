<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('comprobantes.iniciar')">Buscar usuario</inertia-link>
                    </li>
                </ol>
            </div>
            <div class="card-doby">
                <div class="container p-3">                                       
                    <b-row>                        
                        <b-col>
                            <b-form-group id="input-group-1" label="Serie:" label-for="input-1">
                                <b-form-input
                                    id="input-1"
                                    v-model="comprobante.serie"
                                    placeholder="Serie"
                                    readonly
                                ></b-form-input>
                            </b-form-group>                                                
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-2" label="Correlativo:" label-for="input-2">
                                <b-form-input
                                    id="input-2"
                                    v-model="comprobante.correlativo"
                                    placeholder="Correlativo"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-3" label="Fecha:" label-for="input-3">
                                <b-form-input                                                                        
                                    id="input-3"            
                                    type="date" 
                                    :value="getFechaActual()"                                                                        
                                ></b-form-input>
                            </b-form-group>
                        </b-col>                        
                    </b-row>
                    <b-row>                            
                        <b-col cols="2" v-if="comprobante.tipo_usuario !== 'dependencia'">
                            <b-form-group id="input-group-4" label="DNI:" label-for="input-4">
                                <b-form-input
                                    id="input-4"
                                    v-model="comprobante.dni"                                        
                                    readonly
                                ></b-form-input>                                    
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-5" label="Usuario:" label-for="input-5">
                                <b-input-group class="mb-2">
                                    <b-input-group-prepend is-text>
                                        <b-icon icon="person-fill"></b-icon>
                                    </b-input-group-prepend>
                                    <b-form-input
                                        id="input-5"
                                        readonly
                                        v-model="comprobante.usuario"                                        
                                    ></b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>               
                        <b-col>
                            <b-form-group id="input-group-6" label="Email:" label-for="input-6">
                                <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
                                    <b-form-input
                                        id="input-6"
                                        readonly
                                        v-model="comprobante.email"
                                        placeholder="Email"
                                    ></b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>                                    
                    </b-row>
                    <b-row v-if="comprobante.tipo_usuario === 'alumno'">      
                        <b-col cols="4">
                            <b-form-group id="input-group-7" label="CUI:" label-for="input-7">
                                <b-form-input
                                    id="input-7"
                                    v-model="comprobante.codigo"                                        
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>                      
                        <b-col cols="8">
                            <b-form-group id="input-group-8" label="Escuela:" label-for="input-8">
                                <b-form-input
                                    id="input-8"
                                    v-model="comprobante.escuela"                                        
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row v-else-if="comprobante.tipo_usuario === 'docente' || comprobante.tipo_usuario === 'dependencia'">
                        <b-col cols="2">
                            <b-form-group id="input-group-9" label="Código:" label-for="input-9">
                                <b-form-input
                                    id="input-9"
                                    v-model="comprobante.codigo"
                                    placeholder="Código"
                                    :readonly="accion == 'Mostrar'"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>                            
                    </b-row>     
                    <hr> 
                    <form @submit.prevent="agregarDetalle">
                        <b-row>                        
                            <b-col cols="5">
                                <v-select                  
                                    v-model="concepto"
                                    @search="buscarConcepto"   
                                    :filterable="false"                                 
                                    :options="conceptos"                                              
                                    :reduce="concepto => concepto"                 
                                    label="descripcion"                
                                    placeholder="Ingrese código o descripción del concepto"
                                >
                                    <template #search="{attributes, events}">
                                        <input
                                            class="vs__search"
                                            :required="!concepto"
                                            v-bind="attributes"
                                            v-on="events"
                                            v-model="filtro"                                          
                                        />
                                    </template>
                                    <template slot="no-options">
                                        Lo sentimos, no hay resultados de coincidencia.
                                    </template>
                                </v-select>
                            </b-col>
                            <b-col>                                      
                                 <b-button style="height:34px" type="submit" variant="info" class="mb-2" title="Añadir detalle">
                                    <b-icon icon="plus-circle"></b-icon>
                                </b-button>                                
                            </b-col>                        
                        </b-row>
                    </form>
                    <b-row class="mt-3">
                        <b-col>
                            <b-table                        
                                show-empty
                                striped
                                hover
                                bordered
                                small
                                responsive
                                stacked="md"
                                :items="comprobante.detalles"
                                :fields="fields"
                                empty-text="No hay conceptos para mostrar"
                            >
                                <template v-slot:cell(codigo)="row">
                                    <b-form-input          
                                        class="text-center"                      
                                        :value="row.item.codigo"                                
                                        readonly                                
                                    ></b-form-input>
                                </template>
                                <template v-slot:cell(concepto)="row">
                                    <b-form-input                                
                                        :value="row.item.descripcion"                                
                                        readonly                                
                                    ></b-form-input>
                                </template>
                                <template v-slot:cell(cantidad)="row">
                                    <div>
                                        <b-form-input          
                                            class="text-center"                                                
                                            v-model="row.item.cantidad"     
                                            @change="calcularSubTotal(row.item.id)"
                                            type="number"                                                                                        
                                            min="1"                                             
                                        ></b-form-input>
                                    </div>
                                </template>
                                <template v-slot:cell(precio)="row">
                                    <b-form-input         
                                        class="text-center"                                             
                                        :value="row.item.precio"                                
                                        readonly                                
                                    ></b-form-input>
                                </template>
                                <template v-slot:cell(descuento)="row">
                                    <div>
                                        <b-form-select v-model="row.item.tipo_descuento" @change="calcularSubTotal(row.item.id)">
                                            <b-form-select-option value="S/.">S/.</b-form-select-option>
                                            <b-form-select-option value="%">%</b-form-select-option>
                                        </b-form-select>
                                        <b-form-input
                                            class="text-center"                      
                                            v-model="row.item.descuento"            
                                            @keyup="calcularSubTotal(row.item.id)"                                                                                                                    
                                        ></b-form-input>
                                    </div>
                                </template>
                                <template v-slot:cell(subTotal)="row">
                                    <span class="font-weight-bold">
                                        S/. {{ row.item.subtotal | currency }}                                    
                                    </span>                                    
                                </template>
                                <template v-slot:cell(acciones)="row">
                                    <b-button
                                        v-if="!row.item.deleted_at"
                                        variant="danger"
                                        size="sm"
                                        title="Eliminar"
                                        @click="eliminarDetalle(row.index)"
                                    >
                                        <b-icon icon="trash"></b-icon>
                                    </b-button>
                                </template>
                                <template slot="bottom-row" slot-scope="">
                                    <b-td /><b-td /><b-td /><b-td />
                                    <b-td class="text-right font-weight-bold">TOTAL</b-td>
                                    <b-td class="text-right font-weight-bold">S/. {{ precioTotal | currency }}</b-td>
                                    <b-td />
                                </template>
                            </b-table>
                        </b-col>
                    </b-row>                          
                    <div v-show="accion == 'Crear'">
                        <b-button variant="success" @click="registrar()">Registrar</b-button>
                    </div>                           
                </div>                                                
            </div>
        </div>
    </app-layout>
</template>
<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";
export default {
    name: "comprobantes.detalles",
    props: ["comprobante"],
    components: {
        AppLayout
    },
    data() {
        return {
            app_url: this.$root.app_url,
            concepto: null,
            filtro: "",
            conceptos: [],            
            accion: "",                           
            fields: [
                { key: "codigo", label: "CÓDIGO", class: "text-center", tdClass: "codigo" },
                { key: "concepto", label: "CONCEPTO", class: "text-center", tdClass: "concepto" },
                { key: "cantidad", label: "CANTIDAD", class: "text-center" },
                { key: "precio", label: "COSTO", class: "text-center" },
                { key: "descuento", label: "DESCUENTO", class: "text-center" },
                { key: "subTotal", label: "SUBTOTAL", class: "text-right" },
                { key: "acciones", label: "", class: "text-center" }
            ]
        };
    },    
    computed: {       
        precioTotal() {
            this.comprobante.total = this.comprobante.detalles.reduce((acc, item) => acc + item.subtotal, 0)

            return this.comprobante.total
        },                
    },
    watch : {
        filtro:function(val) {
            this.filtro = val.trim()            
        },        
        concepto:function(val) {
            this.filtro = ""
        },        
    },
    filters: {
        currency(value) {
            return value.toFixed(2);
        }
    },
    created() {
        if (!this.comprobante.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }        
    },    
    methods: {  
        getFechaActual() {
            let fecha_actual = new Date();                
            let anio = fecha_actual.getFullYear()
            let mes = fecha_actual.getMonth() + 1
            let dia = fecha_actual.getDate()
            return anio + '-' + mes.toString().padStart(2, "0") + '-' + dia
        },
        buscarConcepto(search, loading) {                 
            loading(true)           
            axios.get(`${this.app_url}/buscarConcepto?filtro=${search}`)
                .then(response => {                                             
                    this.conceptos = response.data;                    
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error)
                });
        },          
        agregarDetalle() {
            if (this.comprobante.detalles.filter(obj => obj.id == this.concepto.id).length == 0) {
                this.$set( this.concepto, 'cantidad', 1)
                this.$set( this.concepto, 'tipo_descuento', 'S/.')                
                this.$set( this.concepto, 'descuento', 0)
                this.$set( this.concepto, 'subtotal', parseFloat(this.concepto.precio))                
                this.comprobante.detalles.push(this.concepto)   
            }
            else {
                this.$bvToast.toast('El concepto del detalle ya existe', {
                    title: 'Añadir detalle',
                    variant: 'warning',
                    toaster: 'b-toaster-bottom-right',
                    solid: true
                })                
            }
            
            this.concepto = null         
        },        
        eliminarDetalle(index) {                        
            this.comprobante.detalles.splice(index, 1);
        },  
        calcularSubTotal(id) {
            let objDetalle = this.comprobante.detalles.find(detalle => detalle.id === id)
            let subtotal_parcial = objDetalle.cantidad * objDetalle.precio

            if (objDetalle.tipo_descuento === 'S/.') {                
                objDetalle.subtotal = subtotal_parcial - objDetalle.descuento
            }
            else if (objDetalle.tipo_descuento === '%') {
                objDetalle.subtotal = subtotal_parcial - (subtotal_parcial * objDetalle.descuento / 100)
            }            
        },
        registrar() {
            this.$bvModal.msgBoxConfirm("¿Esta seguro de querer registrar este comprobante?",
                    {
                        title: "Enviar Comprobante",
                        okVariant: "success",
                        okTitle: "SI",
                        cancelVariant: "danger",
                        cancelTitle: "NO",
                        centered: true
                    }
                )
                .then(value => {
                    if (value) {
                        this.$inertia.post(route("comprobantes.registrar", this.comprobante));
                    }
                });
        },                                         
    },    
};
</script>
<style>
    .codigo {
        width: 150px;        
    }
    .concepto {
        width: 400px;
    }
</style>