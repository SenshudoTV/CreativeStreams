<template>
    <div>
        <nav class="relative z-0 inline-flex rounded-md space-x-2" aria-label="Pagination">
            <a
                href="#"
                class="relative inline-flex items-center px-2 py-2 rounded shadow-sm border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{ 'disabled:opacity-75 cursor-not-allowed': page.current <= 1 }"
                @click.prevent="$emit('click', page.previous)"
            >
                <span class="sr-only">Previous</span>
                <IconChevronLeft class="w-5 h-5" aria-hidden="true" />
            </a>
            <a
                v-for="p in page.range"
                :key="`page${p}`"
                href="#"
                :aria-current="page.current === p ? 'page' : null"
                class="relative inline-flex items-center px-4 py-2 rounded shadow-sm border text-sm font-medium"
                :class="{
                    'z-10 bg-blue-50 border-blue-200 text-blue-600': page.current === p,
                    'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page.current !== p,
                }"
                @click.prevent="$emit('click', p)"
            >
                {{ p }}
            </a>
            <a
                href="#"
                class="relative inline-flex items-center px-2 py-2 rounded shadow-sm border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{
                    'disabled:opacity-75 cursor-not-allowed': page.current >= page.last,
                }"
                @click.prevent="$emit('click', page.next)"
            >
                <span class="sr-only">Next</span>
                <IconChevronRight class="w-5 h-5" aria-hidden="true" />
            </a>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'Pagination',
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
