<template>
  <app-layout>
    <div class="row estado">
      <div class="col-md-6 col-xl-2">
        <div class="card">
          <div class="card-body">
            <div class="float-right mt-2">
              <div id="total-revenue-chart"></div>
            </div>
            <div>
              <h4 class="mb-1 mt-1 text-primary text-center">
                <span data-plugin="counterup">No Enviado</span>
              </h4>
              <p class="text-muted mb-0 text-center">{{ noEnviado }}</p>
              <p class="text-muted mb-0 text-center" v-if="noEnviado == 1">
                comprobante
              </p>
              <p class="text-muted mb-0 text-center" v-else>comprobantes</p>
            </div>
          </div>
        </div>
      </div>
      <!-- end col-->

      <div class="col-md-6 col-xl-2">
        <div class="card">
          <div class="card-body">
            <div class="float-right mt-2">
              <div id="total-revenue-chart"></div>
            </div>
            <div>
              <h4 class="mb-1 mt-1 text-warning text-center">
                <span data-plugin="counterup">Observado</span>
              </h4>
              <p class="text-muted mb-0 text-center">{{ observado }}</p>
              <p class="text-muted mb-0 text-center" v-if="observado == 1">
                comprobante
              </p>
              <p class="text-muted mb-0 text-center" v-else>comprobantes</p>
            </div>
          </div>
        </div>
      </div>
      <!-- end col-->

      <div class="col-md-6 col-xl-2">
        <div class="card">
          <div class="card-body">
            <div class="float-right mt-2">
              <div id="total-revenue-chart"></div>
            </div>
            <div>
              <h4 class="mb-1 mt-1 text-danger text-center">
                <span data-plugin="counterup">Rechazado</span>
              </h4>
              <p class="text-muted mb-0 text-center">{{ rechazado }}</p>
              <p class="text-muted mb-0 text-center" v-if="rechazado == 1">
                comprobante
              </p>
              <p class="text-muted mb-0 text-center" v-else>comprobantes</p>
            </div>
          </div>
        </div>
      </div>
      <!-- end col-->

      <div class="col-md-6 col-xl-2">
        <div class="card">
          <div class="card-body">
            <div class="float-right mt-2">
              <div id="total-revenue-chart"></div>
            </div>
            <div>
              <h4 class="mb-1 mt-1 text-secondary text-center">
                <span data-plugin="counterup">Anulado</span>
              </h4>
              <p class="text-muted mb-0 text-center">{{ anulado }}</p>
              <p class="text-muted mb-0 text-center" v-if="anulado == 1">
                comprobante
              </p>
              <p class="text-muted mb-0 text-center" v-else>comprobantes</p>
            </div>
          </div>
        </div>
      </div>
      <!-- end col-->

      <div class="col-md-6 col-xl-2">
        <div class="card">
          <div class="card-body">
            <div class="float-right mt-2">
              <div id="total-revenue-chart"></div>
            </div>
            <div>
              <h4 class="mb-1 mt-1 text-success text-center">
                <span data-plugin="counterup">Aceptado</span>
              </h4>
              <p class="text-muted mb-0 text-center">{{ aceptado }}</p>
              <p class="text-muted mb-0 text-center" v-if="aceptado == 1">
                comprobante
              </p>
              <p class="text-muted mb-0 text-center" v-else>comprobantes</p>
            </div>
          </div>
        </div>
      </div>
      <!-- end col-->
    </div>
    <!-- end row-->
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
export default {
  name: "sunat.tablero",
  components: {
    AppLayout,
  },
  computed: {
    noEnviado() {
      return this.$store.state.envio.noEnviado;
    },
    observado() {
      return this.$store.state.envio.observado;
    },
    rechazado() {
      return this.$store.state.envio.rechazado;
    },
    anulado() {
      return this.$store.state.envio.anulado;
    },
    aceptado() {
      return this.$store.state.envio.aceptado;
    },
  },
  created() {
    this.$store.dispatch("getEstados");
  },
};
</script>

<style>
.estado {
  background-color: #F5F6F8;
  margin: 0.5rem;
  padding: 0.5rem;
  border: 2px solid #2D313E;
  /* IMPORTANTE */
  text-align: center;
}</style>
