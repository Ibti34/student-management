<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const user = page.props.auth.user

const open = ref(false)
</script>

<template>
<div>

    <!-- NAVBAR -->
    <div class="flex justify-between items-center bg-gray-800 p-4 text-white">

        <!-- LEFT MENU -->
        <div class="space-x-4">
             <Link :href="route('dashboard')" >
         Dashboard
        </Link>
          
            <Link href="/students">Students List</Link>
        </div>

        <!-- RIGHT PROFILE -->
        <div class="relative" v-if="user">
            <button
                @click="open = !open"
                class="flex items-center gap-2"
            >
                <span>{{ user.name }}</span>
                ▼
            </button>

            <!-- DROPDOWN -->
            <div v-if="open"
                 class="absolute right-0 mt-2 w-40 bg-white text-black rounded shadow">

                <Link
                    href="/profile"
                    class="block px-4 py-2 hover:bg-gray-100"
                >
                    Profile
                </Link>

                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                >
                    Log Out
                </Link>

            </div>
        </div>

    </div>

    <!-- PAGE CONTENT -->
    <div class="p-6">
        <slot />
    </div>

</div>
</template>