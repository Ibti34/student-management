<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()
// Using a computed property makes it easier to track auth status reactively
const user = computed(() => page.props.auth?.user)

const open = ref(false)
</script>

<template>
  <div>
    <div class="flex justify-between items-center bg-gray-800 p-4 text-white">
      
      <div class="space-x-4 flex items-center">
        
        <Link :href="route('about')" class="hover:text-gray-300">
          About
        </Link>

        <template v-if="user">
          <Link :href="route('dashboard')" class="hover:text-gray-300">
            Dashboard
          </Link>

          <Link :href="route('students.index')" class="hover:text-gray-300">
            Students
          </Link>

          <Link
            v-if="user.role === 'admin' || user.role === 'teacher'"
            :href="route('attendance.index')"
            class="hover:text-gray-300"
          >
            Attendance
          </Link>

          <Link
            v-if="user.role === 'admin' || user.role === 'teacher'"
            :href="route('attendance.history')"
            class="hover:text-gray-300"
          >
            Attendance History
          </Link>

          <Link
            v-if="user.role === 'student'"
            :href="route('attendance.my')"
            class="hover:text-gray-300"
          >
            My Attendance
          </Link>
        </template>
      </div>

      <div class="relative">
        
        <div v-if="user">
          <button
            @click="open = !open"
            class="flex items-center gap-2 focus:outline-none"
          >
            <span>{{ user.name }}</span>
            <span :class="{'rotate-180': open}" class="transition-transform duration-200">▼</span>
          </button>

          <div
            v-if="open"
            class="absolute right-0 mt-2 w-40 bg-white text-black rounded shadow-lg z-50"
          >
            <Link
              :href="route('profile.edit')"
              class="block px-4 py-2 hover:bg-gray-100"
            >
              Profile
            </Link>

            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="block w-full text-left px-4 py-2 hover:bg-gray-100"
            >
              Log Out
            </Link>
          </div>
        </div>

        <div v-else>
          <Link :href="route('login')" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">
            Login
          </Link>
        </div>

      </div>
    </div>

    <div class="p-6">
      <slot />
    </div>
  </div>
</template>