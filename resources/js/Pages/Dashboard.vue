<template>
    <app-layout>
        <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-screen-75">
        <div
          class="absolute top-0 w-full h-full bg-center bg-cover"
          :style="`background-image: url('${api_url}/img/fondofachada.jpeg')`"
        >
          <span
            id="blackOverlay"
            class="w-full h-full absolute opacity-75 bg-black"
          ></span>
        </div>
        <div class="container relative mx-auto">
          <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
              <div class="pr-12">
                <h1 class="text-white font-semibold text-5xl">
                  Ahora es más facil realizar tus pagos.
                </h1>
                <p class="mt-4 text-lg text-gray-300">
                  Conoce la forma de realizar los pagos a través de las siguientes instrucciones.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div
          class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px"
          style="transform: translateZ(0);"
        >
          <svg
            class="absolute bottom-0 overflow-hidden"
            xmlns="http://www.w3.org/2000/svg"
            preserveAspectRatio="none"
            version="1.1"
            viewBox="0 0 2560 100"
            x="0"
            y="0"
          >
            <polygon
              class="text-gray-300 fill-current"
              points="2560 0 2560 100 0 100"
            ></polygon>
          </svg>
        </div>
      </div>

        <div class="flex flex-wrap">
            <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
              <div
                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg"
              >
                <div class="px-4 py-5 flex-auto">
                  <div
                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-red-400"
                  >
                    <i class="fas fa-award">1</i>
                  </div>
                  <h6 class="text-xl font-semibold">Seleccionar Opciones</h6>
                  <p class="mt-2 mb-4 text-gray-600">
                    Dentro de la plataforma, para obtener su código bancario vaya a la sección "Obtener código bancario" seleccione su escuela y el tipo de pago.
                  </p>
                </div>
              </div>
            </div>

            <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
              <div
                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg"
              >
                <div class="px-4 py-5 flex-auto">
                  <div
                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-blue-400"
                  >
                    <i class="fas fa-retweet">2</i>
                  </div>
                  <h6 class="text-xl font-semibold">Agente BCP/App BCP</h6>
                  <p class="mt-2 mb-4 text-gray-600">
                    Dirígete al agente bcp más cercano o ingresa a la App BCP, luego
                    indique o busque, el nombre de la cuenta: "UNSA VIRTUAL".
                  </p>
                </div>
              </div>
            </div>

            <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
              <div
                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg"
              >
                <div class="px-4 py-5 flex-auto">
                  <div
                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-green-400"
                  >
                    <i class="fas fa-fingerprint">3</i>
                  </div>
                  <h6 class="text-xl font-semibold">Código Bancario</h6>
                  <p class="mt-2 mb-4 text-gray-600">
                    Indique el código obtenido a través de UNSApay (Si el pago lo realiza el mismo día de obtención del código el nombre que podría figurar en el Banco sería: Alumno UNSA.)
                  </p>
                </div>
              </div>
            </div>
          </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'

    export default {
        components: {
            AppLayout,
            Welcome,
        },
        data() {
          return {
            api_url: this.$root.api_url,  
          }
        }
    }
</script>
