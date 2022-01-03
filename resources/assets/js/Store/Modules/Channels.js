export default {
    state: () => ({
        loading: true,
        channels: [],
        meta: null,
    }),
    getters: {
        channels: (state) => state.channels,
        meta: (state) => state.meta,
        loading: (state) => state.loading,
    },
    mutations: {
        SET_CHANNELS(state, channels) {
            state.channels = channels
        },
        SET_META(state, meta) {
            state.meta = meta
        },
        SET_LOADING(state, loading) {
            state.loading = loading
        },
    },
    actions: {
        async getChannels({ commit }, { vm, page, filters }) {
            commit('SET_LOADING', true)

            try {
                const {
                    data: { data, meta },
                } = await window.axios.get(
                    vm.route('channels.list', {
                        page,
                        ...filters,
                    }),
                )

                commit('SET_CHANNELS', data)
                commit('SET_META', meta)
            } catch {
                commit('SET_CHANNELS', [])
                commit('SET_META', null)
            }

            commit('SET_LOADING', false)
        },
    },
}
