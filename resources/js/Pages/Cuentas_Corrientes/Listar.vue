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
                        Lista de cuentas corrientes
                    </li>
                </ol>
                <inertia-link
                    class="btn btn-success float-right"
                    :href="route('cuentas-corrientes.crear')"
                    >Nuevo</inertia-link
                >
            </div>
            <div class="card-body">
                <b-alert
                    show
                    variant="success"
                    v-if="$page.props.successMessage"
                    >{{ $page.props.successMessage }}</b-alert
                >
                <b-alert
                    show
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
                    ref="tbl_cuentas_corrientes"
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
                    empty-text="No hay cuentas corrientes para mostrar"
                    empty-filtered-text="No hay cuentas corrientes que coincidan con su búsqueda."
                >
                    <template v-slot:cell(condicion)="row">
                        <b-badge v-if="!row.item.deleted_at" variant="success"
                            >Activo</b-badge
                        >
                        <b-badge v-else variant="secondary">Inactivo</b-badge>
                    </template>
                    <template v-slot:cell(acciones)="row">
                        <inertia-link
                            v-if="!row.item.deleted_at"
                            class="btn btn-primary btn-sm"
                            :href="
                                route('cuentas-corrientes.mostrar', row.item.id)
                            "
                        >
                            <b-icon icon="eye"></b-icon>
                        </inertia-link>
                        <b-button
                            v-if="!row.item.deleted_at"
                            variant="danger"
                            size="sm"
                            title="Eliminar"
                            @click="eliminar(row.item)"
                        >
                            <b-icon icon="trash"></b-icon>
                        </b-button>
                        <b-button
                            v-else
                            variant="success"
                            size="sm"
                            title="Restaurar"
                            @click="restaurar(row.item)"
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
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "cuentas-corrientes.listar",
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
                { key: "numero_cuenta", label: "Cta. Corriente" },
                { key: "banco", label: "Entidad bancaria" },
                { key: "moneda", label: "Moneda", class: "text-center" },
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
            this.$refs.tbl_cuentas_corrientes.refresh();
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
                `${this.app_url}/cuentas-corrientes/listar${params}`
            );

            return promise.then(response => {
                const cuentasCorrientes = response.data.data;
                this.totalRows = response.data.total;

                return cuentasCorrientes || [];
            });
        },
        eliminar(cuenta_corriente) {
            this.$bvModal
                .msgBoxConfirm(
                    "¿Esta seguro de querer eliminar esta cuenta corriente?",
                    {
                        title: "Eliminar cuenta corriente",
                        okVariant: "danger",
                        okTitle: "SI",
                        cancelTitle: "NO",
                        centered: true
                    }
                )
                .then(value => {
                    if (value) {
                        this.$inertia.delete(
                            route("cuentas-corrientes.eliminar", [
                                cuenta_corriente.id
                            ])
                        );
                        this.refreshTable();
                    }
                });
        },
        async restaurar(cuenta_corriente) {
            this.$bvModal
                .msgBoxConfirm(
                    "¿Esta seguro de querer restaurar esta cuenta corriente?",
                    {
                        title: "Restaurar cuenta corriente",
                        okVariant: "primary",
                        okTitle: "SI",
                        cancelTitle: "NO",
                        centered: true
                    }
                )
                .then(value => {
                    if (value) {
                        this.$inertia.post(
                            route("cuentas-corrientes.restaurar", [
                                cuenta_corriente.id
                            ])
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
