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
            :busy="isBusy"
        >                
            <template v-slot:cell(apn)="row">
                <a href="#" @click="buscarCuiAlumno(row.item)">{{ row.item.apn }}</a>                
            </template>
            <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Cargando...</strong>
            </div>
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
    props: ["apn"],    
    data() {
        return {
            app_url: this.$root.app_url,
            alumno: {},            
            fields: [
                { key: "cui", label: "CUI", class: "text-center" },
                { key: "apn", label: "APELLIDOS Y NOMBRES" },
            ],            
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],            
            isBusy: false,
        };
    },
    methods: {
        refreshTable() {
            this.$refs.tbl_usuarios.refresh();
        },
        myProvider(ctx) {
            this.toggleBusy()
            let params = "?filter=" + this.apn
            params += "&page=" + ctx.currentPage + "&size=" + ctx.perPage            

            const promise = axios.get(`${this.app_url}/buscarApnAlumno${params}`)                            

            return promise.then(response => {                
                this.toggleBusy()
                const usuarios = response.data.data;
                this.totalRows = response.data.total;

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
                /*else {
                    this.showEscuelas = true                    
                }*/
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(matricula) {       
            this.$inertia.get(route('comprobantes.crear_alumno'), {                
                'alumno' : this.alumno,
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