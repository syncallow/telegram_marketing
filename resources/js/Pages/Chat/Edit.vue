<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import {Head, useForm} from "@inertiajs/vue3";
import ToastSuccess from "@/Components/ToastSuccess.vue";

const props = defineProps({
    chat: Object
})

const form = useForm({
    id: props.chat.chat_id
})
const submit = () => {
    form.patch(route('chats.update', props.chat.id), {
        onSuccess: () => {

        }
    })
}
</script>

<template>
    <Head title="Редактировать чат"/>
    <MainLayout>
        <ToastSuccess v-if="form.recentlySuccessful">
            Изменено успешно.
        </ToastSuccess>
        <form @submit.prevent="submit" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <input v-model="form.id" type="text" name="id" id="id" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="id" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Chat ID или @username</label>
            </div>
            <PrimaryButton :type="`submit`">
                Сохранить
            </PrimaryButton>
        </form>

    </MainLayout>
</template>

<style scoped>

</style>
