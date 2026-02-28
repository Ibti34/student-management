<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <!-- MENU -->
        <div class="space-x-4 bg-gray-800 p-4 text-white">
            <Link href="/home">Home</Link>

            <Link href="/about">About</Link>
            <Link href="/students/create">Add Student</Link>

            <Link href="/students">Students List</Link>
        </div>
        <!-- PAGE CONTENT -->
        <div class="p-6">
            <slot />
        </div>
    </div>
</template>
