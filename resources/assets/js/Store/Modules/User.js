import { USER_UPDATE, USER_LOGOUT, USER_THEME } from '@/Store/Actions/User'
import { AUTH_SUCCESS, AUTH_EXPIRED, AUTH_LOGOUT } from '@/Store/Actions/Authentication'

export default {
    state: () => ({
        user: {
            id: null,
            name: null,
        },
        theme: localStorage.getItem('appTheme')
            ? JSON.parse(localStorage.getItem('appTheme'))
            : null,
    }),
    mutations: {
        [USER_UPDATE]: (state, user) => {
            state.user = user
        },
        [USER_LOGOUT]: (state) => {
            state.user = {
                id: null,
                name: null,
            }
        },
        [USER_THEME]: (state, theme) => {
            state.theme = theme
        },
    },
    actions: {
        [USER_UPDATE]: ({ commit, dispatch }, { vm }) => {
            let userData = localStorage.getItem('userToken')

            if (userData !== undefined && userData !== null && userData !== '') {
                userData = JSON.parse(userData)

                if (vm.moment(userData.expires).toDate() > vm.moment().toDate()) {
                    window.twitchAPI.defaults.headers.common['Authorization'] =
                        'OAuth ' + userData.token

                    window.twitchAPI
                        .get('/')
                        .then(({ response: { data } }) => {
                            if (data.client_id === process.env.TWITCH_ID) {
                                commit(USER_UPDATE, {
                                    id: userData.id,
                                    name: userData.name,
                                })

                                commit(AUTH_SUCCESS, userData.token)
                            } else {
                                dispatch(AUTH_LOGOUT)
                            }
                        })
                        .catch(() => {
                            dispatch(AUTH_EXPIRED)
                        })
                } else {
                    dispatch(AUTH_EXPIRED)
                }
            } else {
                dispatch(AUTH_EXPIRED)
            }
        },
        [USER_THEME]: ({ commit }, theme) => {
            commit(USER_THEME, theme)
        },
    },
    getters: {
        user: (state) => state.user,
        theme: (state) => state.theme,
    },
}
