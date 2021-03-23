<template>
    <app-layout>
        <div class="card">
            <div class="card-body">
                <b-alert
                    show
                    dismissible
                    variant="success"
                    v-if="$page.props.successMessage"
                    >{{ $page.props.successMessage }}</b-alert
                >
                <b-alert
                    show
                    dismissible
                    variant="danger"
                    v-if="$page.props.errorMessage"
                    >{{ $page.props.errorMessage }}</b-alert
                >
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
                    ref="tbl_comprobantes"
                    show-empty
                    striped
                    hover
                    bordered
                    small
                    responsive
                    stacked="md"
                    :items="comCabe"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :sort-direction="sortDirection"
                    @filtered="onFiltered"
                    empty-text="No hay tipos de comprobante para mostrar"
                    empty-filtered-text="No hay tipos de comprobante que coincidan con su búsqueda."
                >
                    <template v-slot:cell(estado)="row">
                        <b-badge
                            v-if="row.item.estado == true"
                            variant="success"
                            >Activo</b-badge
                        >
                        <b-badge v-else variant="secondary">Inactivo</b-badge>
                    </template>
                    <template v-slot:cell(acciones)="row">
                        <b-button
                            variant="success"
                            size="sm"
                            title="Enviar a SUNAT"
                            @click="enviar(row.item)"
                        >
                            <b-icon icon="check"></b-icon>
                        </b-button>
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
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "sunat.enviarFacturas",
    components: {
        AppLayout
    },
    data() {
        return {
            app_url: this.$root.app_url,
            fields: [
                {
                    key: "id",
                    label: "ID",
                    sortable: true,
                    class: "text-center"
                },
                { key: "codigo", label: "Codigo", sortable: true },
                { key: "cui", label: "CUI", sortable: true },
                { key: "nues", label: "Codigo de Escuela", sortable: true },
                { key: "serie", label: "Serie", sortable: true },
                { key: "correlativo", label: "Correlativo", sortable: true },
                { key: "detalles", label: "Detalles", sortable: true },
                { key: "estado", label: "Condición", class: "text-center" },
                { key: "acciones", label: "Acciones", class: "text-center" }
            ],
            comCabe: [
                {
                    id: "1",
                    codigo: "111",
                    cui: "20143377",
                    nues: "044",
                    serie: "F001",
                    correlativo: "0001",
                    detalles: [
                        {
                            cantidad: 100,
                            valor_unitario: 15,
                            igv: 18,
                            estado: true
                        },
                        {
                            cantidad: 100,
                            valor_unitario: 15,
                            igv: 18,
                            estado: true
                        }
                    ],
                    estado: true
                }
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
            this.$refs.tbl_comprobantes.refresh();
        },
        enviar(comprobante) {
            console.log(comprobante);
            this.$bvModal
                .msgBoxConfirm(
                    "¿Esta seguro de querer eviar este comprobante?",
                    {
                        title: "Enviar Comprobante",
                        okVariant: "success",
                        okTitle: "SI",
                        cancelTitle: "NO",
                        centered: true
                    }
                )
                .then(async value => {
                    if (value) {
                        this.$inertia.post(
                            route("sunat.enviarFacturas", comprobante)
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
