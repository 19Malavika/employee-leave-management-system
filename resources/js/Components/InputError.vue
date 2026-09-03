
<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class="mt-2">
        <p class="text-xs font-medium leading-5 text-red-600">
            {{ message }}
        </p>
    </div>
</template>

