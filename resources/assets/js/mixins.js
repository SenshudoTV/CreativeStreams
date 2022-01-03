export default {
    methods: {
        formatNumber(num) {
            return parseInt(num)
                .toString()
                .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1)
        },
    },
}
