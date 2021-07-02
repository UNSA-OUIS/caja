<template>
    <app-layout>
        <PeriodoMenu :tab="1"/>
        <div class="card" ref="content">
            <div class="card-header">
                <h1>Descuentos por cajero</h1>
            </div>
            <div class="card-body">
                <b-row>
                    <b-col cols="4">
                        <v-select
                        v-model="cajero"
                        @search="buscarCajero"
                        :filterable="false"
                        :options="cajeros"
                        :reduce="cajero => cajero"
                        label="vista_cajero"
                        placeholder="Ingrese código o nombre del cajero"
                    >
                        <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!cajero"
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
                    <b-col cols="4">
                        <b-form-group
                        label="Fecha inicio: "
                        label-cols-sm="5"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="startDate"
                        class="mb-0"
                        >
                        <b-form-datepicker
                            id="startDate"
                            v-model="filter.fechaInicio"
                            today-button
                            reset-button
                            close-button
                            placeholder="Elige una fecha"
                            size="sm"
                        ></b-form-datepicker>
                         </b-form-group>
                    </b-col>
                    <b-col cols="4">
                        <b-form-group
                        label="Fecha fin: "
                        label-cols-sm="4"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="endDate"
                        class="mb-0"
                        >
                        <b-form-datepicker
                            id="endDate"
                            v-model="filter.fechaFin"
                            today-button
                            reset-button
                            close-button
                            placeholder="Elige una fecha"
                            size="sm"
                        ></b-form-datepicker>
                         </b-form-group>
                    </b-col>
                </b-row>
                <b-button class="btn btn-success float-right mt-2 mb-2" @click="filterTable()">Generar reporte</b-button>
                <b-table
                    ref="tbl_descuentos"
                    show-empty
                    striped
                    hover
                    sticky-header
                    bordered
                    small
                    responsive
                    :items="descuentos"
                    :fields="fields"
                    empty-text="No hay descuentos para mostrar"
                    empty-filtered-text="No hay descuentos que coincidan con su búsqueda.">
                    <template v-slot:cell(created_at)="row">
                        {{ row.item.created_at.substring(0, 10) }}
                    </template>
                    <template v-slot:cell(cajero_id)="row">
                        {{ row.item.comprobante.cajero_id }}
                    </template>
                    <template v-slot:cell(correlativo)="row">
                        {{ row.item.comprobante.correlativo }}
                    </template>
                    <template v-slot:cell(codigo)="row">
                        {{ row.item.comprobante.codi_usuario }}
                    </template>
                    <template v-slot:cell(nombre)="row">
                        <span v-if="row.item.comprobante.tipo_usuario === 'alumno'">
                        {{ reemplazar(row.item.comprobante.comprobanteable.apn) }}
                        </span>
                        <span v-else-if="row.item.comprobante.tipo_usuario === 'empresa'">
                        {{ row.item.comprobante.comprobanteable.razon_social }}
                        </span>
                        <span v-else-if="row.item.comprobante.tipo_usuario === 'particular'">
                        {{ row.item.comprobante.comprobanteable.apellidos }},
                        {{ row.item.comprobante.comprobanteable.nombres }}
                        </span>
                        <span v-else-if="row.item.comprobante.tipo_usuario === 'docente'">
                        {{ reemplazar(row.item.comprobante.comprobanteable.apn) }}
                        </span>
                        <span v-else-if="row.item.comprobante.tipo_usuario === 'dependencia'">
                        {{ row.item.comprobante.comprobanteable.nomb_depe }}
                        </span>
                    </template>
                    <template v-slot:cell(montoxpagar)="row">
                        {{ row.item.cantidad * row.item.valor_unitario }}
                    </template>
                    <template v-slot:cell(descuento)="row">
                        {{ row.item.cantidad * row.item.valor_unitario - row.item.subtotal }}
                    </template>
                    <template v-if="descuentos.length" slot="bottom-row" slot-scope="">
                        <b-td class="text-right font-weight-bold">{{totalRegistros}} registros</b-td>
                        <b-td colspan="3"></b-td>
                        <b-td class="text-right font-weight-bold">TOTALES:</b-td>
                        <b-td class="text-right font-weight-bold">{{totalMontosxPagar}}</b-td>
                        <b-td class="text-right font-weight-bold">{{totalDescuento}}</b-td>
                        <b-td colspan="1"></b-td>
                        <b-td class="text-right font-weight-bold">{{totalMontoPagado}}</b-td>
                    </template>
                </b-table>
                <b-button v-if="descuentos.length" @click="html2pdf">Descargar PDF</b-button>
                <json-excel
                    v-if="descuentos.length"
                    :data="json_data"
                    type="xlsx"
                    :fields="json_fields"
                    worksheet="Reporte_periodo_x_cajero"
                    :name="filename"
                    class="btn btn-success">
                        Descargar Excel
                </json-excel>
            </div>

            <vue-html2pdf
                    :show-layout="false"
                    :float-layout="true"
                    :enable-download="false"
                    :preview-modal="true"
                    :paginate-elements-by-height="1400"
                    :filename="filenamepdf"
                    :pdf-quality="2"
                    :manual-pagination="true"
                    pdf-format="a4"
                    pdf-orientation="landscape"
                    pdf-content-width="1100px"
                    @hasStartedGeneration="hasStartedGeneration()"
                    @beforeDownload="beforeDownload($event)"
                    @hasGenerated="hasGenerated($event)"
                    ref="html2Pdf"
                >
                    <section slot="pdf-content">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <div class="header">
                                        <img
                                            src="https://cdn.jsdelivr.net/gh/unsa-cdn/static@master/logo.png"
                                            alt=""
                                            height="50"
                                            class="logo logo-light float-left mr-2"
                                        />
                                        <div class="float-left">
                                            
                                            <h6><small>UNIVERSIDAD NACIONAL DE SAN AGUSTIN<br>
                                            CALLE SANTA CATALINA 117 AREQUIPA AREQUIPA<br>
                                            SISTEMA DE CAJAS/INGRESOS</small></h6>
                                        </div>
                                        <div class="float-right">
                                            <h6><small>{{filenamepdf}}.pdf</small></h6>
                                        </div>
                                    </div>
                                    <div class="container row align-middle ">
                                        <h1 class="text-center">Reporte de cobros</h1>
                                    </div>
                                    
                                    
                                </div>
                                
                                    <div v-for="(group, key) in grupoDividido" :key="key">
                                       <div class="card-body">
                                        <b-table
                                            ref="tbl_descuentos"
                                            show-empty
                                            striped
                                            hover
                                            bordered
                                            small
                                            responsive
                                            stacked="md"
                                            :items="group"
                                            :fields="fields"
                                            empty-text="No hay descuentos para mostrar"
                                            empty-filtered-text="No hay descuentos que coincidan con su búsqueda."
                                        >
                                        <template v-slot:cell(created_at)="row">
                                            {{ row.item.created_at.substring(0, 10) }}
                                        </template>
                                        <template v-slot:cell(cajero_id)="row">
                                            {{ row.item.comprobante.cajero_id }}
                                        </template>
                                        <template v-slot:cell(correlativo)="row">
                                            {{ row.item.comprobante.correlativo }}
                                        </template>
                                        <template v-slot:cell(codigo)="row">
                                            {{ row.item.comprobante.codi_usuario }}
                                        </template>
                                        <template v-slot:cell(nombre)="row">
                                            <span v-if="row.item.comprobante.tipo_usuario === 'alumno'">
                                            {{ reemplazar(row.item.comprobante.comprobanteable.apn) }}
                                            </span>
                                            <span v-else-if="row.item.comprobante.tipo_usuario === 'empresa'">
                                            {{ row.item.comprobante.comprobanteable.razon_social }}
                                            </span>
                                            <span v-else-if="row.item.comprobante.tipo_usuario === 'particular'">
                                            {{ row.item.comprobante.comprobanteable.apellidos }},
                                            {{ row.item.comprobante.comprobanteable.nombres }}
                                            </span>
                                            <span v-else-if="row.item.comprobante.tipo_usuario === 'docente'">
                                            {{ reemplazar(row.item.comprobante.comprobanteable.apn) }}
                                            </span>
                                            <span v-else-if="row.item.comprobante.tipo_usuario === 'dependencia'">
                                            {{ row.item.comprobante.comprobanteable.nomb_depe }}
                                            </span>
                                        </template>
                                        <template v-slot:cell(montoxpagar)="row">
                                            {{ row.item.cantidad * row.item.valor_unitario }}
                                        </template>
                                        <template v-slot:cell(descuento)="row">
                                            {{ row.item.cantidad * row.item.valor_unitario - row.item.subtotal }}
                                        </template>
                                        <template v-if="descuentos.length" slot="bottom-row" slot-scope="">
                                            <b-td class="text-right font-weight-bold">{{totalRegistros}} registros</b-td>
                                            <b-td colspan="3"></b-td>
                                            <b-td class="text-right font-weight-bold">TOTALES:</b-td>
                                            <b-td class="text-right font-weight-bold">{{totalMontosxPagar}}</b-td>
                                            <b-td class="text-right font-weight-bold">{{totalDescuento}}</b-td>
                                            <b-td colspan="1"></b-td>
                                            <b-td class="text-right font-weight-bold">{{totalMontoPagado}}</b-td>
                                        </template>
                                        </b-table>
                                        <div class="html2pdf__page-break"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </vue-html2pdf>
        </div>
    </app-layout>
</template>

<script>
const axios = require('axios')
import AppLayout from "@/Layouts/AppLayout";
import VueHtml2pdf from 'vue-html2pdf'
import PeriodoMenu from "./PeriodoMenu";
import JsonExcel from "vue-json-excel";

export default {
    name: "reportes.consolidado",
    components: {
        AppLayout,
        VueHtml2pdf,
        PeriodoMenu,
        JsonExcel
    },
    data() {
        return {
            app_url: this.$root.app_url,
            cajero: null,
            cajeros: [],
            filtro: "",
            json_fields: {
                "FECHA": "created_at",
                "CAJERO": "cajero_id",
                "RECIBO": "correlativo",
                "CÓDIGO": "codigo",
                "NOMBRE": "nombre",
                "M.XPAGAR": "montoxpagar",
                "DSCTO.": "descuento",
                "RESOLUC": "resolucion",
                "M.PAGADO": "subtotal",
                },
            json_data: [],
            json_meta: [
                [
                    {
                    key: "charset",
                    value: "utf-8",
                    },
                ],
            ],
            fields: [
                { key: "created_at", label: "FECHA" },
                { key: "cajero_id", label: "CAJERO" },
                { key: "correlativo", label: "RECIBO" },
                { key: "codigo", label: "CÓDIGO" },
                { key: "nombre", label: "NOMBRE" },
                { key: "montoxpagar", label: "M.XPAGAR", class: "text-right" },
                { key: "descuento", label: "DSCTO.", class: "text-right" },
                { key: "resolucion", label: "RESOLUC" },
                { key: "subtotal", label: "M.PAGADO", class: "text-right" },
            ],
            descuentos: [],
            totalRegistros: 0,
            totalMontosxPagar: 0,
            totalDescuento: 0,
            totalMontoPagado: 0,
            filenamepdf: "Reporte_cobros",
            filename: "",
            filenamepdf: "Reporte_cobros",
            filter: {
                fechaInicio: "",
                fechaFin: "",
            },

        };
    },
    watch : {
        filtro:function(val) {
            this.filtro = val.trim()
        },
        cajero:function(val) {
            this.filtro = ""
        },
    },
    created(){
        var today = new Date()
        today.setHours(today.getHours() - 5)
        var dateString = today.toISOString().split("T")[0]
        this.filename = "Reporte_periodo_x_consolidado_" + dateString + ".xls"
        this.filter.fechaInicio = dateString;
        this.filter.fechaFin = dateString;
    },
    methods: {
        reemplazar(nombre) {
            return nombre.replace("/", " ");
        },
        buscarCajero(search, loading) {
            loading(true)
            axios.get(`${this.app_url}/buscarCajero?filtro=${search}`)
                .then(response => {
                    this.cajeros = response.data;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error)
                });
        },
        refreshTable() {
            this.$refs.tbl_descuentos.refresh();
        },
        async filterTable() {
            try {
                let params = "?fechaInicio=" + this.filter.fechaInicio + "&fechaFin=" + this.filter.fechaFin
                if (this.cajero != null){
                    params = params + "&cajeroId=" + this.cajero.cajero_id
                }
                const response = await axios.get(`${this.app_url}/reportes-periodo/filter-reporte/descuentos/${params}`)
                this.descuentos = response.data.descuentos
                this.totalRegistros = response.data.totalRegistros
                this.totalMontosxPagar = response.data.totalMontosxPagar
                this.totalDescuento = response.data.totalDescuento
                this.totalMontoPagado = response.data.totalMontoPagado
                var temp = []
                this.descuentos.forEach(function(cobro) {
                    var cliente = ""
                    switch (cobro.comprobante.tipo_usuario) {
                        case 'alumno':
                            cliente = cobro.comprobante.comprobanteable.apn
                            break;
                        case 'empresa':
                            cliente = cobro.comprobante.comprobanteable.razon_social
                            break;
                        case 'particular':
                            cliente = cobro.comprobante.comprobanteable.apellidos + ", " + cobro.comprobante.comprobanteable.nombres
                            break;
                        case 'docente':
                            cliente = cobro.comprobante.comprobanteable.apn
                            break;
                        case 'dependencia':
                            cliente = cobro.comprobante.comprobanteable.nomb_depe
                            break;
                        default:
                            break;
                    }
                    temp.push({
                        created_at: cobro.created_at,
                        cajero_id: cobro.comprobante.cajero_id,
                        correlativo: cobro.comprobante.correlativo,
                        codigo: cobro.comprobante.codi_usuario,
                        nombre: cliente,
                        montoxpagar: cobro.cantidad * cobro.valor_unitario,
                        descuento: cobro.cantidad * cobro.valor_unitario - cobro.subtotal,
                        resolucion: cobro.resolucion,
                        subtotal: cobro.subtotal,
                    })
                });
                this.json_data = temp.slice()
                this.json_data.push({
                    created_at: "" + this.totalRegistros + " registros",
                    cajero_id: "",
                    correlativo: "",
                    codigo: "",
                    nombre: "TOTALES:",
                    montoxpagar: this.totalMontosxPagar,
                    descuento: this.totalDescuento,
                    resolucion: "",
                    subtotal: this.totalMontoPagado,
                })
                
            } catch (error) {
                console.log(error)
            }
        },
        html2pdf(){
            this.$refs.html2Pdf.generatePdf()
        },
        dompdf(){
            this.$inertia.post(
                route("reportes.cajeropdf"),
                this.grupoFilter
            );
        },
        async beforeDownload ({ html2pdf, options, pdfContent }) {
            await html2pdf().set(options).from(pdfContent).toPdf().get('pdf').then((pdf) => {
                const totalPages = pdf.internal.getNumberOfPages()
                for (let i = 1; i <= totalPages; i++) {
                    pdf.setPage(i)
                    pdf.setFontSize(10)
                    pdf.setTextColor(150)
                    pdf.text('Página ' + i + ' de ' + totalPages, (pdf.internal.pageSize.getWidth() * 0.88), (pdf.internal.pageSize.getHeight() - 0.3))
                } 
            }).save()
        },
    },
    computed:{
        grupoFilter(){
            var group = this.descuentos;
            
            group = this.fechaInicio && this.fechaFin
            ? group.filter(item => 
            new Date(item.created_at.slice(0, 10).split('-')) >= new Date(this.fechaInicio.split('-')) && 
            new Date(item.created_at.slice(0, 10).split('-')) <= new Date(this.fechaFin.split('-')))
            : group
            group = this.dniCliente
            ? group.filter(item => item.cui.includes(this.dniCliente))
            : group
            return group
        },
        grupoDividido(){
            var group = this.grupoFilter;
            const groups = [];
            var i,j,temparray,chunk = 25;
            for (i=0,j=group.length; i<j; i+=chunk) {
                temparray = group.slice(i,i+chunk);
                groups.push(temparray);
            }
            return groups;
        }
    }
};
</script>
