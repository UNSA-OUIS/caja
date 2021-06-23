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
                <a 
                    v-if="row.item.matriculas.length == 1"
                    href="#" 
                    @click="mostrarComprobante(row.item, row.item.matriculas[0])"
                >
                    {{ row.item.apn }}
                </a>
                <a 
                    v-else
                    href="#" 
                    @click="row.toggleDetails"
                >
                    {{ row.item.apn }}
                </a>                
            </template>         
            <template #row-details="row">
                <b-card>
                    <ul>
                        <li v-for="(matricula, key) in row.item.matriculas" :key="key">                            
                            <a href="#" @click="mostrarComprobante(row.item, matricula)">
                                {{ matricula.escuela.nesc }}
                            </a>
                        </li>
                    </ul>
                </b-card>
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
    name: "usuarios.listar",
    props: ["opcion_busqueda", "filtro"],    
    data() {
        return {
            app_url: this.$root.app_url,
            alumno: {},                        
            fields: [
                { key: "cui", label: "CUI", class: "text-center" },
                { key: "data", label: "APELLIDOS Y NOMBRES" },
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
            let params = {
                'tipo_usuario': 'ALUMNO',
                'opcion_busqueda': this.opcion_busqueda,
                'filtro': this.filtro,
                'page': ctx.currentPage,
                'size': ctx.perPage            
            }            
            const promise = axios.get(`${this.app_url}/buscarUsuario`, { params })

            return promise.then(response => {                
                const usuarios = response.data.data
                this.totalRows = response.data.total                    
                if (this.totalRows == 1 && this.opcion_busqueda === 'CUI'){
                    if (usuarios[0].matriculas.length == 1){
                        this.$inertia.get(route('comprobantes.crear_alumno'), {                
                            'alumno' : usuarios[0],
                            'matricula': usuarios[0].matriculas[0]
                        })
                    }
                }
                return usuarios || [];
            });
        },             
        mostrarComprobante(alumno, matricula) {       
            this.$inertia.get(route('comprobantes.crear_alumno'), {                
                'alumno' : alumno,
                'matricula': matricula
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