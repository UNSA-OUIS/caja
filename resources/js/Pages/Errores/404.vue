<template>
            <div class="mainbox">
                <div class="err">4</div>
                <i class="far fa-question-circle fa-spin"></i>
                <div class="err2">4</div>
                <div class="msg">No eres tú, somos nosotros. Tal vez esta página fue movida, se eliminó, se escondió por cuarentena o nunca existió.<p>Vamos a la <inertia-link :href="route('dashboard')">página principal</inertia-link> y prueba nuevamente.</p></div>
            </div>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
export default {
  props: {
    status: Number,
  },
  components: {
        AppLayout
    },
  /*computed: {
    title() {
      return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
      }[this.status]
    },
    description() {
      return {
        503: 'Lo lamentamos, estamos en plena obra de construcción por aquí. Por favor, vuelva a intentar en unos minutos.',
        500: 'No eres tú, ahora son nuestros servidores.',
        404: 'No eres tú, somos nosotros. Tal vez esta página fue movida, se eliminó, se escondió por cuarentena o nunca existió.',
        403: 'Lo lamentamos, al parecer las altas esferas te impiden ingresar a esta página.',
      }[this.status]
    },
  },*/
}
</script>
<style>
.mainbox {
  margin: auto;
  height: 600px;
  width: 600px;
  position: relative;
}

  .err {
    color: #2B2E3B;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 11rem;
    position:absolute;
    left: 20%;
    top: 8%;
  }

.far {
  position: absolute;
  font-size: 8.5rem;
  left: 42%;
  top: 15%;
  color: #2B2E3B;
}

 .err2 {
    color: #2B2E3B;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 11rem;
    position:absolute;
    left: 68%;
    top: 8%;
  }

.msg {
    text-align: center;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 1.6rem;
    position:absolute;
    left: 16%;
    top: 45%;
    width: 75%;
  }

a {
  text-decoration: none;
  color: #5B89EE;
}

a:hover {
  text-decoration: underline;
}
</style>