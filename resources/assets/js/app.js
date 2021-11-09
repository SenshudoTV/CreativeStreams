import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { ZiggyVue } from 'ziggy-js'
import { Ziggy } from '@/ziggy'
import appMixins from '@/mixins'
import moment from 'moment-timezone'
require('@/bootstrap')

InertiaProgress.init()

moment.locale(window.navigator.userLanguage || window.navigator.language)

createInertiaApp({
    resolve: (name) => require(`@/Pahes/${name}`),
    setup({ el, app, props, plugin }) {
        const appInstance = createApp({
            render: () => h(app, props),
        })

        appInstance.config.globalProperties.moment = moment

        appInstance
            .use(plugin)
            .mixin(appMixins)
            .use(ZiggyVue, Ziggy)
            .component('Link', Link)
            .mount(el)
    },
})
