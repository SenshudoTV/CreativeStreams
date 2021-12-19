require('es6-promise/auto')

window.axios = require('axios')

window.axios.defaults.baseURL = '/api'

window.axios.defaults.headers.common = {
    ...window.axios.defaults.headers.common,
    Accept: 'application/json',
}

window.twitchAPI = window.axios.create({
    baseURL: 'https://api.twitch.tv/helix',
    withCredentials: false,
    headers: {
        ...window.axios.defaults.headers.common,
        'Client-ID': 'j083teggo6u0ls77yfy9medgxdtli1',
    },
})
