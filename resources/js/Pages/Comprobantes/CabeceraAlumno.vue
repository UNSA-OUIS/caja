<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-3 border border-light">
                <label class="text-info">CUI:</label>
                <label class="lbl-data" v-text="comprobante.codi_usuario"></label>
            </div>                      
            <div class="form-group col-md-3 border border-light">
                <label class="text-info">DNI:</label>
                <label class="lbl-data" v-text="data.dni"></label>
            </div>                      
            <div class="form-group col-md-6 border border-light">
                <label class="text-info">Email:</label>
                <label class="lbl-data" v-text="data.email"></label>
            </div>                   
        </div>
        <div class="form-row">
            <div class="form-group col-md-6 border border-light">
                <label class="text-info">Alumno:</label>
                <label class="lbl-data" v-text="data.alumno"></label>
            </div>                      
            <div class="form-group col-md-6 border border-light">
                <label class="text-info">Escuela:</label>
                <label class="lbl-data" v-text="data.escuela"></label>
            </div>                                  
        </div>                                                        
    </div>
</template>
<script>
export default {
    name: "comprobantes.cabecera-alumno",
    props: ["comprobante", "data"],    
};
</script>
<style scoped>
    .lbl-data {
        text-align: center;
        border: 0;
        padding: 0;        
        display: block;
        width: 100%;
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 400;
    }    
</style>