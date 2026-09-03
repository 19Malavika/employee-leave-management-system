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
        ? 'block w-full border-l-4 border-[#2E75B5] bg-[#2E75B5]/5 px-4 py-2 text-left text-base font-semibold text-[#2E75B5] transition duration-200 ease-in-out focus:outline-none focus:bg-[#2E75B5]/10'
        : 'block w-full border-l-4 border-transparent px-4 py-2 text-left text-base font-medium text-slate-600 transition duration-200 ease-in-out hover:border-slate-200 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:bg-slate-50 focus:text-slate-900',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
```
