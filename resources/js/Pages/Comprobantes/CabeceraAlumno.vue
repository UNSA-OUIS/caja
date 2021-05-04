<template>
    <div>
        <b-row>                        
            <b-col cols="3">
                <b-form-group id="input-group-4" label="CUI:" label-for="input-4">
                    <b-input-group class="mb-2">                                    
                        <b-form-input
                            id="input-4"
                            readonly
                            v-model="comprobante.codi_usuario"
                            class="text-center"
                        ></b-form-input>
                    </b-input-group>
                </b-form-group>
            </b-col>               
            <b-col cols="3">
                <b-form-group id="input-group-5" label="DNI:" label-for="input-5">
                    <b-form-input
                        id="input-5"
                        v-model="data.dni"
                        readonly
                        class="text-center"
                    ></b-form-input>
                </b-form-group>
            </b-col>         
            <b-col cols="6">
                <b-form-group id="input-group-6" label="Email:" label-for="input-6">
                    <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
                        <b-form-input
                            id="input-6"
                            readonly
                            v-model="data.email"
                            placeholder="Email"
                            class="text-center"
                        ></b-form-input>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>
        <b-row>                        
            <b-col>
                <b-form-group id="input-group-7" label="Alumno:" label-for="input-7">
                    <b-input-group class="mb-2">
                        <b-input-group-prepend is-text>
                            <b-icon icon="person-fill"></b-icon>
                        </b-input-group-prepend>
                        <b-form-input
                            id="input-7"
                            readonly
                            v-model="data.alumno"
                            class="text-center"
                        ></b-form-input>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col>
                <b-form-group id="input-group-8" label="Escuela:" label-for="input-8">
                    <b-form-input
                        id="input-8"
                        v-model="data.escuela"
                        readonly
                        class="text-center"
                    ></b-form-input>
                </b-form-group>
            </b-col>
        </b-row>    
    </div>
</template>
<script>
export default {
    name: "comprobantes.cabecera-alumno",
    props: ["comprobante", "data"],    
};
</script>