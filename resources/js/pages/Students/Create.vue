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
<div class="p-6 max-w-lg">

    <h1 class="text-2xl font-bold mb-6">Create Student</h1>

    <form @submit.prevent="submit" class="space-y-4">

        <!-- Name -->
        <div>
            <input v-model="form.name" placeholder="Name"
                class="border p-2 w-full rounded" />
            <div v-if="form.errors.name" class="text-red-500 text-sm">
                {{ form.errors.name }}
            </div>
        </div>

        <!-- Email -->
        <div>
            <input v-model="form.email" placeholder="Email"
                class="border p-2 w-full rounded" />
            <div v-if="form.errors.email" class="text-red-500 text-sm">
                {{ form.errors.email }}
            </div>
        </div>

        <!-- Age -->
        <div>
            <input type="number" v-model="form.age" placeholder="Age"
                class="border p-2 w-full rounded" />
            <div v-if="form.errors.age" class="text-red-500 text-sm">
                {{ form.errors.age }}
            </div>
        </div>

        <!-- University -->
        <div>
            <input v-model="form.university" placeholder="University"
                class="border p-2 w-full rounded" />
            <div v-if="form.errors.university" class="text-red-500 text-sm">
                {{ form.errors.university }}
            </div>
        </div>

        <button
            type="submit"
            class="bg-green-500 text-white px-4 py-2 rounded"
        >
            Save Student
        </button>

    </form>

</div>
</AppLayout>
</template>