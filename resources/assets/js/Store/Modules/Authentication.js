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
                'client_id=' +
                process.env.TWITCH_ID +
                '&redirect_uri=' +
                encodeURI(siteUrl) +
                '&response_type=token&scope=user_follows_edit'
        },
        [AUTH_REDIRECT]: ({ commit }, { vm, hash }) => {
            if (hash) {
                window.twitchAPI.defaults.headers.common['Authorization'] =
                    'Bearer ' + hash.access_token

                window.twitchAPI
                    .get('https://id.twitch.tv/oauth2/validate')
                    .then(({ response: { data } }) => {
                        if (data.client_id === process.env.TWITCH_ID) {
                            const expires = vm.moment().add(1, 'hours').toDate()

                            const userData = {
                                token: hash.access_token,
                                id: parseInt(data.user_id),
                                name: data.login,
                                expires: expires,
                            }

                            localStorage.setItem('userToken', JSON.stringify(userData))

                            vm.$inertia.get(
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
