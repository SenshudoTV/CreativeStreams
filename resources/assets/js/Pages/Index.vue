<template>
    <div>
        <FeaturedChannel :channel="selected" />
        <section class="max-w-7xl mx-auto py-5">
            <Filters @updated="applyFilters" />
            <div
                v-if="channels.length <= 0"
                class="rounded-md bg-red-50 p-4 text-center text-sm text-red-700"
            >
                <h1 class="font-medium text-red-800">No Channels Live</h1>
                <p>
                    Well this is awkward, we can't seem to find any streams that are currently live.
                </p>
            </div>
            <div v-else class="grid grid-cols-3 gap-4">
                <ChannelItem
                    v-for="(channel, index) in channels"
                    :key="`channel${index}`"
                    :channel="channel"
                    :active="channel.id === active?.id"
                    @click="handleClick"
                />
            </div>

            <Pagination v-if="channels.length > 0 && meta" :meta="meta" @click="changePage" />
        </section>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Layout from '@/Layout/index'
import ChannelItem from '@/Components/ChannelItem'
import FeaturedChannel from '@/Components/FeaturedChannel'
import Pagination from '@/Components/Pagination'

export default {
    name: 'PageIndex',
    layout: Layout,
    components: { ChannelItem, FeaturedChannel, Pagination },
    data() {
        return {
            page: 1,
            active: null,
            filters: {
                tags: null,
                order: null,
            },
            selected: null,
        }
    },
    computed: {
        ...mapGetters(['channels', 'meta']),
    },
    mounted() {
        this.$store.dispatch('getChannels', { vm: this, page: this.page, filters: this.filters })
    },
    methods: {
        handleClick(channel) {
            if (channel !== undefined && channel !== null) {
                this.selected = channel
                document.getElementById('channelEmbed').scrollIntoView()
            }
        },
        applyFilters(filters) {
            if (filters) {
                // TODO
            }
        },
        changePage(page) {
            this.page = page

            this.$store.dispatch('getChannels', {
                vm: this,
                page: this.page,
                filters: this.filters,
            })
        },
    },
}
</script>
