<script setup>
import AppLayout from '../../layouts/AppLayout.vue'
import { useForm, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    students: Array,
    attendances: Array
})

const flash = usePage().props.flash || {}

const form = useForm({
    student_id: '',
    date: '',
    status: ''
})

function submit() {
    form.post('/attendance', {
        onSuccess: () => {
            form.reset('student_id', 'status')
        }
    })
}

function deleteAttendance(id) {
    if (confirm('Are you sure you want to delete this attendance?')) {
        router.delete(`/attendance/${id}`)
    }
}
</script>

<template>
<AppLayout>

<div class="p-6">

<h1 class="text-3xl font-bold mb-6">
Take Attendance
</h1>

<!-- Success Message -->
<div
v-if="flash.success"
class="bg-green-100 text-green-700 p-3 rounded mb-4"
>
{{ flash.success }}
</div>

<!-- Attendance Form -->
<form
@submit.prevent="submit"
class="flex items-center gap-4 mb-6"
>

<input
type="date"
v-model="form.date"
class="border p-2 rounded"
/>

<select
v-model="form.student_id"
class="border p-2 rounded"
>

<option value="">Select Student</option>

<option
v-for="student in students"
:key="student.id"
:value="student.id"
>
{{ student.name }}
</option>

</select>

<select
v-model="form.status"
class="border p-2 rounded"
>

<option value="">Select Status</option>
<option value="present">Present</option>
<option value="absent">Absent</option>
<option value="late">Late</option>

</select>

<button
type="submit"
class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
>
Save Attendance
</button>

</form>

<!-- Attendance Table -->

<table class="w-full border border-gray-300">

<thead class="bg-gray-100">
<tr>
<th class="border p-2 text-left">Student</th>
<th class="border p-2 text-left">Date</th>
<th class="border p-2 text-left">Status</th>
<th class="border p-2 text-left">Action</th>
</tr>
</thead>

<tbody>

<tr
v-for="attendance in attendances"
:key="attendance.id"
>

<td class="border p-2">
{{ attendance.student.name }}
</td>

<td class="border p-2">
{{ attendance.date }}
</td>

<td class="border p-2 capitalize">
{{ attendance.status }}
</td>

<td class="border p-2">

<button
@click="deleteAttendance(attendance.id)"
class="text-red-600 hover:underline"
>
Delete
</button>

</td>

</tr>

<tr v-if="attendances.length === 0">

<td
colspan="4"
class="text-center p-4 text-gray-500"
>
No attendance records yet
</td>

</tr>

</tbody>

</table>

</div>

</AppLayout>
</template>