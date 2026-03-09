<script setup>
import AppLayout from '../../layouts/AppLayout.vue'
import { reactive } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

// Props from parent
const props = defineProps({
  students: Array
})

// Flash messages from Laravel
const flash = usePage().props.flash || {}

// Make attendance reactive for each student
const attendance = reactive(
  props.students.reduce((acc, student) => {
    acc[student.id] = '' // initially empty
    return acc
  }, {})
)

// Initialize Inertia form
const form = useForm({
  date: '',
  attendance: attendance
})

// Submit function
function submit() {
  if (!form.date) {
    alert('Please select a date before saving.')
    return
  }

  // Debug: log form data
  console.log('Submitting form:', JSON.parse(JSON.stringify(form)))

  // Post form to Laravel route
  form.post('/attendance/bulk', {
    onSuccess: () => {
      alert('Attendance saved successfully!')
    },
    onError: (errors) => {
      console.error('Validation errors:', errors)
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 max-w-4xl mx-auto">

      <h1 class="text-3xl font-bold mb-6">Take Attendance</h1>

      <!-- Success message -->
      <div v-if="flash.success" class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ flash.success }}
      </div>

      <!-- Error message -->
      <div v-if="flash.error" class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ flash.error }}
      </div>

      <form @submit.prevent="submit">

        <!-- Date picker -->
        <div class="mb-6">
          <label class="font-semibold mr-2">Select Date:</label>
          <input
            type="date"
            v-model="form.date"
            class="border p-2 rounded"
          />
        </div>

        <!-- Students table -->
        <table class="w-full border border-gray-300">
          <thead class="bg-gray-100">
            <tr>
              <th class="border p-2 text-left">Student</th>
              <th class="border p-2 text-center">Present</th>
              <th class="border p-2 text-center">Late</th>
              <th class="border p-2 text-center">Absent</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="student in props.students" :key="student.id">
              <td class="border p-2">{{ student.name }}</td>
              <td class="border text-center">
                <input type="radio" :value="'present'" v-model="form.attendance[student.id]" />
              </td>
              <td class="border text-center">
                <input type="radio" :value="'late'" v-model="form.attendance[student.id]" />
              </td>
              <td class="border text-center">
                <input type="radio" :value="'absent'" v-model="form.attendance[student.id]" />
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Save button -->
        <button
          type="submit"
          :disabled="!form.date"
          class="mt-6 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
        >
          Save Attendance
        </button>

      </form>
    </div>
  </AppLayout>
</template>