<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-3 border border-light">
                <label class="text-info">Código:</label>
                <label class="lbl-data" v-text="comprobante.codi_usuario"></label>
            </div>
            <div class="form-group col-md-3 border border-light">
                <label class="text-info">DNI:</label>
                <label class="lbl-data" v-text="data.dni"></label>
            </div>
            <div v-if="data.email != ''" class="form-group col-md-6 border border-light">
                <label class="text-info">Email:</label>
                <label class="lbl-data" v-text="data.email"></label>
            </div>
            <div v-else class="form-group col-md-6 border border-light">
                <label class="text-warning">Email (Opcional):</label>
                <b-input class="lbl-data" placeholder="Ingrese un email" v-model="comprobante.email"></b-input>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6 border border-light">
                <label class="text-info">Docente:</label>
                <label class="lbl-data" v-text="data.docente"></label>
            </div>
            <div class="form-group col-md-6 border border-light">
                <label class="text-info">Departamento:</label>
                <label class="lbl-data" v-text="data.departamento"></label>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "comprobantes.cabecera-docente",
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
