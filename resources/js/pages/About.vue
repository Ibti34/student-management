<script setup>
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
    name: String,
    email: String,
    age: Number,
    university: String,
});
</script>

<template>
    <AppLayout>
        <h1>About page</h1>
        <br />

        <p>Name: {{ name }}</p>
        <p>Email: {{ email }}</p>
        <p>Age: {{ age }}</p>
        <p>University: {{ university }}</p>
    </AppLayout>
</template>
