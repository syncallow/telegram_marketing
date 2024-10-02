<script setup>
import {Head, Link} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import ToastSuccess from "@/Components/ToastSuccess.vue";
import ToastError from "@/Components/ToastError.vue";

const posts = ref([]);
const error = ref('');
const success = ref('');

const getPosts = (page = 1) => {
    axios.post(route('posts.show'), {
        page: page
    })
        .then(res => {
            console.log(res);
            posts.value = res.data;
        })
}

const sendPost = (id) => {
    axios.post(route('posts.send', id))
        .then(res => {
            console.log(res);
            success.value = res.data.message;
        })
        .catch(err => {
            console.log(err);
            error.value = err.response.data.message;
        })

}

onMounted(() => {
    getPosts()
})

</script>

<template>
    <Head title="Рекламные посты"/>
    <ToastError v-if="error" @click.prevent="error = ''">
        {{ error }}
    </ToastError>
    <ToastSuccess v-if="$page.props.status.success">
        {{ $page.props.status.success }}
    </ToastSuccess>
    <ToastSuccess v-if="success" @click.prevent="success = ''">
        {{ success }}
    </ToastSuccess>
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
                            <Link :href="route('posts.create')">
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
                                <th scope="col" class="px-4 py-3">Изображение</th>
                                <th scope="col" class="px-4 py-3">Описание</th>
                                <th scope="col" class="px-4 py-3">Рассылка</th>
                                <th scope="col" class="px-4 py-3">
                                    Действия
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(post, index) in posts.data" :key="`post-${index}`" class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">
                                    <img :src="post.image" class="w-[100px]" alt="">
                                </td>
                                <td class="px-4 py-3 truncate max-w-[300px]">{{ post.description }}</td>
                                <td class="px-4 py-3">
                                    <span v-if="post.enable_to_send" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Вкл.</span>
                                    <span v-else class="bg-pink-100 text-pink-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-pink-900 dark:text-pink-300">Выкл.</span>
                                </td>
                                <td class="px-4 py-3">
                                    <button @click.prevent="sendPost(post.id)" class="block py-2 px-4 text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Отправить</button>
                                    <Link :href="route('posts.edit', post.id)" class="block py-2 px-4 text-emerald-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Редактировать</Link>
                                    <Link :href="route('posts.delete', post.id)" method="delete" as="button" class="block py-2 px-4 text-sm text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Удалить</Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination v-if="posts.meta" :pagination="posts.meta" @change-page="getPosts"/>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<style scoped>

</style>
