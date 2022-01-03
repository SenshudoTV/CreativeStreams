<template>
    <div class="flex justify-center">
        <nav class="relative z-0 inline-flex rounded-md space-x-2 my-4" aria-label="Pagination">
            <a
                v-if="page.current > 1"
                href="#"
                class="relative inline-flex items-center px-2 py-2 rounded shadow-sm border border-violet-300 bg-violet-500 text-sm font-medium text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
                :class="{ 'disabled:opacity-75 cursor-not-allowed': page.current <= 1 }"
                @click.prevent="$emit('page', page.previous)"
            >
                <span class="sr-only">Previous</span>
                <ChevronLeftIcon class="w-5 h-5" aria-hidden="true" />
            </a>
            <a
                v-for="p in page.range"
                :key="`page${p}`"
                href="#"
                :aria-current="page.current === p ? 'page' : null"
                class="relative inline-flex items-center px-4 py-2 rounded shadow-sm border text-sm font-medium text-white"
                :class="{
                    'bg-violet-400 border-violet-300 dark:bg-gray-700 dark:border-gray-600':
                        page.current === p,
                    'bg-violet-500 border-violet-300 hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700':
                        page.current !== p,
                }"
                @click.prevent="$emit('page', p)"
            >
                {{ p }}
            </a>
            <a
                v-if="page.current < page.last"
                href="#"
                class="relative inline-flex items-center px-2 py-2 rounded shadow-sm border border-violet-300 bg-violet-500 text-sm font-medium text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
                :class="{
                    'disabled:opacity-75 cursor-not-allowed': page.current >= page.last,
                }"
                @click.prevent="$emit('page', page.next)"
            >
                <span class="sr-only">Next</span>
                <ChevronRightIcon class="w-5 h-5" aria-hidden="true" />
            </a>
        </nav>
    </div>
</template>

<script>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/solid'

export default {
    name: 'Pagination',
    components: { ChevronLeftIcon, ChevronRightIcon },
    props: {
        meta: {
            type: Object,
            required: true,
        },
        range: {
            type: Number,
            required: false,
            default: 10,
        },
    },
    data() {
        return {
            page: {
                first: 1,
                previous: null,
                next: null,
                last: null,
                min: 0,
                max: 0,
                range: [],
            },
        }
    },
    watch: {
        $props: {
            handler() {
                this.calcPageRange()
            },
            deep: true,
            immediate: true,
        },
    },
    methods: {
        calcPageRange() {
            let previousPage = this.meta.current_page - 1
            let nextPage = this.meta.current_page + 1
            const maxPagesBeforeCurrentPage = Math.floor(this.range / 2)
            const maxPagesAfterCurrentPage = Math.floor(this.range / 2) - 1

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
            } else if (this.page.current <= maxPagesBeforeCurrentPage) {
                this.page.min = 1
                this.page.max = this.range
            } else if (this.page.current + maxPagesAfterCurrentPage >= this.page.last) {
                this.page.min = this.page.last - this.range + 1
                this.page.max = this.page.last
            } else {
                this.page.min = this.page.current - maxPagesBeforeCurrentPage
                this.page.max = this.page.current + maxPagesAfterCurrentPage
            }

            this.page.range = Array.from(Array(this.page.max + 1 - this.page.min).keys()).map(
                (i) => this.page.min + i,
            )
        },
    },
}
</script>
