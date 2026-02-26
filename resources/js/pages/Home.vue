<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <div class="p-10">
            <h1 class="text-4xl text-blue-500">
                Welcome to Student Management App
            </h1>
            <p class="mt-4 text-lg">
                This is a simple application built with Laravel, Inertia.js, and
                Vue 3. It demonstrates how to create a student management system
                with basic CRUD operations.
            </p>
        </div>
    </AppLayout>
</template>
