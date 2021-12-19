<template>
    <header class="featured" id="featuredChannel">
        <div class="absolute w-full h-full overflow-hidden top-0">
            <div
                v-for="(animation, animationIndex) in animations"
                class="fancyAnimationLayerItem"
                :key="`animation${animationIndex}`"
                :style="`animation-duration: ${animation.duration}; animation-delay: ${animation.delay}; left: ${animation.left}; top: ${animation.top};`"
            ></div>
        </div>

        <div class="container mx-auto px-4 z-10">
            <div class="channelContainer">
                <div id="channelEmbed"></div>
            </div>
            <div id="channelActions">
                <div class="flex items-center text-white">
                    <EyeIcon class="h-5 w-5 mr-2 inline-block" />
                    <span id="channelViewers">{{ featuredChannel.viewers }}</span>
                </div>
                <div class="text-right">
                    <button
                        v-if="isAuthorized"
                        type="button"
                        class="relative inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500"
                        @click.prevent="handleFollowship"
                    >
                        Follow
                    </button>
                    <a
                        :href="`https://www.twitch.tv/${featuredChannel.slug}`"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500"
                    >
                        Enter Chat
                    </a>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import { mapGetters } from 'vuex'
import { EyeIcon } from '@heroicons/vue/solid'

export default {
    name: 'FeaturedChannel',
    components: { EyeIcon },
    props: {
        channel: {
            type: Object,
            required: false,
        },
    },
    data() {
        return {
            animations: [],
            isFollowing: false,
            EmbedAPi: null,
            featuredChannel: {
                id: 12826,
                name: 'Twitch',
                slug: 'twitch',
                viewers: 0,
            },
        }
    },
    computed: {
        ...mapGetters(['isAuthorized', 'getUser']),
    },
    mounted() {
        for (let i = 0; i < 30; i++) {
            this.animations.push({
                duration: (Math.random() * (5.9 - 5) + 5).toFixed(14) + 's',
                delay: (Math.random() * (0.02 - 1) + 1).toFixed(14) + 's',
                left: (Math.random() * (95 - 5)).toFixed(14) + '%',
                top: (Math.random() * (90 - 5)).toFixed(14) + '%',
            })
        }
        const script = document.createElement('script')
        script.setAttribute('src', 'https://embed.twitch.tv/embed/v1.js')
        script.addEventListener('load', this.fetchFeaturedStream())
        document.body.appendChild(script)
    },
    methods: {
        fetchFeaturedStream() {
            window.axios
                .get(this.route('channels.random'))
                .then(({ response: { data } }) => {
                    this.featuredChannel = {
                        id: data.id,
                        name: data.name,
                        slug: data.slug,
                        viewers: data.viewers,
                    }
                })
                .catch(() => {
                    this.featuredChannel = {
                        id: 12826,
                        name: 'Twitch',
                        slug: 'twitch',
                        viewers: 0,
                    }
                })
                .then(() => {
                    this.EmbedAPi = new window.Twitch.Embed('channelEmbed', {
                        width: 900,
                        height: 506,
                        playsinline: true,
                        layout: 'video',
                        channel: this.featuredChannel.slug,
                        theme: 'dark',
                        parent: ['www.creativestreams.tv', 'development.creativetsreams.tv'],
                    })
                })
        },
        handleFollowship() {
            window.twitchAPI
                .get(`/users/follows?to_id=${this.featuredChannel.id}&from_id=${this.getUser.id}`)
                .then(({ response: { data } }) => {
                    console.log(data)
                })
                .catch((e) => {
                    console.log(e)
                })
        },
    },
    watch: {
        channel(newChannel) {
            if (newChannel !== undefined && newChannel !== null) {
                this.featuredChannel = newChannel
                this.EmbedAPi.setChannel(newChannel.slug)
            }
        },
    },
}
</script>
