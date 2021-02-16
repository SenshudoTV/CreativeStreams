<template>
    <header class="featured mb-2" id="featuredStream">
        <div class="fancyAnimationLayer animated fadeIn" v-if="animations.length">
            <template v-for="(animation, animationIndex) in animations">
                <div
                    class="fancyAnimationLayerItem"
                    :key="`animation${animationIndex}`"
                    :style="`animation-duration: ${animation.duration}; animation-delay: ${animation.delay}; left: ${animation.left}; top: ${animation.top};`"
                ></div>
            </template>
        </div>

        <b-container fluid>
            <b-row>
                <b-col>
                    <div class="player">
                        <div class="embed-responsive embed-responsive-16by9">
                            <div class="embed-responsive-item" id="channelEmbed"></div>
                        </div>
                    </div>
                    <div class="playerBtns">
                        <b-row>
                            <b-col :sm="6" :md="6">
                                <div class="p-2 text-light">
                                    <font-awesome-icon :icon="['fas', 'eye']" />
                                    <span id="viewers">
                                        {{ this.formatNumber(featuredChannel.viewers) }}
                                    </span>
                                </div>
                            </b-col>
                            <b-col :sm="6" :md="6" class="text-right">
                                <b-button
                                    v-if="isAuthorized"
                                    :variant="isFollowing ? 'danger' : 'success'"
                                    @click="handleFollowship"
                                >
                                    {{ isFollowing ? 'Unfollow' : 'Follow' }}
                                </b-button>
                                <b-button
                                    variant="light"
                                    :href="`https://www.twitch.tv/${featuredChannel.slug}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <font-awesome-icon :icon="['fas', 'comments']" />
                                    Enter Chat
                                </b-button>
                            </b-col>
                        </b-row>
                    </div>
                </b-col>
            </b-row>
        </b-container>
    </header>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: 'Featured',
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
        ...mapGetters(['isAuthorized']),
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
        fetchFeaturedStream: function () {
            window.axios
                .get(this.route('channels.random'))
                .then((response) => {
                    this.featuredChannel = {
                        id: response.data.data.id,
                        name: response.data.data.name,
                        slug: response.data.data.slug,
                        viewers: response.data.data.viewers,
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
                        width: 847,
                        height: 476,
                        playsinline: true,
                        layout: 'video',
                        channel: this.featuredChannel.id,
                        theme: 'dark',
                        parent: ['www.creativestreams.tv', 'development.creativetsreams.tv'],
                    })
                })
        },
        handleFollowship: function () {
            // TODO: Add Twitch logic
        },
    },
    watch: {
        channel: function (newChannel) {
            if (newChannel !== undefined && newChannel !== null) {
                this.featuredChannel = newChannel

                this.EmbedAPi.setChannel(newChannel.id)
            }
        },
    },
}
</script>
