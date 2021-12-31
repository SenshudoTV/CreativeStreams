<template>
    <nav class="fixed top-0 z-50 w-full" id="navbar">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div
                    class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start"
                >
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" :src="Logo" alt="CreativeStreams" />
                    </div>
                </div>
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0"
                >
                    <a
                        class="p-2 border border-white dark:border-gray-300 shadow-sm font-medium rounded-md text-white dark:text-gray-300 hover:text-black dark:hover:text-black hover:bg-white dark:hover:bg-gray-300 focus:outline-none"
                        href="https://github.com/SenshudoTV/CreativeStreams"
                        target="_blank"
                        rel="noopener noreferrer"
                        title="Github"
                    >
                        <GithubIcon class="w-5 h-5" />
                    </a>

                    <button
                        type="button"
                        class="ml-3 p-2 border border-white dark:border-gray-300 shadow-sm font-medium rounded-md text-white dark:text-gray-300 hover:text-black dark:hover:text-black hover:bg-white dark:hover:bg-gray-300 focus:outline-none"
                        @click.prevent="toggleTheme(false)"
                    >
                        <span class="sr-only">Toggle theme</span>
                        <MoonIcon v-if="isDarkMode" class="h-5 w-5" />
                        <SunIcon v-else class="h-5 w-5" />
                    </button>

                    <button
                        type="button"
                        class="ml-3 relative inline-flex items-center p-2 border border-white dark:border-gray-300 shadow-sm font-medium rounded-md text-sm text-white dark:text-gray-300 hover:text-black dark:hover:text-black hover:bg-white dark:hover:bg-gray-300 focus:outline-none"
                        @click.prevent="authorize"
                    >
                        <TwitchIcon class="h-5 w-5 mr-2" />
                        {{ !isAuthorized ? 'Sign In' : 'Sign Out' }}
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import { AUTH_LOGIN, AUTH_LOGOUT } from '@/Store/Actions/Authentication'
import { USER_THEME } from '@/Store/Actions/User'
import { mapGetters } from 'vuex'
import { MoonIcon, SunIcon } from '@heroicons/vue/solid'
import GithubIcon from '@/Icons/GithubIcon'
import TwitchIcon from '@/Icons/TwitchIcon'
import LogoImg from '../../images/cs-logo-senshudo.png'

export default {
    name: 'AppNav',
    components: { MoonIcon, SunIcon, GithubIcon, TwitchIcon },
    data() {
        return {
            Logo: LogoImg,
            isDarkMode: false,
        }
    },
    computed: {
        ...mapGetters(['isAuthorized', 'theme']),
    },
    mounted() {
        this.toggleTheme(true)

        const solidClasses = ['drop-shadow-md', 'bg-violet-500/75', 'dark:bg-gray-800/75']

        if (document.documentElement.scrollTop > 90) {
            document.getElementById('navbar').classList.add(...solidClasses)
        }

        window.addEventListener('scroll', function () {
            if (document.documentElement.scrollTop > 90) {
                document.getElementById('navbar').classList.add(...solidClasses)
            } else {
                document.getElementById('navbar').classList.remove(...solidClasses)
            }
        })
    },
    methods: {
        authorize() {
            if (!this.isAuthorized) {
                this.$store.dispatch(AUTH_LOGIN)
            } else {
                this.$store.dispatch(AUTH_LOGOUT)
            }
        },
        toggleTheme(initialSetup = false) {
            if (initialSetup) {
                if (this.theme !== undefined && this.theme !== null) {
                    this.isDarkMode = this.theme.darkMode
                }
            } else {
                this.isDarkMode = !this.isDarkMode
            }

            if (this.isDarkMode) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }

            const themeData = { darkMode: this.isDarkMode }

            this.$store.dispatch(USER_THEME, themeData)

            localStorage.setItem('appTheme', JSON.stringify(themeData))
        },
    },
}
</script>