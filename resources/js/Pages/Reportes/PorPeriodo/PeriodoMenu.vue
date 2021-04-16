<template>
    <div>
        <b-nav tabs>
            <b-nav-item :active="activeTab[0]" :disabled="activeTab[0]" :href="route('comprobantes.reporte')">Por cajero</b-nav-item>
            <b-nav-item :active="activeTab[1]" :disabled="activeTab[1]" href="#">Descuentos por cajero</b-nav-item>
            <b-nav-item :active="activeTab[2]" :disabled="activeTab[2]" href="#">Por centros de costo</b-nav-item>
            <b-nav-item :active="activeTab[3]" :disabled="activeTab[3]" href="#">Por recibo de ingresos</b-nav-item>
            <b-nav-item :active="activeTab[4]" :disabled="activeTab[4]" href="#">Facturas</b-nav-item>
            <b-nav-item :active="activeTab[5]" :disabled="activeTab[5]" href="#">Notas</b-nav-item>
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
            var tabs= [false, false, false, false, false, false];
            tabs[this.tab] = true;
            return tabs;
        },
    },

}
</script>
