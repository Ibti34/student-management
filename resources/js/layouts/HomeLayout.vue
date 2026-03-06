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

    

      </div>
    </nav>

    <!-- Page Content -->
    <main>
      <slot />
    </main>

  </div>
</template>