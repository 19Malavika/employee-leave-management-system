
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2.5 text-left text-sm font-medium text-slate-600 transition duration-200 ease-in-out hover:bg-slate-50 hover:text-[#2E75B5] focus:bg-slate-50 focus:text-[#2E75B5] focus:outline-none"
    >
        <slot />
    </Link>
</template>

