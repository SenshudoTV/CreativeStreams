import Vue from 'vue'
import {
    AUTH_LOGIN,
    AUTH_REDIRECT,
    AUTH_SUCCESS,
    AUTH_LOGOUT,
    AUTH_ERROR,
    AUTH_EXPIRED,
} from '@/Store/Actions/Authentication'
import { USER_UPDATE, USER_LOGOUT } from '@/Store/Actions/User'

const siteUrl =
    'https://' +
    (process.env.NODE_ENV !== 'production' ? 'development' : 'www') +
    '.creativestreams.tv'

export default {
    state: () => ({
        token: localStorage.getItem('userToken') || '',
        loggingIn: false,
        error: '',
    }),
    mutations: {
        [AUTH_LOGIN]: (state) => {
            state.loggingIn = true
        },
        [AUTH_SUCCESS]: (state, token) => {
            state.loggingIn = false
            state.token = token
        },
        [AUTH_LOGOUT]: (state) => {
            state.token = ''
        },
        [AUTH_ERROR]: (state, error) => {
            state.loggingIn = false
            state.error = error !== undefined ? error : 'An unexpected error occured.'
        },
    },
    actions: {
        [AUTH_LOGIN]: ({ commit }) => {
            commit(AUTH_LOGIN)

            window.location.href =
                'https://id.twitch.tv/oauth2/authorize?' +
                'client_id=j083teggo6u0ls77yfy9medgxdtli1&redirect_uri=' +
                encodeURI(siteUrl) +
                '&response_type=token&scope=user_follows_edit'
        },
        [AUTH_REDIRECT]: ({ commit }, hash) => {
            if (hash) {
                window.twitchAPI.defaults.headers.common['Authorization'] =
                    'OAuth ' + hash.access_token

                window.twitchAPI
                    .get('/')
                    .then((response) => {
                        if (
                            response.data.token.valid === true &&
                            response.data.token.client_id === 'j083teggo6u0ls77yfy9medgxdtli1'
                        ) {
                            const expires = Vue.prototype.moment().add(1, 'hours').toDate()

                            const userData = {
                                token: hash.access_token,
                                id: parseInt(response.data.token.user_id),
                                name: response.data.token.user_name,
                                expires: expires,
                            }

                            localStorage.setItem('userToken', JSON.stringify(userData))

                            Vue.prototype.$inertia.get(
                                '/',
                                {},
                                { replace: true, preserveState: true, preserveScroll: true },
                            )

                            commit(USER_UPDATE, { id: userData.id, name: userData.name })
                            commit(AUTH_SUCCESS, hash.access_token)
                        } else {
                            commit(AUTH_ERROR)
                        }
                    })
                    .catch(() => {
                        commit(AUTH_ERROR, 'Failed to retrieve user details')
                    })
            } else {
                commit(AUTH_ERROR)
            }
        },
        [AUTH_EXPIRED]: ({ dispatch }) => {
            dispatch(AUTH_LOGOUT)
        },
        [AUTH_LOGOUT]: ({ commit }) => {
            commit(USER_LOGOUT)
            commit(AUTH_LOGOUT)

            window.twitchAPI.defaults.headers.common['Authorization'] = null

            localStorage.removeItem('userToken')
        },
    },
    getters: {
        isAuthorized: (state) => !!state.token,
        getError: (state) => state.error,
        hasError: (state) => !!state.error,
    },
}
