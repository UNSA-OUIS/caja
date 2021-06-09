<template>
  <app-layout>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item ml-auto">
                <inertia-link :href="route('dashboard')">Inicio</inertia-link>
            </li>
            <li class="breadcrumb-item">
                <inertia-link :href="route('puntosVenta.iniciar')">
                    Lista de puntos de venta
                </inertia-link>
            </li>
            <li class="breadcrumb-item active">
                {{ accion }} punto de venta
            </li>
        </ol>
    </nav> 
    <div class="card">
      <div class="card-header d-flex align-items-center">
          <span class="font-weight-bold">{{ accion }} punto de venta</span>
      </div>      
      <div class="card-body">
        <b-form @submit.prevent="enviar">
          <b-row>
            <b-col cols="6">
              <b-form-group
                id="input-group-6"
                label="Nombre:"
                label-for="input-6"
              >
                <b-form-input
                  id="input-6"
                  v-model="formData.nombre"
                  placeholder="Ingrese nombre"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.nombre" class="text-danger">
                  {{ $page.props.errors.nombre[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group
                id="input-group-1"
                label="Dirección:"
                label-for="input-1"
              >
                <b-form-input
                  id="input-1"
                  v-model="formData.direccion"
                  type="text"
                  placeholder="Ingrese dirección"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.direccion" class="text-danger">
                  {{ $page.props.errors.direccion[0] }}
                </div>
              </b-form-group>
            </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <b-form-group
                        id="input-group-2"
                        label="IP:"
                        label-for="input-2"
                    >
                        <b-form-input
                        id="input-2"
                        v-model="formData.ip"
                        type="text"
                        placeholder="Ingrese IP"
                        :readonly="accion == 'Mostrar'"
                        ></b-form-input>
                        <div v-if="$page.props.errors.ip" class="text-danger">
                        {{ $page.props.errors.ip[0] }}
                        </div>
                    </b-form-group>
                </b-col>
              
            <b-col>
              <b-form-group
                id="input-group-3"
                label="Usuario:"
                label-for="input-3"
              >
                <b-form-select
                  id="input-3"
                  v-model="formData.user_id"
                  :options="usuarios"
                  :disabled="accion == 'Mostrar'"
                >
                  <template v-slot:first>
                    <option :value="null" disabled>Seleccione...</option>
                  </template>
                </b-form-select>
                <div
                  v-if="$page.props.errors.user_id"
                  class="text-danger"
                >
                  {{ $page.props.errors.user_id[0] }}
                </div>
              </b-form-group>
            </b-col>
            </b-row>

          <h2 class="h4 mb-1">Asignación de conceptos</h2>
                    <p class="small text-muted font-italic mb-4">Asignación de conceptos por punto de venta.</p>
                    <b-table bordered striped hover :items="conceptos" :fields="fields">
                        <template v-slot:cell(conceptos)="row">
                            <div style="width:150px" class="custom-control custom-checkbox custom-control-inline" v-for="concepto in row.item.conceptos" :key="concepto.id">
                                <input                                     
                                    class="custom-control-input" 
                                    :id="concepto.id" 
                                    type="checkbox"
                                    v-model="formData.conceptos_asignados"                        
                                    :value="concepto.id"
                                    @change="verificarSeleccion(row.item.nombre)"
                                    :disabled="accion == 'Mostrar'"
                                >
                                <label class="cursor-pointer font-italic d-block custom-control-label" :for="concepto.id">{{ concepto.descripcion }}</label>
                            </div>
                        </template>
                        <!-- --------------- selecionar todo ------------ -->
                        <template v-slot:cell(acciones)="row">
                            <div class="custom-control custom-checkbox custom-control-inline" >
                                <input 
                                    class="custom-control-input"
                                    type="checkbox"
                                    :id=" row.item.nombre"
                                    v-model="bool_allSelecteds"
                                    :value=" row.item.nombre"
                                    :disabled="accion == 'Mostrar'"
                                    @click="seleccionarTodos( row.item.nombre,$event)"
                                >
                                <label class="cursor-pointer font-italic d-block custom-control-label" :for=" row.item.nombre">{{  row.item.acciones }}</label>
                            </div>
                        </template>
                        <!-- --------------- /selecionar todo ------------ -->
                    </b-table>
          <b-button
            v-if="accion == 'Crear'"
            @click="registrar"
            variant="success"
            >Registrar</b-button
          >
          <b-button
            v-else-if="accion == 'Mostrar'"
            @click="accion = 'Editar'"
            variant="warning"
            >Editar</b-button
          >
          <b-button
            v-else-if="accion == 'Editar'"
            @click="actualizar"
            variant="success"
            >Actualizar</b-button
          >
        </b-form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
const axios = require("axios");

export default {
  name: "puntosVenta.nuevo-mostrar",
  props: ["puntoVenta", "usuarios", "conceptos"],
  components: {
    AppLayout,
  },
  remember: "formData",
  data() {
    return {
      app_url: this.$root.app_url,
      formData: this.puntoVenta,
      accion: "",
      fields: [                
        { key: "nombre", label: "Clasificador", sortable: true, tdClass: "categoria" },
        { key: "conceptos",stickyColumn: true, label: "Conceptos" },
        { key: "acciones", label: "Seleccionar" },      
      ],
      bool_allSelecteds: [],
      total_permisos: this.conceptos.length
    };
  },
  created() {
    if (!this.formData.id) {
      this.accion = "Crear";
    } else {
      this.accion = "Mostrar";
    }
  },
  methods: {
    enviar() {
      if (this.accion == "Crear") {
        this.registrar();
      } else if (this.accion == "Mostrar") {
        this.accion = "Editar";
      } else if (this.accion == "Editar") {
        this.actualizar();
      }
    },
    registrar() {
      this.$inertia.post(route("puntosVenta.registrar"), this.formData);
    },
    actualizar() {
      this.$inertia.post(
        route("puntosVenta.actualizar", [this.formData.id]),
        this.formData
      );
    },
    seleccionarTodos(val,e){
      var clasificador = this.conceptos.findIndex(
        (clasificador) => clasificador.nombre === val
      );
      this.conceptos[clasificador].conceptos.forEach(concepto => {
        if(e.target.checked){//if is true add to permissions array
                      
          if(!this.formData.conceptos_asignados.includes(concepto.id)){
            this.formData.conceptos_asignados.push(concepto.id)
          }
        }else{
          const index = this.formData.conceptos_asignados.indexOf(concepto.id);
          if (index > -1) {
            this.formData.conceptos_asignados.splice(index, 1);
          }
        }
      })        
    },
    verificarSeleccion(val){
      var clasificador = this.conceptos.find(
        (clasificador) => clasificador.nombre === val
      );
      var count = 0
      clasificador.conceptos.forEach(concepto => {
        if (this.formData.conceptos_asignados.includes(concepto.id)) {
          count ++
        }
      })

      if(count == clasificador.conceptos.length){
        if(!this.bool_allSelecteds.includes(val))
          this.bool_allSelecteds.push(val)
      }
      else{
        const index = this.bool_allSelecteds.indexOf(val);
        if (index > -1)this.bool_allSelecteds.splice(index, 1);
      }

    },
  },
};
</script>
<style scoped>
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>
