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
                <div class="container">                    
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
                                    readonly
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
                            <b-form-group id="input-group-5" label="Nombre:" label-for="input-5">
                                <b-form-input
                                    id="input-5"
                                    readonly
                                    v-model="comprobante.usuario"                                        
                                ></b-form-input>
                            </b-form-group>
                        </b-col>               
                        <b-col>
                            <b-form-group id="input-group-6" label="Email:" label-for="input-6">
                                <b-form-input
                                    id="input-6"
                                    readonly
                                    v-model="comprobante.email"
                                    placeholder="Email"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>                                    
                    </b-row>
                    <b-row v-if="comprobante.tipo_usuario === 'alumno'">      
                        <b-col>
                            <b-form-group id="input-group-7" label="CUI:" label-for="input-7">
                                <b-form-input
                                    id="input-7"
                                    v-model="comprobante.codigo"                                        
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>                      
                        <b-col>
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
                </div>
                <div class="container">                    
                    <b-button @click="addDetalle" ref="btnAdd">Añadir detalle</b-button>
                </div>                
                <div>
                    <b-table
                        ref="tbl_detalle"
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
                            <label>{{ row.item.codigo }}</label>
                        </template>
                        <template v-slot:cell(concepto)="row">
                            <!--<b-form-select v-model="row.item.concepto_id" @change="completeConcepto($event, row.item.id)" :options="conceptos"></b-form-select>-->
                            <b-form-select
                                v-model="row.item.concepto_id"
                                @change="completeConcepto($event, row.item.concepto_id)"
                                class="mb-3"
                                :disabled="row.item.concepto_id != ''"
                            >
                                <!-- This slot appears above the options from 'options' prop -->
                                <template #first>
                                    <b-form-select-option :value="null" disabled>
                                        -- Por favor, seleccione un concepto --
                                    </b-form-select-option>
                                    <b-form-select-option
                                        v-for="option in conceptos"
                                        v-bind:key="option.id"
                                        :value="option.id"
                                        :disabled="!option.estado"
                                        >{{ option.text }}</b-form-select-option
                                    >
                                </template>
                            </b-form-select>
                        </template>
                        <template v-slot:cell(cantidad)="row">
                            <div>
                                <b-form-input
                                    id="cantidad"
                                    v-model="row.item.cantidad"
                                    type="number"
                                ></b-form-input>
                            </div>
                        </template>
                        <template v-slot:cell(valor_unitario)="row">
                            <b-form-input
                                id="valor_unitario"
                                v-model="row.item.valor_unitario"
                                type="number"
                                readonly
                                :value="row.item.valor_unitario"
                            ></b-form-input>
                        </template>
                        <template v-slot:cell(descuento)="row">
                            <div>
                                <b-form-select
                                    :options="tipo_descuento"
                                    v-model="row.item.tipo_descuento"
                                ></b-form-select>
                                <b-form-input
                                    id="descuento"
                                    @change="calcularDescuento($event, row.item.concepto_id)"
                                    type="number"
                                    value="0.00"
                                ></b-form-input>
                            </div>
                        </template>
                        <template v-slot:cell(subTotal)="row">
                            <div>
                                <label>S/.
                                    {{
                                        (row.item.valor_unitario *
                                            row.item.cantidad -
                                            row.item.descuento)
                                            | currency
                                    }}
                                </label>
                            </div>
                        </template>
                        <template v-slot:cell(acciones)="row">
                            <b-button
                                v-if="!row.item.deleted_at"
                                variant="danger"
                                size="sm"
                                title="Eliminar"
                                @click="eliminar(row.index)"
                            >
                                <b-icon icon="trash"></b-icon>
                            </b-button>
                        </template>
                        <template slot="bottom-row" slot-scope="">
                            <b-td /><b-td /><b-td /><b-td /><b-td>Total</b-td>
                            <b-td>S/.{{ precioTotal | currency }}</b-td
                            ><b-td />
                        </template>
                    </b-table>
                </div>
                <div v-show="accion == 'Crear'" class="container">
                    <b-button
                        variant="success"
                        size="sm"
                        title="Registrar"
                        @click="registrar()"
                    >
                        Registrar<b-icon icon="check"></b-icon>
                    </b-button>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "comprobantes.detalles",
    props: ["comprobante", "conceptos"],
    components: {
        AppLayout
    },
    data() {
        return {
            app_url: this.$root.app_url,
            accion: "",
            tipoDescuento: "",                        
            cantidadState: null,
            conceptosFields: [
                { key: "value", label: "Código", sortable: true, class: "text-center" },
                { key: "text", label: "Descripción", sortable: true },
                { key: "precio", label: "Precio", class: "text-center" },
                { key: "anadir", label: "Añadir", class: "text-center" }
            ],
            index: 1,
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],
            sortBy: null,
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            tipo_descuento: [
                { value: "A", text: "%" },
                { value: "B", text: "S/." }
            ],                                
            fields: [
                { key: "codigo", label: "CÓDIGO", class: "text-center" },
                { key: "concepto", label: "CONCEPTO", class: "text-center" },
                { key: "cantidad", label: "CANTIDAD", class: "text-center" },
                { key: "valor_unitario", label: "PR. UNIT", class: "text-center" },
                { key: "descuento", label: "DESCUENTO", class: "text-center" },
                { key: "subTotal", label: "SUB TOTAL", class: "text-right" },
                { key: "acciones", label: "ELIMINAR", class: "text-center" }
            ]
        };
    },    
    computed: {       
        precioTotal() {
            this.comprobante.total = this.comprobante.detalles.reduce(
                (acc, item) => acc + (item.cantidad * item.valor_unitario - item.descuento), 0);

            return this.comprobante.total;
        },
        conceptosDisponibles(){
            return this.conceptos.filter(option => option.estado == true);
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
        addDetalle() {
            this.comprobante.detalles.push({
                codigo: "",
                concepto_id: "",
                cantidad: "1",
                valor_unitario: "",
                tipo_descuento: "",
                descuento: "0.00"
            });
        },
        agregarConcepto(conc) {
            this.comprobante.detalles.push({
                codigo: conc.value,
                concepto_id: conc.id,
                cantidad: "1",
                valor_unitario: conc.precio,
                tipo_descuento: "",
                descuento: "0.00"
            });
            let concIndex = this.conceptos.findIndex(option => option.id == conc.id);
            this.conceptos[concIndex].estado = false;
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
                .then(async value => {
                    if (value) {
                        this.$inertia.post(route("comprobantes.registrar", this.comprobante));
                    }
                });
        },        
        eliminar(index) {            
            /*let index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            let concIndex = this.conceptos.findIndex(option => option.id == id);
            this.conceptos[concIndex].estado = true;*/
            this.comprobante.detalles.splice(index, 1);
        },
        completeConcepto(event, id) {
            let index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            let conc = this.conceptos.find(option => option.id == event);
            let concIndex = this.conceptos.findIndex(option => option.id == event);
            this.conceptos[concIndex].estado = false;
            this.comprobante.detalles[index].concepto_id = conc.id;
            this.comprobante.detalles[index].codigo = conc.value;
            this.comprobante.detalles[index].valor_unitario = conc.precio;
            this.comprobante.detalles[index].descuento = "0.00";
        },
        calcularDescuento(event, id) {
            let index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            let conc = this.comprobante.detalles[index].tipo_descuento;
            if (conc == "A") {
                this.comprobante.detalles[index].descuento =
                    (this.comprobante.detalles[index].valor_unitario *
                        this.comprobante.detalles[index].cantidad *
                        event) /
                    100;
            } 
            else if (conc == "B") {
                this.comprobante.detalles[index].descuento = event;
            } 
            else {
                this.comprobante.detalles[index].descuento = 0;
            }
        },  
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        }      
    },    
};
</script>