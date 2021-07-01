
<template>

  
  <div>
    <div class="form-row">
      <div class="form-group col-md-12 border border-light">
        <label class="text-info">Tipo de pago:</label>
        <b-form-radio-group :disabled="accion === 'Mostrar'" v-model="comprobante.tipo_pago" :options="tipos_pago" name="detraccion"></b-form-radio-group>
        <b-alert :show="!validacion_tipo" variant="danger" dismissible>{{ validacion_mensaje_tipo }}</b-alert>
      </div>
    </div>
    <div v-if="comprobante.tipo_pago === 'Voucher'" class="form-row">
      <div class="form-group col-md-4 border border-light">
          <label class="text-info">Entidad bancaria:</label>
          <b-form-select id="input-2" :disabled="accion === 'Mostrar'" :state="validacion_banco" aria-describedby="input-2-feedback" v-model="comprobante.entidad_bancaria" :options="entidades_bancarias" >
              <template v-slot:first>
                  <option :value="null" disabled>Seleccione...</option>
              </template>
          </b-form-select>
          <b-form-invalid-feedback id="input-2-feedback">
              {{ validacion_mensaje_banco }}
          </b-form-invalid-feedback>
      </div> 
      <div class="form-group col-md-4 border border-light">
          <label class="text-info">Nro Operación:</label>
          <b-form-input id="input-3" :readonly="accion === 'Mostrar'" :state="validacion" aria-describedby="input-3-feedback" v-model="nro_operacion" @change="verificar()" type="text" placeholder="Ingrese número de operación"></b-form-input>
          <b-form-invalid-feedback id="input-3-feedback">
              {{ validacion_mensaje }}
          </b-form-invalid-feedback>
      </div>
      <div class="form-group col-md-4 border border-light">
          <label class="text-info">Fecha de emisión:</label>
          <b-form-input id="input-4" :readonly="accion === 'Mostrar'" :state="validacion_fecha" aria-describedby="input-4-feedback" v-model="fecha_operacion" @change="verificar()" type="date" placeholder="Ingrese fecha de operación"></b-form-input>
          <b-form-invalid-feedback id="input-4-feedback">
              {{ validacion_mensaje_fecha }}
          </b-form-invalid-feedback>
      </div>
      <b-alert :show="error.estado" variant="danger" dismissible>{{ error.mensaje }}</b-alert>
    </div>
    <div v-if="comprobante.tipo_comprobante_id == 2" class="form-row">
      <div class="form-group col-md-12 border border-light">
        <label class="text-info">¿Se cancelará al momento del pago?</label>
        <b-form-radio-group :disabled="accion === 'Mostrar'" v-model="comprobante.cancelado" :options="opciones_cancelado" name="cancelado"></b-form-radio-group>
      </div>
    </div>
    <b-alert show dismissible variant="success" v-if="alerta == false">
      {{ alerta_mensaje }}
    </b-alert>
    <b-alert show dismissible variant="danger" v-if="alerta == true">
      {{ alerta_mensaje }}
    </b-alert>
    <hr />
    <form @submit.prevent="agregarDetalle">
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
    <b-alert :show="show_error" variant="danger">{{ validacion_mensaje_detalles }}</b-alert>
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
          <template v-slot:cell(codi_depe)="row">
            <template v-if="row.item.codi_depe === 'Centro de costos multiple'">
              <modal-centro-costos :comprobante="comprobante" :index="row.index"></modal-centro-costos>
            </template>
            <template v-else>
              {{ row.item.codi_depe }}
            </template>
          </template>
          <template v-slot:cell(concepto)="row">
            <b-form-input :value="row.item.descripcion" readonly></b-form-input>
          </template>
          <template v-slot:cell(cantidad)="row">
            <div>
              <b-form-input
                class="text-center"
                v-model="row.item.cantidad"
                :readonly="accion === 'Mostrar'"
                @keyup="calcularSubTotal(row.item.concepto_id)"
                @change="calcularSubTotal(row.item.concepto_id)"
                type="number"
                min="1"
              ></b-form-input>
            </div>
          </template>
          <template v-slot:cell(precio)="row">
            <b-form-input
              class="text-center"
              id="precio"
              type="number"
              v-model="row.item.precio"
              @keyup="calcularSubTotal(row.item.concepto_id)"
              @change="calcularSubTotal(row.item.concepto_id)"
              :readonly="row.item.tipo_precio == 'fijo' || accion === 'Mostrar' "
            ></b-form-input>
          </template>
          <template v-slot:cell(descuento)="row">
            <div>
              <b-form-select
                v-model="row.item.tipo_descuento"
                @change="calcularSubTotal(row.item.concepto_id)"
                :disabled="accion === 'Mostrar'"
              >
                <b-form-select-option value="soles">S/.</b-form-select-option>
                <b-form-select-option value="porcentaje"
                  >%</b-form-select-option
                >
              </b-form-select>
              <b-form-input
                class="text-center"
                v-model="row.item.descuento"
                :readonly="accion === 'Mostrar'"
                @keyup="calcularSubTotal(row.item.concepto_id)"
              ></b-form-input>
            </div>
          </template>
          <template v-slot:cell(resolucion)="row">
            <b-form-input
                class="text-center"
                v-model="row.item.resolucion"
                :readonly="accion === 'Mostrar' || row.item.descuento == 0"
              ></b-form-input>
            <b-form-select v-model="row.item.anho_resolucion" :options="anhos_resolucion"
            :disabled="accion === 'Mostrar' || row.item.descuento == 0">
              <template v-slot:first>
                <option :value="'2021'">2021</option>
              </template>
            </b-form-select>
            <b-form-select v-model="row.item.tipo_resolucion" :options="tipos_resolucion"
            :disabled="accion === 'Mostrar' || row.item.descuento == 0">
              <template v-slot:first>
                <option :value="'DIGA'">DIGA</option>
              </template>
            </b-form-select>
          </template>
          <template v-slot:cell(subTotal)="row">
            <span class="font-weight-bold">
              S/. {{ row.item.subtotal | currency }}
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
          <template slot="custom-foot" slot-scope="">
            <b-tr>
              <b-td colspan="6"></b-td>
              <b-td class="text-right font-weight-bold">Imp. Inafecto:</b-td>
              <b-td class="text-right font-weight-bold">S/. {{ comprobante.total_inafecta | currency }}</b-td><b-td />
            </b-tr>
            <b-tr>
              <b-td colspan="6"></b-td>
              <b-td class="text-right font-weight-bold">Imp. Gravado:</b-td>
              <b-td class="text-right font-weight-bold">S/. {{ comprobante.total_gravada | currency }}</b-td><b-td />
            </b-tr>
            <b-tr>
              <b-td colspan="6"></b-td>
              <b-td class="text-right font-weight-bold">IGV:</b-td>
              <b-td class="text-right font-weight-bold">S/. {{ comprobante.total_impuesto | currency }}</b-td><b-td />
            </b-tr>
            <b-tr>
              <b-td colspan="6"></b-td>
              <b-td class="text-right font-weight-bold">Importe total:</b-td>
              <b-td class="text-right font-weight-bold">S/. {{ precioTotal | currency }}</b-td><b-td />
            </b-tr>
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
import ModalCentroCostos from "./ModalCentroCostos";
export default {
  name: "comprobantes.detalle",
  props: ["comprobante"],
  components: {
    AppLayout,
    ModalCentroCostos
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
        { key: "cantidad", label: "CANT.", class: "text-center", tdClass: "cantidad" },
        { key: "precio", label: "COSTO", class: "text-center", tdClass: "costo" },
        { key: "descuento", label: "DESCUENTO", class: "text-center", tdClass: "costo" },
        { key: "resolucion", label: "RESOLUCION", class: "text-center", tdClass: "descuento" },
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
        {value: 'BCP', text: 'Banco de Crédito del Perú (BCP)'},
        {value: 'BN', text: 'Banco de la Nación'},
      ],
      nro_operacion: "",
      fecha_operacion: "",
      error:{
        estado: false,
        mensaje: ""
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
      anhos_resolucion: [
        { text: "2020", value: "2020" },
      ],
    };
  },
  created() {
    this.accion = "Crear";
  },
  computed: {
    precioTotal() {
      this.comprobante.total = this.comprobante.detalles.reduce(
        (acc, item) => acc + item.subtotal, 0
      );
      this.comprobante.total_descuento = this.comprobante.detalles.reduce(
        (acc, item) => acc + (parseFloat(item.precio) * parseFloat(item.cantidad) - item.subtotal), 0
      );
      this.comprobante.total_gravada = this.comprobante.detalles.reduce(
        (acc, item) => acc + item.gravado, 0
      );
      this.comprobante.total_impuesto = this.comprobante.detalles.reduce(
        (acc, item) => acc + item.impuesto, 0
      );
      this.comprobante.total_inafecta = this.comprobante.detalles.reduce(
        (acc, item) => acc + item.inafecto, 0
      );
      return this.comprobante.total;
    },
    validacion() {
      if (this.fecha_operacion.length == 0 && this.nro_operacion.length == 0 && this.comprobante.tipo_pago != "Voucher") return null
      else if(this.comprobante.tipo_pago === "Voucher" && this.nro_operacion.length == 0){
        this.validacion_mensaje = "Debe ingresar un número de operación"
        return false
      }
      else{
        if (this.comprobante.entidad_bancaria == 'BCP' && this.nro_operacion.length != 6 ){
          this.validacion_mensaje = "Debe ingresar exactamente 6 dígitos"
          return false
        }
        if (this.comprobante.entidad_bancaria == 'BN' && this.nro_operacion.length != 6){
          this.validacion_mensaje = "Debe ingresar exactamente 6 dígitos"
          return false
        }
      }
      return true
    },
    validacion_tipo() {
      if (this.comprobante.tipo_pago.length == 0) {
        this.validacion_mensaje_tipo = "Debe seleccionar un tipo de pago"
        return false
      }
      else{
        return true
      }
    },
    validacion_banco() {
      if (this.comprobante.entidad_bancaria === null && this.comprobante.tipo_pago != "Voucher") return null
      else{
        if (this.comprobante.entidad_bancaria === null) {
          this.validacion_mensaje_banco = "Debe seleccionar un tipo de pago"
          return false
        }
        else{
          return true
        }
      }
    },
    validacion_fecha() {
      var regFecha = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/
      if (this.fecha_operacion.length == 0 && this.nro_operacion.length == 0 && this.comprobante.tipo_pago != "Voucher") return null
      else if(this.comprobante.tipo_pago === "Voucher" && this.fecha_operacion.length == 0){
        this.validacion_mensaje_fecha = "Debe ingresar una fecha de operación"
        return false
      }
      else {
        if (!regFecha.test(this.fecha_operacion) || (this.fecha_operacion.length == 0 || this.fecha_operacion.length > 10)){
          this.validacion_mensaje_fecha = "Debe ingresar una fecha válida"
          return false
        }
      }
      return true
    },
    validacion_detalles() {
      if (this.comprobante.detalles.length == 0) {
        this.validacion_mensaje_detalles = "Debe ingresar al menos un detalle para registrar el comprobante."
        return false
      }
      else if(this.comprobante.detalles.some(element => parseFloat(element.precio) == 0 || element.precio.length == 0)) {
        this.validacion_mensaje_detalles = "Se encontraron detalles con precios variables aún no especificados, por favor revisar."
        return false
      }
      else if(this.comprobante.detalles.some(element => element.codi_depe === "Centro de costos multiple")) {
        this.validacion_mensaje_detalles = "Se encontraron detalles con centro de costo indefinido, por favor revisar."
        return false
      }
      return true
    }
  },
  watch: {
    filtro: function (val) {
      this.filtro = val.trim();
    },
    concepto: function (val) {
      this.filtro = "";
    },
    nro_operacion: function () {
      this.comprobante.nro_operacion = this.nro_operacion + "-" + this.fecha_operacion;
    },
    fecha_operacion: function () {
      this.comprobante.nro_operacion = this.nro_operacion + "-" + this.fecha_operacion;
    },
    comprobante: {
      handler() {
        if (this.comprobante.tipo_pago === "Efectivo"){
          this.nro_operacion = ""
          this.fecha_operacion = ""
          this.comprobante.entidad_bancaria = null
        }
      },
      deep: true
    }
  },
  filters: {
    currency(value) {
      return value ? value.toFixed(2) : null;
    },
  },
  methods: {
    verificar() {
            if (this.validacion && this.validacion_fecha && this.fecha_operacion.length == 10){
                axios.get(`${this.app_url}/verificarNroOperacion`, {
                    params: {
                        nro_operacion: this.comprobante.nro_operacion,
                    },
                }).then((response) => {
                    if (!response.data.error) { 
                            this.error.estado = false                     
                        }
                        else {
                            this.error.estado = true
                            this.error.mensaje = response.data.errorMessage
                        }
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
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
        this.comprobante.detalles.filter(
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
          this.concepto.tipo_afectacion == "30" ? 0 : this.concepto.precio * 0.82;
        this.concepto.inafecto =
          this.concepto.tipo_afectacion == "30" ? parseFloat(this.concepto.precio) : 0;
        this.concepto.impuesto =
          this.concepto.tipo_afectacion == "30" ? 0 : this.concepto.precio * 0.18;
        this.$set(this.concepto, "subtotal", parseFloat(this.concepto.precio));
        this.comprobante.detalles.push(this.concepto);
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
      this.comprobante.detalles.splice(index, 1);
    },
    calcularSubTotal(concepto_id) {
      let objDetalle = this.comprobante.detalles.find(
        (detalle) => detalle.concepto_id === concepto_id
      );
      let subtotal_parcial = objDetalle.cantidad * objDetalle.precio;
      if (objDetalle.tipo_descuento === "soles") {
        objDetalle.subtotal = subtotal_parcial - objDetalle.descuento;
      } else if (objDetalle.tipo_descuento === "porcentaje") {
        objDetalle.subtotal =
          subtotal_parcial - (subtotal_parcial * objDetalle.descuento) / 100;
      }

      if (objDetalle.descuento == '0' || objDetalle.descuento == ''){
        objDetalle.resolucion = ''
      }

      if(objDetalle.tipo_afectacion === "30"){
        objDetalle.inafecto = objDetalle.subtotal;
        objDetalle.impuesto = 0;
        objDetalle.gravado = 0;
      }
      else{
        objDetalle.gravado = objDetalle.subtotal * 0.82;
        objDetalle.impuesto = objDetalle.subtotal * 0.18;
        objDetalle.inafecto = 0;
      }
    },
    validarEmail(email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    registrar() {
      const h = this.$createElement;
      this.show_error = false
      if(this.validacion != false && this.validacion_fecha != false && this.validacion_banco != false && this.validacion_detalles && this.validacion_tipo != false){
      if (this.comprobante.email != "") {
        if (this.validarEmail(this.comprobante.email)) {
          const messageVNode = "";
          this.$bvModal
            .msgBoxConfirm(
              [
                "¿Esta seguro de querer registrar este comprobante?",
                "\n",
                messageVNode,
              ],
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
                  .post(`${this.app_url}/comprobantes`, this.comprobante)
                  .then((response) => {
                    if (!response.data.error) {
                      this.alerta = response.data.error;
                      this.alerta_mensaje = response.data.successMessage;
                      let params =
                        "?comprobante_id=" + response.data.comprobante_id;
                      axios.get(`${this.app_url}/generar_pdf`, {
                        params: {
                          comprobante_id: response.data.comprobante_id,
                        },
                      });
                      window.open(
                        `${this.app_url}/generar_ticket/${params}`,
                        "_blank"
                      );
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
        } else {
          this.$bvModal
            .msgBoxOk("Debe ingresar un correo electronico valido", {
              title: "Correo Electronico",
              okVariant: "success",
              okTitle: "ACEPTAR",
              centered: true,
            })
            .then((value) => {})
            .catch((err) => {
              // An error occurred
            });
        }
      } else {
        // More complex structure
        const messageVNode = h("div", { class: ["foobar"] }, [
          h("p", { class: ["text-danger"] }, [
            h(
              "strong",
              "ADVERTENCIA: Al no introducir un email no se le enviara el comprobante en pdf al administrado"
            ),
          ]),
        ]);
        this.$bvModal
          .msgBoxConfirm(
            [
              messageVNode,
              "\n",
              "¿Esta seguro de querer registrar este comprobante?",
            ],
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
                .post(`${this.app_url}/comprobantes`, this.comprobante)
                .then((response) => {
                  if (!response.data.error) {
                    this.alerta = response.data.error;
                    this.alerta_mensaje = response.data.successMessage;
                    let params =
                      "?comprobante_id=" + response.data.comprobante_id;
                    axios.get(`${this.app_url}/generar_pdf`, {
                      params: {
                        comprobante_id: response.data.comprobante_id,
                      },
                    });
                    window.open(
                      `${this.app_url}/generar_ticket/${params}`,
                      "_blank"
                    );
                    this.accion = "Mostrar";
                  } else {
                    console.log("Error");
                  }
                })
                .catch(function (error) {
                  console.log(error);
                });
            }
          });
      }
      }
      else if (this.validacion_detalles) {
        this.show_error = false
      }
      else {
        this.show_error = true
      }
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
