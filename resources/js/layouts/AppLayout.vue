<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()

// Computed property to track the authenticated user reactively
const user = computed(() => page.props.auth?.user)

const open = ref(false)
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="flex justify-between items-center bg-gray-800 p-4 text-white shadow-md">
      
      <div class="space-x-4 flex items-center">
        <Link :href="route('about')" class="hover:text-gray-300 font-medium">
          About
        </Link>

        <template v-if="user">
          <Link :href="route('dashboard')" class="hover:text-gray-300 font-medium">
            Dashboard
          </Link>

          <Link :href="route('students.index')" class="hover:text-gray-300 font-medium">
            Students
          </Link>

          <template v-if="user.role === 'admin' || user.role === 'teacher'">
            <Link :href="route('attendance.index')" class="hover:text-gray-300 font-medium">
              Attendance
            </Link>
            <Link :href="route('attendance.history')" class="hover:text-gray-300 font-medium">
              Attendance History
            </Link>
          </template>

          <Link
            v-if="user.role === 'student'"
            :href="route('attendance.my')"
            class="hover:text-gray-300 font-medium"
          >
            My Attendance
          </Link>

          <Link
            v-if="user.role === 'admin' || user.role === 'teacher'"
            :href="route('marks.index')"
            class="hover:text-gray-300 font-medium"
          >
            Marks
          </Link>

          <Link
            v-if="user.role === 'student'"
            :href="route('marks.my')"
            class="hover:text-gray-300 font-medium"
          >
            My Marks
          </Link>
        </template>
      </div>

      <div class="relative">
        <div v-if="user">
          <button
            @click="open = !open"
            class="flex items-center gap-2 focus:outline-none hover:text-gray-300 transition-colors"
          >
            <span class="capitalize">{{ user.name }}</span>
            <span :class="{'rotate-180': open}" class="transition-transform duration-200 text-xs">▼</span>
          </button>

          <div
            v-if="open"
            @click="open = false"
            class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-xl z-50 border border-gray-200 py-1"
          >
            <Link
              :href="route('profile.edit')"
              class="block px-4 py-2 text-sm hover:bg-gray-100"
            >
              Your Profile
            </Link>

            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 text-red-600"
            >
              Log Out
            </Link>
          </div>
        </div>

        <div v-else>
          <Link :href="route('login')" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 transition font-medium">
            Login
          </Link>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6">
      <slot />
    </main>
  </div>
</template>

<style scoped>
/* Ensure the dropdown stays above other content */
.z-50 {
  z-index: 50;
}
</style>