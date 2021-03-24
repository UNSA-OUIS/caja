<template>
    <div>
        <div class="home-btn d-none d-sm-block">
            <a href="#" class="text-dark"><i class="mdi mdi-home-variant h2"></i></a>
        </div>
        <div class="account-pages my-4 pt-sm-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <a href="#" class="mb-4 d-block auth-logo">
                                <img src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/siscaja/siscaja_blanco.png" alt="" height="85" class="logo logo-dark">
                                <img src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/siscaja/siscaja_blanco.png" alt="" height="85" class="logo logo-light">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">   
                            <div class="card-body p-3"> 
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Bienvenid@ Nuevamente !</h5>
                                    <p class="text-muted">Inicie sesión para ingresar a SISTEMA DE CAJA.</p>
                                </div>
                                <div class="p-2 mt-3">
                                    
                                    <jet-validation-errors class="mb-4" />

                                    <form @submit.prevent="submit">

                                        <div class="form-group">
                                            <label for="email">Nombre de usuario</label>
                                            <input type="email" v-model="form.email" class="form-control" id="email" placeholder="Ingrese su correo electrónico">
                                        </div>

                                        <div class="form-group">
                                            <div class="float-right">
                                                <a v-if="canResetPassword" href="#" class="text-muted">¿Se te olvidó tu contraseña?</a>
                                            </div>
                                            <label for="userpassword">Contraseña</label>
                                            <input type="password" v-model="form.password" class="form-control" id="userpassword" placeholder="Ingrese su contraseña">
                                        </div>

                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" v-model="form.remember" class="custom-control-input" id="auth-remember-check">
                                            <label class="custom-control-label" for="auth-remember-check">Recuérdame</label>
                                        </div>
                                        
                                        <div class="mt-3 text-right">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Iniciar sesión</button>
                                        </div>
                                       

                                        <div class="mt-4 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="font-size-14 mb-3 title login_redes_sociales p-1">Iniciar sesión con</h5>
                                            </div>
                                            

                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a :href="`${app_url}/google`" class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <p class="mb-0">¿No tienes una cuenta? <a href="#" class="font-weight-medium text-primary"> Regístrate ahora </a> </p>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <p>SISTEMA DE CAJA © 2021. Elaborado con <i class="mdi mdi-heart text-danger"></i> por OUIS-UNSA</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
    </div>
</template>
<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                app_url: this.$root.app_url,
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        created() {
            let sitebody = document.body;
            sitebody.classList.add("authentication-bg")
        },  

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        }
    }
</script>
<style scoped>
    .login_redes_sociales {
        color: #a6b0cf
    }
</style>