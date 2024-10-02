<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import {useForm, Head} from "@inertiajs/vue3";
import ToastSuccess from "@/Components/ToastSuccess.vue";
import InputLabel from "@/Components/InputLabel.vue";

const form = useForm({
    image: '',
    description: '',
    enable_to_send: true,
    frequency: 'daily',
    chat_ids: []
})

const chats = ref([]);

const getChats = () => {
    axios.get(route('chats.get.all'))
        .then(res => {
            console.log(res);
            chats.value = res.data
        })
}

const store = () => {
    form.post(route('posts.store'))
}

onMounted(() => {
    getChats()
})

</script>

<template>
    <Head title="Добавить пост"/>
    <MainLayout>
        <ToastSuccess v-if="form.recentlySuccessful">
            Добавлено успешно.
        </ToastSuccess>
        <form class="max-w-lg mx-auto">
            <div class="mb-3">
                <InputLabel>Загрузить картинку</InputLabel>
                <input @input="form.image = $event.target.files[0]" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help" id="user_avatar" type="file">
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">Картинка которая будет показана над описанием</div>
            </div>
            <div class="mb-3">
                <input v-model="form.enable_to_send" checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Включить в рассылку</label>
            </div>
            <div class="mb-3">
                <InputLabel>Частота рассылки</InputLabel>
                <select v-model="form.frequency" id="frequency" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500">
                    <option selected>Выберите частоту</option>
                    <option value="daily">Каждый день</option>
                    <option value="weekly">Каждую неделю</option>
                    <option value="monthly">Каждый месяц</option>
                </select>
            </div>
            <div class="mb-3">
                <InputLabel>Чаты для рассылки</InputLabel>
                <select v-model="form.chat_ids" multiple id="chats" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500">
                    <option v-for="(chat, index) in chats.data" :key="`chat-idx_${index}`" :value="chat.id">{{ chat.title }}</option>
                </select>
            </div>
            <div class="mb-3">
                <InputLabel>Описание</InputLabel>
                <textarea v-model="form.description" id="message" rows="20" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500" placeholder="Напишите описание..."></textarea>
            </div>
            <PrimaryButton @click.prevent="store">
                Добавить
            </PrimaryButton>
        </form>
    </MainLayout>
</template>

<style scoped>

</style>
