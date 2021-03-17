<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <h1>Agregar detalles</h1>
                <b-button @click="showModal" ref="btnShow">Open Modal</b-button>

                <b-modal id="modal-1" title="Selecciona concepto">
                    <form ref="form" @submit.stop.prevent="handleSubmit">
                        <b-form-group
                        label="Concepto"
                        label-for="concepto-input"
                        invalid-feedback="Concepto is required"
                        :state="conceptoState"
                        >
                            <b-form-select v-model="selected" :options="options" class="mb-3">
                            <!-- This slot appears above the options from 'options' prop -->
                                <template #first>
                                    <b-form-select-option :value="null" disabled>-- Por favor, seleccione un concepto --</b-form-select-option>
                                </template>
                            </b-form-select>
                        </b-form-group>
                        <b-form-group
                        label="Cantidad"
                        label-for="cantidad-input"
                        invalid-feedback="Name is required"
                        :state="nameState"
                        >
                        <b-form-input
                            id="name-input"
                            v-model="name"
                            :state="nameState"
                            type="number"
                            required
                            placeholder="Ingrese cantidad"
                        ></b-form-input>
                        </b-form-group>

                    </form>
                </b-modal>
            </div>
            <div class="card-doby">
                <b-table
                    ref="tbl_detalle"
                    show-empty
                    striped
                    hover
                    bordered
                    small
                    responsive
                    stacked="md"
                    :items="myProvider"  
                    :fields="fields"
                    @filtered="onFiltered"   
                    empty-text="No hay conceptos para mostrar"
                >
                    <template v-slot:cell(acciones)="row">                        
                        <inertia-link 
                            v-if="!row.item.deleted_at"
                            class="btn btn-primary btn-sm"
                            :href="route('accesos-google.mostrar', row.item.id)"
                        >
                            <b-icon icon="eye"></b-icon>
                        </inertia-link>             
                        <b-button
                            v-if="!row.item.deleted_at"
                            variant="danger"
                            size="sm"
                            title="Eliminar"
                            @click="eliminar(row.item)"
                        >
                            <b-icon icon="trash"></b-icon>
                        </b-button>
                        <b-button
                            v-else
                            variant="success"
                            size="sm"
                            title="Restaurar"
                            @click="restaurar(row.item)"
                        >
                            <b-icon icon="check"></b-icon>
                        </b-button>
                    </template>
                </b-table>
            </div>
        </div>
    </app-layout>
</template>
<script>
export default {
    methods: {
        showModal() {
            this.$root.$emit('bv::show::modal', 'modal-1', '#btnShow')
        },
        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            this.nameState = valid
            return valid
        },
        resetModal() {
            this.name = ''
            this.nameState = null
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()
            // Trigger submit handler
            this.handleSubmit()
        },
        handleSubmit() {
            // Exit when the form isn't valid
            if (!this.checkFormValidity()) {
            return
            }
            // Push the name to submitted names
            this.submittedNames.push(this.name)
            // Hide the modal manually
            this.$nextTick(() => {
            this.$bvModal.hide('modal-prevent-closing')
            })
        }
    },
    data() {
      return {
        cantidad: '',
        cantidadState: null,

        submittedNames: [],
        selected: null,
        options: [
          { value: 'A', text: 'Pago Matricula' },
          { value: 'B', text: 'Rematricula' }
        ],

        form: {
          email: '',
          name: '',
          food: null,
          checked: []
        },
      }
    },
}
</script>