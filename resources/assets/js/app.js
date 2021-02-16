import route from 'ziggy'
import { Ziggy } from '@/ziggy'
import { App, plugin } from '@inertiajs/inertia-vue'
import { InertiaProgress } from '@inertiajs/progress'
import Vue from 'vue'
import VueMeta from 'vue-meta'
import { BootstrapVue } from 'bootstrap-vue'
import moment from 'moment'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
    faComments,
    faEye,
    faFilter,
    faHeart,
    faHeartBroken,
    faMoon,
    faSun,
} from '@fortawesome/free-solid-svg-icons'
import {
    faDiscord,
    faFacebookF,
    faGoogle,
    faGithub,
    faInstagram,
    faLinkedinIn,
    faMicrosoft,
    faTwitch,
    faTwitter,
    faYoutube,
} from '@fortawesome/free-brands-svg-icons'
import { faBadgeCheck } from '@fortawesome/pro-solid-svg-icons'
import store from '@/Store'
require('@/bootstrap')
import '../sass/app.scss'

library.add(
    faBadgeCheck,
    faComments,
    faDiscord,
    faEye,
    faFacebookF,
    faFilter,
    faGoogle,
    faGithub,
    faHeart,
    faHeartBroken,
    faInstagram,
    faLinkedinIn,
    faMicrosoft,
    faMoon,
    faSun,
    faTwitch,
    faTwitter,
    faYoutube,
)

Vue.config.productionTip = false
Vue.use(plugin)
Vue.use(BootstrapVue)
Vue.use(VueMeta)
Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.prototype.moment = moment

Vue.mixin({
    methods: {
        getFormatedDate(date, format = 'DD/MM/YYYY hh:mm:ss') {
            return moment(date).format(format)
        },
        formatNumber: (num) => {
            return parseInt(num)
                .toString()
                .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        },
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    },
})

InertiaProgress.init()

const el = document.getElementById('app')

new Vue({
    store,
    metaInfo: {
        titleTemplate: (title) => (title ? `${title} | Creative Streams` : 'Creative Streams'),
        link: [{ rel: 'canonical', href: 'https://www.creativestreams.tv' }],
        meta: [
            {
                property: 'og:title',
                content: 'Creative Streams',
            },
            {
                name: 'twitter:title',
                content: 'Creative Streams',
            },
            {
                property: 'og:url',
                content: 'https://www.creativestreams.tv',
            },
        ],
    },
    render: (h) =>
        h(App, {
            props: {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: (name) =>
                    import(`@/Pages/${name}`).then((module) => module.default),
                resolveErrors: (page) => page.props.errors || {},
            },
        }),
}).$mount(el)
