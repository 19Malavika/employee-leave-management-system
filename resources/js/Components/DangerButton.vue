
<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 ease-in-out hover:bg-red-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:ring-offset-2 active:bg-red-800 disabled:cursor-not-allowed disabled:opacity-50"
    >
        <slot />
    </button>
</template>

