import { USER_THEME } from '@/Store/Actions/User'

export default {
    state: () => ({
        theme: localStorage.getItem('appTheme')
            ? JSON.parse(localStorage.getItem('appTheme'))
            : null,
    }),
    mutations: {
        [USER_THEME]: (state, theme) => {
            state.theme = theme
        },
    },
    actions: {
        [USER_THEME]: ({ commit }, theme) => {
            commit(USER_THEME, theme)
        },
    },
    getters: {
        theme: (state) => state.theme,
    },
}
