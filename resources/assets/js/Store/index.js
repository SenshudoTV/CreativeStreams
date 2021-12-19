import { createStore } from 'vuex'
import Authentication from '@/Store/Modules/Authentication'
import Channels from '@/Store/Modules/Channels'
import User from '@/Store/Modules/User'

export const Store = createStore({
    modules: {
        Authentication,
        Channels,
        User,
    },
    strict: process.env.NODE_ENV !== 'production',
})
