<template>
    <app-layout>
        <PeriodoMenu :tab="5"/>
        <div class="card" ref="content">
            <div class="card-header">
                <h1>Facturas</h1>
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
                        label="Tipo de factura: "
                        label-cols-sm="4"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="tipoFactura"
                        class="mb-0"
                        >
                            <b-form-select
                            v-model="filter.tipo_factura"
                            id="tipoFactura"
                            :options="tipos_factura"
                            size="sm">
                            <template v-slot:first>
                                <option :value=0>TODOS</option>
                            </template>
                            </b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col cols="4">
                        <b-form-group
                        label="Tipo de fecha: "
                        label-cols-sm="4"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="tipoFactura"
                        class="mb-0"
                        >
                            <b-form-select
                            v-model="filter.tipo_fecha"
                            id="tipoFactura"
                            :options="tipos_fecha"
                            size="sm">
                            <template v-slot:first>
                                <option :value=0>POR FEC. DIGITACIÓN</option>
                            </template>
                            </b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col cols="4">
                        <b-form-group
                        label="Cuenta corriente: "
                        label-cols-sm="4"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="ctaCorriente"
                        class="mb-0"
                        >
                            <b-form-select
                            v-model="filter.cta_corriente"
                            id="ctaCorriente"
                            :options="ctas_corriente"
                            size="sm">
                            <template v-slot:first>
                                <option :value=0>TODOS</option>
                            </template>
                            </b-form-select>
                        </b-form-group>
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
                    ref="tbl_comprobantes"
                    show-empty
                    striped
                    hover
                    sticky-header
                    bordered
                    small
                    responsive
                    :items="comprobantes"
                    :fields="fields"
                    empty-text="No hay comprobantes para mostrar"
                    empty-filtered-text="No hay comprobantes que coincidan con su búsqueda."
                >
                    <template v-slot:cell(serieCorre)="row">
                        {{ row.item.serie }}-{{ row.item.correlativo }}
                    </template>
                    <template v-slot:cell(created_at)="row">
                        {{ row.item.created_at.substring(0, 10) }}
                    </template>
                    <template v-slot:cell(fecha_cancelacion)="row">
                        <template v-if="row.item.fecha_cancelacion">
                        {{ row.item.fecha_cancelacion.substring(0, 10) }}
                        </template>
                    </template>
                    <template v-slot:cell(nro_operacion)="row">
                        <template v-if="row.item.nro_operacion">
                        {{ row.item.nro_operacion.substring(0, 6) }}
                        </template>
                    </template>
                    <template v-slot:cell(razon_social)="row">
                        {{ row.item.comprobanteable.razon_social }}
                    </template>
                    <template v-slot:cell(direccion)="row">
                        {{ row.item.comprobanteable.direccion }}
                    </template>
                    <template v-if="comprobantes.length" slot="bottom-row" slot-scope="">
                        <b-td class="text-right font-weight-bold">{{totalRegistros}} registros</b-td><b-td colspan="5"></b-td>
                        <b-td class="text-right font-weight-bold">TOTALES:</b-td>
                        <b-td class="text-right font-weight-bold">{{totalDescuentos}}</b-td>
                        <b-td class="text-right font-weight-bold">{{totalIGV}}</b-td>
                        <b-td class="text-right font-weight-bold">{{totalDetra}}</b-td>
                        <b-td class="text-right font-weight-bold">{{totalMontos}}</b-td>
                    </template>
                </b-table>
                <b-button v-if="comprobantes.length" @click="html2pdf">Descargar PDF</b-button>
                <json-excel
                    v-if="comprobantes.length"
                    :data="json_data"
                    type="xlsx"
                    :fields="json_fields"
                    worksheet="Reporte_periodo_x_cajero"
                    :name="filename"
                    class="btn btn-success">
                        Descargar Excel
                </json-excel>
                <!--<a v-if="comprobantes.length" class="btn btn-success float-right" href="#" @click="dompdf">Descargar (dompdf)</a>-->
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
                                            ref="tbl_comprobantes"
                                            show-empty
                                            striped
                                            hover
                                            bordered
                                            small
                                            responsive
                                            stacked="md"
                                            :items="group"
                                            :fields="fields"
                                            empty-text="No hay comprobantes para mostrar"
                                            empty-filtered-text="No hay comprobantes que coincidan con su búsqueda."
                                        >
                                            <template v-slot:cell(serieCorre)="row">
                                                {{ row.item.serie }}-{{ row.item.correlativo }}
                                            </template>
                                            <template v-slot:cell(created_at)="row">
                                                {{ row.item.created_at.substring(0, 10) }}
                                            </template>
                                            <template v-slot:cell(fecha_cancelacion)="row">
                                                <template v-if="row.item.fecha_cancelacion">
                                                {{ row.item.fecha_cancelacion.substring(0, 10) }}
                                                </template>
                                            </template>
                                            <template v-slot:cell(nro_operacion)="row">
                                                <template v-if="row.item.nro_operacion">
                                                {{ row.item.nro_operacion.substring(0, 6) }}
                                                </template>
                                            </template>
                                            <template v-slot:cell(razon_social)="row">
                                                {{ row.item.comprobanteable.razon_social }}
                                            </template>
                                            <template v-slot:cell(direccion)="row">
                                                {{ row.item.comprobanteable.direccion }}
                                            </template>
                                            <template slot="bottom-row" slot-scope="">
                                                <b-td class="text-right font-weight-bold">{{totalRegistros}} registros</b-td><b-td colspan="5"></b-td>
                                                <b-td class="text-right font-weight-bold">TOTALES:</b-td>
                                                <b-td class="text-right font-weight-bold">{{totalDescuentos}}</b-td>
                                                <b-td class="text-right font-weight-bold">{{totalIGV}}</b-td>
                                                <b-td class="text-right font-weight-bold">{{totalDetra}}</b-td>
                                                <b-td class="text-right font-weight-bold">{{totalMontos}}</b-td>
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
import VueHtml2pdf from "vue-html2pdf";
import PeriodoMenu from "./PeriodoMenu";
import JsonExcel from "vue-json-excel";

export default {
    name: "reportes.cajero",
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
                "SERIE-CORRE": "serieCorre",
                "F. DIGI": "created_at",
                "F. CANC": "fecha_cancelacion",
                "R. ING": "nro_operacion",
                RUC: "codi_usuario",
                CLIENTE: "razon_social",
                "DIRECCIÓN": "direccion",
                "Dscto.": "total_descuento",
                IGV: "total_impuesto",
                DETRA: "detraccion",
                Monto: "total",
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
                { key: "serieCorre", label: "SERIE-CORRE"},
                { key: "created_at", label: "F. DIGI" },
                { key: "fecha_cancelacion", label: "F. CANC" },
                { key: "nro_operacion", label: "R. ING" },
                { key: "codi_usuario", label: "RUC" },
                { key: "razon_social", label: "CLIENTE" ,class: "text-center" },
                { key: "direccion", label: "DIRECCIÓN" ,class: "text-center" },
                { key: "total_descuento", label: "Dscto." ,class: "text-right" },
                { key: "total_impuesto", label: "IGV" ,class: "text-right" },
                { key: "detraccion", label: "DETRA" ,class: "text-right" },
                { key: "total", label: "Monto" ,class: "text-right" },

            ],
            tipos_factura: [
                { value: 1, text: "CANCELADOS" },
                { value: 2, text: "PENDIENTES" },
                { value: 3, text: "CON DETRACCIÓN" },
            ],
            tipos_fecha: [
                { value: 1, text: "POR FEC. CANCELACIÓN" },
            ],
            ctas_corriente: [
                { value: 1, text: "Option 1" },
                { value: 2, text: "Option 2" },
                { value: 3, text: "Option 3" },
            ],
            filename: "",
            comprobantes : [],
            totalRegistros: 0,
            totalCobros: 0,
            totalDescuentos: 0,
            totalIGV: 0,
            totalMontos: 0,
            totalDetra: 0,
            filenamepdf: "Reporte_cobros",
            currentPage: 1,
            perPage: 5,
            filter: {
                cajeroId: "",
                fechaInicio: "",
                fechaFin: "",
                tipo_factura: 0,
                tipo_fecha: 0,
                cta_corriente: 0
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
        this.filename = "Reporte_periodo_x_cajero_" + dateString + ".xls"
        this.filter.fechaInicio = dateString;
        this.filter.fechaFin = dateString;
    },
    methods: {
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
            this.$refs.tbl_comprobantes.refresh();
        },
        async filterTable() {
            try {
                let params = "?fechaInicio=" + this.filter.fechaInicio + "&fechaFin=" + this.filter.fechaFin 
                + "&tipo_factura=" + this.filter.tipo_factura + "&tipo_fecha=" + this.filter.tipo_fecha
                if (this.cajero != null){
                    params = params + "&cajeroId=" + this.cajero.cajero_id
                }
                const response = await axios.get(`${this.app_url}/reportes-periodo/filter-reporte/facturas/${params}`)
                this.comprobantes = response.data.comprobantes
                this.totalRegistros = response.data.totalRegistros
                this.totalCobros = response.data.totalCobros
                this.totalDescuentos = response.data.totalDescuentos
                this.totalIGV = response.data.totalIGV
                this.totalMontos = response.data.totalMontos
                //this.json_data = this.comprobantes.slice()
                var temp = []
                this.comprobantes.forEach(function(cobro) {
                    temp.push({
                        serieCorre: "" + cobro.serie + "-" + cobro.correlativo,
                        created_at: cobro.created_at,
                        fecha_cancelacion: cobro.fecha_cancelacion,
                        nro_operacion: cobro.nro_operacion,
                        codi_usuario: cobro.codi_usuario,
                        razon_social: cobro.comprobanteable.razon_social,
                        direccion: cobro.comprobanteable.direccion,
                        total_descuento: cobro.total_descuento,
                        total_impuesto: cobro.total_impuesto,
                        detraccion: 0,
                        total: cobro.total
                    })
                });
                this.json_data = temp.slice()
                this.json_data.push({
                    serieCorre: "" + this.totalRegistros + " registros",
                    created_at: "",
                    cancelado_at: "",
                    ring: "",
                    codi_usuario: "",
                    razon_social: "",
                    direccion: "TOTALES:",
                    total_descuento: this.totalDescuentos,
                    total_impuesto: this.totalIGV,
                    detraccion: 0,
                    total: this.totalMontos
                })
                
            } catch (error) {
                console.log(error)
            }
        },
        html2pdf(){
            this.$refs.html2Pdf.generatePdf()
        },
        dompdf(){
            let params = "?cajeroId=" + this.filter.cajeroId + "&fechaInicio=" + this.filter.fechaInicio + "&fechaFin=" + this.filter.fechaFin
            window.open(`${this.app_url}/reportes-periodo/cajero/pdf/${params}`, '_blanck');
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
  computed: {
    grupoFilter() {
      var group = this.comprobantes;

      group =
        this.fechaInicio && this.fechaFin
          ? group.filter(
              (item) =>
                new Date(item.created_at.slice(0, 10).split("-")) >=
                  new Date(this.fechaInicio.split("-")) &&
                new Date(item.created_at.slice(0, 10).split("-")) <=
                  new Date(this.fechaFin.split("-"))
            )
          : group;
      group = this.cajeroId
        ? group.filter((item) => item.cui.includes(this.cajeroId))
        : group;
      return group;
    },
    grupoDividido(){
        var group = this.comprobantes;
        const groups = [];
        var i,j,temparray,chunk = 25;
        for (i=0,j=group.length; i<j; i+=chunk) {
            temparray = group.slice(i,i+chunk);
            groups.push(temparray);
        }
        return groups;
    } 
  }
}
</script>
