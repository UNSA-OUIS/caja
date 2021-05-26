<template>            
    <div>                   
        <b-table
            ref="tbl_usuarios"
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
            empty-text="No hay usuarios para mostrar"
            empty-filtered-text="No hay usuarios que coincidan con su búsqueda."
            
        >                
            <template v-slot:cell(data)="row">                
                <a href="#" @click="mostrarComprobante(row.item)">{{ row.item.nomb_depe }}</a>                
            </template>                     
        </b-table>
        <b-row>
            <b-col class="mr-auto">
                <span class="font-weight-bold">Total: </span> {{ totalRows }} registros                    
            </b-col>
            <b-col class="ml-auto">
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
</template>
<script>
const axios = require("axios");

export default {
    name: "cobros.buscarusuario.dependencias",
    props: ["opcion_busqueda", "filtro"],    
    data() {
        return {
            app_url: this.$root.app_url,
            alumno: {},                        
            fields: [
                { key: "codi_depe", label: "CÓDIGO", class: "text-center" },
                { key: "data", label: "NOMBRE DEPENDENCIA" },
            ],                      
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],                        
        };
    },
    methods: {
        refreshTable() {
            this.$refs.tbl_usuarios.refresh();
        },
        myProvider(ctx) {
            //this.toggleBusy()
            let params = {
                'tipo_usuario': 'DEPENDENCIA',
                'opcion_busqueda': this.opcion_busqueda,
                'filtro': this.filtro,
                'page': ctx.currentPage,
                'size': ctx.perPage            
            }            
            const promise = axios.get(`${this.app_url}/buscarUsuario`, { params })

            return promise.then(response => {                
                //this.toggleBusy()                               
                const usuarios = response.data.data
                this.totalRows = response.data.total                    
                
                return usuarios || [];
            });
        },             
        mostrarComprobante(dependencia) {       
            this.$inertia.get(route('comprobantes.crear_dependencia'), {                
                'dependencia' : dependencia,                
            })
        },      
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        toggleBusy() {
            this.isBusy = !this.isBusy
        },
    }
};
</script>