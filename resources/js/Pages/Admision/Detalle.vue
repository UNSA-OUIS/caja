
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
            v-if="accion === 'Crear'"
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
            v-if="accion === 'Crear'"
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
    <b-alert :show="show_error" variant="danger">{{
      validacion_mensaje_detalles
    }}</b-alert>
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
          <template v-if="admision.id" v-slot:cell(codigo)="row">
            <b-form-input
              class="text-center"
              :value="row.item.concepto.codigo"
              readonly
            ></b-form-input>
          </template>
          <template v-else v-slot:cell(codigo)="row">
            <b-form-input
              class="text-center"
              :value="row.item.codigo"
              readonly
            ></b-form-input>
          </template>
          <template v-if="admision.id" v-slot:cell(descripcion)="row">
            {{ row.item.concepto.descripcion }}
          </template>
          <template v-else v-slot:cell(descripcion)="row">
            {{ row.item.descripcion }}
          </template>
          <template v-slot:cell(cantidad)>
            <div>
              <b-form-input
                readonly
                class="text-center"
                value="1"
              ></b-form-input>
            </div>
          </template>
          <template v-if="admision.id" v-slot:cell(precio)="row">
            {{ row.item.precio_variable }}
          </template>
          <template v-else v-slot:cell(precio)="row">
            <b-form-input
              class="text-center"
              id="precio"
              type="number"
              v-model="row.item.precio"
              @keyup="calcularSubTotal(row.item.concepto_id)"
              @change="calcularSubTotal(row.item.concepto_id)"
              :readonly="row.item.tipo_precio == 'fijo' || accion === 'Mostrar'"
            ></b-form-input>
          </template>
          <template v-slot:cell(subTotal)="row">
            <span v-if="!admision.id" class="font-weight-bold">
              S/. {{ row.item.subtotal | currency }}
            </span>
            <span v-else class="font-weight-bold">
              S/. {{ row.item.precio_variable | currency }}
            </span>
          </template>
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
      <b-button v-if="accion === 'Crear'" variant="success" @click="registrar()"
        >Registrar</b-button
      >
    </div>
  </div>
</template>
<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";
export default {
  name: "admision.detalle",
  props: ["admision"],
  components: {
    AppLayout,
  },
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
        { key: "subTotal", label: "SUBTOTAL", class: "text-right" },
        { key: "acciones", label: "", class: "text-center" },
      ],
      validacion_mensaje: "",
      validacion_mensaje_tipo: "",
      validacion_mensaje_fecha: "",
      validacion_mensaje_banco: "",
      validacion_mensaje_detalles: "",
      show_error: false,
      entidades_bancarias: [
        { value: "BCP", text: "Banco de Crédito del Perú (BCP)" },
        { value: "BN", text: "Banco de la Nación" },
      ],
      nro_operacion: "",
      fecha_operacion: "",
      error: {
        estado: false,
        mensaje: "",
      },
      tipos_pago: [
        { text: "Efectivo", value: "Efectivo" },
        { text: "Voucher", value: "Voucher" },
      ],
      opciones_cancelado: [
        { text: "Cancelado", value: true },
        { text: "Pendiente", value: false },
      ],
      tipos_resolucion: [
        { text: "VRAC", value: "VRAC" },
        { text: "RR", value: "RR" },
        { text: "CU", value: "CU" },
        { text: "OTRO", value: "OTRO" },
      ],
      anhos_resolucion: [{ text: "2020", value: "2020" }],
    };
  },
  created() {
    if (!this.admision.id) {
      this.accion = "Crear";
    } else {
      this.accion = "Mostrar";
    }
  },
  computed: {
    precioTotal() {
      this.admision.monto_total = this.admision.detalles.reduce(
        (acc, item) => acc + item.subtotal,
        0
      );
      return this.admision.monto_total;
    },
    validacion_detalles() {
      if (this.admision.detalles.length == 0) {
        this.validacion_mensaje_detalles =
          "Debe ingresar al menos un detalle para registrar el comprobante.";
        return false;
      } else if (
        this.admision.detalles.some(
          (element) =>
            parseFloat(element.precio) == 0 || element.precio.length == 0
        )
      ) {
        this.validacion_mensaje_detalles =
          "Se encontraron detalles con precios variables aún no especificados, por favor revisar.";
        return false;
      } else if (
        this.admision.detalles.some(
          (element) => element.codi_depe === "Centro de costos multiple"
        )
      ) {
        this.validacion_mensaje_detalles =
          "Se encontraron detalles con centro de costo indefinido, por favor revisar.";
        return false;
      }
      return true;
    },
  },
  watch: {
    filtro: function (val) {
      this.filtro = val.trim();
    },
    concepto: function (val) {
      this.filtro = "";
    },
  },
  filters: {
    currency(value) {
      return value ? value.toFixed(2) : null;
    },
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
        this.$set(this.concepto, "resolucion", "");
        this.$set(this.concepto, "anho_resolucion", "2021");
        this.$set(this.concepto, "tipo_resolucion", "DIGA");
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
    calcularSubTotal(concepto_id) {
      let objDetalle = this.admision.detalles.find(
        (detalle) => detalle.concepto_id === concepto_id
      );
      let subtotal_parcial = objDetalle.cantidad * objDetalle.precio;
      if (objDetalle.tipo_descuento === "soles") {
        objDetalle.subtotal = subtotal_parcial - objDetalle.descuento;
      } else if (objDetalle.tipo_descuento === "porcentaje") {
        objDetalle.subtotal =
          subtotal_parcial - (subtotal_parcial * objDetalle.descuento) / 100;
      }

      if (objDetalle.descuento == "0" || objDetalle.descuento == "") {
        objDetalle.resolucion = "";
      }

      if (objDetalle.tipo_afectacion === "30") {
        objDetalle.inafecto = objDetalle.subtotal;
        objDetalle.impuesto = 0;
        objDetalle.gravado = 0;
      } else {
        objDetalle.gravado = objDetalle.subtotal * 0.82;
        objDetalle.impuesto = objDetalle.subtotal * 0.18;
        objDetalle.inafecto = 0;
      }
    },
    validarEmail(email) {
      var re =
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    registrar() {
      this.alerta = null;
      this.alerta_mensaje = "";
      this.$bvModal
        .msgBoxConfirm(
          ["¿Esta seguro de querer registrar este proceso de admision?", "\n"],
          {
            title: "Registrar proceso de admision",
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
                  this.accion = "Mostrar";
                } else {
                  this.alerta = response.data.error;
                  this.alerta_mensaje = response.data.errorMessage;
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
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>


<!--<template>
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
          <template v-if="admision.id" v-slot:cell(codigo)="row">
            {{ row.item.concepto.codigo }}
          </template>
          <template v-else v-slot:cell(codigo)="row">
            {{row.item.codigo}}
          </template>
          <template v-if="admision.id" v-slot:cell(descripcion)="row">
            {{ row.item.concepto.descripcion }}
          </template>
          <template v-else v-slot:cell(descripcion)="row">
            {{row.item.descripcion}}
          </template>
          <template v-slot:cell(cantidad)>
            <span>1</span>
          </template>
          <template v-if="admision.id" v-slot:cell(precio)="row">
            {{ row.item.precio_variable }}
          </template>
          <template v-else v-slot:cell(precio)="row">
            <span v-if="row.item.tipo_precio == 'fijo'">{{
              row.item.precio
            }}</span>
            <b-input v-else v-model="row.item.precio"></b-input>
          </template>
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
      accion: "",
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
  created() {
    if (!this.admision.id) {
      this.accion = "Crear";
    } else {
      this.accion = "Mostrar";
    }
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
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>-->
