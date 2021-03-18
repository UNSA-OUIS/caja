<template>
    <div class="vertical-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a :href="route('dashboard')" class="mb-4 d-block auth-logo">
                <img
                    src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/siscaja/siscaja_blanco.png"
                    alt=""
                    height="50"
                    class="logo logo-dark"
                />
                <img
                    src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/siscaja/siscaja_blanco.png"
                    alt=""
                    height="50"
                    class="logo logo-light"
                />
            </a>
        </div>

        <button
            type="button"
            class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn"
        >
            <i class="fa fa-fw fa-bars"></i>
        </button>

        <div data-simplebar class="sidebar-menu-scroll">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Menu</li>
                    <li>
                        <a href="#">
                            <i class="uil-home-alt"></i
                            ><span
                                class="badge badge-pill badge-primary float-right"
                                >01</span
                            >
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="javascript: void(0);"
                            class="has-arrow waves-effect"
                            @click="mostrarMenuAcceso"
                        >
                            <i class="uil-window-section"></i>
                            <span>Accesos</span>
                        </a>
                        <ul
                            class="sub-menu"
                            aria-expanded="false"
                            v-show="menuAcceso"
                        >
                            <li>
                                <inertia-link                                
                                    :href="route('roles.iniciar')"
                                    v-if="$permissions.can([{name: 'Listar Roles'}])"
                                >
                                    Roles
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('usuarios.iniciar')"
                                    v-if="$permissions.can([{name: 'Listar Usuarios'}])"
                                >
                                    Usuarios
                                </inertia-link>
                            </li>                                          
                        </ul>
                    </li>

                    <li>
                        <a
                            href="javascript: void(0);"
                            class="has-arrow waves-effect"
                            @click="mostrarMenuMantenimiento"
                        >
                            <i class="uil-window-section"></i>
                            <span>Mantenimiento</span>
                        </a>
                        <ul
                            class="sub-menu"
                            aria-expanded="false"
                            v-show="menuMantenimiento"
                        >
                            <li>
                                <inertia-link
                                    :href="route('unidades-medida.iniciar')"
                                    v-if="$permissions.can([{name: 'Listar Unidades-Medida'}])"
                                >
                                    Unidades de medida
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('clasificadores.iniciar')"
                                    v-if="$permissions.can([{name: 'Listar Clasificadores'}])"
                                >
                                    Clasificadores
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link 
                                    :href="route('tipos-concepto.iniciar')"
                                    v-if="$permissions.can([{name: 'Listar Tipos-Concepto'}])"
                                >
                                    Tipos de concepto
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link 
                                    :href="route('conceptos.iniciar')"
                                    v-if="$permissions.can([{name: 'Listar Conceptos'}])"
                                >
                                    Conceptos
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('tipo-comprobante.iniciar')"
                                    v-if="$permissions.can([{name: 'Listar Tipos-Comprobante'}])"                                                                    
                                >
                                    Tipos de comprobante
                                </inertia-link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a
                            href="javascript: void(0);"
                            class="has-arrow waves-effect"
                            @click="mostrarMenuSunat"
                        >
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Sunat</span>
                        </a>
                        <ul
                            class="sub-menu"
                            aria-expanded="false"
                            v-show="menuSunat"
                        >
                            <li>
                                <inertia-link :href="route('sunat.dashboard')">
                                    Dashboard
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('sunat.listarFacturas')"
                                >
                                    Enviar Facturas
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('sunat.resumenBoletas')"
                                >
                                    Enviar Resumen Boletas
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('sunat.comunicacionBaja')"
                                >
                                    Enviar Comunicacion Baja
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link :href="route('sunat.ticketCDR')">
                                    Envio Tickets CDR
                                </inertia-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            app_url: this.$root.app_url,
            menuAcceso: false,
            menuMantenimiento: false,
            menuSunat: false
        };
    },
    methods: {
        mostrarMenuAcceso() {
            this.menuAcceso = !this.menuAcceso;
        },
        mostrarMenuMantenimiento() {
            this.menuMantenimiento = !this.menuMantenimiento;
        },
        mostrarMenuSunat() {
            this.menuSunat = !this.menuSunat;
        }
    }
};
</script>
