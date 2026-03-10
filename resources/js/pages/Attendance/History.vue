<script setup>
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
  attendance: Array
})
</script>

<template>
<AppLayout>

<div class="p-6 max-w-5xl mx-auto">

<h1 class="text-3xl font-bold mb-6">
Attendance History
</h1>

<table class="w-full border border-gray-300">

<thead class="bg-gray-100">
<tr>
<th class="border p-2">Student</th>
<th class="border p-2">Date</th>
<th class="border p-2">Status</th>
</tr>
</thead>

<tbody>

<tr
v-for="record in attendance"
:key="record.id"
>

<td class="border p-2">
{{ record.student?.name || 'Unknown' }}
</td>

<td class="border p-2">
{{ record.date }}
</td>

<td class="border p-2">

<span
v-if="record.status === 'present'"
class="text-green-600 font-semibold"
>
Present
</span>

<span
v-else-if="record.status === 'late'"
class="text-yellow-600 font-semibold"
>
Late
</span>

<span
v-else
class="text-red-600 font-semibold"
>
Absent
</span>

</td>

</tr>

</tbody>

</table>

</div>

</AppLayout>
</template>