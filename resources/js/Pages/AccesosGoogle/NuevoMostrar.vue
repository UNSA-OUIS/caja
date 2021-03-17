<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">                
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link></li>                    
                    <li class="breadcrumb-item"><inertia-link :href="route('accesos-google.iniciar')">Lista de Accesos Google</inertia-link></li>
                    <li class="breadcrumb-item active">{{ accion }} acceso</li>
                </ol>              
            </div>
            <div class="card-body">                
                <b-form>
                    <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
                        <b-form-input
                            id="input-1"
                            v-model="accesoGoogle.nombre"
                            placeholder="Nombre"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <div v-if="$page.props.errors.nombre" class="text-danger">
                            {{ $page.props.errors.nombre[0] }}
                        </div>
                    </b-form-group> 
                    <b-form-group id="input-group-4" label="Correo:" label-for="input-4">
                        <b-form-input
                            id="input-4"
                            v-model="accesoGoogle.correo"
                            placeholder="Correo"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <div v-if="$page.props.errors.correo" class="text-danger">
                            {{ $page.props.errors.correo[0] }}
                        </div> 
                    </b-form-group>    
                    <b-form-group id="input-group-5" label="Cargo:" label-for="input-5">
                        <b-form-input
                            id="input-5"
                            v-model="accesoGoogle.cargo"
                            placeholder="Cargo"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <div v-if="$page.props.errors.cargo" class="text-danger">
                            {{ $page.props.errors.cargo[0] }}
                        </div> 
                    </b-form-group>                 
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
        name: "accesos-google.mostrar",
        props: ["accesoGoogle"],
        components: {
            AppLayout,                      
        },
        data() {
            return {                     
                accion: '',
            };
        },       
        created() {
            if (!this.accesoGoogle.id) {
                this.accion = 'Crear'
            }
            else {
                this.accion = 'Mostrar'
            }            
        },
        methods: {            
            registrar() {
                this.$inertia.post(route('accesos-google.registrar'), this.accesoGoogle)
            },       
            actualizar() {
                this.$inertia.post(route('accesos-google.actualizar', [this.accesoGoogle.id]), this.accesoGoogle)                
            },   
        },
    }
</script>
