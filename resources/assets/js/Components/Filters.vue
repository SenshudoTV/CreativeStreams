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
            v-model="form.order"
            class="relative text-left flex flex-none md:flex-1 w-full md:max-w-[30%]"
        >
            <ListboxButton
                class="inline-block align-middle w-full px-4 py-2 text-sm font-medium shadow-sm rounded-md border focus:outline-none border-violet-300 bg-violet-500 text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
            >
                {{ selectedLabel.label }}
            </ListboxButton>
            <ListboxOptions
                class="absolute right-0 top-full w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <ListboxOption
                    v-for="(sort, index) in order"
                    :key="`sort${index}`"
                    :value="sort.value"
                    class="p-2 cursor-pointer"
                >
                    {{ sort.label }}
                </ListboxOption>
            </ListboxOptions>
        </Listbox>

        <span
            class="relative z-0 inline-flex flex-none justify-center md:justify-end w-full md:w-auto"
        >
            <button
                type="button"
                class="relative inline-flex items-center px-4 py-2 rounded-l-md border focus:z-10 shadow-sm text-sm font-medium focus:outline-none border-violet-300 bg-violet-500 text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
            >
                Apply Filters
            </button>
            <button
                type="button"
                class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-l-0 focus:z-10 shadow-sm text-sm font-medium focus:outline-none border-violet-300 bg-violet-500 text-white hover:bg-violet-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
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
                { value: 'created-desc', label: 'Broadcast Time (Longest)' },
                { value: 'created-asc', label: 'Broadcast Time (Recently Started)' },
            ],
            form: {
                tags: null,
                order: 'created-asc',
            },
        }
    },
    computed: {
        selectedLabel() {
            return this.order.find((o) => o.value === this.form.order)
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
