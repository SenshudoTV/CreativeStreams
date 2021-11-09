export default {
    methods: {
        formatDate(value, timezone = 'UTC', format = 'DD/MM/YYYY HH:mm') {
            if (value) {
                if (format === null || format === '' || format === 'd/m/Y H:i:s') {
                    format = 'DD/MM/YYYY HH:mm'
                } else if (format === 'd M Y, H:i') {
                    format = 'DD MMM YYYY, HH:mm'
                } else if (format === 'd M Y H:i') {
                    format = 'DD MMM YYYY HH:mm'
                } else if (format === "M jS, 'y, H:i") {
                    format = "MMM Do, 'YY, HH:mm"
                } else if (format === 'D M d, Y g:i a') {
                    format = 'ddd MMM DD, YYYY hh:mm a'
                } else if (format === 'F jS, Y, g:i a') {
                    format = 'MMMM Do, YYYY, hh:mm a'
                } else if (format === '|d M Y|, H:i') {
                    format = '|DD MMM YYYY|, HH:mm'
                } else if (format === '|F jS, Y|, g:i a') {
                    format = '|MMMM Do, YYYY|, hh:mm a'
                }

                if (timezone === null || timezone === '') {
                    timezone = 'UTC'
                }

                return this.moment(String(value)).tz(timezone).format(format)
            }
        },
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
