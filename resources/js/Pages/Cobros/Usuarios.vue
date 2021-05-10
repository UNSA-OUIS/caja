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
                <!--<a v-if="!showEscuelas" href="#" @click="mostrarComprobante(row.item)">{{ row.item.apn }}</a>                
                <a v-else href="#" @click="mostrarComprobante(row.item)">{{ row.item.escuela.nesc }}</a>                -->
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
            alumnos: [
                { key: "cui", label: "CUI", class: "text-center" },
                { key: "data", label: "APELLIDOS Y NOMBRES" },
            ],          
            escuelas: [
                { key: "nues", label: "CÓDIGO", class: "text-center" },
                { key: "data", label: "ESCUELA" },
            ],          
            fields: [],
            showEscuelas: false,
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
                'opcion_busqueda': this.opcion_busqueda,
                'filtro': this.filtro,
                'page': ctx.currentPage,
                'size': ctx.perPage            
            }
            /*let params = "?filtro=" + this.apn
            params += "&page=" + ctx.currentPage + "&size=" + ctx.perPage*/

            const promise = axios.get(`${this.app_url}/buscarAlumno`, { params })

            return promise.then(response => {                
                //this.toggleBusy()
                let usuarios
                if (!this.showEscuelas) {                    
                    usuarios = response.data.data
                    this.totalRows = response.data.total
                    this.fields = this.alumnos
                }
                else {                     
                    usuarios = response.data.data[0].matriculas //matriculas de un unico alumno
                    this.totalRows = response.data.data[0].matriculas.length                    
                    this.fields = this.escuelas                   
                }                

                return usuarios || [];
            });
        },     
        async buscarCuiAlumno(alumno) {
            try {
                const response = await axios.get(`${this.app_url}/buscarCuiAlumno/${alumno.cui}`)
                this.alumno = response.data                

                if (this.alumno.matriculas.length == 1) {
                    this.mostrarComprobante(this.alumno.matriculas[0])                    
                }
                else {
                    console.log('jeiken')
            
                    this.showEscuelas = true   
                    this.opcion_busqueda = 'CUI'
                    this.refreshTable()
                }
            } catch (error) {
                console.log(error)
            }      
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