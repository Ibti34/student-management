<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    studentsCount: Number,
    usersCount: Number,
})
</script>

<template>
<AuthenticatedLayout>

<Head title="Dashboard" />

<template #header>
<h2 class="text-2xl font-bold text-gray-800">
Dashboard
</h2>
</template>

<div class="py-10">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<!-- STATISTICS -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

<!-- TOTAL STUDENTS -->
<div class="bg-blue-100 rounded-xl p-6 shadow text-center">

<h3 class="text-blue-700 font-bold text-lg">
Total Students
</h3>

<p class="text-3xl font-bold text-blue-900 mt-2">
{{ studentsCount }}
</p>

</div>


<!-- TOTAL USERS -->
<div class="bg-green-100 rounded-xl p-6 shadow text-center">

<h3 class="text-green-700 font-bold text-lg">
Total Users
</h3>

<p class="text-3xl font-bold text-green-900 mt-2">
{{ usersCount }}
</p>

</div>

</div>


<!-- ACTION CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<!-- ADD STUDENT -->
<Link
:href="route('students.create')"
class="bg-gray-200 rounded-xl p-6 shadow hover:shadow-lg transition"
>

<h3 class="text-indigo-600 font-bold text-lg mb-3">
Add Student
</h3>

<p class="text-gray-700">
Create and register a new student in the system.
</p>

</Link>


<!-- STUDENT LIST -->
<Link
:href="route('students.index')"
class="bg-purple-100 rounded-xl p-6 shadow hover:shadow-lg transition"
>

<h3 class="text-purple-600 font-bold text-lg mb-3">
Student List
</h3>

<p class="text-gray-700">
View and manage all registered students.
</p>

</Link>

</div>

</div>
</div>

</AuthenticatedLayout>
</template>