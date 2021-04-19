<template>
    <app-layout>
        <PeriodoMenu :tab="1"/>
        <div class="card" ref="content">
            <div class="card-header">
                <h1>Descuentos por cajero</h1>
                <b-button @click="html2pdf">Descargar (html2pdf)</b-button>
                <!--<b-button @click="dompdf">Descargar (dompdf)</b-button>
                <a
                    class="btn btn-success float-right" method="post"
                    href="#" @click="dompdf"
                    >Descargar (dompdf)</a
                >-->
            </div>
            <div class="card-body">
                <b-row>
                    <b-col cols="4">
                        <b-form-group
                        label="Buscar cajero: "
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="filterInput"
                        class="mb-0"
                        >
                        <b-input-group size="sm">
                            <b-form-input
                            v-model="dniCliente"
                            type="search"
                            id="filterInput"
                            placeholder="Escriba el texto a buscar..."
                            ></b-form-input>
                            <b-input-group-append>
                            <b-button :disabled="!dniCliente" @click="dniCliente = ''"
                                >Limpiar</b-button
                            >
                            </b-input-group-append>
                        </b-input-group>
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
                            v-model="fechaInicio"
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
                            v-model="fechaFin"
                            today-button
                            reset-button
                            close-button
                            placeholder="Elige una fecha"
                            size="sm"
                        ></b-form-datepicker>
                         </b-form-group>
                    </b-col>
                </b-row>
                <b-table
                            ref="tbl_comprobantes"
                            show-empty
                            striped
                            hover
                            sticky-header
                            bordered
                            small
                            responsive
                            :items="grupoFilter"
                            :fields="fields"
                            empty-text="No hay comprobantes para mostrar"
                            empty-filtered-text="No hay comprobantes que coincidan con su búsqueda."
                        >
                        </b-table>
                
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
                                
                                    <div v-for="(group) in grupoDividido">
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
import AppLayout from "@/Layouts/AppLayout";
import VueHtml2pdf from 'vue-html2pdf'
import PeriodoMenu from "./PeriodoMenu";

export default {
    name: "comprobantes.descuentos",
    props: ["comprobantes"],
    components: {
        AppLayout,
        VueHtml2pdf,
        PeriodoMenu
    },
    data() {
        return {
            app_url: this.$root.app_url,
            fields: [
                { key: "codigo", label: "Código" },
                { key: "serie", label: "Serie" },
                { key: "correlativo", label: "Correlativo" },
                { key: "cui", label: "Cliente" },
                { key: "total", label: "Precio Total" },

            ],
            filenamepdf: "Reporte_cobros",
            currentPage: 1,
            perPage: 5,
            dniCliente: "",
            fechaInicio: "",
            fechaFin: "",
            month: "",

        };
    },
    methods: {
        refreshTable() {
            this.$refs.tbl_comprobantes.refresh();
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
            var group = this.comprobantes;
            
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
