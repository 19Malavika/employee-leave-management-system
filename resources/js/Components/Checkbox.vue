<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },

    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(value) {
        emit('update:checked', value);
    },
});
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="h-4 w-4 rounded border-slate-300 text-[#2E75B5] shadow-sm transition focus:ring-2 focus:ring-[#2E75B5]/30 focus:ring-offset-0"
    />
</template>