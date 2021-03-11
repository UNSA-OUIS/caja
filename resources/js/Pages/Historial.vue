<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Historial de códigos generados
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- component -->
					<div>
						<div class="flex flex-col max-w-full overflow-x-hidden shadow-md m-8">							
							<table class="overflow-x-auto w-full bg-white">
								<thead class="bg-blue-100 border-b border-gray-300">
									<tr>
										<th class="p-4 text-left text-sm font-medium text-gray-500">Código</th>
										<th class="p-4 text-left text-sm font-medium text-gray-500">Escuela</th>
										<th class="p-4 text-left text-sm font-medium text-gray-500">Tipo de pago</th>
										<th class="p-4 text-left text-sm font-medium text-gray-500">Fecha vencimiento</th>
										<th class="p-4 text-left text-sm font-medium text-gray-500">Estado</th>																														
										<th class="p-4 text-left text-sm font-medium text-gray-500">Acciones</th>										
									</tr>
								</thead>
								<tbody class="text-gray-600 text-sm divide-y divide-gray-300">
									<tr v-for="(pago, key) in pagos" :key="key" class="bg-white font-medium text-sm divide-y divide-gray-200">
										<td class="p-4 whitespace-nowrap" v-text="pago.codigo"></td>
										<td class="p-4 whitespace-nowrap" v-text="pago.escuela"></td>
										<td class="p-4 whitespace-nowrap" v-if="pago.tipo_pago == 'MM'">Modificación de matrícula</td>
										<td class="p-4 whitespace-nowrap" v-else-if="pago.tipo_pago == 'ME'">Matrícula por excepción</td>
										<td class="p-4 whitespace-nowrap" v-text="pago.fecha_vencimiento"></td>
										<td class="p-4 whitespace-nowrap" v-if="pago.estado == 1">
											<span class="bg-yellow-100 text-yellow-600 text-xs font-semibold rounded-2xl py-1 px-4">Generado</span>											
										</td>
										<td class="p-4 whitespace-nowrap" v-else>
											<span class="bg-green-100 text-green-600 text-xs font-semibold rounded-2xl py-1 px-4">Pagado</span>
										</td>																																								
										<td class="p-4 whitespace-nowrap">
											<button @click="descargarPdf(pago.codigo, pago.nues)" class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-4 py-2 rounded-md border-0">Descargar pdf</button>
										</td>
									</tr>									
								</tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout'      

    export default {  
		props:['pagos'],      
        components: {
            AppLayout,                        
        },
        data() {
            return {
                api_url: this.$root.api_url,                
            }
        },      
        created() {
            
        },  
        methods: {
			descargarPdf(codigo, nues) {
				window.open(`${this.api_url}/pdf/${codigo}/${nues}`, '_self');
			}            
        }
    }
</script>