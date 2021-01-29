<template>
    <footer class="AppFooter text-light">
        <b-container>
            <b-row>
                <b-col :sm="12" :md="4">

                </b-col>
                <b-col :sm="12" :md="4">

                </b-col>
                <b-col :sm="12" :md="4">

                </b-col>
            </b-row>
            <b-row>
                <b-col class="text-center">
                    <p>&copy; {{ year }} Senshudo Ltd. All Rights Reserved.</p>
                </b-col>
            </b-row>
        </b-container>
    </footer>
</template>

<script>
export default {
    name: 'AppFooter',
    data() {
        const now = new Date();

        return {
            year: now.getFullYear(),
        }
    },
}
</script>