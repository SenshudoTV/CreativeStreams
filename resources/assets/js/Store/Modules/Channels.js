export default {
    state: () => ({
        channels: [],
        meta: null,
    }),
    getters: {
        channels: (state) => state.channels,
        meta: (state) => state.meta,
    },
    mutations: {
        SET_CHANNELS(state, channels) {
            state.channels = channels
        },
        SET_META(state, meta) {
            state.meta = meta
        },
    },
    actions: {
        async getChannels({ commit }, { vm, page, filters }) {
            const { data, meta } = await window.axios.get(
                vm.route('channels.list', {
                    page,
                    'filter[tag]': filters.tags,
                    order: filters.order,
                }),
            )

            commit('SET_CHANNELS', data)
            commit('SET_META', meta)
        },
    },
}
