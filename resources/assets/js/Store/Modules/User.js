import Vue from 'vue'
import { USER_UPDATE, USER_LOGOUT } from '@/Store/Actions/User'
import { AUTH_SUCCESS, AUTH_EXPIRED, AUTH_LOGOUT } from '@/Store/Actions/Authentication'

export default {
    state: () => ({
        user: {
            id: null,
            name: null,
        },
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
    },
    actions: {
        [USER_UPDATE]: ({ commit, dispatch }) => {
            let userData = localStorage.getItem('userToken')

            if (userData !== undefined && userData !== null && userData !== '') {
                userData = JSON.parse(userData)

                if (
                    Vue.prototype.moment(userData.expires).toDate() >
                    Vue.prototype.moment().toDate()
                ) {
                    window.twitchAPI.defaults.headers.common['Authorization'] =
                        'OAuth ' + userData.token

                    window.twitchAPI
                        .get('/')
                        .then((response) => {
                            if (
                                response.data.token.valid === true &&
                                response.data.token.client_id === 'j083teggo6u0ls77yfy9medgxdtli1'
                            ) {
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
    },
    getters: {
        getUser: (state) => state.user,
    },
}
