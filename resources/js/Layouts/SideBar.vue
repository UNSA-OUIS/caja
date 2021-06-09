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
      @click="mostrarOcultarBarraLateral()"
    >
      <i class="fa fa-fw fa-bars"></i>
    </button>
    <!--<button
            v-b-toggle.sidebar-no-header
            type="button"
            class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn"
        >
            <i class="fa fa-fw fa-bars"></i>
        </button>
        <b-sidebar id="sidebar-no-header" aria-labelledby="sidebar-no-header-title" no-header shadow class="bg-dark">
        <template #default="{ hide }">
        <div class="p-3 bg-dark">-->

    <div data-simplebar style="width:100%; height:100%; overflow: scroll;">
      <div id="sidebar-menu">
        <ul class="metismenu list-unstyled" id="side-menu">
          <li class="menu-title">Menu</li>
          <li :class="{ 'mm-active': path == 'dashboard' }">
            <inertia-link
              :class="{ active: path == 'dashboard' }"
              :href="route('dashboard')"
              v-if="$permissions.can([{ name: 'Mostrar Dashboard' }])"
            >
              <i class="uil-home-alt"></i>
              <span class="badge badge-pill badge-primary float-right">01</span>
              <span>Dashboard</span>
            </inertia-link>
          </li>
          <li
            v-if="
              $permissions.can([
                { name: 'Listar Roles' },
                { name: 'Listar Usuarios' },
              ])
            "
          >
            <a
              href="javascript: void(0);"
              class="has-arrow waves-effect"
              @click="mostrarMenu(0)"
              :class="{
                'mm-active': path == 'roles' || path == 'usuarios',
              }"
            >
              <i class="uil-window-section"></i>
              <span>Accesos</span>
            </a>
            <ul class="sub-menu" aria-expanded="false" v-show="show_menus[0]">
              <li :class="{ 'mm-active': path == 'roles' }">
                <inertia-link
                  :href="route('roles.iniciar')"
                  :class="{ active: path == 'roles' }"
                  v-if="$permissions.can([{ name: 'Listar Roles' }])"
                >
                  Roles
                </inertia-link>
              </li>
              <li :class="{ 'mm-active': path == 'usuarios' }">
                <inertia-link
                  :href="route('usuarios.iniciar')"
                  :class="{ active: path == 'usuarios' }"
                  v-if="$permissions.can([{ name: 'Listar Usuarios' }])"
                >
                  Usuarios
                </inertia-link>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $permissions.can([
                { name: 'Listar Puntos-Venta' },
                { name: 'Listar Números-Comprobante' },
              ])
            "
          >
            <a
              href="javascript: void(0);"
              class="has-arrow waves-effect"
              @click="mostrarMenu(1)"
              :class="{
                'mm-active':
                  path == 'puntos-venta' || path == 'numeros-operacion',
              }"
            >
              <i class="uil-window-section"></i>
              <span>Configuración</span>
            </a>
            <ul class="sub-menu" aria-expanded="false" v-show="show_menus[1]">
              <li :class="{ 'mm-active': path == 'puntos-venta' }">
                <inertia-link
                  :href="route('puntosVenta.iniciar')"
                  :class="{ active: path == 'puntos-venta' }"
                  v-if="$permissions.can([{ name: 'Listar Puntos-Venta' }])"
                >
                  Puntos de venta
                </inertia-link>
              </li>
              <li :class="{ 'mm-active': path == 'numeros-operacion' }">
                <inertia-link
                  :href="route('numerosOperacion.iniciar')"
                  :class="{ active: path == 'numeros-operacion' }"
                  v-if="
                    $permissions.can([{ name: 'Listar Números-Comprobante' }])
                  "
                >
                  Números de comprobante
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
                { name: 'Listar Tipos-Comprobante' },
                { name: 'Listar Cuentas-Corrientes' },
                { name: 'Listar Particulares' },
                { name: 'Listar Empresas' },
              ])
            "
          >
            <a
              href="javascript: void(0);"
              class="has-arrow waves-effect"
              @click="mostrarMenu(2)"
              :class="{
                'mm-active':
                  path == 'unidades-medida' ||
                  path == 'clasificadores' ||
                  path == 'tipos-concepto' ||
                  path == 'conceptos' ||
                  path == 'particulares' ||
                  path == 'empresas' ||
                  path == 'cuentas-corrientes',
                //path == 'asignar',
              }"
            >
              <i class="uil-window-section"></i>
              <span>Mantenimiento</span>
            </a>
            <ul class="sub-menu" aria-expanded="false" v-show="show_menus[2]">
              <li
                :class="{
                  'mm-active': path == 'unidades-medida',
                }"
              >
                <inertia-link
                  :href="route('unidades-medida.iniciar')"
                  :class="{
                    active: path == 'unidades-medida',
                  }"
                  v-if="$permissions.can([{ name: 'Listar Unidades-Medida' }])"
                >
                  Unidades de medida
                </inertia-link>
              </li>
              <li
                :class="{
                  'mm-active': path == 'clasificadores',
                }"
              >
                <inertia-link
                  :href="route('clasificadores.iniciar')"
                  :class="{
                    active: path == 'clasificadores',
                  }"
                  v-if="$permissions.can([{ name: 'Listar Clasificadores' }])"
                >
                  Clasificadores
                </inertia-link>
              </li>
              <li
                :class="{
                  'mm-active': path == 'tipos-concepto',
                }"
              >
                <inertia-link
                  :href="route('tipos-concepto.iniciar')"
                  :class="{
                    active: path == 'tipos-concepto',
                  }"
                  v-if="$permissions.can([{ name: 'Listar Tipos-Concepto' }])"
                >
                  Tipos de concepto
                </inertia-link>
              </li>
              <li :class="{ 'mm-active': path == 'conceptos' }">
                <inertia-link
                  :href="route('conceptos.iniciar')"
                  :class="{ active: path == 'conceptos' }"
                  v-if="$permissions.can([{ name: 'Listar Conceptos' }])"
                >
                  Conceptos
                </inertia-link>
              </li>
              <li :class="{ 'mm-active': path == 'particulares' }">
                <inertia-link
                  :href="route('particulares.iniciar')"
                  :class="{ active: path == 'particulares' }"
                  v-if="$permissions.can([{ name: 'Listar Particulares' }])"
                >
                  Particulares
                </inertia-link>
              </li>
              <li :class="{ 'mm-active': path == 'empresas' }">
                <inertia-link
                  :href="route('empresas.iniciar')"
                  :class="{ active: path == 'empresas' }"
                  v-if="$permissions.can([{ name: 'Listar Empresas' }])"
                >
                  Empresas
                </inertia-link>
              </li>
              <li
                :class="{
                  'mm-active': path == 'cuentas-corrientes',
                }"
              >
                <inertia-link
                  :href="route('cuentas-corrientes.iniciar')"
                  :class="{
                    active: path == 'cuentas-corrientes',
                  }"
                  v-if="
                    $permissions.can([{ name: 'Listar Cuentas-Corrientes' }])
                  "
                >
                  Cuentas Corrientes
                </inertia-link>
              </li>
              <!--<li
                :class="{
                  'mm-active': path == 'asignar',
                }"
              >
                <inertia-link
                  :href="route('usuarios.asignar')"
                  :class="{
                    active: path == 'asignar',
                  }"
                >
                  Asignar Serie y Correlativo
                </inertia-link>
              </li>-->
            </ul>
          </li>
          <li :class="{ 'mm-active': path == 'cobros' }">
            <inertia-link
              :class="{ active: path == 'cobros' }"
              :href="route('cobros.iniciar')"
            >
              <i class="uil-window-section"></i>
              <span>Comprobantes</span>
            </inertia-link>
          </li>
          <!--<li :class="{ 'mm-active': path == 'comprobantes' }">
            <inertia-link
              :class="{ active: path == 'comprobantes' }"
              :href="route('comprobantes.iniciar')"
            >
              <i class="uil-window-section"></i>
              <span>Comprobantes</span>
            </inertia-link>
          </li>-->
          <!--<li>
            <a
              href="javascript: void(0);"
              class="has-arrow waves-effect"
              @click="mostrarMenu(2)"
              :class="{ 'mm-active': path == 'comprobantes' }"
            >
              <i class="uil-upload"></i>
              <span>Comprobantes</span>
            </a>
            <ul class="sub-menu" aria-expanded="false" v-show="show_menus[2]">
              <li :class="{ 'mm-active': path == 'comprobantes' }">
                <inertia-link
                  :href="route('comprobantes.iniciar')"
                  :class="{ active: path == 'comprobantes' }"
                >
                  Registrar comprobante
                </inertia-link>
              </li>
            </ul>
          </li>-->
          <li>
            <a
              href="javascript: void(0);"
              class="has-arrow waves-effect"
              @click="mostrarMenu(3)"
              :class="{
                'mm-active':
                  path == 'boletas' ||
                  path == 'facturas' ||
                  path == 'comunicacion-baja'||
                  path == 'notas-credito' ||
                  path == 'notas-debito' ||
                  path == 'consultas-resumen-diario',
              }"
            >
              <i class="fas fa-cloud-upload-alt"></i>
              <span>Sunat</span>
            </a>
            <ul class="sub-menu" aria-expanded="false" v-show="show_menus[3]">
              <!--<li
                :class="{
                  'mm-active': path == 'dashboard-sunat',
                }"
              >
                <inertia-link
                  :href="route('sunat.tablero')"
                  :class="{
                    active: path == 'dashboard-sunat',
                  }"
                >
                  Dashboard Sunat
                </inertia-link>
              </li>-->
              <li
                :class="{
                  'mm-active': path == 'boletas',
                }"
              >
                <inertia-link
                  :href="route('boletas.iniciar')"
                  :class="{
                    active: path == 'boletas',
                  }"
                >
                  Resumen Diario
                </inertia-link>
              </li>
              <li
                :class="{
                  'mm-active': path == 'facturas',
                }"
              >
                <inertia-link
                  :href="route('facturas.iniciar')"
                  :class="{
                    active: path == 'facturas',
                  }"
                >
                  Facturas
                </inertia-link>
              </li>
              <li
                :class="{
                  'mm-active': path == 'comunicacion-baja',
                }"
              >
                <inertia-link
                  :href="route('comunicacion-baja.iniciar')"
                  :class="{
                    active: path == 'comunicacion-baja',
                  }"
                >
                  Comunicacion de baja
                </inertia-link>
              </li>
              <!--<li
                :class="{
                  'mm-active': path == 'notas-debito',
                }"
              >
                <inertia-link
                  :href="route('notas-debito.iniciar')"
                  :class="{
                    active: path == 'notas-debito',
                  }"
                >
                  Enviar notas de debito
                </inertia-link>
              </li>
              <li
                :class="{
                  'mm-active': path == 'notas-credito',
                }"
              >
                <inertia-link
                  :href="route('notas-credito.iniciar')"
                  :class="{
                    active: path == 'notas-credito',
                  }"
                >
                  Enviar notas de credito
                </inertia-link>
              </li>
              <li>
                <inertia-link
                  :href="route('resumen-diario.iniciar')"
                  :class="{ 'mm-active': path == 'consultas-resumen-diario' }"
                >
                  Enviar resumen diario
                </inertia-link>
              </li>-->
            </ul>
          </li>
          <li>
            <a
              href="javascript: void(0);"
              class="has-arrow waves-effect"
              @click="mostrarMenu(4)"
              :class="{
                'mm-active':
                  path == 'tipo-comprobante' ||
                  path == 'consultas-dependencias' ||
                  path == 'comprobantes-consultas',
              }"
            >
              <i class="fas fa-clipboard-list"></i>
              <span>Consultas</span>
            </a>
            <ul class="sub-menu" aria-expanded="false" v-show="show_menus[4]">
              <li>
                <inertia-link
                  :href="route('comprobantes.consultas')"
                  :class="{ 'mm-active': path == 'comprobantes-consultas' }"
                >
                  Documentos
                </inertia-link>
              </li>
              <li
                :class="{
                  'mm-active': path == 'tipo-comprobante',
                }"
              >
                <inertia-link
                  :href="route('tipo-comprobante.iniciar')"
                  :class="{
                    active: path == 'tipo-comprobante',
                  }"
                  v-if="$permissions.can([{ name: 'Listar Tipos-Comprobante' }])"
                >
                  Tipos de comprobante
                </inertia-link>
              </li>
              <li>
                <inertia-link
                  :href="route('dependencias.iniciar')"
                  :class="{ 'mm-active': path == 'consultas-dependencias' }"
                >
                  Dependencias
                </inertia-link>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $permissions.can([
                { name: 'Cajero Reportes-Periodo' },
                { name: 'Descuento Reportes-Periodo' },
                { name: 'Centro-Costo Reportes-Periodo' },
                { name: 'Recibo-Ingreso Reportes-Periodo' },
                { name: 'Consolidado Reportes-Periodo' },
                { name: 'Facturas Reportes-Periodo' },
                { name: 'Notas Reportes-Periodo' },
              ])
            "
          >
            <a
              href="javascript: void(0);"
              class="has-arrow waves-effect"
              @click="mostrarMenu(5)"
              :class="{
                'mm-active':
                  path == 'reportes-diario' ||
                  path == 'reportes-periodo' ||
                  path == 'reportes-ingresos' ||
                  path == 'reportes-conceptos',
              }"
            >
              <i class="fas fa-file-upload"></i>
              <span>Reportes</span>
            </a>
            <ul class="sub-menu" aria-expanded="false" v-show="show_menus[5]">
              <li>
                <inertia-link
                  href="#"
                  :class="{ 'mm-active': path == 'reportes-diario' }"
                >
                  Diario
                </inertia-link>
              </li>
              <li>
                <inertia-link
                  :href="route('reportes.cajero')"
                  :class="{ 'mm-active': path == 'reportes-periodo' }"
                  v-if="
                    $permissions.can([
                      { name: 'Cajero Reportes-Periodo' },
                      { name: 'Descuento Reportes-Periodo' },
                      { name: 'Centro-Costo Reportes-Periodo' },
                      { name: 'Recibo-Ingreso Reportes-Periodo' },
                      { name: 'Consolidado Reportes-Periodo' },
                      { name: 'Facturas Reportes-Periodo' },
                      { name: 'Notas Reportes-Periodo' },
                    ])
                  "
                >
                  Por periodo
                </inertia-link>
              </li>
              <li>
                <inertia-link
                  href="#"
                  :class="{ 'mm-active': path == 'reportes-ingresos' }"
                >
                  Ingresos
                </inertia-link>
              </li>
              <li>
                <inertia-link
                  href="#"
                  :class="{ 'mm-active': path == 'reportes-conceptos' }"
                >
                  Conceptos
                </inertia-link>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- <b-button variant="primary" block @click="hide">Close Sidebar</b-button>
        </div>
      </template>
    </b-sidebar>-->
  </div>
</template>
<script>
export default {
  data() {
    return {
      app_url: this.$root.app_url,
      show_menus: this.$store.getters.getEstadosMenu,
    };
  },
  computed: {
    path() {
      return window.location.pathname.split("/")[1];
    },
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
    },
    mostrarOcultarBarraLateral() {
      let sitebody = document.body;
      let show_sidebar = !this.$store.getters.getShowSideBar;

      if (show_sidebar) {
        sitebody.classList.remove("vertical-collpsed");
      } else {
        sitebody.classList.add("vertical-collpsed");
      }

      this.$store.dispatch("setShowSideBar", show_sidebar);
    },
  },
};
</script>
