<template>
    <div>
        <Featured :channel="selected" />
        <div class="container">
            <div class="row mb-2" v-if="total > 0" id="filterHeader">
                <div class="col-sm-12 col-md-6 col-lg-6 ps-0">
                    <h2 class="mb-0" id="streamHeading">{{ total }} Streams</h2>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 pe-0 text-end">
                    <button class="btn btn-primary" type="button" @click.prevent="toggleFilters">
                        <font-awesome-icon :icon="['fas', 'filter']" />
                        Filter
                    </button>
                </div>
            </div>
            <div class="row mb-2" id="filterContainer" v-if="displayFilters">
                <div class="col-sm-12 col-md-6 col-lg-8 ps-0">
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
                </div>
                <div class="col-sm-12 col-md-4 col-lg-3 px-0">
                    <select class="form-control custom-select" v-model="filters.order">
                        <option
                            v-for="(option, index) in options.order"
                            :key="index"
                            :value="option.value"
                        >
                            {{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-2 col-lg-1 pe-0">
                    <button class="btn btn-primary w-100" type="button" @click="fetchChannels()">
                        <font-awesome-icon :icon="['fas', 'search']" />
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col loading text-center" v-if="loading">
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
                </div>
                <div class="col" v-else-if="error">
                    <div class="alert alert-danger text-center flex-fill">
                        {{ errorMessage }}
                    </div>
                </div>
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
                    <div class="col-12" v-if="meta.last_page > 1">
                        <nav aria-label="Page Navigation">
                            <ul class="pagination my-3 justify-content-center">
                                <li class="page-item" v-if="page.current > page.first">
                                    <a
                                        href="#"
                                        @click.prevent="fetchChannels(page.first, true)"
                                        class="page-link"
                                        aria-link="First Page"
                                        title="First Page"
                                    >
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item" v-if="page.current > 1">
                                    <a
                                        href="#"
                                        @click.prevent="fetchChannels(page.current - 1, true)"
                                        class="page-link"
                                        aria-link="Previous Page"
                                        title="Previous Page"
                                    >
                                        <span aria-hidden="true">&lt;</span>
                                    </a>
                                </li>
                                <li
                                    v-for="p in page.range"
                                    :key="`page${p}`"
                                    :class="{
                                        'page-item': true,
                                        active: page.current === p,
                                    }"
                                    :aria-current="page.current === p ? 'page' : null"
                                >
                                    <a
                                        href="#"
                                        @click.prevent="fetchChannels(p, true)"
                                        class="page-link"
                                        :aria-link="`Page ${p}`"
                                        :title="`Page ${p}`"
                                    >
                                        {{ p }}
                                    </a>
                                </li>
                                <li
                                    class="page-item"
                                    v-if="page.current >= 1 && page.current < page.last"
                                >
                                    <a
                                        href="#"
                                        @click.prevent="fetchChannels(page.current + 1, true)"
                                        class="page-link"
                                        aria-link="Next Page"
                                        title="Next Page"
                                    >
                                        <span aria-hidden="true">&gt;</span>
                                    </a>
                                </li>
                                <li
                                    class="page-item"
                                    v-if="page.current !== page.last && page.next < page.last"
                                >
                                    <a
                                        href="#"
                                        @click.prevent="fetchChannels(page.last, true)"
                                        class="page-link"
                                        aria-link="Last Page"
                                        title="Last Page"
                                    >
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
            </div>
        </div>
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
            range: 15,
            page: {
                first: 1,
                previous: null,
                current: 1,
                next: null,
                last: null,
                min: 0,
                max: 0,
                range: [],
            },
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
        fetchChannels: function (page = 1, pagination = false) {
            this.loading = true

            let tags = null

            if (pagination) {
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
                    this.calcPageRange()

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
        calcPageRange: function () {
            let previousPage = this.meta.current_page - 1
            let nextPage = this.meta.current_page + 1
            let maxPagesBeforeCurrentPage = Math.floor(this.range / 2)
            let maxPagesAfterCurrentPage = Math.floor(this.range / 2) - 1

            if (previousPage < 1) {
                previousPage = null
            }

            if (nextPage > this.meta.last_page) {
                nextPage = null
            }

            this.page.current = this.meta.current_page
            this.page.previous = previousPage
            this.page.next = nextPage
            this.page.last = this.meta.last_page

            if (this.page.last <= this.range) {
                this.page.min = 1
                this.page.max = this.page.last
            } else {
                if (this.page.current <= maxPagesBeforeCurrentPage) {
                    this.page.min = 1
                    this.page.max = this.range
                } else if (this.page.current + maxPagesAfterCurrentPage >= this.page.last) {
                    this.page.min = this.page.last - this.range + 1
                    this.page.max = this.page.last
                } else {
                    this.page.min = this.page.current - maxPagesBeforeCurrentPage
                    this.page.max = this.page.current + maxPagesAfterCurrentPage
                }
            }

            this.page.range = Array.from(Array(this.page.max + 1 - this.page.min).keys()).map(
                (i) => this.page.min + i,
            )
        },
    },
}
</script>
