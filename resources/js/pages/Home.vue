<script setup>
import HomeLayout from '@/layouts/HomeLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
  layout: HomeLayout
})
</script>

<template>
  <div class="flex items-center justify-center py-24 px-6">
    <div class="text-center max-w-3xl">

      <h1 class="text-5xl font-extrabold text-gray-800 mb-6">
        Student Management System
      </h1>

      <p class="text-lg text-gray-600 mb-10">
        A modern platform to manage students, departments,
        and academic records efficiently and professionally.
      </p>

      <div class="flex justify-center gap-6">
        <Link
          href="/students"
          class="bg-indigo-600 text-white px-8 py-3 rounded-lg shadow hover:bg-indigo-700 transition"
        >
          Go to Dashboard
        </Link>

        <Link
          href="/about"
          class="border border-gray-400 px-8 py-3 rounded-lg hover:bg-gray-100 transition"
        >
          Learn More
        </Link>
      </div>

    </div>
  </div>
</template>