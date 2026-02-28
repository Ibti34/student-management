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
  <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4">

    <div class="bg-white shadow-xl rounded-2xl w-full max-w-lg p-8">

      <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
        Create Student
      </h2>

      <form @submit.prevent="submit" class="space-y-6">

        <!-- Name -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
            Name
          </label>
          <input
            v-model="form.name"
            type="text"
            placeholder="Enter student name"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
            Email
          </label>
          <input
            v-model="form.email"
            type="email"
            placeholder="Enter student email"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>

        <!-- Age -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
            Age
          </label>
          <input
            v-model="form.age"
            type="number"
            placeholder="Enter age"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>

        <!-- University -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
            University
          </label>
          <input
            v-model="form.university"
            type="text"
            placeholder="Enter university name"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>

        <!-- Button -->
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition shadow-md"
        >
          Save Student
        </button>

      </form>

    </div>

  </div>
  </AppLayout>
</template>