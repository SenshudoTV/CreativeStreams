import { ZiggyVue } from 'ziggy-js'
import { Ziggy } from '@/ziggy'
import Vue from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue'
import { InertiaProgress } from '@inertiajs/progress'
import { Alert, Button, Carousel, Collapse, Dropdown, Modal, Toast, Tooltip } from 'bootstrap'
import Multiselect from 'vue-multiselect'
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
    faSearch,
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
    faSearch,
    faSun,
    faTwitch,
    faTwitter,
    faYoutube,
)

Vue.config.productionTip = false
Vue.use(ZiggyVue, Ziggy)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('multiselect', Multiselect)

Vue.prototype.moment = moment
Vue.prototype.bootstrap = {
    Alert,
    Button,
    Carousel,
    Collapse,
    Dropdown,
    Modal,
    Toast,
    Tooltip,
}

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
    },
})

InertiaProgress.init()

createInertiaApp({
    resolve: (name) => import(`@/Pages/${name}`),
    setup({ el, app, props }) {
        new Vue({
            store,
            render: (h) => h(app, props),
        }).$mount(el)
    },
})
