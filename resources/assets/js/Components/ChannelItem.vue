<template>
    <div
        class="flex flex-col rounded-md hover:bg-violet-400 dark:hover:bg-gray-700 cursor-pointer shadow-md"
        :class="{
            'bg-violet-500 dark:bg-gray-800': !active,
            'bg-violet-400 dark:bg-gray-700': active,
        }"
        @click.prevent="handleClick"
    >
        <div
            id="thumbnail"
            class="flex flex-row items-start content-start bg-gray-200 h-[210px] w-full p-2 rounded-md bg-cover bg-no-repeat"
            :style="`background-image: url(${thumbnail})`"
        >
            <div v-if="channel.partner" class="rounded-md p-2 bg-black/75 text-white mr-2">
                <BadgeCheckIcon class="h-5 w-5" />
            </div>
            <div class="rounded-md p-2 bg-black/75 text-white leading-4">
                <EyeIcon class="h-5 w-5 mr-1 inline-block align-middle" />
                <span class="inline-block text-sm leading-4 align-middle">
                    {{ formatNumber(channel.viewers) }}
                </span>
            </div>
        </div>
        <div class="flex flex-row items-center p-2">
            <Avatar class="flex-none" :src="avatar" :width="50" :height="50" />
            <div class="flex-1 text-sm text-white/[.65] ml-2" style="width: calc(100% - 60px)">
                <div class="text-ellipsis whitespace-nowrap overflow-hidden text-white">
                    {{ channel.title }}
                </div>
                <div class="text-ellipsis whitespace-nowrap overflow-hidden">
                    {{ channel.name }}
                </div>
                <div class="text-ellipsis whitespace-nowrap overflow-hidden">
                    {{ channel.category }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { BadgeCheckIcon, EyeIcon } from '@heroicons/vue/outline'
import Avatar from '@/Components/Avatar'
import GenericAvatarWEBP from '../../images/generic-avatar.webp'
import GenericCardWEBP from '../../images/generic-card.webp'

export default {
    name: 'ChannelItem',
    components: { Avatar, BadgeCheckIcon, EyeIcon },
    props: {
        channel: {
            type: Object,
            required: true,
        },
        active: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        avatar() {
            if (this.channel.avatar) {
                return this.channel.avatar
            }

            return GenericAvatarWEBP
        },
        thumbnail() {
            if (this.channel.thumbnail) {
                return this.channel.thumbnail
            }

            return GenericCardWEBP
        },
    },
    methods: {
        handleClick() {
            this.$emit('change', this.channel)
        },
    },
}
</script>
