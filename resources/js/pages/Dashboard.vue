<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
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

<!-- Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<!-- Add Student Card -->
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

<!-- Student List Card -->
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