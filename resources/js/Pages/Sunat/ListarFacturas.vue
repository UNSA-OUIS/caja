<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb float-left">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`"
                            >Inicio</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item active">
                        Lista de facturas
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <b-row>
                    <b-col sm="12" md="4" lg="4" class="my-1">
                        <b-form-group
                            label="Registros por página: "
                            label-cols-sm="6"
                            label-align-sm="right"
                            label-size="sm"
                            label-for="perPageSelect"
                            class="mb-0"
                        >
                            <b-form-select
                                v-model="perPage"
                                id="perPageSelect"
                                size="sm"
                                :options="pageOptions"
                            ></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col sm="12" offset-md="3" md="5" lg="5" class="my-1">
                        <b-form-group
                            label="Buscar: "
                            label-cols-sm="3"
                            label-align-sm="right"
                            label-size="sm"
                            label-for="filterInput"
                            class="mb-0"
                        >
                            <b-input-group size="sm">
                                <b-form-input
                                    v-model="filter"
                                    type="search"
                                    id="filterInput"
                                    placeholder="Escriba el texto a buscar..."
                                ></b-form-input>
                                <b-input-group-append>
                                    <b-button
                                        :disabled="!filter"
                                        @click="filter = ''"
                                        >Limpiar</b-button
                                    >
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-table
                    ref="tbl_facturas"
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
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :sort-direction="sortDirection"
                    @filtered="onFiltered"
                    empty-text="No hay facturas para mostrar"
                    empty-filtered-text="No hay facturas que coincidan con su búsqueda."
                >
                    <template v-slot:cell(estado)="row">
                        <b-badge
                            v-if="row.item.estado == 'noEnviado'"
                            variant="primary"
                            >No Enviado</b-badge
                        >
                        <b-badge
                            v-if="row.item.estado == 'observado'"
                            variant="warning"
                            >Observado
                        </b-badge>
                        <b-badge
                            v-if="row.item.estado == 'rechazado'"
                            variant="danger"
                            >Rechazado</b-badge
                        >
                        <b-badge
                            v-if="row.item.estado == 'anulado'"
                            variant="secondary"
                            >Anulado</b-badge
                        >
                        <div v-if="row.item.estado == 'aceptado'">
                            <b-badge variant="success">Aceptado</b-badge>
                            <br />
                            <a :href="`${app_url}/${row.item.url_xml}`" download
                                >XML</a
                            >
                            <a :href="`${app_url}/${row.item.url_cdr}`">CDR</a>
                        </div>
                    </template>
                    <template v-slot:cell(acciones)="row">
                        <b-button
                            v-if="
                                row.item.estado == 'aceptado' ||
                                    row.item.estado == 'observado'
                            "
                            target="_blank"
                            variant="primary"
                            size="sm"
                            title="Ver"
                            :href="`${app_url}/${row.item.url_pdf}`"
                        >
                            <b-icon icon="printer"></b-icon>
                        </b-button>
                        <b-button
                            v-if="row.item.estado == 'noEnviado'"
                            variant="danger"
                            size="sm"
                            title="Anular"
                            @click="anular(row.item)"
                        >
                            <b-icon icon="x-circle"></b-icon>
                        </b-button>
                        <b-button
                            v-if="
                                row.item.estado == 'observado' ||
                                    row.item.estado == 'noEnviado'
                            "
                            variant="success"
                            size="sm"
                            title="Enviar"
                            @click="enviar(row.item)"
                        >
                            <b-icon icon="cloud-arrow-up"></b-icon>
                        </b-button>
                        <b-button
                            v-if="row.item.estado == 'observado' || row.item.estado == 'aceptado'"
                            size="sm"
                            @click="row.toggleDetails"
                        >
                            <b-icon
                                v-if="row.detailsShowing"
                                icon="dash-circle"
                            ></b-icon>
                            <b-icon v-else icon="plus-circle"></b-icon>
                        </b-button>
                    </template>
                    <template #row-details="row">
                        <b-card>
                            <ul>
                                <li>
                                    {{ row.item.observaciones }}
                                </li>
                            </ul>
                        </b-card>
                    </template>
                </b-table>
                <b-row>
                    <b-col offset-md="8" md="4" class="my-1">
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
            </div>
        </div>
    </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "sunat.listarFacturas",
    components: {
        AppLayout
    },
    data() {
        return {
            app_url: this.$root.app_url,
            fields: [
                { key: "codigo", label: "Codigo", sortable: true },
                { key: "serie", label: "Serie", sortable: true },
                { key: "correlativo", label: "Correlativo", sortable: true },
                {
                    key: "estado",
                    label: "Estado",
                    class: "text-center",
                    sortable: true
                },
                { key: "acciones", label: "Acciones", class: "text-center" }
            ],
            index: 1,
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],
            sortBy: null,
            sortDesc: false,
            sortDirection: "asc",
            filter: null
        };
    },
    methods: {
        refreshTable() {
            this.$refs.tbl_facturas.refresh();
        },
        myProvider(ctx) {
            let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

            if (ctx.filter !== "" && ctx.filter !== null) {
                params += "&filter=" + ctx.filter;
            }

            if (ctx.sortBy !== "" && ctx.sortBy !== null) {
                params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
            }

            const promise = axios.get(
                `${this.app_url}/sunat/listarFacturas${params}`
            );

            return promise.then(response => {
                const factura = response.data.data;
                console.log(factura);
                this.totalRows = response.data.total;

                return factura || [];
            });
        },
        anular(factura) {
            this.$bvModal
                .msgBoxConfirm("¿Esta seguro de querer anular esta factura?", {
                    title: "Anular factura",
                    okVariant: "danger",
                    okTitle: "SI",
                    cancelTitle: "NO",
                    centered: true
                })
                .then(async value => {
                    if (value) {
                        this.$inertia.post(
                            route("sunat.anularFactura", [factura])
                        );
                        this.refreshTable();
                    }
                });
        },
        enviar(factura) {
            this.$bvModal
                .msgBoxConfirm("¿Esta seguro de querer enviar esta factura?", {
                    title: "Enviar factura",
                    okVariant: "success",
                    okTitle: "SI",
                    cancelTitle: "NO",
                    centered: true
                })
                .then(async value => {
                    if (value) {
                        this.$inertia.post(
                            route("sunat.enviarFactura", [factura])
                        );
                        this.refreshTable();
                    }
                });
        },
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        }
    }
};
</script>
