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
            empty-text="No hay comprobantes para mostrar"
            empty-filtered-text="No hay comprobantes que coincidan con su búsqueda."
            
        >                
            <template v-slot:cell(numero)="row">                
                <a href="#" @click="mostrarComprobante(row.item)">{{ row.item.serie }}-{{ row.item.correlativo }}</a>                
            </template>
            <template v-slot:cell(usuario)="row">                
                <span v-if="row.item.tipo_usuario === 'alumno'">
                {{ row.item.comprobanteable.apn }}
                </span>
                <span v-else-if="row.item.tipo_usuario === 'empresa'">
                {{ row.item.comprobanteable.razon_social }}
                </span>
                <span v-else-if="row.item.tipo_usuario === 'particular'">
                {{ row.item.comprobanteable.apellidos }},
                {{ row.item.comprobanteable.nombres }}
                </span>
                <span v-else-if="row.item.tipo_usuario === 'docente'">
                {{ row.item.comprobanteable.apn }}
                </span>
                <span v-else-if="row.item.tipo_usuario === 'dependencia'">
                {{ row.item.comprobanteable.nomb_depe }}
                </span>                
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
    name: "cobros.buscarusuario.comprobantes",
    props: ["tipo_comprobante", "serie", "correlativo"],    
    data() {
        return {
            app_url: this.$root.app_url,
            alumno: {},                        
            fields: [
                { key: "numero", label: "NÚMERO", class: "text-center" },
                { key: "usuario", label: "CLIENTE" },
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
                'tipo_usuario': 'COMPROBANTE',
                'serie': this.serie,
                'correlativo': this.correlativo,
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
        mostrarComprobante(comprobante) {       
            this.$inertia.get(route('comprobantes.crear_nota'), {                
                'comprobanteId' : comprobante.id,
                'tipo_comprobante': this.tipo_comprobante             
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