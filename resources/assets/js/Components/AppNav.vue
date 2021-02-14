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
                            class="text-light"
                            href="https://github.com/SenshudoTV/CreativeStreams"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Github"
                        >
                            <font-awesome-icon :icon="['fab', 'github']" />
                        </b-button>
                    </b-nav-item>
                    <b-nav-item>
                        <b-button variant="outline-light" id="toggleTheme">
                            <font-awesome-icon :icon="['fas', themeIcon]" />
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
            themeIcon: 'moon',
        }
    },
    computed: {
        ...mapGetters(['isAuthorized']),
    },
    mounted() {
        this.toggleTheme(true)
    },
    methods: {
        authorize() {
            if (!this.$store.getters.isAuthorized) {
                this.$store.dispatch(AUTH_LOGIN)
            } else {
                this.$store.dispatch(USER_UPDATE)
            }
        },
        toggleTheme(initialSetup = false) {
            let theme = 'light'

            if (initialSetup) {
                theme = localStorage.getItem('app-theme')

                if (theme === undefined && theme === null) {
                    theme = 'light'

                    if (
                        window.matchMedia &&
                        window.matchMedia('(prefers-color-scheme: dark)').matches
                    ) {
                        theme = 'dark'
                    }
                }
            } else {
                theme = theme === 'dark' ? 'light' : 'dark'
            }

            const appBody = document.getElementsByName('body')
            appBody.classList.replace(
                'app-' + (theme === 'light' ? 'dark' : 'light'),
                'app-' + theme,
            )
        },
    },
}
</script>
