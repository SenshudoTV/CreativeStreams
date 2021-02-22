window._ = require('lodash')
window.axios = require('axios')

window.axios.defaults.baseURL = '/api'
window.axios.defaults.withCredentials = true

window.twitchAPI = window.axios.create({
    baseURL: 'https://api.twitch.tv/kraken',
    withCredentials: false,
    headers: {
        ...window.axios.defaults.headers.common,
        'Client-ID': 'j083teggo6u0ls77yfy9medgxdtli1',
        Accept: 'application/vnd.twitchtv.v5+json',
    },
})
