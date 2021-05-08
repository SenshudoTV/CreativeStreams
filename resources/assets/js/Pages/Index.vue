<template>
    <div>
        <Featured :channel="selected" />
        <b-container>
            <b-row v-if="total > 0" class="mb-2" id="filterHeader">
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
            <b-row class="mb-2" id="filterContainer" v-if="displayFilters">
                <b-col :sm="12" :md="6" :lg="8" class="pl-0">
                    <multiselect
                        v-model="filters.filter"
                        :options="options.filters"
                        :searchable="true"
                        :allow-empty="true"
                        :multiple="true"
                        placeholder="Select A Category, Tag or Hashtag..."
                        label="tag"
                        track-by="id"
                    />
                </b-col>
                <b-col :sm="12" :md="4" :lg="3" class="px-0">
                    <b-select v-model="filters.order" :options="options.order" />
                </b-col>
                <b-col :sm="12" :md="2" :lg="1" class="pr-0">
                    <b-button block variant="primary" @click="fetchChannels()">
                        <font-awesome-icon :icon="['fas', 'search']" />
                    </b-button>
                </b-col>
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
                        {{ errorMessage }}
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
                            <ul class="pagination justify-content-center flex-wrap">
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
            errorMessage: 'Failed to fetch streams.',
            displayFilters: false,
            filters: {
                filter: [],
                order: 'created-asc',
            },
            options: {
                filters: [],
                order: [
                    { value: 'viewers-desc', text: 'Viewers (High to Low)' },
                    { value: 'viewers-asc', text: 'Viewers (Low to High)' },
                    { value: 'created-desc', text: 'Broadcast Time (Longest)' },
                    { value: 'created-asc', text: 'Broadcast Time (Recently Started)' },
                ],
            },
            total: 0,
            selected: undefined,
            channels: [],
            meta: [],
        }
    },
    mounted() {
        this.fetchChannels()
        this.fetchFilters()
    },
    methods: {
        handleClick: function (channel) {
            if (channel !== undefined && channel !== null) {
                this.selected = channel

                document.getElementById('channelEmbed').scrollIntoView()
            }
        },
        fetchChannels: function (link = null) {
            this.loading = true

            let page = 1,
                tags = null

            if (link !== null) {
                const url = new URL(link)

                page = parseInt(url.searchParams.get('page'))

                document.getElementById('streamHeading').scrollIntoView()
            }

            this.filters.filter.forEach((element) => {
                if (tags === null) {
                    tags = element.id + ','
                } else {
                    tags += element.id + ','
                }
            })

            if (tags !== null) {
                tags = tags.replace(/,\s*$/, '')
            }

            window.axios
                .get(
                    this.route('channels.list', {
                        page: page,
                        'filter[tag]': tags,
                        order: this.filters.order,
                    }),
                )
                .then((response) => {
                    this.loading = false
                    this.error = false
                    this.channels = response.data.data
                    this.meta = response.data.meta
                    this.total = response.data.meta.total

                    if (this.total === 0) {
                        this.error = true
                        this.errorMessage =
                            this.filters.filter.length > 0
                                ? // eslint-disable-next-line quotes
                                  "Well this is awkward, we can't seem to find any streams that match your search filters"
                                : // eslint-disable-next-line quotes
                                  "Well this is awkward, we can't seem to find any streams that are currently live."
                    }
                })
                .catch(() => {
                    this.error = true
                    this.errorMessage = 'Failed to fetch streams.'
                    this.loading = false
                })
        },
        fetchFilters: function () {
            window.axios
                .get(this.route('tags.list'))
                .then((response) => {
                    this.options.filters = response.data.data
                })
                .catch(() => {})
        },
        toggleFilters: function () {
            this.displayFilters = !this.displayFilters
        },
    },
}
</script>
