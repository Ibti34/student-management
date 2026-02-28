<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    student: Object,
});

const form = useForm({
    name: props.student.name,
    email: props.student.email,
    age: props.student.age,
    university: props.student.university,
});

function submit() {
    form.put('/students/' + props.student.id);
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
      <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg">

        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
          Edit Student
        </h1>

        <form @submit.prevent="submit" class="space-y-4">

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
              Name
            </label>
            <input
              v-model="form.name"
              type="text"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
              Email
            </label>
            <input
              v-model="form.email"
              type="email"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
              Age
            </label>
            <input
              v-model="form.age"
              type="number"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
              University
            </label>
            <input
              v-model="form.university"
              type="text"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>

          <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-300"
          >
            Update Student
          </button>

        </form>

      </div>
    </div>
  </AppLayout>
</template>