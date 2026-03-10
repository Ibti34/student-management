<script setup>
import AppLayout from '../../layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    age: '',
    phone: '',
    university: '',
    department:'',
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
<div v-if="Object.keys(form.errors).length" class="bg-red-100 p-4 mb-4">
    <pre>{{ form.errors }}</pre>
</div>
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
<!-- phone number -->
  <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
          phone number
          </label>
          <input
            v-model="form.phone "
            type="number"
            placeholder="Enter phone number"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>
        <!-- University -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
            University
          </label>
         <select
  v-model="form.university"
  class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
>
  <option value="">Select University</option>
  <option value="Addis Ababa University">Addis Ababa University</option>
  <option value="Adama Science and Technology University">Adama Science and Technology University</option>
  <option value="Jimma University">Jimma University</option>
  <option value="Bahir Dar University">Bahir Dar University</option>
  <option value="Hawassa University">Hawassa University</option>
  <option value="Mekelle University">Mekelle University</option>
  <option value="Dire Dawa University">Dire Dawa University</option>
  <option value="Debre Berhan University">Debre Berhan University</option>
  <option value="Arba Minch University">Arba Minch University</option>
  <option value="Wolaita Sodo University">Wolaita Sodo University</option>
</select>
        </div>
        <!-- departement -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
            Department
            </label>
      <select v-model="form.department" class="w-full border rounded-lg px-4 py-2">
  <option value="">Select Department</option>
  <option value="Software Engineering">Software Engineering</option>
  <option value="Computer Science">Computer Science</option>
  <option value="Information Systems">Information Systems</option>
  <option value="UI/UX Design">UI/UX Design</option>
</select>
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