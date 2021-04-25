<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <inertia-link :href="route('dashboard')">Inicio</inertia-link>
          </li>
          <li class="breadcrumb-item">
            <inertia-link :href="route('conceptos.iniciar')"
              >Lista de conceptos</inertia-link
            >
          </li>
          <li class="breadcrumb-item active">{{ accion }} concepto</li>
        </ol>
      </div>
      <div class="card-body">
        <b-form>
          <b-row>
            <b-col cols="2">
              <b-form-group
                id="input-group-6"
                label="Código:"
                label-for="input-6"
              >
                <b-form-input
                  id="input-6"
                  v-model="concepto.codigo"
                  type="number"
                  placeholder="Ingrese código"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.codigo" class="text-danger">
                  {{ $page.props.errors.codigo[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group
                id="input-group-1"
                label="Descripción:"
                label-for="input-1"
              >
                <b-form-input
                  id="input-1"
                  v-model="concepto.descripcion"
                  type="text"
                  placeholder="Ingrese descripción"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.descripcion" class="text-danger">
                  {{ $page.props.errors.descripcion[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="4">
              <b-form-group
                id="input-group-2"
                label="Descripción corta:"
                label-for="input-2"
                description="Descripción que aparecerá en el comprobante."
              >
                <b-form-input
                  id="input-2"
                  v-model="concepto.descripcion_imp"
                  type="text"
                  placeholder="Ingrese descripción corta"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div
                  v-if="$page.props.errors.descripcion_imp"
                  class="text-danger"
                >
                  {{ $page.props.errors.descripcion_imp[0] }}
                </div>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group
                id="input-group-7"
                label="Precio:"
                label-for="input-7"
              >
                <b-form-radio
                  v-model="concepto.tipo_precio"
                  name="some-radios"
                  value="fijo"
                  >Fijo</b-form-radio
                >
                <b-form-radio
                  v-model="concepto.tipo_precio"
                  name="some-radios"
                  value="variable"
                  >Variable</b-form-radio
                >

                <b-form-input
                  v-if="concepto.tipo_precio == 'fijo'"
                  id="input-7"
                  v-model="concepto.precio"
                  type="number"
                  placeholder="Ingrese precio"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.precio" class="text-danger">
                  {{ $page.props.errors.precio[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-3"
                label="Tipo de concepto:"
                label-for="input-3"
              >
                <b-form-select
                  id="input-3"
                  v-model="concepto.tipo_concepto_id"
                  :options="tipos_concepto"
                  :readonly="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.tipo_concepto_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.tipo_concepto_id[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-4"
                label="Clasificador:"
                label-for="input-4"
              >
                <b-form-select
                  id="input-4"
                  v-model="concepto.clasificador_id"
                  :options="clasificadores"
                  :readonly="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.clasificador_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.clasificador_id[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-5"
                label="Unidad de medida:"
                label-for="input-5"
              >
                <b-form-select
                  id="input-5"
                  v-model="concepto.unidad_medida_id"
                  :options="unidades_medida"
                  :readonly="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.unidad_medida_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.unidad_medida_id[0] }}
                </div>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group
                id="input-group-5"
                label="Semestre:"
                label-for="input-5"
              >
                <b-form-select
                  id="input-5"
                  v-model="concepto.semestre"
                  :options="semestre"
                  :readonly="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.unidad_medida_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.unidad_medida_id[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-5"
                label="Tipo Afectacion IGV:"
                label-for="input-5"
              >
                <b-form-select
                  id="input-5"
                  v-model="concepto.tipo_afectacion"
                  :options="tipoAfectacionIGV"
                  :readonly="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.unidad_medida_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.unidad_medida_id[0] }}
                </div>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group
                id="input-group-5"
                label="Cuenta Corriente:"
                label-for="input-5"
              >
                <b-form-select
                  id="input-5"
                  v-model="concepto.cuenta_corriente"
                  :options="cuentaCorriente"
                  :readonly="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.unidad_medida_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.unidad_medida_id[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                label="Centro de costos"
              >
                <b-form-radio
                  v-model="selected"
                  name="some-radios"
                  value="A"
                  >Sin centro de costos</b-form-radio
                >
                <b-form-radio
                  v-model="selected"
                  name="some-radios"
                  value="B"
                  >Con centro de costos especifico</b-form-radio
                >
                <v-select
                  v-if="selected == 'B'"
                  v-model="concepto.centro_costo"
                  @search="buscarConcepto"
                  :filterable="false"
                  :options="centroCostos"
                  :reduce="(concepto) => concepto"
                  label="nomb_depe"
                  placeholder="Búsqueda por código o descripción del centro de costos"
                >
                  <template slot="no-options">
                    Lo sentimos, no hay resultados de coincidencia.
                  </template>
                </v-select>
                <b-form-radio
                  v-model="selected"
                  name="some-radios"
                  value="C"
                  >Con centro de costos multiple</b-form-radio
                >
              </b-form-group>
            </b-col>
          </b-row>
          <b-button
            v-if="accion == 'Crear'"
            @click="registrar"
            variant="success"
            >Registrar</b-button
          >
          <b-button
            v-else-if="accion == 'Mostrar'"
            @click="accion = 'Editar'"
            variant="warning"
            >Editar</b-button
          >
          <b-button
            v-else-if="accion == 'Editar'"
            @click="actualizar"
            variant="success"
            >Actualizar</b-button
          >
        </b-form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
const axios = require("axios");

export default {
  name: "conceptos.nuevo-mostrar",
  props: ["concepto", "tipos_concepto", "clasificadores", "unidades_medida"],
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      accion: "",
      selected: "",
      centroCostos: [],
      tipoAfectacionIGV: [
        { value: 10, text: "Gravado: Operacion Onerosa" },
        { value: 20, text: "Exonerado: Operacion Onerosa" },
        { value: 30, text: "Inafecto: Operacion Onerosa" },
      ],
      semestre: [
        { value: 1, text: "2004-A" },
        { value: 2, text: "2004-B" },
        { value: 3, text: "2005-A" },
        { value: 4, text: "2020-B" },
      ],
      cuentaCorriente: [
        { value: 1, text: "215-123456789-0-255 | BCP: Cuenta General" },
        { value: 2, text: "215-123456789-0-255 | BCP: Cuenta General" },
        { value: 3, text: "215-123456789-0-255 | BCP: Cuenta General" },
        { value: 4, text: "215-123456789-0-255 | BCP: Cuenta General" },
      ],
    };
  },
  created() {
    if (!this.concepto.id) {
      this.accion = "Crear";
    } else {
      this.accion = "Mostrar";
    }
  },
  methods: {
    buscarConcepto(search, loading) {
      loading(true);

      axios
        .get(`${this.app_url}//buscarCentroCosto?filtro=${search}`)
        .then((response) => {
          this.centroCostos = response.data;
          console.log(this.centroCostos);
          loading(false);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    registrar() {
      this.$inertia.post(route("conceptos.registrar"), this.concepto);
    },
    actualizar() {
      this.$inertia.post(
        route("conceptos.actualizar", [this.concepto.id]),
        this.concepto
      );
    },
  },
};
</script>
