<template>
  <app-layout>
    <PeriodoMenu :tab="0" />
    <div class="card" ref="content">
      <div class="card-header">
        <h1>Boletas</h1>
      </div>
      <div class="card-body">
        <b-row>
          <b-col cols="4">
            <b-form-group
              label="Fecha inicio: "
              label-cols-sm="5"
              label-align-sm="right"
              label-size="sm"
              label-for="startDate"
              class="mb-0"
            >
              <b-form-datepicker
                id="startDate"
                v-model="filter.fechaInicio"
                today-button
                reset-button
                close-button
                placeholder="Elige una fecha"
                size="sm"
              ></b-form-datepicker>
            </b-form-group>
          </b-col>
          <b-col cols="4">
            <b-form-group
              label="Fecha fin: "
              label-cols-sm="4"
              label-align-sm="right"
              label-size="sm"
              label-for="endDate"
              class="mb-0"
            >
              <b-form-datepicker
                id="endDate"
                v-model="filter.fechaFin"
                today-button
                reset-button
                close-button
                placeholder="Elige una fecha"
                size="sm"
              ></b-form-datepicker>
            </b-form-group>
          </b-col>
        </b-row>
        <b-button
          class="btn btn-success float-right mt-2 mb-2"
          @click="filterTable()"
          >Generar reporte</b-button
        >
        <b-table
          ref="tbl_comprobantes"
          show-empty
          striped
          hover
          sticky-header
          bordered
          small
          responsive
          :items="comprobantes"
          :fields="fields"
          empty-text="No hay comprobantes para mostrar"
          empty-filtered-text="No hay comprobantes que coincidan con su búsqueda."
        >
        </b-table>
      </div>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "sunat.filtrar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      fields: [
        { key: "codigo", label: "Código" },
        { key: "serie", label: "Serie" },
        { key: "correlativo", label: "Correlativo" },
        { key: "cui", label: "Cliente" },
        { key: "total", label: "Precio Total" },
      ],
      comprobantes: [],
      currentPage: 1,
      perPage: 5,
      filter: {
        fechaInicio: "",
        fechaFin: "",
      },
    };
  },
  methods: {
    refreshTable() {
      this.$refs.tbl_comprobantes.refresh();
    },
    async filterTable() {
      /*try {
        let params =
          "&fechaInicio=" +
          this.filter.fechaInicio +
          "&fechaFin=" +
          this.filter.fechaFin;
        const response = await axios.get(
          `${this.app_url}/sunat/filtrar${params}`
        );
        this.comprobantes = response.data.comprobantes;
      } catch (error) {
        console.log(error);
      }*/
      this.$inertia.post(route("resumen.enviar"));
    },
  },
  computed: {
    grupoFilter() {
      var group = this.comprobantes;

      group =
        this.fechaInicio && this.fechaFin
          ? group.filter(
              (item) =>
                new Date(item.created_at.slice(0, 10).split("-")) >=
                  new Date(this.fechaInicio.split("-")) &&
                new Date(item.created_at.slice(0, 10).split("-")) <=
                  new Date(this.fechaFin.split("-"))
            )
          : group;
      return group;
    },
    computed: {
      grupoFilter() {
        var group = this.comprobantes;

        group =
          this.fechaInicio && this.fechaFin
            ? group.filter(
                (item) =>
                  new Date(item.created_at.slice(0, 10).split("-")) >=
                    new Date(this.fechaInicio.split("-")) &&
                  new Date(item.created_at.slice(0, 10).split("-")) <=
                    new Date(this.fechaFin.split("-"))
              )
            : group;
        return group;
      },
    },
  },
};
</script>
