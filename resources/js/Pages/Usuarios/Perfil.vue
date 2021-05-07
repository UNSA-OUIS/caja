<template>
    <app-layout>
        <div class="row mb-4">
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="clearfix"></div>
                            <div>
                                <img :src="$page.props.user.profile_photo_path" alt="" class="avatar-lg rounded-circle img-thumbnail">
                            </div>
                            <h5 class="mt-3 mb-1">{{ $page.props.user.name }}</h5>
                            <p class="text-muted">
                                <span class="d-block" v-for="(rol, key) in $page.props.roles" :key="key">{{ rol }}</span>
                            </p>
                        </div>

                        <hr class="my-4">

                        <div class="text-muted">
                            <div class="table-responsive mt-4">
                                <div class="mt-4">
                                    <p class="mb-1">Código cajero :</p>
                                    <h5 class="font-size-16">{{usuario.persona.codigo}}</h5>
                                </div>
                                <div class="mt-4">
                                    <p class="mb-1">Nombre cajero :</p>
                                    <h5 class="font-size-16">{{usuario.persona.nombre}}</h5>
                                </div>
                                <div class="mt-4">
                                    <p class="mb-1">Celular :</p>
                                    <h5 class="font-size-16">{{usuario.persona.celular}}</h5>
                                </div>
                                <div class="mt-4">
                                    <p class="mb-1">Correo institucional:</p>
                                    <h5 class="font-size-16">{{usuario.email}}</h5>
                                </div>
                                <div class="mt-4">
                                    <p class="mb-1">Correo personal:</p>
                                    <h5 class="font-size-16">{{usuario.persona.email_personal}}</h5>
                                </div>
                                <div class="mt-4">
                                    <p class="mb-1">Dirección :</p>
                                    <h5 class="font-size-16">{{usuario.persona.direccion}}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card mb-0">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#about" role="tab">
                                <i class="uil uil-user-circle font-size-20"></i>
                                <span class="d-none d-sm-block">Editar perfil</span> 
                            </a>
                        </li>
                    </ul>
                    <!-- Tab content -->
                    <div class="tab-content p-4">
                        <div class="tab-pane active" id="about" role="tabpanel">
                            <b-alert
                                show
                                variant="success"
                                v-if="$page.props.successMessage"
                                >{{ $page.props.successMessage }}</b-alert
                            >
                            <b-alert
                                show
                                variant="danger"
                                v-if="$page.props.errorMessage"
                                >{{ $page.props.errorMessage }}</b-alert
                            >
                            <div>
                                <b-form>
                                    <b-row>
                                        <b-col>
                                            <b-form-group
                                                id="input-group-6"
                                                label="Código cajero:"
                                                label-for="input-6"
                                            >
                                                <b-form-input
                                                    id="input-6"
                                                    v-model="editarUsuario.codigo"
                                                    placeholder="Código de cajero"
                                                ></b-form-input>
                                                <div
                                                    v-if="$page.props.errors.codigo"
                                                    class="text-danger"
                                                >
                                                    {{ $page.props.errors.codigo[0] }}
                                                </div>
                                            </b-form-group>
                                        </b-col>
                                        <b-col>
                                            <b-form-group
                                                id="input-group-7"
                                                label="Nombre cajero:"
                                                label-for="input-7"
                                            >
                                                <b-form-input
                                                    id="input-7"
                                                    v-model="editarUsuario.nombre"
                                                    placeholder="Nombre de cajero"
                                                ></b-form-input>
                                                <div
                                                    v-if="$page.props.errors.nombre"
                                                    class="text-danger"
                                                >
                                                    {{ $page.props.errors.nombre[0] }}
                                                </div>
                                            </b-form-group>
                                        </b-col>
                                    </b-row>
                                    <b-row>
                                        <b-col>
                                            <b-form-group
                                                id="input-group-1"
                                                label="Celular:"
                                                label-for="input-1"
                                            >
                                                <b-form-input
                                                    id="input-1"
                                                    v-model="editarUsuario.celular"
                                                    placeholder="Celular"
                                                    type="text"
                                                ></b-form-input>
                                                <div
                                                    v-if="$page.props.errors.celular"
                                                    class="text-danger"
                                                >
                                                    {{ $page.props.errors.celular[0] }}
                                                </div>
                                            </b-form-group>
                                        </b-col>
                                        <b-col>
                                            <b-form-group
                                                id="input-group-2"
                                                label="Correo personal:"
                                                label-for="input-2"
                                            >
                                                <b-form-input
                                                    id="input-2"
                                                    v-model="editarUsuario.email_personal"
                                                    placeholder="Correo eletrónico personal"
                                                    type="email"
                                                ></b-form-input>
                                                <div
                                                    v-if="$page.props.errors.email_personal"
                                                    class="text-danger"
                                                >
                                                    {{ $page.props.errors.email_personal[0] }}
                                                </div>
                                            </b-form-group>
                                        </b-col>                                              
                                    </b-row>
                                    <b-row>
                                        <b-col>
                                            <b-form-group
                                                id="input-group-4"
                                                label="Dirección:"
                                                label-for="input-4"
                                            >
                                                <b-form-input
                                                    id="input-4"
                                                    v-model="editarUsuario.direccion"
                                                    placeholder="Dirección"
                                                    type="text"
                                                ></b-form-input>
                                                <div
                                                    v-if="$page.props.errors.direccion"
                                                    class="text-danger"
                                                >
                                                    {{ $page.props.errors.direccion[0] }}
                                                </div>
                                            </b-form-group>
                                        </b-col> 
                                    </b-row>
                                    <!-- <label for="">Si desea cambiar su contraseña tendrá que iniciar sesión nuevamente cuando los cambios sean guardados.</label>
                                    <b-alert :show="desiguales" variant="danger" dismissible>¡Los campos de la contraseña deben ser iguales!</b-alert>
                                    <b-row>
                                        <b-col>
                                            <b-form-group
                                                id="input-group-3"
                                                label="Nueva contraseña:"
                                                label-for="input-3"
                                            >
                                                <b-form-input
                                                    id="input-3"
                                                    v-model="editarUsuario.password"
                                                    placeholder="Contraseña"
                                                    type="password"
                                                ></b-form-input>
                                                <div
                                                    v-if="$page.props.errors.password"
                                                    class="text-danger"
                                                >
                                                    {{ $page.props.errors.password[0] }}
                                                </div>
                                            </b-form-group>
                                        </b-col>
                                        <b-col>
                                            <b-form-group
                                                id="input-group-5"
                                                label="Repita contraseña:"
                                                label-for="input-5"
                                            >
                                                <b-form-input
                                                    id="input-5"
                                                    v-model="editarUsuario.passwordRepetido"
                                                    placeholder="Contraseña"
                                                    type="password"
                                                ></b-form-input>
                                                <div
                                                    v-if="$page.props.errors.password"
                                                    class="text-danger"
                                                >
                                                    {{ $page.props.errors.password[0] }}
                                                </div>
                                            </b-form-group>
                                        </b-col>                        
                                    </b-row> -->
                                    <b-button @click="actualizar" variant="success">Actualizar</b-button>
                                </b-form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "usuarios.perfil",   
    props: ["usuario"], 
    components: {
        AppLayout
    },
    data() {
        return {
            accion: "",            
            fields: [                
                { key: "categoria", label: "Menú", sortable: true },
                { key: "permisos", label: "Permisos" },                
            ],
            categoria_permisos: [],   
            editarUsuario: {
                id: "",
                celular: "",
                email_personal: "",
                direccion: "",
                codigo: "",
                nombre: "",
                password: "",
                passwordRepetido: "",
            },
            desiguales: false,    
        };
    },
    created() {        
        this.editarUsuario.id = this.usuario.persona.id
        this.editarUsuario.celular = this.usuario.persona.celular
        this.editarUsuario.email_personal = this.usuario.persona.email_personal
        this.editarUsuario.direccion = this.usuario.persona.direccion
        this.editarUsuario.codigo = this.usuario.persona.codigo
        this.editarUsuario.nombre = this.usuario.persona.nombre
    },
    methods: {
        mostrar_permisos() {
            if (this.permissions.length > 0) {                            
                let permisos = []            
                let categoria_anterior = this.permissions[0].name.split(" ").pop()
                let categoria, permiso_id, permiso_nombre

                this.permissions.forEach(permission => {
                    categoria = permission.name.split(" ").pop()
                    permiso_id = permission.id
                    permiso_nombre = permission.name.split(" ").shift()
                    
                    if (categoria !== categoria_anterior) {                                                    
                        this.categoria_permisos.push({
                            'categoria': categoria_anterior,
                            'permisos': permisos
                        })
                        permisos = []
                    }

                    categoria_anterior = categoria
                    permisos.push({
                        'id': permiso_id,
                        'nombre': permiso_nombre
                    })               
                })

                this.categoria_permisos.push({
                    'categoria': categoria_anterior,
                    'permisos': permisos
                })                           
            }            
        },
        registrar() {            
            this.$inertia.post(
                route("usuarios.registrar"),
                this.usuario
            );
        },
        actualizar() {
            if (this.editarUsuario.password == this.editarUsuario.passwordRepetido) {
                this.$bvModal
                    .msgBoxConfirm(
                        "¿Esta seguro de querer editar su usuario?",
                        {
                            title: "Editar usuario",
                            okVariant: "danger",
                            okTitle: "SI",
                            cancelTitle: "NO",
                            centered: true
                        }
                    )
                    .then(value => {
                        if (value) {
                            this.$inertia.post(
                                route("usuarios.actualizarPerfil", [this.usuario.id]),
                                this.editarUsuario
                            );
                        }
                    });
            }
            else{
                this.desiguales = true;
            }
        }
    }
};
</script>
