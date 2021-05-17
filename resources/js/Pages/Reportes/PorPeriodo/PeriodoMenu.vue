<template>
    <div>
        <b-nav tabs fill>
            <b-nav-item :active="activeTab[0]" :disabled="activeTab[0]" :class="{'font-weight-bold text-uppercase text-danger':activeTab[0]}"
             v-if="$permissions.can([{ name: 'Cajero Reportes-Periodo' }])" :href="route('reportes.cajero')">Por cajero</b-nav-item>
            <b-nav-item :active="activeTab[1]" :disabled="activeTab[1]" :class="{'font-weight-bold text-uppercase text-danger':activeTab[1]}"
             v-if="$permissions.can([{ name: 'Descuento Reportes-Periodo' }])" :href="route('reportes.descuentos')">Descuentos por cajero</b-nav-item>
            <b-nav-item :active="activeTab[2]" :disabled="activeTab[2]" :class="{'font-weight-bold text-uppercase text-danger':activeTab[2]}"
             v-if="$permissions.can([{ name: 'Centro-Costo Reportes-Periodo' }])" :href="route('reportes.centroDeCosto')">Por centros de costo</b-nav-item>
            <b-nav-item :active="activeTab[3]" :disabled="activeTab[3]" :class="{'font-weight-bold text-uppercase text-danger':activeTab[3]}"
             v-if="$permissions.can([{ name: 'Recibo-Ingreso Reportes-Periodo' }])" :href="route('reportes.reciboIngreso')">Por recibo de ingresos</b-nav-item>
            <b-nav-item :active="activeTab[4]" :disabled="activeTab[4]" :class="{'font-weight-bold text-uppercase text-danger':activeTab[4]}"
             v-if="$permissions.can([{ name: 'Consolidado Reportes-Periodo' }])" :href="route('reportes.consolidado')">Consolidado</b-nav-item>
            <b-nav-item :active="activeTab[5]" :disabled="activeTab[5]" :class="{'font-weight-bold text-uppercase text-danger':activeTab[5]}"
             v-if="$permissions.can([{ name: 'Facturas Reportes-Periodo' }])" :href="route('reportes.facturas')">Facturas</b-nav-item>
            <b-nav-item :active="activeTab[6]" :disabled="activeTab[6]" :class="{'font-weight-bold text-uppercase text-danger':activeTab[6]}"
             v-if="$permissions.can([{ name: 'Notas Reportes-Periodo' }])" :href="route('reportes.notas')">Notas</b-nav-item>
        </b-nav>
    </div>
        
</template>
<script>

export default {
    props: ["tab"],
    
    data (){
        return{
            app_url: this.$root.app_url,
        }
    },
    computed:{
        activeTab(){
            var tabs= [false, false, false, false, false, false, false];
            tabs[this.tab] = true;
            return tabs;
        },
    },

}
</script>
