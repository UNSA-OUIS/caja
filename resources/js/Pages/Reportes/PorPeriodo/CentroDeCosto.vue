<template>
    <app-layout>
        <PeriodoMenu :tab="2"/>
        <div class="card" ref="content">
            <div class="card-header">
                <h1>Por centro de costo</h1>
            </div>
            <div class="card-body">
                <b-row>
                    <b-col cols="4">
                        <b-form-group
                        label="Buscar cliente: "
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="filterInput"
                        class="mb-0"
                        >
                        <b-input-group size="sm">
                            <b-form-input
                            v-model="filter.dniCliente"
                            type="search"
                            id="filterInput"
                            placeholder="Escriba el texto a buscar..."
                            ></b-form-input>
                            <b-input-group-append>
                            <b-button :disabled="!filter.dniCliente" @click="filter.dniCliente = ''"
                                >Limpiar</b-button
                            >
                            </b-input-group-append>
                        </b-input-group>
                        </b-form-group>
                    </b-col>
                    <b-col cols="4">
                        <b-form-group
                        label="Buscar centro de costo: "
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="filterInput"
                        class="mb-0"
                        >
                        <b-input-group size="sm">
                            <b-form-input
                            v-model="centroCosto"
                            type="search"
                            id="filterInput"
                            placeholder="Escriba el texto a buscar..."
                            ></b-form-input>
                            <b-input-group-append>
                            <b-button :disabled="!centroCosto" @click="centroCosto = ''"
                                >Limpiar</b-button
                            >
                            </b-input-group-append>
                        </b-input-group>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
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
                    :items="centros"
                    :fields="fields"
                    empty-text="No hay comprobantes para mostrar"
                    empty-filtered-text="No hay comprobantes que coincidan con su búsqueda.">
                    <template v-if="centros.length" slot="bottom-row" slot-scope="">
                        <b-td class="text-right font-weight-bold">{{totalRegistros}} registros</b-td>
                        <b-td class="text-right font-weight-bold">TOTALES:</b-td>
                        <b-td class="text-right font-weight-bold">{{totalMontos}}</b-td>
                    </template>
                </b-table>
                <b-button v-if="centros.length" @click="html2pdf">Descargar PDF</b-button>
                <json-excel
                    v-if="centros.length"
                    :data="json_data"
                    type="xlsx"
                    :fields="json_fields"
                    worksheet="Reporte_periodo_x_centro"
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
                    pdf-orientation="portrait"
                    pdf-content-width="800px"
            
                    @progress="onProgress($event)"
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
    name: "reportes.centroDeCosto",
    components: {
        AppLayout,
        VueHtml2pdf,
        PeriodoMenu,
        JsonExcel
    },
    data() {
        return {
            app_url: this.$root.app_url,
            json_fields: {
                "Código": "codigo",
                "Centro de costos": "dependencia.nomb_depe",
                "Monto": "monto",
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
                { key: "codi_depe", label: "Código" },
                { key: "dependencia.nomb_depe", label: "Centro de costos" },
                { key: "monto", label: "Monto" },
            ],
            filenamepdf: "Reporte_cobros",
            centroCosto: "",
            centros: [],
            totalRegistros: 0,
            totalMontos: 0,
            filter: {
                fechaInicio: "",
                fechaFin: "",
            },
            filename: "",

        };
    },
    created(){
        var today = new Date()
        today.setHours(today.getHours() - 5)
        var dateString = today.toISOString().split("T")[0]
        this.filename = "Reporte_periodo_x_centro_" + dateString + ".xls"
        this.filter.fechaInicio = dateString;
        this.filter.fechaFin = dateString;
    },
    methods: {
        refreshTable() {
            this.$refs.tbl_comprobantes.refresh();
        },
        html2pdf(){
            this.$refs.html2Pdf.generatePdf()
        },
        async filterTable() {
            try {
                let params = "?fechaInicio=" + this.filter.fechaInicio + "&fechaFin=" + this.filter.fechaFin
                const response = await axios.get(`${this.app_url}/reportes-periodo/filter-reporte/centros/${params}`)
                console.log(`${this.app_url}/reportes-periodo/filter-reporte/centros/${params}`)
                this.centros = response.data.centros
                this.totalRegistros = response.data.totalRegistros
                this.totalMontos = response.data.totalMontos
                this.json_data = this.centros.slice()
                this.json_data.push({ 
                    codigo: "" + this.totalRegistros + " registros",
                    dependencia:{nomb_depe: "TOTALES:"},
                    monto: this.totalMontos
                })
            } catch (error) {
                console.log(error)
            }
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
        
        grupoDividido(){
            var group = this.centros;
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
