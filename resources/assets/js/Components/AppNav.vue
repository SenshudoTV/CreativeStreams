<template>
    <b-navbar toggleable="lg" fixed="top">
        <b-container>
            <b-navbar-brand href="/">
                <img
                    :src="Logo"
                    class="logo"
                    alt="Creative Streams"
                    aria-label="Creative Streams Brought to you by Senshudo"
                />
            </b-navbar-brand>
            <b-navbar-toggle target="nav-collapse" />

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav class="ml-auto">
                    <b-nav-item>
                        <b-button
                            variant="link"
                            href="https://github.com/sweptsquash/CreativeStreams"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Github"
                        >
                            <font-awesome-icon :icon="['fab', 'github']" />
                        </b-button>
                    </b-nav-item>
                    <b-nav-item>
                        <b-button variant="outline-light" @click="authorize">
                            <font-awesome-icon :icon="['fab', 'twitch']" />
                            Sign {{ !isAuthorized ? 'In' : 'Out' }}
                        </b-button>
                    </b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-container>
    </b-navbar>
</template>

<script>
import { AUTH_LOGIN } from '@/Store/Actions/Authentication'
import { USER_UPDATE } from '@/Store/Actions/User'
import { mapGetters } from 'vuex'
import LogoImg from '../../images/cs-logo-senshudo.png'

export default {
    name: 'AppNav',
    data() {
        return {
            Logo: LogoImg,
        }
    },
    computed: {
        ...mapGetters(['isAuthorized']),
    },
    methods: {
        authorize() {
            if (!this.$store.getters.isAuthorized) {
                this.$store.dispatch(AUTH_LOGIN)
            } else {
                this.$store.dispatch(USER_UPDATE)
            }
        },
    },
}
</script>
