<template>
    <nav
        :class="{
            navbar: true,
            'fixed-top': true,
            'navbar-expand-lg': true,
            'navbar-light': theme.mode === 'dark',
            'navbar-dark': theme.mode === 'light',
        }"
        id="navbar"
    >
        <div class="container">
            <inertia-link :href="route('homepage')" class="navbar-brand">
                <img
                    :src="Logo"
                    class="logo"
                    alt="Creative Streams"
                    aria-label="Creative Streams brought to you by Senshudo"
                />
            </inertia-link>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a
                            class="btn btn-outline-light"
                            href="https://github.com/SenshudoTV/CreativeStreams"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Github"
                        >
                            <font-awesome-icon :icon="['fab', 'github']" />
                        </a>
                    </li>
                    <li class="nav-item px-1">
                        <button
                            class="btn btn-outline-light"
                            id="toggleTheme"
                            :title="`Toggle ${theme.mode} theme`"
                            @click.prevent="toggleTheme(false)"
                        >
                            <font-awesome-icon :icon="['fas', theme.icon]" />
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-outline-light" type="button" @click="authorize">
                            <font-awesome-icon :icon="['fab', 'twitch']" />
                            Sign {{ !isAuthorized ? 'In' : 'Out' }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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

        if (document.documentElement.scrollTop > 90) {
            document.getElementById('navbar').classList.add('isSolid')
        }

        window.addEventListener('scroll', function () {
            if (document.documentElement.scrollTop > 90) {
                document.getElementById('navbar').classList.add('isSolid')
            } else {
                document.getElementById('navbar').classList.remove('isSolid')
            }
        })
    },
    beforeDestroy() {
        window.removeEventListener('scroll')
    },
    methods: {
        authorize: function () {
            if (!this.$store.getters.isAuthorized) {
                this.$store.dispatch(AUTH_LOGIN)
            } else {
                this.$store.dispatch(USER_UPDATE)
            }
        },
        toggleTheme: function (initialSetup = false) {
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
