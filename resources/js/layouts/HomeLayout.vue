<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
<div>

<!-- Navbar -->
<nav class="flex justify-between items-center px-10 py-4 bg-white shadow">

<h1 class="text-xl font-bold">
Student Management
</h1>

<div class="space-x-4">
<Link href="/login" class="text-blue-600">Login</Link>
<Link href="/register" class="bg-blue-600 text-white px-4 py-2 rounded">
Register
</Link>
</div>

</nav>

<!-- Page content -->
<main>
<slot />
</main>

</div>
</template>