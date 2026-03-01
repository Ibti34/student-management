<script setup>
import HomeLayout from '@/layouts/HomeLayout.vue'
import { Link } from '@inertiajs/vue3'
import heroImage from '@/../images/home-page-img.png'
import AppLayout from '@/layouts/AppLayout.vue';
defineOptions({
  layout: HomeLayout
})
</script>

<template>
  <div class="relative min-h-screen flex items-center justify-center">

    <!-- Background Image -->
    <img
      :src="heroImage"
      class="absolute inset-0 w-full h-full object-cover"
      alt="Background"
    />

     <!-- Overlay  -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Content -->
    <div class="relative text-center text-white px-6 max-w-3xl">

      <h1 class="text-5xl md:text-6xl font-extrabold mb-6">
        Student Management System
      </h1>

      <p class="text-lg md:text-xl mb-10 text-gray-200">
        Manage students, departments, and academic records
        in a simple and professional way.
      </p>

      <div class="flex justify-center gap-6">
        <Link
          href="/students"
          class="bg-indigo-600 px-8 py-3 rounded-lg shadow hover:bg-indigo-700 transition"
        >
          Go to Dashboard
        </Link>

        <Link
          href="/about"
          class="border border-white px-8 py-3 rounded-lg hover:bg-white hover:text-black transition"
        >
          Learn More
        </Link>
      </div>

    </div>

  </div>
</template>