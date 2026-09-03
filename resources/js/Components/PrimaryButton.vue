<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex w-full items-center justify-center rounded-xl bg-[#2E75B5] px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#2E75B5]/25 transition duration-200 ease-in-out hover:bg-[#245f94] hover:shadow-xl hover:shadow-[#2E75B5]/30 focus:outline-none focus:ring-4 focus:ring-[#2E75B5]/20 active:bg-[#1e507c] active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:bg-[#2E75B5]"
    >
        <slot />
    </button>
</template>
