<script setup>
import {computed} from "vue";

const props = defineProps({
    pagination: Object
})

const links = computed(() => {
    const totalVisible= 5;
    const pages = [];
    const currentPage = props.pagination.current_page;
    const lastPage = props.pagination.last_page;
    let startPage = currentPage - Math.floor(totalVisible / 2);
    let endPage =  currentPage + Math.floor(totalVisible / 2);

    if (startPage < 1) {
        startPage = 1;
        endPage = Math.min(lastPage, startPage + totalVisible - 1);
    }

    if (endPage > lastPage) {
        endPage = lastPage;
        startPage = Math.max(1, endPage - totalVisible + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }

    return pages;
})

const emit = defineEmits(['changePage'])

</script>

<template>
    <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
            <span  class="text-sm font-normal text-gray-500 dark:text-gray-400">
                <template v-if="pagination.from && pagination.to">
                    Показано
                    <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.from }}-{{ pagination.to }}</span>
                    из
                </template>
                <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.total }}</span>
            </span>
        <ul class="flex items-center -space-x-px h-8 text-sm">
            <li v-if="pagination.current_page > 1">
                <a href="#" @click.prevent="emit('changePage', pagination.links[pagination.current_page - 1].label)" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <span class="sr-only">Пред</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                </a>
            </li>
            <li v-for="(link, index) in links" :key="`pagination=${index}`">
                <a
                    href="#"
                    class="flex items-center justify-center px-3 h-8 leading-tight border"
                    @click.prevent="emit('changePage', link)"
                    :class="{
                    'text-emerald-600 bg-emerald-50 border-emerald-300 hover:bg-emerald-100 hover:text-emerald-700' : link === pagination.current_page,
                    'bg-white text-gray-500 border-gray-300 hover:bg-gray-100 hover:text-gray-700' : link !== pagination.current_page
                    }"
                >
                    {{ link }}
                </a>
            </li>

            <li>
                <a v-if="pagination.links[pagination.links.length - 1].url" @click.prevent="emit('changePage', pagination.links[pagination.current_page + 1].label)" href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <span class="sr-only">След</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</template>

<style scoped>

</style>
