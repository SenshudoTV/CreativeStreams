<template>
    <div></div>
</template>

<script>
export default {
    name: 'Filters',
    data() {
        return {
            showForm: false,
            form: {},
        }
    },
    methods: {
        applyFilters() {
            this.$emit('updated', this.form)
        },
    },
}
</script>
