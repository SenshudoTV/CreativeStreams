<template>
    <div>
        <Featured
            :id="featuredChannel.id"
            :name="featuredChannel.name"
            :url="featuredChannel.url"
            :viewers="featuredChannel.viewers"
        />
        <b-container fluid>
            <b-row>
                <b-col :sm="12" :md="6" :lg="6"></b-col>
                <b-col :sm="12" :md="6" :lg="6"></b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import Featured from '@/Components/Featured'

export default {
    name: 'Index',
    layout: Layout,
    components: {
        Featured,
    },
    data() {
        return {
            featuredChannel: {
                id: 0,
                name: 'Twitch',
                url: 'twitch',
                viewers: 0,
            },
        }
    },
    mounted() {
        const script = document.createElement('script')
        script.setAttribute('src', 'https://embed.twitch.tv/embed/v1.js')
        script.addEventListener('load', this.fetchFeaturedStream())
        document.body.appendChild(script)
    },
    methods: {
        fetchFeaturedStream() {
            window.axios
                .get('/streams/random')
                .then((response) => {
                    this.featuredChannel = {
                        id: response.data.id,
                        name: response.data.name,
                        url: response.data.url,
                        viewers: response.data.viewers,
                    }
                })
                .catch(() => {
                    this.featuredChannel = {
                        id: 0,
                        name: 'Twitch',
                        url: 'twitch',
                        viewers: 0,
                    }

                    // TODO: Display Error
                })
        },
    },
}
</script>
