```vue
<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },

    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center border-b-2 border-[#2E75B5] px-1 pt-1 text-sm font-semibold leading-5 text-slate-900 transition duration-200 ease-in-out focus:outline-none focus:border-[#2E75B5]'
        : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-slate-500 transition duration-200 ease-in-out hover:border-slate-300 hover:text-slate-800 focus:outline-none focus:border-[#2E75B5] focus:text-slate-800',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
```
