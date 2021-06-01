<template>
  <app-layout>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item">
          <inertia-link :href="route('dependencias.iniciar')"
            >Lista de dependencias</inertia-link
          >
        </li>
        <li class="breadcrumb-item active">Mostrar dependencia</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <span class="font-weight-bold">Mostrar dependencia</span>
      </div>
      <div class="card-body">
        <b-form>
          <b-row>
            <b-col cols="2">
              <b-form-group
                id="input-group-1"
                label="Código:"
                label-for="input-1"
              >
                <b-form-input
                  id="input-1"
                  v-model="formData.codi_depe"
                  type="number"
                  placeholder="Ingrese código"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.codigo" class="text-danger">
                  {{ $page.props.errors.codigo[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="5">
              <b-form-group
                id="input-group-2"
                label="Nombre:"
                label-for="input-2"
              >
                <b-form-input
                  id="input-2"
                  v-model="formData.nomb_depe"
                  type="text"
                  placeholder="Ingrese descripción"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.descripcion" class="text-danger">
                  {{ $page.props.errors.descripcion[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="5">
              <b-form-group
                id="input-group-3"
                label="Nombre Impresion:"
                label-for="input-3"
              >
                <b-form-input
                  id="input-3"
                  v-model="formData.noms_depe"
                  type="text"
                  placeholder="Ingrese descripción corta"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div
                  v-if="$page.props.errors.descripcion_imp"
                  class="text-danger"
                >
                  {{ $page.props.errors.descripcion_imp[0] }}
                </div>
              </b-form-group>
            </b-col>
          </b-row>
          <!-- /////////////// new row /////////////////// -->
          <b-row>
            <b-col cols="2">
              <b-form-group
                id="input-group-4"
                label="Código Analitico"
                label-for="input-4"
              >
                <b-form-input
                  id="input-3"
                  v-model="formData.codi_analitico"
                  type="number"
                  :placeholder="formData.codi_analitico"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.codigo" class="text-danger">
                  {{ $page.props.errors.codigo[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="2">
              <b-form-group
                id="input-group-5"
                label="Nues:"
                label-for="input-5"
              >
                <b-form-input
                  id="input-5"
                  v-model="formData.nues_depe"
                  type="text"
                  :placeholder="formData.nues_depe"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div v-if="$page.props.errors.descripcion" class="text-danger">
                  {{ $page.props.errors.descripcion[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="2">
              <b-form-group
                id="input-group-6"
                label="Siglas:"
                label-for="input-6"
              >
                <b-form-input
                  id="input-6"
                  v-model="formData.sigl_depe"
                  type="text"
                  :placeholder="formData.sigl_depe"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div
                  v-if="$page.props.errors.descripcion_imp"
                  class="text-danger"
                >
                  {{ $page.props.errors.descripcion_imp[0] }}
                </div>
              </b-form-group>
            </b-col>
            <b-col cols="3">
              <b-form-group
                id="input-group-7"
                label="Estado:"
                label-for="input-7"
              >
                <b-form-input
                  id="input-7"
                  v-model="this.estado"
                  type="text"
                  placeholder="Ingrese descripción corta"
                  :readonly="accion == 'Mostrar'"
                ></b-form-input>
                <div
                  v-if="$page.props.errors.descripcion_imp"
                  class="text-danger"
                >
                  {{ $page.props.errors.descripcion_imp[0] }}
                </div>
              </b-form-group>
            </b-col>
          </b-row>
        </b-form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "dependencias.mostrar",
  props: ["dependencia"],
  components: {
    AppLayout,
  },
  remember: "formData",

  data() {
    return {
      app_url: this.$root.app_url,
      formData: this.dependencia,
      accion: "",
      estado: "Activo",
    };
  },
  created() {
    if (!this.formData.codi_depe) {
      this.accion = "Crear";
    } else {
      this.accion = "Mostrar";
      if (this.formData.esta_depe == "A") {
        this.estado = "Activo";
      } else if (formData.esta_depe == "I") {
        this.estado = "Inactivo";
      }
    }
  },
};
</script>
<style scoped>
fieldset {
  border-radius: 4px;
  border: 1px solid #ddd;
  background-color: #fff;
  padding-bottom: 10px;
  height: auto;
}

legend {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 600;
  padding: 3px 5px 3px 7px;
  width: auto;
}

.breadcrumb li a {
  color: blue;
}

.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
</style>
