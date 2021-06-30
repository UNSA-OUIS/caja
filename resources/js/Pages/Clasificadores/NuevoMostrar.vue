<template>
    <app-layout>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ml-auto">
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('clasificadores.iniciar')">
                        Lista de clasificadores
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    {{ accion }} clasificador
                </li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">{{ accion }} clasificador</span>
            </div>
            <div class="card-body">
                <b-form>
                    <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
                        <b-form-input
                            id="input-1"
                            v-model="formData.nombre"
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
        remember: 'formData',
        data() {
            return {
                accion: '',
                formData: this.clasificador
            };
        },
        created() {
            if (!this.formData.id) {
                this.accion = 'Crear'
            }
            else {
                this.accion = 'Mostrar'
            }
        },
        methods: {
            registrar() {
                this.$inertia.post(route('clasificadores.registrar'), this.formData)
            },
            actualizar() {
                this.$inertia.post(route('clasificadores.actualizar', [this.formData.id]), this.formData)
            },
        },
    }
</script>
<style scoped>
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>

