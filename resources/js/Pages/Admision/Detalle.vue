
<template>
  <div>
    <b-alert show dismissible variant="success" v-if="alerta == false">
      {{ alerta_mensaje }}
    </b-alert>
    <b-alert show dismissible variant="danger" v-if="alerta == true">
      {{ alerta_mensaje }}
    </b-alert>
    <hr />
    <form v-if="accion === 'Crear'" @submit.prevent="agregarDetalle">
      <b-row>
        <b-col cols="6">
          <v-select
            v-model="concepto"
            @search="buscarConcepto"
            :filterable="false"
            :options="conceptos"
            :reduce="(concepto) => concepto"
            label="vista_concepto"
            placeholder="Ingrese código o descripción del concepto"
          >
            <template #search="{ attributes, events }">
              <input
                class="vs__search"
                :required="!concepto"
                v-bind="attributes"
                v-on="events"
                v-model="filtro"
              />
            </template>
            <template slot="no-options">
              Lo sentimos, no hay resultados de coincidencia.
            </template>
          </v-select>
        </b-col>
        <b-col>
          <b-button
            style="height: 34px"
            type="submit"
            variant="info"
            class="mb-2"
            title="Añadir detalle"
          >
            <b-icon icon="plus-circle"></b-icon>
          </b-button>
        </b-col>
      </b-row>
    </form>
    <b-row class="mt-3">
      <b-col>
        <b-table
          show-empty
          striped
          hover
          bordered
          small
          responsive
          stacked="md"
          :items="admision.detalles"
          :fields="fields"
          empty-text="No hay conceptos para mostrar"
        >
          <template v-slot:cell(acciones)="row">
            <b-button
              v-if="accion === 'Crear'"
              variant="danger"
              size="sm"
              title="Eliminar"
              @click="eliminarDetalle(row.index)"
            >
              <b-icon icon="trash"></b-icon>
            </b-button>
          </template>
        </b-table>
      </b-col>
    </b-row>
    <div>
      <b-button v-if="accion == 'Crear'" variant="success" @click="registrar()"
        >Registrar</b-button
      >
    </div>
  </div>
</template>
<script>
const axios = require("axios");
export default {
  name: "admision.detalle",
  props: ["admision"],
  data() {
    return {
      accion: "Crear",
      alerta: null,
      alerta_mensaje: "",
      app_url: this.$root.app_url,
      concepto: null,
      filtro: "",
      conceptos: [],
      fields: [
        {
          key: "codigo",
          label: "CÓDIGO",
          class: "text-center",
          tdClass: "codigo",
        },
        {
          key: "codi_depe",
          label: "C. COSTO",
          class: "text-center",
          tdClass: "centro",
        },
        {
          key: "descripcion",
          label: "CONCEPTO",
          class: "text-center",
          tdClass: "concepto",
        },
        {
          key: "cantidad",
          label: "CANT.",
          class: "text-center",
          tdClass: "cantidad",
        },
        {
          key: "precio",
          label: "COSTO",
          class: "text-center",
          tdClass: "costo",
        },
        { key: "acciones", label: "", class: "text-center" },
      ],
    };
  },
  methods: {
    buscarConcepto(search, loading) {
      loading(true);
      axios
        .get(`${this.app_url}/buscarConcepto?filtro=${search}`)
        .then((response) => {
          this.conceptos = response.data;
          loading(false);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    agregarDetalle() {
      if (
        this.admision.detalles.filter(
          (obj) => obj.concepto_id == this.concepto.concepto_id
        ).length == 0
      ) {
        this.$set(this.concepto, "cantidad", 1);
        this.$set(this.concepto, "tipo_descuento", "soles");
        this.$set(this.concepto, "descuento", 0);
        this.concepto.precio =
          this.concepto.tipo_precio == "variable" ? 0 : this.concepto.precio;
        this.concepto.gravado =
          this.concepto.tipo_afectacion == "30"
            ? 0
            : this.concepto.precio * 0.82;
        this.concepto.inafecto =
          this.concepto.tipo_afectacion == "30"
            ? parseFloat(this.concepto.precio)
            : 0;
        this.concepto.impuesto =
          this.concepto.tipo_afectacion == "30"
            ? 0
            : this.concepto.precio * 0.18;
        this.$set(this.concepto, "subtotal", parseFloat(this.concepto.precio));
        this.admision.detalles.push(this.concepto);
      } else {
        this.$bvToast.toast("El concepto del detalle ya existe", {
          title: "Añadir detalle",
          variant: "warning",
          toaster: "b-toaster-bottom-right",
          solid: true,
        });
      }

      this.concepto = null;
    },
    eliminarDetalle(index) {
      this.admision.detalles.splice(index, 1);
    },
    registrar() {
      this.alerta = null;
      this.alerta_mensaje = "";
      this.$bvModal
        .msgBoxConfirm(
          ["¿Esta seguro de querer registrar este comprobante?", "\n"],
          {
            title: "Enviar Comprobante",
            okVariant: "success",
            okTitle: "SI",
            cancelVariant: "danger",
            cancelTitle: "NO",
            centered: true,
          }
        )
        .then((value) => {
          if (value) {
            axios
              .post(`${this.app_url}/admision`, this.admision)
              .then((response) => {
                if (!response.data.error) {
                  this.alerta = response.data.error;
                  this.alerta_mensaje = response.data.successMessage;
                  console.log("Exito");
                  this.accion = "Mostrar";
                } else {
                  this.alerta = response.data.error;
                  this.alerta_mensaje = response.data.errorMessage;
                  console.log("Error");
                }
              })
              .catch(function (error) {
                console.log(error);
              });
          }
        });
    },
  },
};
</script>
<style>
.codigo {
  max-width: 100px;
}
.cantidad {
  max-width: 100px;
}
.costo {
  max-width: 100px;
}
.centro {
  max-width: 150px;
}
.descuento {
  max-width: 120px;
}
.concepto {
  width: 400px;
}
input[type="number"][id="precio"]::-webkit-inner-spin-button,
input[type="number"][id="precio"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
