<template>
    <div>
        <FeaturedChannel :channel="selected" />
        <section class="max-w-7xl mx-auto py-5 px-2 sm:px-6 lg:px-8" id="channels">
            <div v-if="loading" class="text-center text-black dark:text-white mb-5">
                <LoadingIcon class="animate-spin block mx-auto w-20 h-20 mb-3" />
                <h2>Loading Streams...</h2>
            </div>
            <div
                v-else-if="channels.length <= 0"
                class="rounded-md bg-red-50 p-4 text-center text-sm text-red-700"
            >
                <h1 class="font-medium text-red-800">No Channels Live</h1>
                <p>
                    Well this is awkward, we can't seem to find any streams that are currently live.
                </p>
            </div>
            <template v-else>
                <div class="grid grid-cols-2 mb-3">
                    <div class="text-black dark:text-white font-medium">
                        {{ meta.total }} Streams
                    </div>
                    <div class="place-self-end">
                        <button
                            type="button"
                            class="px-2 py-2 rounded shadow-sm border border-violet-300 bg-violet-500 text-sm font-medium text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
                            @click.prevent="showFilters = !showFilters"
                        >
                            {{ showFilters ? 'Hide Filters' : 'Show Filters' }}
                        </button>
                    </div>
                    <Filters
                        class="col-span-2"
                        :filters="filters"
                        :show="showFilters"
                        @apply="applyFilters"
                        @clear="clearFilters"
                    />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <ChannelItem
                        v-for="(channel, index) in channels"
                        :key="`channel${index}`"
                        :channel="channel"
                        :active="channel.id === selected?.id"
                        @change="channelChannel"
                    />
                </div>
            </template>
            <Pagination v-if="channels.length > 0 && meta" :meta="meta" @page="changePage" />
        </section>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Layout from '@/Layout/index'
import ChannelItem from '@/Components/ChannelItem'
import FeaturedChannel from '@/Components/FeaturedChannel'
import Filters from '@/Components/Filters'
import Pagination from '@/Components/Pagination'
import LoadingIcon from '@/Icons/LoadingIcon'

export default {
    name: 'PageIndex',
    layout: Layout,
    components: { ChannelItem, FeaturedChannel, Filters, Pagination, LoadingIcon },
    data() {
        return {
            page: 1,
            showFilters: false,
            filters: {
                tags: null,
                sort: null,
                direction: null,
            },
            selected: null,
        }
    },
    computed: {
        ...mapGetters(['loading', 'channels', 'meta']),
    },
    mounted() {
        this.$store.dispatch('getChannels', { vm: this, page: this.page, filters: this.filters })
    },
    methods: {
        channelChannel(channel) {
            if (channel !== undefined && channel !== null) {
                this.selected = channel
                document.getElementById('channelEmbed').scrollIntoView()
            }
        },
        applyFilters(filters) {
            if (filters) {
                let tags = null

                if (filters?.tags) {
                    this.filters.tags = filters.tags

                    this.filters.tags.forEach((tag) => {
                        if (tags === null) {
                            tags = tag.id + ','
                        } else {
                            tags += tag.id + ','
                        }
                    })

                    if (tags !== null) {
                        tags = tags.replace(/,\s*$/, '')
                    }
                } else {
                    this.filters.tags = null
                    tags = null
                }

                if (filters?.sort) {
                    const split = filters.sort.split('-')

                    this.filters.sort = split[0]
                    this.filters.direction = split[1]
                } else {
                    this.filters.sort = null
                    this.filters.direction = null
                }

                this.$store.dispatch('getChannels', {
                    vm: this,
                    page: 1,
                    filters: { ...this.filters, tags: tags },
                })
            }
        },
        clearFilters() {
            this.page = 1
            this.filters = {
                tags: null,
                sort: null,
                direction: null,
            }

            this.$store.dispatch('getChannels', { vm: this, page: 1 })
        },
        changePage(page) {
            this.page = page

            this.$store.dispatch('getChannels', {
                vm: this,
                page: this.page,
                filters: this.filters,
            })

            document.getElementById('channels').scrollIntoView()
        },
    },
}
</script>
