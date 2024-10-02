<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'button'
    }
})
</script>

<template>
    <button :type="type" class="flex items-center justify-center text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-emerald-600 dark:hover:bg-emerald-700 focus:outline-none dark:focus:ring-emerald-800">
        <slot />
    </button>
</template>
