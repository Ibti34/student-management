<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { reactive } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

// Props coming from Laravel controller
const props = defineProps({
  students: Array,
  attendanceRecords: Object,
  selectedDate: String
})

const flash = usePage().props.flash || {}

// Build attendance object for each student
const attendance = reactive(
  props.students.reduce((acc, student) => {
    acc[student.id] = props.attendanceRecords?.[student.id]?.status || ''
    return acc
  }, {})
)

// Inertia form
const form = useForm({
  date: props.selectedDate || '',
  attendance: attendance
})

// Submit attendance
function submit() {

  if (!form.date) {
    alert('Please select a date first')
    return
  }

  form.post(route('attendance.bulk'), {
    preserveScroll: true,

    onSuccess: () => {
      alert('Attendance saved successfully')
    },

    onError: (errors) => {
      console.error(errors)
    }
  })
}
</script>

<template>
<AppLayout>

<div class="p-6 max-w-5xl mx-auto">

<h1 class="text-3xl font-bold mb-6">
Take Attendance
</h1>

<!-- SUCCESS MESSAGE -->
<div
v-if="flash.success"
class="bg-green-100 text-green-700 p-3 rounded mb-4"
>
{{ flash.success }}
</div>

<form @submit.prevent="submit">

<!-- DATE -->
<div class="mb-6">

<label class="font-semibold mr-2">
Select Date:
</label>

<input
type="date"
v-model="form.date"
class="border p-2 rounded"
/>

</div>


<!-- STUDENTS TABLE -->
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

<tr
v-for="student in students"
:key="student.id"
>

<td class="border p-2">
{{ student.name }}
</td>

<td class="border text-center">
<input
type="radio"
:value="'present'"
:name="'attendance_' + student.id"
v-model="form.attendance[student.id]"
>
</td>

<td class="border text-center">
<input
type="radio"
:value="'late'"
:name="'attendance_' + student.id"
v-model="form.attendance[student.id]"
>
</td>

<td class="border text-center">
<input
type="radio"
:value="'absent'"
:name="'attendance_' + student.id"
v-model="form.attendance[student.id]"
>
</td>

</tr>

</tbody>

</table>


<!-- SAVE BUTTON -->
<button
type="submit"
:disabled="!form.date"
class="mt-6 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
>
Save Attendance
</button>

</form>

</div>

</AppLayout>
</template>