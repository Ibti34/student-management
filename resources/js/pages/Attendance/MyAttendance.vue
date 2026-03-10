<script setup>
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
    attendance: Array
})
</script>

<template>
<AppLayout>

<h1 class="text-2xl font-bold mb-6">
My Attendance
</h1>

<table class="w-full border">

<thead class="bg-gray-200">
<tr>
<th class="p-2 border">Date</th>
<th class="p-2 border">Status</th>
</tr>
</thead>

<tbody>

<tr
v-for="record in attendance"
:key="record.id"
>

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
v-else
class="text-red-600 font-semibold"
>
Absent
</span>

</td>

</tr>

</tbody>

</table>

</AppLayout>
</template>