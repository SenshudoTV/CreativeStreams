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
    faHeart,
    faHeartBroken,
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
import store from '@/Store'
require('@/bootstrap')
import '../sass/app.scss'

library.add(
    faComments,
    faDiscord,
    faEye,
    faFacebookF,
    faGoogle,
    faGithub,
    faHeart,
    faHeartBroken,
    faInstagram,
    faLinkedinIn,
    faMicrosoft,
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
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    },
})

InertiaProgress.init()

const el = document.getElementById('app')

new Vue({
    store,
    metaInfo: {
        titleTemplate: (title) => (title ? `${title} | Creative Streams` : 'Creative Streams'),
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
