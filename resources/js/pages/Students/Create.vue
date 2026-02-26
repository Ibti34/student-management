<script setup>
import AppLayout from '../../layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    age: '',
    university: '',
});

function submit() {
    form.post('/students');
}
</script>

<template>
    <AppLayout>
        <h1 class="mb-4 text-2xl">Add Student</h1>

        <form @submit.prevent="submit">
            <input
                v-model="form.name"
                placeholder="Name"
                class="mb-2 block border p-2"
            />

            <input
                v-model="form.email"
                placeholder="Email"
                class="mb-2 block border p-2"
            />

            <input
                v-model="form.age"
                placeholder="Age"
                class="mb-2 block border p-2"
            />

            <input
                v-model="form.university"
                placeholder="University"
                class="mb-2 block border p-2"
            />

            <button class="bg-blue-500 px-4 py-2 text-white">Save</button>
        </form>
    </AppLayout>
</template>
