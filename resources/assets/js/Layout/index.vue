<template>
    <div class="d-flex flex-column">
        <AppNav />
        <slot />
        <AppFooter />
    </div>
</template>

<script>
import AppNav from '@/Components/AppNav'
import AppFooter from '@/Components/AppFooter'

export default {
    name: 'LayoutIndex',
    components: { AppNav, AppFooter },
}
</script>
