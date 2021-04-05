<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`"
                            >Inicio</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('comprobantes.iniciar')"
                            >Lista de comprobantes</inertia-link
                        >
                    </li>
                </ol>
            </div>
            <div class="card-doby">
                <div class="container">
                    <b-form>
                        <b-row>
                            <b-col>
                                <b-form-group
                                    id="select-g-1"
                                    label="Tipo de cliente:"
                                    label-for="select-1"
                                >
                                    <b-form-select
                                        id="select-1"
                                        v-model="tipoCliente"
                                        :options="tipos_cliente"
                                        class="mb-3"
                                    >
                                        <template #first>
                                            <b-form-select-option
                                                :value="null"
                                                disabled
                                                >-- Por favor, seleccione un
                                                concepto
                                                --</b-form-select-option
                                            >
                                        </template>
                                    </b-form-select>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group
                                    id="input-group-8"
                                    label="DNI:"
                                    label-for="input-8"
                                >
                                    <b-form-input
                                        id="input-8"
                                        v-model="clienteDni"
                                        list="clientes"
                                        @change="getCliente($event)"
                                    ></b-form-input>
                                    <datalist id="clientes">
                                        <option
                                            v-for="cliente in clientes"
                                            :value="cliente.dni"
                                            >{{ cliente.nombre }}</option
                                        >
                                    </datalist>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group
                                    id="input-group-9"
                                    label="CUI:"
                                    label-for="input-9"
                                >
                                    <b-form-input
                                        id="input-9"
                                        v-model="comprobante.cui"
                                        placeholder="Cui"
                                        :readonly="accion == 'Mostrar'"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <b-form-group
                                    id="input-group-4"
                                    label="Nombre:"
                                    label-for="input-4"
                                >
                                    <b-form-input
                                        id="input-4"
                                        readonly
                                        v-model="cliente.nombre"
                                        placeholder="Nombre"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group
                                    id="input-group-10"
                                    label="Email:"
                                    label-for="input-10"
                                >
                                    <b-form-input
                                        id="input-10"
                                        readonly
                                        v-model="cliente.email"
                                        placeholder="Email"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <b-form-group
                                    id="input-group-1"
                                    label="Código:"
                                    label-for="input-1"
                                >
                                    <b-form-input
                                        id="input-1"
                                        v-model="comprobante.codigo"
                                        placeholder="Código"
                                        :readonly="accion == 'Mostrar'"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group
                                    id="input-group-5"
                                    label="NUES:"
                                    label-for="input-5"
                                >
                                    <b-form-input
                                        id="input-5"
                                        v-model="comprobante.nues"
                                        placeholder="NUES"
                                        :readonly="accion == 'Mostrar'"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <b-form-group
                                    id="input-group-6"
                                    label="Serie:"
                                    label-for="input-6"
                                >
                                    <b-form-input
                                        id="input-6"
                                        v-model="comprobante.serie"
                                        placeholder="Serie"
                                        :readonly="accion == 'Mostrar'"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group
                                    id="input-group-7"
                                    label="Correlativo:"
                                    label-for="input-7"
                                >
                                    <b-form-input
                                        id="input-7"
                                        v-model="comprobante.correlativo"
                                        placeholder="Correlativo"
                                        :readonly="accion == 'Mostrar'"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-form>
                </div>

                <div class="container">
                    <b-button @click="showModal" ref="btnShow"
                        >Búsqueda avanzada de conceptos</b-button
                    >
                    <b-button @click="addDetalle" ref="btnAdd"
                        >Añadir concepto</b-button
                    >
                </div>
                <!-- Modal nueva opcion para agregar conceptos -->
                <b-modal id="modal-1" title="Selecciona concepto">
                    <b-row>
                        <b-col sm="12" md="4" lg="4" class="my-1">
                            <b-form-group
                                label="Registros por página: "
                                label-cols-sm="6"
                                label-align-sm="right"
                                label-size="sm"
                                label-for="perPageSelect"
                                class="mb-0"
                            >
                                <b-form-select
                                    v-model="perPage"
                                    id="perPageSelect"
                                    size="sm"
                                    :options="pageOptions"
                                ></b-form-select>
                            </b-form-group>
                        </b-col>
                        <b-col sm="12" offset-md="3" md="5" lg="5" class="my-1">
                            <b-form-group
                                label="Buscar: "
                                label-cols-sm="3"
                                label-align-sm="right"
                                label-size="sm"
                                label-for="filterInput"
                                class="mb-0"
                            >
                                <b-input-group size="sm">
                                    <b-form-input
                                        v-model="filter"
                                        type="search"
                                        id="filterInput"
                                        placeholder="Escriba el texto a buscar..."
                                    ></b-form-input>
                                    <b-input-group-append>
                                        <b-button
                                            :disabled="!filter"
                                            @click="filter = ''"
                                            >Limpiar</b-button
                                        >
                                    </b-input-group-append>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-table
                        ref="tbl_conceptos"
                        show-empty
                        striped
                        hover
                        bordered
                        small
                        responsive
                        stacked="md"
                        :items="conceptosDisponibles"
                        :fields="conceptosFields"
                        :current-page="currentPage"
                        :per-page="perPage"
                        :filter="filter"
                        :sort-by.sync="sortBy"
                        :sort-desc.sync="sortDesc"
                        :sort-direction="sortDirection"
                        @filtered="onFiltered"
                        empty-text="No hay conceptos para mostrar"
                        empty-filtered-text="No hay conceptos que coincidan con su búsqueda."
                        
                    >
                        <template v-slot:cell(anadir)="row">
                            <b-button
                                variant="success"
                                size="sm"
                                title="Restaurar"
                                @click="agregarConcepto(row.item)"
                            >
                                <b-icon icon="check"></b-icon>
                            </b-button>
                        </template>
                    </b-table>
                    <b-row>
                        <b-col offset-md="8" md="4" class="my-1">
                            <b-pagination
                                v-model="currentPage"
                                :total-rows="totalRows"
                                :per-page="perPage"
                                align="fill"
                                size="sm"
                                class="my-0"
                            ></b-pagination>
                        </b-col>
                    </b-row>
                </b-modal>

                <div>
                    <b-table
                        ref="tbl_detalle"
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
                            <label>{{ row.item.codigo }}</label>
                        </template>
                        <template v-slot:cell(concepto)="row">
                            <!--<b-form-select v-model="row.item.concepto_id" @change="completeConcepto($event, row.item.id)" :options="conceptos"></b-form-select>-->
                            <b-form-select
                                v-model="row.item.concepto_id"
                                @change="completeConcepto($event, row.item.concepto_id)"
                                class="mb-3"
                                :disabled="row.item.concepto_id != ''"
                            >
                                <!-- This slot appears above the options from 'options' prop -->
                                <template #first>
                                    <b-form-select-option :value="null" disabled
                                        >-- Por favor, seleccione un concepto
                                        --</b-form-select-option
                                    >
                                    <b-form-select-option
                                        v-for="option in conceptos"
                                        v-bind:key="option.id"
                                        :value="option.id"
                                        :disabled="!option.estado"
                                        >{{ option.text }}</b-form-select-option
                                    >
                                </template>
                            </b-form-select>
                        </template>
                        <template v-slot:cell(cantidad)="row">
                            <div>
                                <b-form-input
                                    id="cantidad"
                                    v-model="row.item.cantidad"
                                    type="number"
                                ></b-form-input>
                            </div>
                        </template>
                        <template v-slot:cell(valor_unitario)="row">
                            <b-form-input
                                id="valor_unitario"
                                v-model="row.item.valor_unitario"
                                type="number"
                                readonly
                                :value="row.item.valor_unitario"
                            ></b-form-input>
                        </template>

                        <template v-slot:cell(descuento)="row">
                            <div>
                                <b-form-select
                                    :options="tipo_descuento"
                                    v-model="row.item.tipo_descuento"
                                ></b-form-select>
                                <b-form-input
                                    id="descuento"
                                    @change="
                                        calcularDescuento($event, row.item.concepto_id)
                                    "
                                    type="number"
                                    value="0.00"
                                ></b-form-input>
                            </div>
                        </template>

                        <template v-slot:cell(subTotal)="row">
                            <div>
                                <label
                                    >S/.
                                    {{
                                        (row.item.valor_unitario * row.item.cantidad -
                                            row.item.descuento)
                                            | currency
                                    }}</label
                                >
                            </div>
                        </template>

                        <template v-slot:cell(acciones)="row">
                            <b-button
                                v-if="!row.item.deleted_at"
                                variant="danger"
                                size="sm"
                                title="Eliminar"
                                @click="eliminar(row.item.concepto_id)"
                            >
                                <b-icon icon="trash"></b-icon>
                            </b-button>
                        </template>
                        <template slot="bottom-row" slot-scope="">
                            <b-td /><b-td /><b-td /><b-td /><b-td>Total</b-td>
                            <b-td>S/.{{ precioTotal | currency }}</b-td
                            ><b-td />
                        </template>
                    </b-table>
                </div>
                <div v-show="accion == 'Crear'" class="container">
                    <b-button
                        variant="success"
                        size="sm"
                        title="Registrar"
                        @click="registrar()"
                    >
                        Registrar<b-icon icon="check"></b-icon>
                    </b-button>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "comprobantes.mostrar",
    props: ["comprobante", "conceptos"],
    components: {
        AppLayout
    },

    data() {
        return {
            app_url: this.$root.app_url,
            accion: "",

            tipoDescuento: "",
            tipoCliente: "",
            clienteDni: "",
            cantidadState: null,

            /*comprobante: {
                codigo: "",
                cui: "",
                nues: "",
                serie: "",
                correlativo: "",
                submittedDetails: [
                    {
                        codigo: "",
                        concepto_id: "",
                        cantidad: "1",
                        valor_unitarios: "",
                        tipo_descuento: "",
                        descuento: "0.00"
                    }
                ],
                total: ""
            },*/

            conceptosFields: [
                {
                    key: "value",
                    label: "Código",
                    sortable: true,
                    class: "text-center"
                },
                { key: "text", label: "Descripción", sortable: true },
                { key: "precio", label: "Precio", class: "text-center" },
                { key: "anadir", label: "Añadir", class: "text-center" }
            ],
            index: 1,
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],
            sortBy: null,
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            tipo_descuento: [
                { value: "A", text: "%" },
                { value: "B", text: "S/." }
            ],
            tipos_cliente: [
                { value: "A", text: "Alumno" },
                { value: "B", text: "Docente" },
                { value: "C", text: "Particular" }
            ],
            clientesCompleto: [
                {
                    dni: "77654321",
                    nombre: "Carlos Duarte",
                    email: "cduarte@unsa.edu.pe",
                    codigo: "20167384",
                    tipo: "Alumno"
                },
                {
                    dni: "72345678",
                    nombre: "Claudia Chaud",
                    email: "cchaud@unsa.edu.pe",
                    codigo: "20136858",
                    tipo: "Alumno"
                },
                {
                    dni: "76736251",
                    nombre: "Aracely Zeballos",
                    email: "azeballos@unsa.edu.pe",
                    codigo: "20124343",
                    tipo: "Docente"
                },
                {
                    dni: "74637281",
                    nombre: "Martin Zegarra",
                    email: "mzegarra@unsa.edu.pe",
                    codigo: "20024367",
                    tipo: "Docente"
                },
                {
                    dni: "78462749",
                    nombre: "Alfredo Zarate",
                    email: "azarate@unsa.edu.pe",
                    codigo: "",
                    tipo: "Particular"
                },
                {
                    dni: "74235656",
                    nombre: "Maria Cuadros",
                    email: "mcuadros@unsa.edu.pe",
                    codigo: "",
                    tipo: "Particular"
                }
            ],
            cliente: {
                dni: "",
                email: "",
                codigo: "",
                nombre: ""
            },
            fields: [
                { key: "codigo", label: "CÓDIGO", class: "text-center" },
                { key: "concepto", label: "CONCEPTO", class: "text-center" },
                { key: "cantidad", label: "CANTIDAD", class: "text-center" },
                { key: "valor_unitario", label: "PR. UNIT", class: "text-center" },
                { key: "descuento", label: "DESCUENTO", class: "text-center" },
                { key: "subTotal", label: "SUB TOTAL", class: "text-right" },
                { key: "acciones", label: "ACCIONES", class: "text-center" }
            ]
        };
    },
    created() {
        if (!this.comprobante.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }
    },
    methods: {
        showModal() {
            this.$root.$emit("bv::show::modal", "modal-1", "#btnShow");
        },
        registrar() {
            this.$bvModal
                .msgBoxConfirm(
                    "¿Esta seguro de querer enviar este comprobante?",
                    {
                        title: "Enviar Comprobante",
                        okVariant: "success",
                        okTitle: "SI",
                        cancelVariant: "danger",
                        cancelTitle: "NO",
                        centered: true
                    }
                )
                .then(async value => {
                    if (value) {
                        this.$inertia.post(
                            route("comprobantes.registrar", this.comprobante)
                        );
                    }
                });
        },
        addDetalle() {
            this.comprobante.detalles.push({
                codigo: "",
                concepto_id: "",
                cantidad: "1",
                valor_unitario: "",
                tipo_descuento: "",
                descuento: "0.00"
            });
        },
        agregarConcepto(conc) {
            this.comprobante.detalles.push({
                codigo: conc.value,
                concepto_id: conc.id,
                cantidad: "1",
                valor_unitario: conc.precio,
                tipo_descuento: "",
                descuento: "0.00"
            });
            var concIndex = this.conceptos.findIndex(option => option.id == conc.id);
            this.conceptos[concIndex].estado = false;
        },
        eliminar(id) {
            var index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            var concIndex = this.conceptos.findIndex(option => option.id == id);
            this.conceptos[concIndex].estado = true;

            this.comprobante.detalles.splice(index, 1);
        },
        completeConcepto(event, id) {
            var index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            var conc = this.conceptos.find(option => option.id == event);
            var concIndex = this.conceptos.findIndex(option => option.id == event);
            this.conceptos[concIndex].estado = false;
            this.comprobante.detalles[index].concepto_id = conc.id;
            this.comprobante.detalles[index].codigo = conc.value;
            this.comprobante.detalles[index].valor_unitario = conc.precio;
            this.comprobante.detalles[index].descuento = "0.00";
        },
        calcularDescuento(event, id) {
            var index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            var conc = this.comprobante.detalles[index].tipo_descuento;
            if (conc == "A") {
                this.comprobante.detalles[index].descuento =
                    (this.comprobante.detalles[index].valor_unitario *
                        this.comprobante.detalles[index].cantidad *
                        event) /
                    100;
            } else if (conc == "B") {
                this.comprobante.detalles[index].descuento = event;
            } else {
                this.comprobante.detalles[index].descuento = 0;
            }
        },
        getCliente(dni) {
            var cli = this.clientesCompleto.find(cliente => cliente.dni == dni);
            this.cliente.dni = cli.dni;
            this.comprobante.cui = cli.dni;
            this.cliente.nombre = cli.nombre;
            this.cliente.email = cli.email;
            this.cliente.codigo = cli.codigo;
        }
    },
    filters: {
        currency(value) {
            return value.toFixed(2);
        }
    },
    computed: {
        clientes() {
            if (this.tipoCliente == "A") {
                return this.clientesCompleto.filter(
                    cliente => cliente.tipo == "Alumno"
                );
            } else if (this.tipoCliente == "B") {
                return this.clientesCompleto.filter(
                    cliente => cliente.tipo == "Docente"
                );
            } else if (this.tipoCliente == "C") {
                return this.clientesCompleto.filter(
                    cliente => cliente.tipo == "Particular"
                );
            }
            return [];
        },

        precioTotal() {
            this.comprobante.total = this.comprobante.detalles.reduce(
                (acc, item) =>
                    acc + (item.cantidad * item.valor_unitario - item.descuento),
                0
            );
            return this.comprobante.total;
        },

        conceptosDisponibles(){
            return this.conceptos.filter(option => option.estado == true);
        }
    }
};
</script>
