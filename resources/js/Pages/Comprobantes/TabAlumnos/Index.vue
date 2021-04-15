<template>
    <div>
        <template v-if="!showEscuelas">
            <div class="d-flex justify-content-center mb-3">    
                <form @submit.prevent="buscarCuiAlumno">                                                                        
                    <table>
                        <caption class="mb-3" style="caption-side: top;">BÚSQUEDA POR ALUMNO</caption>
                        <tr>
                            <th class="text-right">
                                <label class="mr-sm-2" for="cui">CUI</label>
                            </th>
                            <td>
                                <b-form-input
                                    id="cui"
                                    v-model="cui"
                                    class="mb-2 mr-sm-2 mb-sm-0"   
                                    required                                         
                                ></b-form-input>                                    
                            </td>
                            <td>
                                <b-button type="submit" class="ml-sm-2" variant="primary">Buscar</b-button>
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
                                    class="mb-2 mr-sm-2 mb-sm-0"                                    
                                ></b-form-input>                                
                            </td>
                            <td>
                                <b-button class="ml-sm-2" variant="primary">Buscar</b-button>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right">
                                <label class="mr-sm-2" for="ap_materno">Ap. Materno</label>
                            </th>
                            <td>
                                <b-form-input
                                    id="ap_materno"
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
                                    class="mb-2 mr-sm-2 mb-sm-0"                                    
                                ></b-form-input>                                
                            </td>
                            <td></td>
                        </tr>
                    </table>                       
                </form>                
            </div>     
        </template>
        <template v-else>
            <escuelas :alumno="alumno" :matriculas="matriculas"></escuelas>
        </template>
    </div>    
</template>
<script>
const axios = require("axios");
import Escuelas from "./Escuelas";

export default {
    name: "comprobantes.tab-alumnos",    
    components: {        
        Escuelas
    },
    data() {
        return {
            app_url: this.$root.app_url,
            cui: '',
            alumno: {},
            matriculas: [],
            showEscuelas: false
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
                }
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(matricula) {       
            this.$inertia.post(route('comprobantes.crear'), {
                'alumno' : this.alumno,
                'matricula': matricula
            })
        },        
        
    }
};
</script>