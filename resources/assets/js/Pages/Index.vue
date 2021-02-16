<template>
    <div>
        <Featured :channel="selected" />
        <b-container>
            <b-row v-if="total > 0" class="mb-2">
                <b-col :sm="12" :md="6" :lg="6" class="pl-0">
                    <h2 class="mb-0" id="streamHeading">{{ total }} Streams</h2>
                </b-col>
                <b-col :sm="12" :md="6" :lg="6" class="pr-0 text-right">
                    <b-button variant="primary" @click.prevent="toggleFilters">
                        <font-awesome-icon :icon="['fas', 'filter']" />
                        Filter
                    </b-button>
                </b-col>
            </b-row>
            <b-row class="mb-4" id="filterContainer" v-if="filters">
                <b-col :sm="12" :md="6" :lg="6"></b-col>
                <b-col :sm="12" :md="6" :lg="6"></b-col>
            </b-row>
            <b-row>
                <b-col v-if="loading" class="loading text-center">
                    <div class="lds-roller mb-2">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <p>Loading Streams...</p>
                </b-col>
                <b-col v-else-if="error">
                    <b-alert variant="danger" class="text-center flex-fill" show>
                        Failed to fetch streams.
                    </b-alert>
                </b-col>
                <template v-else>
                    <Channel
                        v-for="channel in channels"
                        :key="`channel${channel.id}`"
                        :active="selected !== undefined ? selected.id === channel.id : false"
                        :id="channel.id"
                        :name="channel.name"
                        :slug="channel.slug"
                        :title="channel.title"
                        :category="channel.category"
                        :viewers="channel.viewers"
                        :partner="channel.partner"
                        :avatarSRC="channel.avatar"
                        :thumbnailSRC="channel.thumbnail"
                        @click="handleClick"
                    />
                    <b-col :sm="12" :md="12" :lg="12" v-if="meta.last_page > 1">
                        <nav class="my-4" aria-label="Page Navigation">
                            <ul class="pagination justify-content-center">
                                <li
                                    v-for="(link, index) in meta.links"
                                    :key="`pageItem${index}`"
                                    class="page-item"
                                    :class="{ disabled: link.url === null, active: link.active }"
                                >
                                    <a
                                        class="page-link"
                                        href="#"
                                        v-html="link.label"
                                        @click.prevent="fetchChannels(link.url)"
                                        :aria-disabled="link.url === null"
                                    ></a>
                                </li>
                            </ul>
                        </nav>
                    </b-col>
                </template>
            </b-row>
        </b-container>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import Featured from '@/Components/Featured'
import Channel from '@/Components/Channel'

export default {
    name: 'Index',
    layout: Layout,
    components: {
        Featured,
        Channel,
    },
    data() {
        return {
            loading: true,
            error: false,
            filters: false,
            total: 0,
            selected: undefined,
            channels: [],
            meta: [],
        }
    },
    mounted() {
        this.fetchChannels()
    },
    methods: {
        handleClick: function (channel) {
            if (channel !== undefined && channel !== null) {
                this.selected = channel
            }
        },
        fetchChannels: function (link = null) {
            let page = 1

            if (link !== null) {
                const url = new URL(link)

                page = parseInt(url.searchParams.get('page'))
            }

            window.axios
                .get(this.route('channels.list', { page: page }))
                .then((response) => {
                    this.loading = false
                    this.error = false
                    this.channels = response.data.data
                    this.meta = response.data.meta
                    this.total = response.data.meta.total
                })
                .catch(() => {
                    this.error = true
                    this.loading = false
                })
        },
        toggleFilters: function () {
            this.filters = !this.filters
        },
    },
}
</script>
