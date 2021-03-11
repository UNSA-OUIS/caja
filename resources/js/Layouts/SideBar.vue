<template>
    <div class="vertical-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="#" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="assets/images/logo-sm.png" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="assets/images/logo-dark.png" alt="" height="20">
                </span>
            </a>

            <a href="#" class="logo logo-light">
                <span class="logo-sm">
                    <img src="assets/images/logo-sm.png" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="assets/images/logo-light.png" alt="" height="20">
                </span>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
            <i class="fa fa-fw fa-bars"></i>
        </button>

        <div data-simplebar class="sidebar-menu-scroll">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Menu</li>
                    <li>
                        <a href="#">
                            <i class="uil-home-alt"></i><span class="badge badge-pill badge-primary float-right">01</span>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect" @click="mostrarMenuMantenimiento">
                            <i class="uil-window-section"></i>
                            <span>Mantenimiento</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false" v-show="menuMantenimiento">
                            <li>                                
                                <inertia-link :href="route('unidades-medida.iniciar')">
                                    Unidades de medida
                                </inertia-link>                                
                            </li>
                            <li><a href="#">Clasificadores</a></li>
                            <li><a href="#">Tipos de concepto</a></li>
                            <li><a href="#">Conceptos</a></li>                            
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
                menuMantenimiento: false,
            }
        },
        methods: {
            mostrarMenuMantenimiento() {                
                this.menuMantenimiento = !this.menuMantenimiento
            }
        }
    }
</script>
