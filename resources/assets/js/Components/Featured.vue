<template>
    <header class="featured mb-4" id="featuredStream">
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
                                        {{ formatNumber(viewers) }}
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
                                    :href="url"
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
        id: {
            type: Number,
            default: 0,
        },
        name: {
            type: String,
            default: 'Twitch',
        },
        url: {
            type: String,
            default: 'twitch',
        },
        viewers: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            animations: [],
            isFollowing: false,
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
    },
    methods: {
        formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        },
        handleFollowship() {
            // TODO: Add Twitch logic
        },
    },
    watch: {
        id: function (newId) {
            // TODO: Add Twitch logic for checking follow status
        },
        url: function (newUrl) {
            window.twitch.setChannel(newUrl)
        },
    },
}
</script>
