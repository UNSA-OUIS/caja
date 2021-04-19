<template>
    <div>
        <b-nav tabs fill>
            <b-nav-item :active="activeTab[0]" :disabled="activeTab[0]" >
                <inertia-link :href="route('reportes.cajero')">
                Por cajero</inertia-link>
                </b-nav-item>
            <b-nav-item :active="activeTab[1]" :disabled="activeTab[1]" :href="route('reportes.descuentos')">Descuentos por cajero</b-nav-item>
            <b-nav-item :active="activeTab[2]" :disabled="activeTab[2]" :href="route('reportes.centroDeCosto')">Por centros de costo</b-nav-item>
            <b-nav-item :active="activeTab[3]" :disabled="activeTab[3]" :href="route('reportes.reciboIngreso')">Por recibo de ingresos</b-nav-item>
            <b-nav-item :active="activeTab[4]" :disabled="activeTab[4]" :href="route('reportes.consolidado')">Consolidado</b-nav-item>
            <b-nav-item :active="activeTab[5]" :disabled="activeTab[5]" :href="route('reportes.facturas')">Facturas</b-nav-item>
            <b-nav-item :active="activeTab[6]" :disabled="activeTab[6]" :href="route('reportes.notas')">Notas</b-nav-item>
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
