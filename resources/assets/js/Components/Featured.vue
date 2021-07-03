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

        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="player">
                        <div class="ratio ratio-16x9" id="channelEmbed"></div>
                    </div>
                    <div class="playerBtns">
                        <div class="row">
                            <div class="col-6">
                                <div class="p-2 text-light">
                                    <font-awesome-icon :icon="['fas', 'eye']" />
                                    <span id="viewers">
                                        {{ this.formatNumber(featuredChannel.viewers) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <button
                                    v-if="isAuthorized"
                                    :class="{
                                        btn: true,
                                        'btn-danger': isFollowing,
                                        'btn-success': !isFollowing,
                                    }"
                                    type="button"
                                    @click="handleFollowship"
                                >
                                    {{ isFollowing ? 'Unfollow' : 'Follow' }}
                                </button>
                                <button
                                    class="btn btn-light"
                                    :href="`https://www.twitch.tv/${featuredChannel.slug}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <font-awesome-icon :icon="['fas', 'comments']" />
                                    Enter Chat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        channel: this.featuredChannel.slug,
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

                this.EmbedAPi.setChannel(newChannel.slug)
            }
        },
    },
}
</script>
