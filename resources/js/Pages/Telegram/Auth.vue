<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import ToastSuccess from "@/Components/ToastSuccess.vue";
import ToastError from "@/Components/ToastError.vue";

const authState = ref(0);
const form = reactive({
    value: ''
})
const success = ref()
const error = ref()
const checkAuth = () => {
    axios.post(route('telegram.status'))
        .then(res => {
            console.log(res);
            authState.value = res.data.id
        })
}

const sendValue = () => {
    axios.post(route('telegram.login'), { value: form.value })
        .then(res => {
            console.log(res);
            checkAuth()
        })
}

onMounted(() => {
    checkAuth()

    Echo.private('users.' + usePage().props.auth.user.id)
        .notification((notification) => {
            if (notification['type'] === 'App\\Notifications\\ErrorNotification') {
                console.error(notification['message']);
                error.value = notification['message'];
            }
            if (notification['type'] === 'App\\Notifications\\PhoneSentNotification') {
                form.value = '';
                console.log(notification['message']);
                success.value = notification['message'];
            }
            if (notification['type'] === 'App\\Notifications\\CodeSentNotification') {
                form.value = '';
                console.log(notification['message']);
                success.value = notification['message'];
            }
            if (notification['type'] === 'App\\Notifications\\PasswordSentNotification') {
                form.value = '';
                console.log(notification['message']);
                success.value = notification['message'];
            }
            return checkAuth()
        })
})

</script>

<template>
<Head title="Авторизация в Telegram" />
    <MainLayout>
        <ToastSuccess v-if="success">
            {{ success }}
        </ToastSuccess>
        <ToastError v-if="error">
            {{ error }}
        </ToastError>
        <form v-if="authState !== 3" class="max-w-sm mx-auto">
            <div v-if="authState === 0" class="mb-5">
                <label for="value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Введите номер телефона</label>
                <input type="text" id="value" v-model="form.value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div v-if="authState === 1" class="mb-5">
                <label for="value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Введите код подтверждения</label>
                <input type="text" id="value" v-model="form.value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div v-if="authState === 2" class="mb-5">
                <label for="value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Введите Пароль</label>
                <input type="text" id="value" v-model="form.value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <button @click.prevent="sendValue" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Отправить</button>
        </form>
        <div v-else class="p-4 my-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Вы авторизованы</span> Вам не нужно ничего делать!
        </div>
    </MainLayout>
</template>

<style scoped>

</style>
