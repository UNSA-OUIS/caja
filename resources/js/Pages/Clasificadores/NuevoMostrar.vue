<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">                
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link></li>                    
                    <li class="breadcrumb-item"><inertia-link :href="route('clasificadores.iniciar')">Lista de clasificadores</inertia-link></li>
                    <li class="breadcrumb-item active">{{ accion }} clasificador</li>
                </ol>              
            </div>
            <div class="card-body">                
                <b-form>
                    <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
                        <b-form-input
                            id="input-1"
                            v-model="clasificador.nombre"
                            placeholder="Nombre de clasificador"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <div v-if="$page.props.errors.nombre" class="text-danger">
                            {{ $page.props.errors.nombre[0] }}
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
        name: "clasificadores.mostrar",
        props: ["clasificador"],
        components: {
            AppLayout,                      
        },
        data() {
            return {                     
                accion: '',
            };
        },       
        created() {
            if (!this.clasificador.id) {
                this.accion = 'Crear'
            }
            else {
                this.accion = 'Mostrar'
            }            
        },
        methods: {            
            registrar() {
                this.$inertia.post(route('clasificadores.registrar'), this.clasificador)
            },       
            actualizar() {
                this.$inertia.post(route('clasificadores.actualizar', [this.clasificador.id]), this.clasificador)
            },     
        },
    }
</script>
