<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  section: '',
  academic_year: '2026/2027',
  homeroom_teacher: '',
  capacity: '',
})

const submit = () => {
  form.post(route('classes.store'))
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
      <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Create Cohort</h1>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Program</label>
            <input v-model="form.name" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" placeholder="Software Engineering" />
            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-1">Cohort / Year</label>
              <input v-model="form.section" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" placeholder="Year 1" />
              <div v-if="form.errors.section" class="text-red-500 text-xs mt-1">{{ form.errors.section }}</div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-1">Academic Year</label>
              <input v-model="form.academic_year" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" placeholder="2026/2027" />
              <div v-if="form.errors.academic_year" class="text-red-500 text-xs mt-1">{{ form.errors.academic_year }}</div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Academic Advisor</label>
            <input v-model="form.homeroom_teacher" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" placeholder="Dr. Bekele" />
            <div v-if="form.errors.homeroom_teacher" class="text-red-500 text-xs mt-1">{{ form.errors.homeroom_teacher }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Capacity</label>
            <input v-model="form.capacity" type="number" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" placeholder="40" />
            <div v-if="form.errors.capacity" class="text-red-500 text-xs mt-1">{{ form.errors.capacity }}</div>
          </div>

          <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300 disabled:bg-blue-300">
            {{ form.processing ? 'Saving...' : 'Save Cohort' }}
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
