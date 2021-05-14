<template>
  <app-layout>
    <div class="card">
      <div class="card-header">
        <!--{{ comprobante }}
        <br />
        <br />
        <br />
        {{ conceptos }}-->
        <h1 class="text-center">
          {{ comprobante.tipo_comprobante.nombre }}
          <br />
          {{ comprobante.serie }}-{{ comprobante.correlativo }}
        </h1>
      </div>
      <div class="card-body">
        <b-row>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              v-if="comprobante.comprobanteable.apn"
              label="Nombre Usuario: "
              label-cols-sm="4"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-model="comprobante.comprobanteable.apn"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
            <b-form-group
              v-if="comprobante.comprobanteable.razon_social"
              label="Razon Social: "
              label-cols-sm="4"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-model="comprobante.comprobanteable.razon_social"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              label="Tipo Usuario: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-model="comprobante.tipo_usuario"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              v-if="comprobante.comprobanteable.ruc"
              label="RUC: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-model="comprobante.codi_usuario"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
            <b-form-group
              v-else
              label="RUC: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-model="comprobante.codi_usuario"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              v-if="comprobante.comprobanteable.direccion"
              label="Direccion: "
              label-cols-sm="4"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-textarea
                v-model="comprobante.comprobanteable.direccion"
                size="sm"
                disabled
              ></b-form-textarea>
            </b-form-group>
            <b-form-group
              v-else
              label="Numero de Escuela: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-model="comprobante.nues_espe"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              label="Estado: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-if="comprobante.estado == 'noEnviado'"
                value="No enviado"
                size="sm"
                disabled
              ></b-form-input>
              <b-form-input
                v-if="comprobante.estado == 'anulado'"
                value="Anulado"
                size="sm"
                disabled
              ></b-form-input>
              <b-form-input
                v-if="comprobante.estado == 'observado'"
                value="Observado"
                size="sm"
                disabled
              ></b-form-input>
              <b-form-input
                v-if="comprobante.estado == 'rechazado'"
                value="Rechazado"
                size="sm"
                disabled
              ></b-form-input>
              <b-form-input
                v-if="comprobante.estado == 'aceptado'"
                value="Aceptado"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              label="Cancelado: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
            >
              <b-form-input
                v-if="comprobante.cancelado == true"
                value="Si"
                size="sm"
                disabled
              ></b-form-input>
              <b-form-input
                v-if="comprobante.cancelado == false"
                value="No"
                size="sm"
                disabled
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm="12" md="4" lg="4" class="my-1">
            <b-form-group
              label="Descargar: "
              label-cols-sm="6"
              label-align-sm="right"
              label-size="sm"
            >
              <a :href="`${comprobante.url_xml}`" download> XML</a>
              <a :href="`${comprobante.url_cdr}`" download> CDR</a>
              <a :href="`${comprobante.url_pdf}`" download> PDF</a>
            </b-form-group>
          </b-col>
        </b-row>
        <template>
          <h4 class="text-center">Detalles</h4>
          <div>
            <b-table
              striped
              hover
              :items="conceptos"
              :fields="fields"
            ></b-table>
          </div>
        </template>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "comprobantes.listar",
  props: ["comprobante", "conceptos"],
  components: {
    AppLayout,
  },
  data() {
    return {
      fields: [
        { key: "codigo", label: "Codigo", sortable: true },
        {
          key: "descripcion",
          label: "Descripcion",
          class: "text-center",
          sortable: true,
        },
        { key: "precio", label: "Precio", sortable: true },
        { key: "tipo_precio", label: "Tipo Precio", sortable: true },
        { key: "detraccion", label: "Detraccion", sortable: true },
        { key: "tipo_concepto.nombre", label: "Tipo Concepto", sortable: true },
        { key: "clasificador.nombre", label: "Clasificador", sortable: true },
        {
          key: "unidad_medida.codigo",
          label: "Codigo de unidad de medida",
          sortable: true,
        },
        {
          key: "unidad_medida.nombre",
          label: "Nombre de unidad de medida",
          sortable: true,
        },
      ],
    };
  },
};
</script>

