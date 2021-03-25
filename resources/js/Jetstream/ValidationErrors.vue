<template>
    <div v-if="hasErrors">
        <div class="text-danger font-weight-bold">¡Ups! Algo salió mal.</div>

        <ul class="mt-3 text-danger">
            <li v-for="(error, key) in errors" :key="key">{{ error[0] }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        computed: {
            errors() {
                return this.$page.props.errors
            },

            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        }
    }
</script>
