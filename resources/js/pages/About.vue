<script setup>
import AppLayout from '../layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
  <div class="min-h-screen bg-gray-100 py-16 px-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-10">

      <h1 class="text-4xl font-bold text-indigo-600 mb-6 text-center">
        About This Project
      </h1>

      <p class="text-gray-700 mb-4 leading-relaxed">
        This Student Management System is a web-based application
        developed to manage student records in an organized and efficient way.
      </p>

      <p class="text-gray-700 mb-6 leading-relaxed">
        It allows administrators to create, update, and delete student
        information while maintaining a clean and modern user interface.
      </p>

      <div class="grid md:grid-cols-2 gap-6 mt-8">

        <div class="bg-indigo-50 p-6 rounded-lg">
          <h2 class="font-semibold text-indigo-700 mb-2">
            Technologies Used
          </h2>
          <ul class="list-disc pl-5 text-gray-700 space-y-1">
            <li>Laravel</li>
            <li>Inertia.js</li>
            <li>Vue.js</li>
            <li>Tailwind CSS</li>
          </ul>
        </div>

        <div class="bg-purple-50 p-6 rounded-lg">
          <h2 class="font-semibold text-purple-700 mb-2">
            Purpose
          </h2>
          <p class="text-gray-700">
            This project is built for learning purposes to improve backend,
            frontend, and full-stack development skills.
          </p>
        </div>

      </div>

      <p class="text-center text-sm text-gray-500 mt-10">
        © 2026 Student Management System
      </p>

    </div>
  </div>
  </AppLayout>
</template>