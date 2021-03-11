<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Obtener código bancario
            </h2>
        </template>
        <!-- Alert Success  -->
        <div class="flex justify-between text-green-200 shadow-inner rounded p-3 bg-green-600" v-if="successMessage">
          <p class="self-center">{{ successMessage }}</p>
          <strong class="text-xl align-center cursor-pointer" @click="successMessage=''">&times;</strong>
        </div>
        <!-- Alert Info  -->
        <div class="flex justify-between text-blue-200 shadow-inner rounded p-3 bg-blue-600" v-if="infoMessage">
          <p class="self-center">{{ infoMessage }}</p>
          <strong class="text-xl align-center cursor-pointer" @click="infoMessage=''">&times;</strong>
        </div>
        <!-- Alert Warning  -->
        <div class="flex justify-between text-yellow-200 shadow-inner rounded p-3 bg-yellow-600" v-if="warningMessage">
          <p class="self-center">{{ warningMessage }}</p>
          <strong class="text-xl align-center cursor-pointer" @click="warningMessage=''">&times;</strong>
        </div>
        <!-- Alert Danger  -->
        <div class="flex justify-between text-red-200 shadow-inner rounded p-3 bg-red-600" v-if="errorMessage">
          <p class="self-center">{{ errorMessage }}</p>
          <strong class="text-xl align-center cursor-pointer" @click="errorMessage=''">&times;</strong>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">                    
                    <form @submit.prevent="showModal = true">
                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-12 gap-12">                                    
                                    <div class="lg:col-span-2 sm:col-span-3">
                                        <label for="dni" class="block text-sm font-bold text-gray-700">C.U.I.</label>
                                        <input type="text" :value="$page.props.user.cui" readonly id="dni" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md opacity-70">
                                    </div>
                                    <div class="lg:col-span-2 sm:col-span-3">
                                        <label for="dni" class="block text-sm font-bold text-gray-700">D.N.I.</label>
                                        <input type="text" :value="alumno.dni" readonly id="dni" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md opacity-70">
                                    </div>
                                    <div class="lg:col-span-4 sm:col-span-3">
                                        <label for="nombres" class="block text-sm font-bold text-gray-700">Nombres</label>
                                        <input type="text" :value="alumno.nombres" readonly id="nombres" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md opacity-70">
                                    </div>
                                    <div class="lg:col-span-4 sm:col-span-3">
                                        <label for="apellidos" class="block text-sm font-bold text-gray-700">Apellidos</label>
                                        <input type="text" :value="alumno.apellidos" readonly id="apellidos" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md opacity-70">
                                    </div>

                                    <div class="lg:col-span-6 sm:col-span-12">
                                        <label for="escuela" class="block text-sm font-bold text-gray-700">Escuela</label>
                                        <select id="escuela" v-model="matricula" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="" disabled selected="selected">-- Por favor seleccione una escuela --</option>
                                            <option 
                                                v-for="(matricula, key) in matriculas" 
                                                :key="key" 
                                                :value="matricula"
                                                v-text="matricula.escuela.nesc"
                                            />                                                                                                     
                                        </select>
                                    </div>                                    

                                    <div class="lg:col-span-6 sm:col-span-12">
                                        <label for="tipo_pago" class="block text-sm font-bold text-gray-700">Tipo de pago</label>
                                        <select id="tipo_pago" v-model="tipo_pago" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="" disabled selected="selected">-- Por favor seleccione un tipo de pago --</option>
                                            <option 
                                                v-for="(tipo_pago, key) in tipos_pago" 
                                                :key="key" 
                                                :value="tipo_pago"
                                                v-text="tipo_pago.nombre"
                                            />                                                                                                                          
                                        </select>
                                    </div>                                   
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Obtener código
                                </button>                                
                            </div>
                        </div>
                    </form>            
                </div>
            </div>
        </div>

        <!-- This example requires Tailwind CSS v2.0+ -->
<div class="fixed z-10 inset-0 overflow-y-auto" v-show="showModal">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <!--
      Background overlay, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <!-- This element is to trick the browser into centering the modal contents. -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <!--
      Modal panel, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        To: "opacity-100 translate-y-0 sm:scale-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100 translate-y-0 sm:scale-100"
        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    -->
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
            <!-- Heroicon name: outline/exclamation -->            
            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-headline">
              Obtener Código Bancario
            </h3>
            <div class="mt-2">
              <p v-if="matricula !== ''">
                <span class="font-semibold">Escuela:</span> {{ matricula.escuela.nesc }}
              </p>
              <p v-if="tipo_pago !== ''">
                <span class="font-semibold">Tipo de pago:</span> {{ tipo_pago.nombre }}
              </p>
              <p class="text-sm text-gray-500 mt-3">
                ¿Esta seguro de obtener el código bancario para la escuela y tipo de pago seleccionado?                
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="button" @click="generarCodigo" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
          SI
        </button>
        <button type="button" @click="showModal = false"  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
          NO
        </button>
      </div>
    </div>
  </div>
</div>
    </app-layout>

</template>

<script> 
    const axios = require('axios');
    import AppLayout from '@/Layouts/AppLayout'      

    export default {
        props: ['alumno', 'matriculas'],
        components: {
            AppLayout,                        
        },
        data() {
            return {
                api_url: this.$root.api_url,                                
                matricula: '',                     
                tipo_pago: '',
                tipos_pago: [                    
                    { codigo: 'MM', nombre: 'Modificación de matrícula' },
                    { codigo: 'ME', nombre: 'Matrícula por excepción' },                    
                ],
                showModal: false,                
                successMessage: '',
                infoMessage: '',
                warningMessage: '',
                errorMessage: '',
                errors: []
            }
        },              
        methods: {          
          async generarCodigo() 
          {
            try {
              
              const response = await axios.post(`${this.api_url}/banco`, 
                                    {
                                      'alumno': this.alumno,
                                      'matricula': this.matricula,
                                      'tipo_pago': this.tipo_pago
                                    }
                                );
              this.showModal = false              

              if (!response.data.error) {                     
                let codigo = response.data.codigo
                window.open(`${this.api_url}/pdf/${codigo}/${this.matricula.nues}`, '_self');
                this.successMessage = response.data.successMessage
              }
              else {
                if (response.data.warningMessage) {
                  this.warningMessage = response.data.warningMessage                
                }
                else if (response.data.errorMessage) {
                  this.errorMessage = response.data.errorMessage                
                }                
              }
              
              this.matricula = ''                     
              this.tipo_pago = ''

            } catch (error) {
              //console.error(error);
            }
          }
        }
    }
</script>
