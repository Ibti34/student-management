<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    age: '',
});

function submit() {
    form.post('/student');
}
</script>

<template>
    <AppLayout>
        <h1>Student Form</h1>

        <form @submit.prevent="submit">
            <input v-model="form.name" placeholder="Name" />

            <br /><br />

            <input v-model="form.age" placeholder="Age" />

            <br /><br />

            <button type="submit">Submit</button>
        </form>
    </AppLayout>
</template>
