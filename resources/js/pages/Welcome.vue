<script setup>
import HomeLayout from '@/layouts/HomeLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import heroImage from '@/../images/home-page-img.png'

defineOptions({
  layout: HomeLayout
})

const page = usePage()
const user = page.props.auth?.user
</script>

<template>

<!-- HERO SECTION -->
<section class="relative min-h-screen flex items-center justify-center text-center text-white">

  <!-- Background -->
  <img
    :src="heroImage"
    class="absolute inset-0 w-full h-full object-cover"
    alt="Background"
  />

  <!-- Dark overlay -->
  <div class="absolute inset-0 bg-black/40"></div>

  <!-- Content -->
  <div class="relative z-10 px-6 max-w-3xl">

    <h1 class="text-5xl md:text-6xl font-extrabold mb-6">
      Student Management System
    </h1>

    <p class="text-lg md:text-xl text-gray-200 mb-10">
      A simple and powerful system to manage students,
      attendance, and academic information efficiently.
    </p>

    <!-- Buttons -->
    <div class="flex flex-wrap justify-center gap-6">

      <!-- If logged in -->
      <Link
        v-if="user"
        :href="route('dashboard')"
        class="bg-indigo-600 px-8 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition"
      >
        Go to Dashboard
      </Link>

      <!-- If not logged in -->
      <template v-else>

        <Link
          :href="route('login')"
          class="bg-indigo-600 px-8 py-3 rounded-lg shadow hover:bg-indigo-700 transition"
        >
          Login
        </Link>

        <Link
          :href="route('register')"
          class="bg-green-600 px-8 py-3 rounded-lg shadow hover:bg-green-700 transition"
        >
          Register
        </Link>

      </template>

      <Link
  href="/about"
  class="border border-white px-8 py-3 rounded-lg hover:bg-white hover:text-black transition"
>
  Learn More
</Link>
    </div>

  </div>

</section>


<!-- FEATURES SECTION -->
<section class="py-20 bg-gray-100">

  <div class="max-w-6xl mx-auto px-6 text-center">

    <h2 class="text-3xl font-bold mb-12 text-gray-800">
      System Features
    </h2>

    <div class="grid md:grid-cols-3 gap-10">

      <!-- Feature 1 -->
      <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-xl font-semibold mb-4 text-indigo-600">
          Student Management
        </h3>
        <p class="text-gray-600">
          Add, edit, and manage student information easily in one place.
        </p>
      </div>

      <!-- Feature 2 -->
      <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-xl font-semibold mb-4 text-indigo-600">
          Attendance Tracking
        </h3>
        <p class="text-gray-600">
          Record daily attendance and keep track of student participation.
        </p>
      </div>

      <!-- Feature 3 -->
      <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-xl font-semibold mb-4 text-indigo-600">
          Secure System
        </h3>
        <p class="text-gray-600">
          Authentication and protected routes keep your data safe.
        </p>
      </div>

    </div>

  </div>

</section>


<!-- FOOTER -->
<footer class="bg-gray-900 text-gray-400 py-6">

  <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center  gap-4 text-center">

    <p>
      © {{ new Date().getFullYear() }} Student Management System
    </p>

   

  </div>

</footer>

</template>