<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-4 border border-light">
                <label class="text-warning">Tipo de nota:</label>
                <b-form-select id="input-2" v-model="comprobante.tipo_nota" :options="tipos_de_nota" >                   
                    <template v-slot:first>
                        <option :value="null" disabled>Seleccione...</option>
                    </template>
                </b-form-select>
            </div>                      
            <div class="form-group col-md-8 border border-light">
                <label class="text-warning">Motivo o sustento:</label>
                <b-form-input id="input-3" v-model="comprobante.motivo" type="text" placeholder="Ingrese motivo"></b-form-input>
            </div>                  
        </div>  
        <div class="card-header d-flex align-items-center">
            <span class="font-weight-bold">Documento a modificar:</span>
        </div>   
        <div class="form-row">
            <div class="form-group col-md-4 border border-light">
                <label class="text-info">Boleta electrónica:</label>
                <label class="lbl-data" v-text="data.comprobante.serie + '-' + data.comprobante.correlativo"></label>
            </div>                      
            <div class="form-group col-md-4 border border-light">
                <label class="text-info">Cliente:</label>
                <label v-if="data.comprobante.tipo_usuario === 'alumno'" class="lbl-data" v-text="reemplazar(data.comprobante.comprobanteable.apn)"></label>
                <label v-else-if="data.comprobante.tipo_usuario === 'empresa'" class="lbl-data" v-text="data.comprobante.comprobanteable.razon_social"></label>
                <label v-else-if="data.comprobante.tipo_usuario === 'particular'" class="lbl-data" v-text="data.comprobante.comprobanteable.nombres"></label>
                <label v-else-if="data.comprobante.tipo_usuario === 'docente'" class="lbl-data" v-text="reemplazar(data.comprobante.comprobanteable.apn)"></label>
                <label v-else-if="data.comprobante.tipo_usuario === 'dependencia'" class="lbl-data" v-text="data.comprobante.comprobanteable.nomb_depe"></label>
            </div> 
            <div class="form-group col-md-4 border border-light">
                <label class="text-info">Importe total:</label>
                <label class="lbl-data" v-text="data.comprobante.total + ' NUEVOS SOLES'"></label>
            </div>                  
        </div>
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
                :items="data.comprobante.detalles"
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
                <template v-slot:cell(subTotal)="row">
                    <span class="font-weight-bold">
                    S/. {{ row.item.subtotal }}
                    </span>
                </template>
                <template slot="bottom-row" slot-scope="">
                    <b-td /><b-td />
                    <b-td class="text-right font-weight-bold">TOTAL</b-td>
                    <b-td class="text-right font-weight-bold">S/. {{ data.comprobante.total }}</b-td>
                </template>
                </b-table>
            </b-col>
            </b-row>
            <div>
                <b-button variant="success" @click="registrar()">Registrar</b-button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "comprobantes.cabecera-nota",
    props: ["comprobante", "data"],    
    data() {
        return {
            app_url: this.$root.app_url,
            tipos_de_nota: [],
            tipos_nota_debito: [
                {value: '01', text: 'Interés por mora'},
                {value: '02', text: 'Aumento en el valor'},
                {value: '03', text: 'Penalidades'},
            ],
            tipos_nota_credito: [
                {value: '01', text: 'Anulación por operación'},
                {value: '02', text: 'Anulación por error en el RUC'},
                {value: '03', text: 'Corrección por error en la descripción'},
                {value: '04', text: 'Descuento global'},
                {value: '05', text: 'Descuento por ítem'},
                {value: '06', text: 'Devolución total'},
                {value: '07', text: 'Devolución por ítem'},
                {value: '08', text: 'Bonificación'},
                {value: '09', text: 'Disminución en el valor'},
                {value: '10', text: 'Otros conceptos'},
            ],
            fields: [
                { key: "cantidad", label: "CANTIDAD", class: "text-center" },
                { key: "concepto.descripcion", label: "CONCEPTO", class: "text-center", tdClass: "concepto" },
                { key: "concepto.precio", label: "COSTO", class: "text-center" },
                { key: "subTotal", label: "SUBTOTAL", class: "text-right" },
            ],
        };
    },
    created() {
        if (this.data.tipo_comprobante === 'NOTA_DEBITO'){
            this.tipos_de_nota = this.tipos_nota_debito   
        }
        else if (this.data.tipo_comprobante === 'NOTA_CREDITO'){
            this.tipos_de_nota = this.tipos_nota_credito   
        }
    },
    methods: {
        reemplazar(nombre){
            return nombre.replace('/', ' ')
        },
        registrar() {
            this.$bvModal.msgBoxConfirm("¿Esta seguro de querer registrar esta nota?", {
                title: "Enviar nota",
                okVariant: "success",
                okTitle: "SI",
                cancelVariant: "danger",
                cancelTitle: "NO",
                centered: true,
            }).then((value) => {
                if (value) {
                    this.$inertia.post(route("comprobantes.registrar_nota"), this.comprobante);
                }
            });
        },
    }
};
</script>
<style scoped>
    .lbl-data {
        text-align: center;
        border: 0;
        padding: 0;        
        display: block;
        width: 100%;
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 400;
    }    
</style>