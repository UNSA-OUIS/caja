<template>
    <div>
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
                        label="vista_concepto"
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
        <div>
            <b-button variant="success" @click="registrar()">Registrar</b-button>
        </div>
    </div>
</template>
<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";
export default {
    name: "comprobantes.detalle",
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
    methods: {
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
            if (this.comprobante.detalles.filter(obj => obj.concepto_id == this.concepto.concepto_id).length == 0) {
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
