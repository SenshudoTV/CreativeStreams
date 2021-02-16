<template>
    <div :class="{ 'channel-card': true, active: active }" @click="handleClick">
        <div class="channel-thumbnail" :style="`background-image: url(${thumbnailSRC});`">
            <div class="channel-partnered" v-if="partner">
                <font-awesome-icon :icon="['fas', 'badge-check']" />
            </div>
            <div :class="{ 'channel-viewers': true, 'is-partnered': partner }">
                <font-awesome-icon :icon="['fas', 'eye']" />
                {{ this.formatNumber(viewers) }}
            </div>
        </div>
        <div class="channel-info">
            <div>
                <Avatar :src="avatarSRC" :width="50" :height="50" />
            </div>
            <div>
                <span class="channel-title">
                    {{ title }}
                </span>
                <span class="channel-name">
                    {{ name }}
                </span>
                <span class="channel-category">
                    {{ category }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import Avatar from '@/Components/Avatar'
import GenericAvatarWEBP from '../../images/generic-avatar.webp'
import GenericCardWEBP from '../../images/generic-card.webp'

export default {
    name: 'Channels',
    components: {
        Avatar,
    },
    props: {
        id: {
            type: Number,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        slug: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        category: {
            type: String,
            required: true,
        },
        viewers: {
            type: Number,
            required: true,
            default: 0,
        },
        partner: {
            type: Boolean,
            required: false,
            default: false,
        },
        thumbnailSRC: {
            type: String,
            required: false,
            default: GenericCardWEBP,
        },
        avatarSRC: {
            type: String,
            required: false,
            default: GenericAvatarWEBP,
        },
        active: {
            type: Boolean,
            require: false,
            default: false,
        },
    },
    methods: {
        handleClick: function () {
            this.$emit('click', {
                id: this.id,
                name: this.name,
                slug: this.slug,
                viewers: this.viewers,
            })
        },
    },
}
</script>
