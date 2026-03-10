<script setup>
import { ref } from 'vue'
import AppLayout from '../../layouts/AppLayout.vue'
import { router, Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user 

defineProps({
    students: Array,
})

// search variable
const search = ref('')

// search function
const searchStudent = () => {
    router.get(
        '/students',
        { search: search.value },
        { preserveState: true }
    )
}

// delete function
const deleteStudent = (id) => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(`/students/${id}`)
    }
}
</script>

<template>
<AppLayout>
<div class="p-6">

<h1 class="mb-6 text-3xl font-bold text-gray-800">
Students List
</h1>

<!-- Search -->
<div class="mb-4 flex gap-2">
<input
v-model="search"
placeholder="Search by name or email..."
class="border px-3 py-2 rounded w-64"
/>

<button
@click="searchStudent"
class="bg-black text-white px-4 py-2 rounded hover:bg-black-600"
>
Search
</button>
</div>

<!-- add student -->
<div class="mb-4 flex gap-2 justify-end">

    <Link
    v-if="user.role === 'admin'"
    href="/students/create"
    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
    >
        Add Student
    </Link>

</div>
<!-- Table -->
<div class="overflow-x-auto bg-white shadow rounded-xl">

<table class="w-full text-left border-collapse">

<thead class="bg-gray-100">
<tr>
<th class="p-3">ID</th>
<th class="p-3">Name</th>
<th class="p-3">Email</th>
<th class="p-3">Age</th>
<th class="p-3">Phone</th>
<th class="p-3">University</th>
<th class="p-3">Department</th>
<th v-if="user.role === 'admin'" class="p-3 text-center space-x-2 text-align-center">
Actions
</th>
</tr>
</thead>

<tbody>

<tr
v-for="student in students"
:key="student.id"
class="border-t hover:bg-gray-50"
>

<td class="p-3">{{ student.id }}</td>
<td class="p-3 font-medium">{{ student.name }}</td>
<td class="p-3">{{ student.email }}</td>
<td class="p-3">{{ student.age }}</td>
<td class="p-3">{{ student.phone }}</td>
<td class="p-3">{{ student.university }}</td>
<td class="p-3">{{ student.department }}</td>
<td class="p-3">{{ student.user_id }}</td>

<td v-if="user.role === 'admin'" class="p-3 text-center space-x-2">

<!-- Edit -->
<Link
:href="route('students.edit', student.id)"
class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition"
>
Edit
</Link>

<!-- Delete -->
<button
@click="deleteStudent(student.id)"
class="bg-red-100 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-200 transition"
>
Delete
</button>

</td>

</tr>

</tbody>
</table>

</div>

</div>
</AppLayout>
</template>