<template>
    <div class="flex flex-col md:flex-row mt-3 gap-3" :class="{ hidden: !show }">
        <VueMultiselect
            v-model="form.tags"
            :options="tags"
            :searchable="true"
            :close-on-select="false"
            :allow-empty="true"
            :multiple="true"
            placeholder="Select A Category, Tag or Hashtag..."
            label="tag"
            track-by="id"
            class="flex-none md:flex-1 w-full md:w-auto"
        />

        <Listbox
            as="div"
            v-model="form.sort"
            class="relative text-left flex flex-none md:flex-1 w-full md:max-w-[30%]"
        >
            <ListboxButton
                class="inline-block align-middle w-full px-4 py-2 text-sm font-medium shadow-sm rounded-md border focus:outline-none border-violet-300 bg-violet-500 text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700 cursor-default"
            >
                <span class="block truncate text-left">{{ selectedLabel.label }}</span>
                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        class="w-5 h-5 text-gray-400"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </span>
            </ListboxButton>
            <ListboxOptions
                class="absolute right-0 top-full w-72 mt-2 py-1 origin-top-right bg-violet-500 dark:bg-gray-800 divide-y divide-violet-100 dark:divide-gray-600 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <ListboxOption
                    as="template"
                    v-for="(sort, index) in order"
                    :key="`sort${index}`"
                    v-slot="{ active, selected }"
                    :value="sort.value"
                >
                    <li
                        class="cursor-default select-none relative py-2 px-4 text-white bg-violet-500 dark:bg-gray-800"
                        :class="{
                            'bg-violet-400 dark:bg-gray-700': active || selected,
                        }"
                    >
                        <span class="block truncate" :class="{ 'font-medium': selected }">
                            {{ sort.label }}
                        </span>
                    </li>
                </ListboxOption>
            </ListboxOptions>
        </Listbox>

        <span
            class="relative z-0 inline-flex flex-none justify-center md:justify-end w-full md:w-auto"
        >
            <button
                type="button"
                class="relative inline-flex items-center px-4 py-2 rounded-l-md border focus:z-10 shadow-sm text-sm font-medium focus:outline-none border-violet-300 bg-violet-500 text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
                @click.prevent="applyFilters"
            >
                Apply Filters
            </button>
            <button
                type="button"
                class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-l-0 focus:z-10 shadow-sm text-sm font-medium focus:outline-none border-violet-300 bg-violet-500 text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
                @click.prevent="$emit('clear')"
            >
                Clear Filters
            </button>
        </span>
    </div>
</template>

<script>
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import VueMultiselect from 'vue-multiselect'

export default {
    name: 'Filters',
    components: { Listbox, ListboxButton, ListboxOptions, ListboxOption, VueMultiselect },
    props: {
        filters: {
            type: Object,
            required: true,
        },
        show: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    data() {
        return {
            tags: [],
            order: [
                { value: 'viewers-desc', label: 'Viewers (High to Low)' },
                { value: 'viewers-asc', label: 'Viewers (Low to High)' },
                { value: 'stream_created-desc', label: 'Broadcast Time (Longest)' },
                { value: 'stream_created-asc', label: 'Broadcast Time (Recently Started)' },
            ],
            form: {
                tags: this.filters.tags || null,
                sort:
                    this.filters.sort !== null && this.filters.direction !== null
                        ? [this.filters.sort, this.filters.direction].join('-')
                        : 'stream_created-asc',
            },
        }
    },
    computed: {
        selectedLabel() {
            return this.order.find((o) => o.value === this.form.sort)
        },
    },
    mounted() {
        this.fetchTags()
    },
    methods: {
        fetchTags() {
            window.axios.get(this.route('tags.list')).then(({ data: { data } }) => {
                this.tags = data
            })
        },
        applyFilters() {
            this.$emit('apply', this.form)
        },
    },
}
</script>
