<script setup>
defineProps({
    wide: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="min-h-screen w-full bg-slate-50">
        <main class="w-full">
            <slot />
        </main>
    </div>
</template>
