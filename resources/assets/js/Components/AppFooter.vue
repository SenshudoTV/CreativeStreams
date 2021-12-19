<template>
    <footer class="footer">
        <div class="container mx-auto px-4 py-20 text-center text-white">
            &copy; {{ year }} Senshudo. All Rights Reserved.
        </div>
    </footer>
</template>

<script>
export default {
    name: 'AppFooter',
    data() {
        const now = new Date()
        return {
            year: now.getFullYear(),
        }
    },
}
</script>
