<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue'; // Added missing import

const props = defineProps({
    student: Object,
});

const form = useForm({
    name: props.student.name,
    email: props.student.email,
    age: props.student.age,
    phone: props.student.phone,
    university: props.student.university,
    department: props.student.department,
});

function submit() {
    // Sending the PUT request to the update route in StudentController
    form.put('/students/' + props.student.id, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Add a notification logic here
        }
    });
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
      <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg">

        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
          Edit Student
        </h1>

        <form @submit.prevent="submit" class="space-y-4">

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
              :class="{'border-red-500': form.errors.name}"
            />
            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
              :class="{'border-red-500': form.errors.email}"
            />
            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-1">Age</label>
              <input
                v-model="form.age"
                type="number"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-1">Phone</label>
              <input
                v-model="form.phone"
                type="text"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">University</label>
            <input
              v-model="form.university"
              type="text"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Department</label>
            <input
              v-model="form.department"
              type="text"
              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300 disabled:bg-blue-300"
          >
            {{ form.processing ? 'Updating...' : 'Update Student' }}
          </button>

          <div class="text-center mt-4">
            <Link href="/students" class="text-sm text-gray-500 hover:underline">Cancel and Go Back</Link>
          </div>

        </form>
      </div>
    </div>
  </AppLayout>
</template>