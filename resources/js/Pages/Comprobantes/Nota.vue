<template>
    <app-layout>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ml-auto">
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('cobros.iniciar')">
                        Lista de cobros
                    </inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('cobros.buscarUsuario')">
                        Buscar usuario
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    Nuevo nota
                </li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">{{data.tipo_comprobante}}</span>
            </div>
            <div class="card-doby">
                <div class="container p-3">
                    <div class="row mb-1">
                        <fieldset class="col-12 px-2">
                            <legend>Nota:</legend>
                            <b-row>
                                <b-col>
                                    <b-form-group id="input-group-1" label="Fecha:" label-for="input-1">
                                        <b-form-input id="input-1" v-model="data.fecha_actual" type="text" placeholder="Ingrese código" readonly></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col>
                                    <b-form-group
                                        id="input-group-2"
                                        label="Tipo de nota:"
                                        label-for="input-2">
                                        <b-form-select id="input-2" :options="tipos_de_nota" >
                                            <template v-slot:first>
                                                <option :value="null" disabled>Seleccione...</option>
                                            </template>
                                        </b-form-select>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                            <b-row>
                                <b-col>
                                    <b-form-group id="input-group-3" label="Motivo o sustento:" label-for="input-3">
                                        <b-form-input id="input-3" type="text" placeholder="Ingrese motivo"></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                        </fieldset>
                    </div>
                    <div class="row mb-1">
                        <fieldset class="col-12 px-2">
                            <legend>Documento a modificar:</legend>
                            <b-row>
                                <b-col>
                                    <b-form-group id="input-group-4" label="Serie:" label-for="input-4">
                                        <b-form-input id="input-4" v-model="comprobante.serie" type="text" readonly></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col>
                                    <b-form-group id="input-group-5" label="Correlativo:" label-for="input-5">
                                        <b-form-input id="input-5" v-model="comprobante.correlativo" type="text" readonly></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                        </fieldset>
                    </div>
                </div>
                
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "comprobantes.cabecera",
    props: ["comprobante", "data"],
    components: {
        AppLayout,
    },
    data() {
        return {
            app_url: this.$root.app_url,
            tipos_de_nota: [],
            tipos_nota_debito: [
                {value: '01', text: 'Interés por mora'},
                {value: '02', text: 'Aumento en el valor'},
                {value: '03', text: 'Penalidades'},
            ],
            tipos_nota_credito: [
                {value: '01', text: 'Anulación por operación'},
                {value: '02', text: 'Anulación por error en el RUC'},
                {value: '03', text: 'Corrección por error en la descripción'},
                {value: '04', text: 'Descuento global'},
                {value: '05', text: 'Descuento por ítem'},
                {value: '06', text: 'Devolución total'},
                {value: '07', text: 'Devolución por ítem'},
                {value: '08', text: 'Bonificación'},
                {value: '09', text: 'Disminución en el valor'},
            ]
        };
    },
    created() {
        if (this.data.tipo_comprobante === 'NOTA_DEBITO'){
            this.tipos_de_nota = this.tipos_nota_debito   
        }
        else if (this.data.tipo_comprobante === 'NOTA_CREDITO'){
            this.tipos_de_nota = this.tipos_nota_credito   
        }
    },
};
</script>
<style scoped>
    .lbl-data {
        text-align: center;
        border: 0;
        padding: 0;
        display: block;
        width: 100%;
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 400;
    }
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
    fieldset {    
        border-radius: 4px;
        border: 1px solid #ddd;    
        background-color: #fff;    
        padding-bottom: 10px;
        height: auto;
    }

    legend {        
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;    
        font-size: 14px;    
        font-weight: 600;
        padding: 3px 5px 3px 7px;
        width: auto;
    }
</style>
