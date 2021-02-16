<template>
    <b-navbar toggleable="lg" fixed="top">
        <b-container>
            <b-navbar-brand href="/">
                <img
                    :src="Logo"
                    class="logo"
                    alt="Creative Streams"
                    aria-label="Creative Streams brought to you by Senshudo"
                />
            </b-navbar-brand>
            <b-navbar-toggle target="nav-collapse" />

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav class="ml-auto">
                    <li class="nav-item">
                        <b-button
                            variant="outline-light"
                            href="https://github.com/SenshudoTV/CreativeStreams"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Github"
                        >
                            <font-awesome-icon :icon="['fab', 'github']" />
                        </b-button>
                    </li>
                    <li class="nav-item px-1">
                        <b-button
                            variant="outline-light"
                            id="toggleTheme"
                            :title="`Toggle ${theme.mode} theme`"
                            @click.prevent="toggleTheme(false)"
                        >
                            <font-awesome-icon :icon="['fas', theme.icon]" />
                        </b-button>
                    </li>
                    <li class="nav-item">
                        <b-button variant="outline-light" @click="authorize">
                            <font-awesome-icon :icon="['fab', 'twitch']" />
                            Sign {{ !isAuthorized ? 'In' : 'Out' }}
                        </b-button>
                    </li>
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
            theme: {
                icon: 'moon',
                mode: 'dark',
            },
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
            if (initialSetup) {
                const themeStored = localStorage.getItem('app-theme')

                if (themeStored === undefined || themeStored === null) {
                    this.theme.icon = 'moon'
                    this.theme.mode = 'dark'

                    if (
                        window.matchMedia &&
                        window.matchMedia('(prefers-color-scheme: dark)').matches
                    ) {
                        this.theme.icon = 'sun'
                        this.theme.mode = 'light'
                    }
                } else {
                    const targetTheme = JSON.parse(themeStored)

                    this.theme.icon = targetTheme.icon
                    this.theme.mode = targetTheme.mode
                }
            } else {
                this.theme.icon = this.theme.icon === 'moon' ? 'sun' : 'moon'
                this.theme.mode = this.theme.mode === 'dark' ? 'light' : 'dark'
            }

            document.body.classList.replace(
                'app-' + this.theme.mode,
                'app-' + (this.theme.mode === 'light' ? 'dark' : 'light'),
            )

            localStorage.setItem('app-theme', JSON.stringify(this.theme))
        },
    },
}
</script>
