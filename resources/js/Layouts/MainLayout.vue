<script setup>
import 'flowbite';
import Sidebar from "@/Components/Sidebar.vue";
import Navigation from "@/Components/Navigation.vue";


</script>

<template>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <Navigation/>

        <!-- Sidebar -->
        <Sidebar/>

        <main class="p-4 md:ml-64 h-auto pt-20">
            <slot/>
        </main>
    </div>
</template>

<style scoped>

</style>
