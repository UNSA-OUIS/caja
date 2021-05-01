<template>
    <div>
        <template v-if="!showEscuelas && !showAlumnos">
            <div class="d-flex justify-content-center mb-3">    
                <form @submit.prevent="buscarCuiAlumno" id="cui_form"></form> 
                <form @submit.prevent="buscarApnAlumno" id="apn_form"></form>                                                          
                <table>
                    <caption class="mb-3" style="caption-side: top; text-align:center;">BÚSQUEDA POR ALUMNO</caption>                        
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="cui">CUI</label>
                        </th>
                        <td>
                            <b-form-input
                                id="cui"
                                v-model="cui"
                                class="mb-2 mr-sm-2 mb-sm-0 text-center"   
                                required           
                                form="cui_form"                         
                                maxlength="8"     
                            ></b-form-input>                                    
                        </td>
                        <td>
                            <b-button form="cui_form" type="submit" class="ml-sm-2" variant="info">
                                <b-icon icon="search"></b-icon> Buscar
                            </b-button>
                        </td>
                    </tr> 
                    <tr>
                        <td>&nbsp;</td>
                    </tr> 
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="ap_paterno">Ap. Paterno <span class="text-danger">*</span></label>
                        </th>
                        <td>
                            <b-form-input
                                id="ap_paterno"     
                                v-model="ap_paterno"                               
                                class="mb-2 mr-sm-2 mb-sm-0"  
                                required        
                                form="apn_form"                                                              
                            ></b-form-input>                                
                        </td>
                        <td>
                            <b-button form="apn_form" type="submit" class="ml-sm-2" variant="info">
                                <b-icon icon="search"></b-icon> Buscar
                            </b-button>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="ap_materno">Ap. Materno</label>
                        </th>
                        <td>
                            <b-form-input
                                id="ap_materno"
                                v-model="ap_materno"                               
                                class="mb-2 mr-sm-2 mb-sm-0"                                    
                            ></b-form-input>                                
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="nombres">Nombres</label>
                        </th>
                        <td>
                            <b-form-input
                                id="nombres"
                                v-model="nombres"                               
                                class="mb-2 mr-sm-2 mb-sm-0"                                    
                            ></b-form-input>                                
                        </td>
                        <td></td>
                    </tr>                    
                </table>                                                                            
            </div>             
        </template>
        <template v-else-if="showEscuelas">
            <escuelas :alumno="alumno" :matriculas="matriculas"></escuelas>
        </template>
        <template v-else-if="showAlumnos">
            <alumnos :alumnos="alumnos"></alumnos>
        </template>
    </div>    
</template>
<script>
const axios = require("axios");
import Escuelas from "./Escuelas";
import Alumnos from "./Alumnos";

export default {
    name: "comprobantes.tab-alumnos",    
    components: {        
        Escuelas,
        Alumnos
    },
    data() {
        return {
            app_url: this.$root.app_url,
            cui: '',
            ap_paterno: '',
            ap_materno: '',
            nombres: '',
            alumno: {},
            matriculas: [],
            alumnos: [],
            showEscuelas: false,
            showAlumnos: false
        };
    },
    methods: {                
        async buscarCuiAlumno() {
            try {
                const response = await axios.get(`${this.app_url}/buscarCuiAlumno/${this.cui}`)
                this.alumno = response.data.alumno
                this.matriculas = response.data.matriculas

                if (this.matriculas.length == 1) {
                    this.mostrarComprobante(this.matriculas[0])                    
                }
                else {
                    this.showEscuelas = true
                    this.showAlumnos = false
                }
            } catch (error) {
                console.log(error)
            }      
        },
        async buscarApnAlumno() {            
            try {
                const response = await axios.get(`${this.app_url}/buscarApnAlumno`, { 
                                        params: { 
                                            ap_paterno: this.ap_paterno,
                                            ap_materno: this.ap_materno,
                                            nombres: this.nombres,
                                        }
                                })
                
                this.alumnos = response.data
                this.showAlumnos = true
                this.showEscuelas = false

                /*if (this.alumnos.length == 1) {
                    //this.mostrarComprobante(this.matriculas[0])                    
                }
                else {
                    this.showAlumnos = true
                }*/
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(matricula) {       
            this.$inertia.get(route('comprobantes.crear'), {
                'tipo_usuario' : 'alumno',
                'alumno' : this.alumno,
                'matricula': matricula
            })
        },        
        
    }
};
</script>