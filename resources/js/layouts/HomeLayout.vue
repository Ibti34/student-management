<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <h1 class="text-xl font-bold text-indigo-600">
          SMS
        </h1>

        <div class="space-x-6">
          <Link href="/" class="text-gray-700 hover:text-indigo-600 font-medium">
            Home
          </Link>

          <Link href="/about" class="text-gray-700 hover:text-indigo-600 font-medium">
            About
          </Link>

          <Link href="/students" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
            Dashboard
          </Link>
        </div>

      </div>
    </nav>

    <!-- Page Content -->
    <main>
      <slot />
    </main>

  </div>
</template>