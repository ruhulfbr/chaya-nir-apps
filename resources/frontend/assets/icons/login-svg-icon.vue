<script setup>
defineProps({
    color: {
        type: String,
        default: "#757779", // Default color
    },
    width: {
        type: String,
        default: "30px", // Default width
    },
    height: {
        type: String,
        default: "30px", // Default height
    },
});
</script>

<template>
    <svg
        :fill="color"
        :width="width"
        :height="height"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
    >
        <!-- Outer box representing the login boundary -->
        <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" stroke-width="2" />
        <path
            d="M14 16L18 12L14 8"
            stroke="currentColor"
            stroke-width="2"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
        <path
            d="M18 12H8"
            stroke="currentColor"
            stroke-width="2"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
</template>
