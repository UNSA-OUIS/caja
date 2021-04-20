<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">                
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><inertia-link :href="route('dashboard')">Inicio</inertia-link></li>                    
                    <li class="breadcrumb-item"><inertia-link :href="route('particulares.iniciar')">Lista de particulares</inertia-link></li>
                    <li class="breadcrumb-item active">{{ accion }} particular</li>
                </ol>              
            </div>
            <div class="card-body">                  
                <b-form>
                    <b-row>
                        <b-col cols="6">
                            <b-form-group id="input-group-1" label="DNI:" label-for="input-1">
                                <b-form-input
                                    id="input-1"
                                    v-model="particular.dni"
                                    type="text"
                                    placeholder="Ingrese DNI" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.dni" class="text-danger">
                                    {{ $page.props.errors.dni[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>
                        <b-col cols="6">
                            <b-form-group id="input-group-2" label="Email:" label-for="input-2">
                                <b-form-input
                                    id="input-2"
                                    v-model="particular.email"
                                    type="text"
                                    placeholder="Ingrese correo electrónico" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.email" class="text-danger">
                                    {{ $page.props.errors.email[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>                        
                    </b-row>
                    <b-row>
                        <b-col cols="6">
                            <b-form-group id="input-group-3" label="Nombres:" label-for="input-3">
                                <b-form-input
                                    id="input-3"
                                    v-model="particular.nombres"
                                    type="text"
                                    placeholder="Ingrese Nombres" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.nombres" class="text-danger">
                                    {{ $page.props.errors.nombres[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>
                        <b-col cols="6">
                            <b-form-group id="input-group-4" label="Apellidos:" label-for="input-4">
                                <b-form-input
                                    id="input-4"
                                    v-model="particular.apellidos"
                                    type="text"
                                    placeholder="Ingrese apellidos" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.apellidos" class="text-danger">
                                    {{ $page.props.errors.apellidos[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>                        
                    </b-row>                                 
                    <b-button v-if="accion == 'Crear'" @click="registrar"  variant="success">Registrar</b-button>
                    <b-button v-else-if="accion == 'Mostrar'" @click="accion = 'Editar'" variant="warning">Editar</b-button>
                    <b-button v-else-if="accion == 'Editar'" @click="actualizar" variant="success">Actualizar</b-button>
                </b-form>                                
            </div>
        </div>            
    </app-layout>
</template>

<script>    
    import AppLayout from '@/Layouts/AppLayout'    

    export default {
        name: "particulares.nuevo-mostrar",
        props: ["particular"],
        components: {
            AppLayout,                      
        },
        data() {
            return {                
                accion: '',               
            };
        },       
        created() {                        
            if (!this.particular.id) {               
                this.accion = 'Crear'
            }
            else {                
                this.accion = 'Mostrar'
            }            
        },
        methods: {            
            registrar() {                                         
                this.$inertia.post(route('particulares.registrar'), this.particular)
            },       
            actualizar() {                
                this.$inertia.post(route('particulares.actualizar', [this.particular.id]), this.particular)                
            }            
        },
    }
</script>