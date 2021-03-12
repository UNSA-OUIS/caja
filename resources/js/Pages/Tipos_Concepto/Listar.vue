<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Tipos de Concepto</h3>
                <inertia-link class="btn btn-success float-right" :href="route('tipos-concepto.crear')">Nuevo</inertia-link>
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
                            <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
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
                                    <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-table
                    ref="tbl_tipos_concepto"
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
                    empty-text="No hay tipos de concepto para mostrar"
                    empty-filtered-text="No hay tipos de concepto que coincidan con su búsqueda." 
                >
                    <template v-slot:cell(condicion)="row">
                        <b-badge v-if="!row.item.deleted_at" variant="success">Activo</b-badge>
                        <b-badge v-else variant="secondary">Inactivo</b-badge>
                    </template>
                    <template v-slot:cell(acciones)="row">                        
                        <inertia-link 
                            v-if="!row.item.deleted_at"
                            class="btn btn-primary btn-sm"
                            :href="route('tipos-concepto.mostrar', row.item.id)"
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
    const axios = require('axios')
    import AppLayout from '@/Layouts/AppLayout'    

    export default {
        name: "tipos-concepto.listar",        
        components: {
            AppLayout,            
        },
        data() {
            return {
                app_url: this.$root.app_url,
                fields: [
                    { key: "id", label: "ID", sortable: true, class: "text-center" },
                    { key: "nombre", label: "Tipo de Concepto", sortable: true },    
                    { key: "condicion", label: "Condición", class: "text-center" },                
                    { key: "acciones", label: "Acciones", class: "text-center" },
                ],
                index: 1,
                totalRows: 1,
                currentPage: 1,
                perPage: 5,
                pageOptions: [5, 10, 15],
                sortBy: null,
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,          
            };
        },
        created() {
            //this.totalRows = this.unidadesMedida.length;    
        },
        methods: {
            refreshTable() {
                this.$refs.tbl_tipos_concepto.refresh();
            },     
            myProvider(ctx) {                
                let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

                if (ctx.filter !== "" && ctx.filter !== null) {                    
                    params += "&filter=" + ctx.filter;
                }

                if (ctx.sortBy !== "" && ctx.sortBy !== null) {
                    params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
                }

                const promise = axios.get(`${this.app_url}/tipos-concepto/listar${params}`)
                
                return promise.then(response => {                                        
                    const tiposConcepto = response.data.data                                   
                    this.totalRows = response.data.total;

                    return tiposConcepto || []
                })
            },  
            async eliminar(tipos_concepto) {                
                this.$bvModal.msgBoxConfirm("¿Esta seguro de querer eliminar este tipo de concepto?", {
                        title: "Eliminar tipo de concepto",
                        okVariant: "danger",
                        okTitle: "SI",
                        cancelTitle: "NO",
                        centered: true,
                    })
                    .then(async (value) => {
                        if (value) {                            
                            try {
                                const response = await axios.delete(`${this.app_url}/tipos-concepto/${tipos_concepto.id}`)
                                
                                if (!response.data.error) {    
                                    this.makeToast(response.data.successMessage, 'success')                                                                                                             
                                    this.refreshTable()
                                }
                                else {                        
                                    this.makeToast(response.data.errorMessage, 'danger')        
                                }                                
                            } catch (error) {
                                console.log(error)
                                this.makeToast('Se ha producido un error, vuelve a intentarlo más tarde', 'danger')        
                            }     
                        }
                    })     
            },
            async restaurar(tipos_concepto) {
                this.$bvModal.msgBoxConfirm("¿Esta seguro de querer restaurar este tipo de concepto?", {
                        title: "Restaurar tipo de concepto",
                        okVariant: "primary",
                        okTitle: "SI",
                        cancelTitle: "NO",
                        centered: true,
                    })
                    .then(async (value) => {
                        if (value) { 
                            try {
                                const response = await axios.post(`${this.app_url}/tipos-concepto/${tipos_concepto.id}/restaurar`)
                                
                                if (!response.data.error) {    
                                    this.makeToast(response.data.successMessage, 'success')                                                                                                             
                                    this.refreshTable()
                                }
                                else {                        
                                    this.makeToast(response.data.errorMessage, 'danger')        
                                }                                
                            } catch (error) {
                                console.log(error)
                                this.makeToast('Se ha producido un error, vuelve a intentarlo más tarde', 'danger')        
                            }     
                        }
                    })                   
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1;
            },            
            makeToast(message, variant = null) {
                this.$bvToast.toast(message, {
                    title: `Tipos de Concepto`,
                    variant: variant,
                    solid: true
                })
            }     
        },
    }
</script>
