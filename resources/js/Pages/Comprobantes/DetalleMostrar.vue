
<template>
  <div>
    <hr />
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
          :items="comprobante.detalles"
          :fields="fields"
          empty-text="No hay conceptos para mostrar"
        >
          <template v-slot:cell(codigo)="row">
            <b-form-input
              class="text-center"
              :value="row.item.codigo"
              readonly
            ></b-form-input>
          </template>
          <template v-slot:cell(concepto)="row">
            <b-form-input :value="row.item.descripcion" readonly></b-form-input>
          </template>
          <template v-slot:cell(cantidad)="row">
            <div>
              <b-form-input
                class="text-center"
                v-model="row.item.cantidad"
                readonly
              ></b-form-input>
            </div>
          </template>
          <template v-slot:cell(precio)="row">
            <b-form-input
              class="text-center"
              v-model="row.item.precio"
              readonly
            ></b-form-input>
          </template>
          <template v-slot:cell(descuento)="row">
            <div>
              <b-form-select
                disabled
                v-model="row.item.tipo_descuento"
              >
                <b-form-select-option value="soles">S/.</b-form-select-option>
                <b-form-select-option value="porcentaje">%</b-form-select-option>
              </b-form-select>
              <b-form-input
                readonly
                class="text-center"
                v-model="row.item.descuento"
              ></b-form-input>
            </div>
          </template>
          <template v-slot:cell(subTotal)="row">
            <span class="font-weight-bold">
              S/. {{ row.item.subtotal }}
            </span>
          </template>
          <template slot="bottom-row" slot-scope="">
            <b-td /><b-td /><b-td /><b-td />
            <b-td class="text-right font-weight-bold">TOTAL</b-td>
            <b-td class="text-right font-weight-bold"
              >S/. {{ comprobante.total }}</b-td
            >
            <b-td />
          </template>
        </b-table>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
export default {
  name: "comprobantes.detalleMostrar",
  props: ["comprobante"],
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      concepto: null,
      fields: [
        { key: "concepto.codigo", label: "CÓDIGO", class: "text-center", tdClass: "codigo" },
        { key: "concepto.descripcion", label: "CONCEPTO", class: "text-center", tdClass: "concepto" },
        { key: "cantidad", label: "CANTIDAD", class: "text-center" },
        { key: "concepto.precio", label: "COSTO", class: "text-center" },
        { key: "descuento", label: "DESCUENTO", class: "text-center" },
        { key: "subTotal", label: "SUBTOTAL", class: "text-right" },
        { key: "acciones", label: "", class: "text-center" },
      ],
    };
  },
  filters: {
    currency(value) {
      return value ? value.toFixed(2) : null;
    },
  },
};
</script>
<style>
.codigo {
  width: 150px;
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
