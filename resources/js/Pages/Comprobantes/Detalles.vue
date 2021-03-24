<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link></li>                    
                    <li class="breadcrumb-item active">Registrar comprobante</li>
                </ol>
                <h1>Registrar comprobante</h1>
                
                
            </div>
            <div class="card-doby">
                <div class="container">
                    <b-form>
                        <b-row>
                            <b-col> 
                                <b-form-group id="select-g-1" label="Tipo de cliente:" label-for="select-1">
                                    <b-form-select id="select-1" v-model="tipoCliente" :options="tipos_cliente" class="mb-3">
                                        <template #first>
                                            <b-form-select-option :value="null" disabled>-- Por favor, seleccione un concepto --</b-form-select-option>
                                        </template>
                                    </b-form-select>
                                </b-form-group>
                            </b-col>
                            <b-col> 
                                <b-form-group id="input-group-8" label="DNI:" label-for="input-8">
                                    <b-form-input id="input-8" v-model="clienteDni" list="clientes" @change="getCliente($event)"></b-form-input>
                                    <datalist id="clientes">
                                    <option v-for="cliente in clientes" v-bind:key="cliente" :value="cliente.dni">{{ cliente.nombre }}</option>
                                    </datalist> 
                                </b-form-group> 
                            </b-col>
                            <b-col>
                                <b-form-group id="input-group-9" label="Código:" label-for="input-9">
                                    <b-form-input
                                        id="input-9"
                                        readonly
                                        v-model="cliente.codigo"
                                        placeholder="Código"             
                                    ></b-form-input>
                                </b-form-group> 
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <b-form-group id="input-group-4" label="Nombre:" label-for="input-4">
                                    <b-form-input
                                        id="input-4"
                                        readonly
                                        v-model="cliente.nombre"
                                        placeholder="Nombre"            
                                    ></b-form-input>
                                </b-form-group>   
                            </b-col>
                            <b-col>
                                <b-form-group id="input-group-10" label="Email:" label-for="input-10">
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
                                <b-form-group id="input-group-1" label="Código:" label-for="input-1">
                                    <b-form-input
                                        id="input-1"
                                        v-model="compCabe.codigo"
                                        placeholder="Código"             
                                    ></b-form-input>
                                </b-form-group> 
                            </b-col>
                            <b-col> 
                                <b-form-group id="input-group-5" label="NUES:" label-for="input-5">
                                    <b-form-input
                                        id="input-5"
                                        v-model="compCabe.nues"
                                        placeholder="NUES"              
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <b-form-group id="input-group-6" label="Serie:" label-for="input-6">
                                    <b-form-input
                                        id="input-6"
                                        v-model="compCabe.serie"
                                        placeholder="Serie"              
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group id="input-group-7" label="Correlativo:" label-for="input-7">
                                    <b-form-input
                                        id="input-7"
                                        v-model="compCabe.correlativo"
                                        placeholder="Correlativo"              
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-form>  
                </div>

                <div class="container">
                    <b-button @click="showModal" ref="btnShow">Búsqueda avanzada de conceptos</b-button>
                    <b-button @click="addDetalle" ref="btnAdd">Añadir concepto</b-button>
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
                            <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
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
                                    <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
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
                    :items="options"  
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
                    :items="compCabe.submittedDetails"  
                    :fields="fields"   
                    empty-text="No hay conceptos para mostrar"
                >
                    <template v-slot:cell(codigo)="row">
                        <label>{{ row.item.codigo }}</label>
                    </template>
                    <template v-slot:cell(concepto)="row">
                        <!--<div>
                            <b-form-input list="conceptos" @change="completeConcepto(option, row.item.id)"></b-form-input>
                            <datalist id="conceptos">
                            <option v-for="option in options" v-bind:key="option" >{{ option.text }}</option>
                            </datalist>                          
                        </div>-->
                        <b-form-select @change="completeConcepto($event, row.item.id)" class="mb-3">
                            <!-- This slot appears above the options from 'options' prop -->
                                <template #first>
                                    <b-form-select-option :value="null" disabled>-- Por favor, seleccione un concepto --</b-form-select-option>
                                    <b-form-select-option v-for="option in options" v-bind:key="option" :value="option.value" >{{ option.text }}</b-form-select-option>
                                </template>
                            </b-form-select>
                    </template>
                    <template v-slot:cell(cantidad)="row">
                        <div>
                            <b-form-input id="cantidad" v-model="row.item.cantidad" type="number" ></b-form-input>
                        </div>
                    </template>
                    <template v-slot:cell(prUnit)="row">
                        <b-form-input id="prUnit" v-model="row.item.prUnit" type="number"  readonly :value="row.item.prUnit"></b-form-input>
                    </template>

                    <template v-slot:cell(descuento)="row"> 
                        <div>
                            <b-form-select :options="tipo_descuento" v-model="row.item.tipo_descuento"></b-form-select>
                            <b-form-input id="descuento" @change="calcularDescuento($event, row.item.id)" type="number" value="0.00"></b-form-input>
                        </div>
                    </template>

                    <template v-slot:cell(subTotal)="row">
                        <div>
                            <label>S/. {{ row.item.prUnit * row.item.cantidad - row.item.descuento | currency }}</label>
                        </div>
                        
                    </template>

                    <template v-slot:cell(acciones)="row">                                     
                        <b-button
                            v-if="!row.item.deleted_at"
                            variant="danger"
                            size="sm"
                            title="Eliminar"
                            @click="eliminar(row.item.id)"
                        >
                            <b-icon icon="trash"></b-icon>
                        </b-button>
                    </template>
                    <template slot="bottom-row" slot-scope="data">
                        <b-td/><b-td/><b-td/><b-td/><b-td>Total</b-td>
                        <b-td>S/.{{ total | currency }}</b-td><b-td/>
                    </template>
                </b-table>
                </div>
                <div class="container">
                    <b-button ref="btnAdd">Registrar</b-button>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
    name: "comprobante.registrar",
    
    components: {
            AppLayout,                      
        },
    methods: {
        showModal() {
            this.$root.$emit('bv::show::modal', 'modal-1', '#btnShow')
        },
        addDetalle() {
            this.compCabe.submittedDetails.push({
                "id": this.id++,
                "codigo": '',
                "concepto": '',
                "cantidad": '1',
                "prUnit": '',
                'tipo_descuento': '',
                "descuento": '0.00',
            });
        },
        agregarConcepto(conc) {
            this.compCabe.submittedDetails.push({
                "id": this.id++,
                "codigo": conc.value,
                "concepto": conc.text,
                "cantidad": '1',
                "prUnit": conc.precio,
                'tipo_descuento': '',
                "descuento": '0.00',
            });
        },
        eliminar(id) {
            var index = this.compCabe.submittedDetails.findIndex(detalle => detalle.id == id);
            this.compCabe.submittedDetails.splice(index, 1);
        },
        completeConcepto(event, id){
            var index = this.compCabe.submittedDetails.findIndex(detalle => detalle.id == id);
            var conc = this.options.find(option => option.value == event);
            this.compCabe.submittedDetails[index].codigo = conc.value;
            this.compCabe.submittedDetails[index].prUnit = conc.precio;
        },
        calcularDescuento(event, id) {
            var index = this.compCabe.submittedDetails.findIndex(detalle => detalle.id == id);
            var conc = this.compCabe.submittedDetails[index].tipo_descuento;
            if (conc == 'A'){
                this.compCabe.submittedDetails[index].descuento = (this.compCabe.submittedDetails[index].prUnit * this.compCabe.submittedDetails[index].cantidad) * event/100;
            }
            else if (conc == 'B'){
                this.compCabe.submittedDetails[index].descuento = event;
            }
            else{
                this.compCabe.submittedDetails[index].descuento = 0;
            }

        },
        getCliente(dni) {
            var cli = this.clientesCompleto.find(cliente => cliente.dni == dni);
            this.cliente.dni = cli.dni;
            this.compCabe.cui = cli.dni;
            this.cliente.nombre = cli.nombre;
            this.cliente.email = cli.email;
            this.cliente.codigo = cli.codigo;
        },
    },
    filters: {
        currency(value) {
        return value.toFixed(2);
        }
    },
    computed: {
        /*getCliente() {
            var cli = this.clientesCompleto.find(cliente => cliente.dni == clienteDni);
            this.cliente.dni = cli.dni;
            this.compCabe.cui = cli.dni;
            this.cliente.nombre = cli.nombre;
            this.cliente.email = cli.email;
            this.cliente.codigo = cli.codigo;
        },*/

        clientes(){
            if ( this.tipoCliente == 'A' ){
                return this.clientesCompleto.filter(cliente => cliente.tipo == 'Alumno');
            }
            else if (this.tipoCliente == 'B'){
                return this.clientesCompleto.filter(cliente => cliente.tipo == 'Docente');
            }
            else if (this.tipoCliente == 'C'){
                return this.clientesCompleto.filter(cliente => cliente.tipo == 'Particular');
            }
            return [];
        },
        
        total() {
            this.compCabe.total = this.compCabe.submittedDetails.reduce(
                (acc, item) => acc + (item.cantidad * item.prUnit - item.descuento),
                0
            );
            return this.compCabe.total;
        }
    },
    data() {
      return {
        tipoDescuento: '',
        tipoCliente: '',
        clienteDni: '',
        id: 1,
        cantidadState: null,

        compCabe: {
            "codigo": '',
            "cui": '',
            "nues": '',
            "serie": '',
            "correlativo": '',
            submittedDetails: [{
                "id": 0,
                "codigo": '',
                "concepto": '',
                "cantidad": '1',
                "prUnit": '',
                'tipo_descuento': '',
                "descuento": '0.00',
            }],
            "total": '',
        },

        conceptosFields: [
                    { key: "value", label: "Código", sortable: true, class: "text-center" },
                    { key: "text", label: "Descripción", sortable: true },    
                    { key: "precio", label: "Precio", class: "text-center" },
                    { key: "anadir", label: "Añadir", class: "text-center" },
                ],
        index: 1,
        totalRows: 1,
        currentPage: 1,
        perPage: 5,
        pageOptions: [5, 10, 15],
        sortBy: null,
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,  
        
        options: [
          { value: '123', text: 'Pago Matricula', precio: '10.00' },
          { value: '10', text: 'Rematricula', precio: '12.00' },
          { value: '11', text: 'Rematricula 2', precio: '15.00' },
          { value: '12', text: 'Rematricula 3', precio: '20.00' },
        ],
        tipo_descuento: [
          { value: 'A', text: '%' },
          { value: 'B', text: 'S/.' }
        ],
        tipos_cliente: [
          { value: 'A', text: 'Alumno' },
          { value: 'B', text: 'Docente' },
          { value: 'C', text: 'Particulares' },
        ],
        tipos_cliente: [
          { value: 'A', text: 'Alumno' },
          { value: 'B', text: 'Docente' },
          { value: 'C', text: 'Particular' },
        ],
        clientesCompleto: [
            {dni: '77654321', nombre: 'Carlos Duarte', email: 'cduarte@unsa.edu.pe', codigo: '20167384', tipo: 'Alumno'},
            {dni: '72345678', nombre: 'Claudia Chaud', email: 'cchaud@unsa.edu.pe', codigo: '201385', tipo: 'Alumno'},
            {dni: '76736251', nombre: 'Aracely Zeballos', email: 'azeballos@unsa.edu.pe', codigo: '20124343', tipo: 'Docente'},
            {dni: '74637281', nombre: 'Martin Zegarra', email: 'mzegarra@unsa.edu.pe', codigo: '20024367', tipo: 'Docente'},
            {dni: '78462749', nombre: 'Alfredo Zarate', email: 'azarate@unsa.edu.pe', codigo: '', tipo: 'Particular'},
            {dni: '74235656', nombre: 'Maria Cuadros', email: 'mcuadros@unsa.edu.pe', codigo: '', tipo: 'Particular'},
        ],
        cliente: {
            dni: '',
            email: '',
            codigo: '',
            nombre: ''
        },
        fields: [
                    { key: "codigo", label: "CÓDIGO", class: "text-center" },
                    { key: "concepto", label: "CONCEPTO", class: "text-center" },
                    { key: "cantidad", label: "CANTIDAD", class: "text-center" },
                    { key: "prUnit", label: "PR. UNIT", class: "text-center" },    
                    { key: "descuento", label: "DESCUENTO", class: "text-center" },              
                    { key: "subTotal", label: "SUB TOTAL", class: "text-right" },              
                    { key: "acciones", label: "ACCIONES", class: "text-center" },
                ],
      }
    },
}
</script>