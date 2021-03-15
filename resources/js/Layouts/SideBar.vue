<template>
  <div class="vertical-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
      <a href="#" class="logo logo-dark">
        <span class="logo-sm">
          <img
            :src="`${app_url}/assets/images/logo-sm.png`"
            alt=""
            height="22"
          />
        </span>
        <span class="logo-lg">
          <img
            :src="`${app_url}/assets/images/logo-dark.png`"
            alt=""
            height="20"
          />
        </span>
      </a>

      <a href="#" class="logo logo-light">
        <span class="logo-sm">
          <img
            :src="`${app_url}/assets/images/logo-sm.png`"
            alt=""
            height="22"
          />
        </span>
        <span class="logo-lg">
          <img
            :src="`${app_url}/assets/images/logo-light.png`"
            alt=""
            height="20"
          />
        </span>
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
              ><span class="badge badge-pill badge-primary float-right"
                >01</span
              >
              <span>Dashboard</span>
            </a>
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
                <inertia-link :href="route('unidades-medida.iniciar')">
                  Unidades de medida
                </inertia-link>
              </li>              
              <li>
                <inertia-link :href="route('clasificadores.iniciar')">
                  Clasificadores
                </inertia-link>
              </li>
              <li>
                <inertia-link :href="route('tipos-concepto.iniciar')">
                  Tipos de concepto
                </inertia-link>
              </li>
              <li><a href="#">Conceptos</a></li>
              <li>
                <inertia-link :href="route('tipo-comprobante.iniciar')">
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
                <inertia-link :href="route('sunat.enviarFacturas')">
                  Enviar Facturas
                </inertia-link>
              </li>
              <li>
                <inertia-link :href="route('sunat.resumenBoletas')">
                  Enviar Resumen Boletas
                </inertia-link>
              </li>
              <li>
                <inertia-link :href="route('sunat.comunicacionBaja')">
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
      menuMantenimiento: false,
      menuSunat: false
    };
  },
  methods: {
    mostrarMenuMantenimiento() {
      this.menuMantenimiento = !this.menuMantenimiento;
    },
    mostrarMenuSunat() {
      this.menuSunat = !this.menuSunat;
    },
  },
};
</script>
