<template>
    <div id="app" class="d-flex flex-column">
        <AppNav />
        <slot />
        <AppFooter />
    </div>
</template>

<script>
import { AUTH_REDIRECT } from '@/Store/Actions/Authentication'
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
        } else {
            let hash = document.location.hash

            if (hash) {
                hash = hash
                    .substr(1)
                    .split('&')
                    .map((v) => v.split('='))
                    .reduce((pre, [key, value]) => ({ ...pre, [key]: value }), {})

                if (Object.prototype.hasOwnProperty.call(hash, 'access_token')) {
                    this.$store.dispatch(AUTH_REDIRECT, hash)
                }
            }
        }
    },
}
</script>
