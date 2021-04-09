<template>
    <div class="vertical-menu">
        <!-- LOGO
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
        </div> -->

        <b-navbar variant="faded" type="light">
            <b-navbar-brand :href="route('dashboard')">
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
            </b-navbar-brand>
        </b-navbar>

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

                    <li
                        v-if="
                            $permissions.can([
                                { name: 'Listar Roles' },
                                { name: 'Listar Usuarios' }
                            ])
                        "
                    >
                        <a
                            href="javascript: void(0);"
                            class="has-arrow waves-effect"
                            @click="mostrarMenu(0)"
                            :class="{
                                'mm-active':
                                    path == 'roles' || path == 'usuarios'
                            }"
                        >
                            <i class="uil-window-section"></i>
                            <span>Accesos</span>
                        </a>
                        <ul
                            class="sub-menu"
                            aria-expanded="false"
                            v-show="show_menus[0]"
                        >
                            <li :class="{ 'mm-active': path == 'roles' }">
                                <inertia-link
                                    :href="route('roles.iniciar')"
                                    :class="{ active: path == 'roles' }"
                                    v-if="
                                        $permissions.can([
                                            { name: 'Listar Roles' }
                                        ])
                                    "
                                >
                                    Roles
                                </inertia-link>
                            </li>
                            <li :class="{ 'mm-active': path == 'usuarios' }">
                                <inertia-link
                                    :href="route('usuarios.iniciar')"
                                    :class="{ active: path == 'usuarios' }"
                                    v-if="
                                        $permissions.can([
                                            { name: 'Listar Usuarios' }
                                        ])
                                    "
                                >
                                    Usuarios
                                </inertia-link>
                            </li>
                        </ul>
                    </li>

                    <li
                        v-if="
                            $permissions.can([
                                { name: 'Listar Unidades-Medida' },
                                { name: 'Listar Clasificadores' },
                                { name: 'Listar Tipos-Concepto' },
                                { name: 'Listar Conceptos' },
                                { name: 'Listar Tipos-Comprobante' }
                            ])
                        "
                    >
                        <a
                            href="javascript: void(0);"
                            class="has-arrow waves-effect"
                            @click="mostrarMenu(1)"
                            :class="{
                                'mm-active':
                                    path == 'unidades-medida' ||
                                    path == 'clasificadores' ||
                                    path == 'tipos-concepto' ||
                                    path == 'conceptos' ||
                                    path == 'tipo-comprobante' ||
                                    path == 'comprobantes'
                            }"
                        >
                            <i class="uil-window-section"></i>
                            <span>Mantenimiento</span>
                        </a>
                        <ul
                            class="sub-menu"
                            aria-expanded="false"
                            v-show="show_menus[1]"
                        >
                            <li
                                :class="{
                                    'mm-active': path == 'unidades-medida'
                                }"
                            >
                                <inertia-link
                                    :href="route('unidades-medida.iniciar')"
                                    :class="{
                                        active: path == 'unidades-medida'
                                    }"
                                    v-if="
                                        $permissions.can([
                                            { name: 'Listar Unidades-Medida' }
                                        ])
                                    "
                                >
                                    Unidades de medida
                                </inertia-link>
                            </li>
                            <li
                                :class="{
                                    'mm-active': path == 'clasificadores'
                                }"
                            >
                                <inertia-link
                                    :href="route('clasificadores.iniciar')"
                                    :class="{
                                        active: path == 'clasificadores'
                                    }"
                                    v-if="
                                        $permissions.can([
                                            { name: 'Listar Clasificadores' }
                                        ])
                                    "
                                >
                                    Clasificadores
                                </inertia-link>
                            </li>
                            <li
                                :class="{
                                    'mm-active': path == 'tipos-concepto'
                                }"
                            >
                                <inertia-link
                                    :href="route('tipos-concepto.iniciar')"
                                    :class="{
                                        active: path == 'tipos-concepto'
                                    }"
                                    v-if="
                                        $permissions.can([
                                            { name: 'Listar Tipos-Concepto' }
                                        ])
                                    "
                                >
                                    Tipos de concepto
                                </inertia-link>
                            </li>
                            <li :class="{ 'mm-active': path == 'conceptos' }">
                                <inertia-link
                                    :href="route('conceptos.iniciar')"
                                    :class="{ active: path == 'conceptos' }"
                                    v-if="
                                        $permissions.can([
                                            { name: 'Listar Conceptos' }
                                        ])
                                    "
                                >
                                    Conceptos
                                </inertia-link>
                            </li>
                            <li
                                :class="{
                                    'mm-active': path == 'tipo-comprobante'
                                }"
                            >
                                <inertia-link
                                    :href="route('tipo-comprobante.iniciar')"
                                    :class="{
                                        active: path == 'tipo-comprobante'
                                    }"
                                    v-if="
                                        $permissions.can([
                                            { name: 'Listar Tipos-Comprobante' }
                                        ])
                                    "
                                >
                                    Tipos de comprobante
                                </inertia-link>
                            </li>
                            <!--<li>
                                <inertia-link
                                    :href="route('accesos-google.iniciar')"
                                >
                                    Accesos Google
                                </inertia-link>
                            </li>-->
                            <li
                                :class="{ 'mm-active': path == 'comprobantes' }"
                            >
                                <inertia-link
                                    :href="route('comprobantes.iniciar')"
                                    :class="{ active: path == 'comprobantes' }"
                                >
                                    Registrar comprobante
                                </inertia-link>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a
                            href="javascript: void(0);"
                            class="has-arrow waves-effect"
                            @click="mostrarMenu(2)"
                            :class="{
                                'mm-active':
                                    path == 'dashboard sunat' ||
                                    path == 'facturas' ||
                                    path == 'boletas'
                            }"
                        >
                            <i class="uil-upload"></i>
                            <span>Sunat</span>
                        </a>
                        <ul
                            class="sub-menu"
                            aria-expanded="false"
                            v-show="show_menus[2]"
                        >
                            <li
                                :class="{
                                    'mm-active': path == 'dashboard sunat'
                                }"
                            >
                                <inertia-link
                                    :href="route('sunat.tablero')"
                                    :class="{
                                        active: path == 'dashboard sunat'
                                    }"
                                >
                                    Dashboard Sunat
                                </inertia-link>
                            </li>
                            <li
                                :class="{
                                    'mm-active': path == 'facturas'
                                }"
                            >
                                <inertia-link
                                    :href="route('sunat.iniciarFacturas')"
                                    :class="{
                                        active: path == 'facturas'
                                    }"
                                >
                                    Facturas
                                </inertia-link>
                            </li>
                            <li
                                :class="{
                                    'mm-active': path == 'boletas'
                                }"
                            >
                                <inertia-link
                                    :href="route('sunat.iniciarBoletas')"
                                    :class="{
                                        active: path == 'boletas'
                                    }"
                                >
                                    Boletas
                                </inertia-link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a
                            href="javascript: void(0);"
                            class="has-arrow waves-effect"
                            @click="mostrarMenu(3)"
                            :class="{ 'mm-active': path == 'reportes' }"
                        >
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Reportes</span>
                        </a>
                        <ul
                            class="sub-menu"
                            aria-expanded="false"
                            v-show="show_menus[3]"
                        >
                            <li>
                                <inertia-link 
                                :href="route('comprobantes.reporte')"
                                :class="{ 'mm-active': path == 'reportes' }">
                                    Reporte de ventas
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link 
                                href="#"
                                :class="{ 'mm-active': path == 'reportes' }">
                                    Otro reporte
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
            show_menus: this.$store.getters.getEstadosMenu
        };
    },
    computed: {
        path() {
            return window.location.pathname.split("/")[1];
        }
    },
    methods: {
        mostrarMenu(orden) {
            this.$set(this.show_menus, orden, !this.show_menus[orden]);

            for (let index = 0; index < this.show_menus.length; index++) {
                if (index == orden) {
                    continue;
                }

                this.$set(this.show_menus, index, false);
            }

            this.$store.dispatch("setEstadoMenu", this.show_menus);

        }
    }
};
</script>
