<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({
    focus: () => input.value?.focus(),
});
</script>

<template>
    <input
        ref="input"
        v-model="model"
        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm transition duration-150 ease-in-out hover:border-slate-300 focus:border-[#2E75B5] focus:outline-none focus:ring-4 focus:ring-[#2E75B5]/15 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-500"
    />
</template>
