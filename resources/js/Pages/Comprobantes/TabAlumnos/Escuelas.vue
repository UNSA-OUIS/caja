<template>     
    <b-container>
        <b-row class="justify-content-md-center">                
            <b-col cols="12" md="auto">
                <b-card :title="alumno.apn">
                    <b-card-text>
                        El alumno está registrado en {{ alumno.matriculas.length }} escuelas.<br>
                        Elija la escuela a consultar
                    </b-card-text>
                </b-card>
            </b-col>                
        </b-row>
        <b-row class="justify-content-md-center">                
            <b-col cols="12" md="auto">
                <b-table 
                    bordered 
                    hover
                    small
                    head-variant="light"
                    :fields="fields" 
                    :items="alumno.matriculas"
                >   
                    <template v-slot:cell(codigo)="row">
                        {{ row.item.escuela.nues }}
                    </template>     
                    <template v-slot:cell(escuela)="row">
                        <a href="#" @click="mostrarComprobante(row.item)">{{ row.item.escuela.nesc }}</a>
                    </template>
                </b-table>                
            </b-col>                
        </b-row>
    </b-container>                                                 
</template>

<script>
export default {
    name: "comprobantes.tab-alumnos.escuelas",  
    props: ["alumno"],
    data() {
        return {
            app_url: this.$root.app_url,
            fields: [
                { key: "codigo", label: "Código", sortable: true, class: "text-center" },
                { key: "escuela", label: "Escuela", sortable: true },                                
            ],            
        };
    },        
    methods: {        
        mostrarComprobante(matricula) {       
            this.$inertia.get(route('comprobantes.crear_alumno'), {                
                'alumno' : this.alumno,
                'matricula': matricula
            })
        },        
    }
};
</script>
