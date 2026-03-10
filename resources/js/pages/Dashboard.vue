<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    studentsCount: Number,
    usersCount: Number,
})

const page = usePage()
const user = computed(() => page.props.auth.user)
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <template #header>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                System Overview
            </h2>
        </template>

        <div class="py-10 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <div class="bg-white border border-blue-100 rounded-2xl p-6 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Students</p>
                            <p class="text-2xl font-bold text-gray-900">{{ studentsCount }}</p>
                        </div>
                    </div>

                    <div class="bg-white border border-green-100 rounded-2xl p-6 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-green-50 rounded-lg text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ usersCount }}</p>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <Link
                        v-if="user.role === 'admin'"
                        :href="route('students.index')"
                        class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:border-purple-300 hover:shadow-md transition-all"
                    >
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-lg mb-1">Student Directory</h3>
                        <p class="text-gray-500 text-sm">View, edit, and manage all student profiles.</p>
                    </Link>

                    <Link
                        v-if="user.role === 'admin'"
                        :href="route('attendance.index')"
                        class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:border-blue-300 hover:shadow-md transition-all"
                    >
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-lg mb-1">Mark Attendance</h3>
                        <p class="text-gray-500 text-sm">Record daily attendance for all active students.</p>
                    </Link>

                    <Link
                        :href="user.role === 'admin' ? route('attendance.history') : route('attendance.my')"
                        class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:border-orange-300 hover:shadow-md transition-all"
                    >
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-lg mb-1">Attendance Logs</h3>
                        <p class="text-gray-500 text-sm">
                            {{ user.role === 'admin' ? 'Review class-wide attendance trends.' : 'Check your personal attendance record.' }}
                        </p>
                    </Link>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>