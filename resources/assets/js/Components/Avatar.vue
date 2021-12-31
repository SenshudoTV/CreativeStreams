<template>
    <svg class="avatar" role="none" :style="`height: ${height}px; width: ${width}px;`">
        <mask :id="avatarID">
            <circle x="0" y="0" :cx="radius" :cy="radius" fill="white" :r="radius"></circle>
        </mask>
        <g :mask="`url(#${avatarID})`">
            <image
                x="0"
                y="0"
                height="100%"
                preserveAspectRatio="xMidYMid slice"
                width="100%"
                :xlink:href="src"
                :style="`height: ${height}px; width: ${width}px;`"
            ></image>
            <circle class="avatar-outline" :cx="radius" :cy="radius" :r="radius"></circle>
        </g>
    </svg>
</template>

<script>
import uniqid from 'uniqid'

export default {
    name: 'Avatar',
    props: {
        height: {
            type: Number,
            required: true,
        },
        width: {
            type: Number,
            required: true,
        },
        src: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            avatarID: uniqid('avatar-'),
            radius: this.width / 2,
        }
    },
}
</script>
