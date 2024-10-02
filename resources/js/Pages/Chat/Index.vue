<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Link, Head} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import axios from "axios";
import {onMounted, ref} from "vue";

const chats = ref([]);

const getChats = (page = 1) => {
    axios.post(route('chats.show'), {
        page: page
    })
        .then(res => {
            console.log(res);
            chats.value = res.data
        })
}

onMounted(() => {
    getChats()
})
</script>

<template>
    <Head title="Чаты"/>
    <MainLayout>
        <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Поиск</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500" placeholder="Поиск" required="">
                                </div>
                            </form>
                        </div>
                        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <Link :href="route('chats.create')">
                                <PrimaryButton>
                                    Добавить
                                </PrimaryButton>
                            </Link>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Chat ID</th>
                                <th scope="col" class="px-4 py-3">Название</th>
                                <th scope="col" class="px-4 py-3">
                                    Действия
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(chat, index) in chats.data" :key="`chat-${index}`" class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ chat.chat_id }}</td>
                                <td class="px-4 py-3">{{ chat.title }}</td>
                                <td class="px-4 py-3 flex items-center justify-start">
                                    <Link :href="route('chats.edit', chat.id)" class="block py-2 px-4 text-emerald-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</Link>
                                    <Link :href="route('chats.delete', chat.id)" method="delete" as="button" class="block py-2 px-4 text-sm text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination v-if="chats.meta" :pagination="chats.meta" @change-page="getChats"/>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<style scoped>

</style>
