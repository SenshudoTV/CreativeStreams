<template>
    <div id="app" class="d-flex flex-column">
        <AppNav />
        <slot />
        <AppFooter />
    </div>
</template>

<script>
import { USER_UPDATE } from '@/Store/Actions/User'
import AppNav from '@/Components/AppNav'
import AppFooter from '@/Components/AppFooter'

export default {
    name: 'Layout',
    components: {
        AppNav,
        AppFooter,
    },
    mounted: function () {
        if (this.$store.getters.isAuthorized) {
            this.$store.dispatch(USER_UPDATE)
        }
    },
}
</script>
