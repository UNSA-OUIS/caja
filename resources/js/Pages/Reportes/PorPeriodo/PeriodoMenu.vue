<template>
    <div>
        <!--<b-tabs
                    active-nav-item-class="font-weight-bold text-uppercase text-danger"
                    active-tab-class="font-weight-bold"
                    content-class="mt-3"
                >
                    <b-tab title="Alumnos" active :href="route('reportes.cajero')"> 
                    </b-tab>
                    <b-tab title="Docentes" :href="route('reportes.descuentos')"> 
                    </b-tab>
                    <b-tab title="Dependencias">
                        <tab-dependencias></tab-dependencias>  
                    </b-tab>
                    <b-tab title="Particulares">
                        <tab-particulares></tab-particulares>                          
                    </b-tab>
                </b-tabs>-->
        <b-nav tabs fill>
            <b-nav-item :active="activeTab[0]" :disabled="activeTab[0]" >
                <inertia-link :class="{'font-weight-bold text-uppercase text-danger': activeTab[0]}" :href="route('reportes.cajero')">Por cajero</inertia-link>
            </b-nav-item>
            <b-nav-item :active="activeTab[1]" :disabled="activeTab[1]">
                <inertia-link :class="{'font-weight-bold text-uppercase text-danger': activeTab[1]}" :href="route('reportes.descuentos')">Descuentos por cajero</inertia-link>
            </b-nav-item>
            <b-nav-item :active="activeTab[2]" :disabled="activeTab[2]">
                <inertia-link :class="{'font-weight-bold text-uppercase text-danger': activeTab[2]}" :href="route('reportes.centroDeCosto')">Por centros de costo</inertia-link>
            </b-nav-item>
            <b-nav-item :active="activeTab[3]" :disabled="activeTab[3]">
                <inertia-link :class="{'font-weight-bold text-uppercase text-danger': activeTab[3]}" :href="route('reportes.reciboIngreso')">Por recibo de ingresos</inertia-link>
            </b-nav-item>
            <b-nav-item :active="activeTab[4]" :disabled="activeTab[4]">
                <inertia-link :class="{'font-weight-bold text-uppercase text-danger': activeTab[4]}" :href="route('reportes.consolidado')">Consolidado</inertia-link>
            </b-nav-item>
            <b-nav-item :active="activeTab[5]" :disabled="activeTab[5]">
                <inertia-link :class="{'font-weight-bold text-uppercase text-danger': activeTab[5]}" :href="route('reportes.facturas')">Facturas</inertia-link>
            </b-nav-item>
            <b-nav-item :active="activeTab[6]" :disabled="activeTab[6]">
                <inertia-link :class="{'font-weight-bold text-uppercase text-danger': activeTab[6]}" :href="route('reportes.notas')">Notas</inertia-link>
            </b-nav-item>
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
