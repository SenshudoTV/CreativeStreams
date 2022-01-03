import { createStore } from 'vuex'
import Channels from '@/Store/Modules/Channels'
import User from '@/Store/Modules/User'

export const Store = createStore({
    modules: {
        Channels,
        User,
    },
    strict: process.env.NODE_ENV !== 'production',
})
