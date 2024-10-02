<script setup>
import {usePage} from "@inertiajs/vue3";
</script>

<template>
    <div class="ms-3 flex items-center gap-x-3">
        <h3>Статус телеграмма:</h3>
        <span v-if="usePage().props.telegram.auth.status.id === 0" class="bg-pink-100 text-pink-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-pink-900 dark:text-pink-300">{{ $page.props.telegram.auth.status.message }}</span>
        <span v-if="usePage().props.telegram.auth.status.id === 1" class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ $page.props.telegram.auth.status.message }}</span>
        <span v-if="usePage().props.telegram.auth.status.id === 2" class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ $page.props.telegram.auth.status.message }}</span>
        <span v-if="usePage().props.telegram.auth.status.id === 3" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $page.props.telegram.auth.status.message }}</span>
    </div>
</template>

<style scoped>

</style>
