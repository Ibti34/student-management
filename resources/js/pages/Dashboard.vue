<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    studentsCount: Number,
    usersCount: Number,
    registeredUsers: Array,
})

const page = usePage()
const user = computed(() => page.props.auth.user)

// State for the hidden user list
const showUserList = ref(false)
const toggleUserList = () => {
    showUserList.value = !showUserList.value
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Admin Dashboard" />

        <div class="min-h-screen bg-[#f8fafc] px-4 py-12 text-slate-900">
            <div class="max-w-6xl mx-auto">
                
                <header class="mb-12 flex justify-between items-end">
                    <div>
                        <h1 class="text-5xl font-black tracking-tighter text-slate-900">
                            System <span class="text-indigo-600">Overview</span>
                        </h1>
                        <p class="text-slate-500 font-medium mt-2">Welcome back, {{ user.name }}</p>
                    </div>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-white flex justify-between items-center">
                        <div>
                            <p class="text-xs font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">Total Students</p>
                            <h2 class="text-6xl font-black text-slate-900">{{ studentsCount }}</h2>
                        </div>
                        <div class="h-20 w-20 bg-indigo-50 rounded-3xl flex items-center justify-center text-indigo-600 shadow-inner">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                        </div>
                    </div>

                    <button 
                        @click="toggleUserList"
                        class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl shadow-slate-900/20 text-white flex justify-between items-center text-left transition-all hover:bg-indigo-950 active:scale-95 group relative overflow-hidden"
                    >
                        <div class="relative z-10">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Registered Users</p>
                            <h2 class="text-6xl font-black">{{ usersCount }}</h2>
                            <p class="text-[10px] text-indigo-400 font-black mt-2 uppercase tracking-widest flex items-center gap-2">
                                {{ showUserList ? 'Hide Registry ↑' : 'View Registry ↓' }}
                            </p>
                        </div>
                        <div class="relative z-10 h-20 w-20 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center text-white border border-white/20">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                    </button>
                </div>

                <transition 
                    enter-active-class="transition duration-500 ease-out" 
                    enter-from-class="transform scale-95 opacity-0 -translate-y-8" 
                    enter-to-class="transform scale-100 opacity-100 translate-y-0"
                    leave-active-class="transition duration-300 ease-in"
                    leave-from-class="transform scale-100 opacity-100 translate-y-0"
                    leave-to-class="transform scale-95 opacity-0 -translate-y-8"
                >
                    <div v-if="showUserList && user.role === 'admin'" class="mb-16">
                        <div class="bg-white rounded-[3rem] shadow-2xl shadow-indigo-100 border border-indigo-50 overflow-hidden">
                            <div class="p-10 border-b border-slate-50 flex justify-between items-center">
                                <h3 class="text-2xl font-black text-slate-800">User Registry</h3>
                                <div class="px-4 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase">Authenticated Accounts</div>
                            </div>
                            <table class="w-full">
                                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-10 py-5 text-left">Account</th>
                                        <th class="px-10 py-5 text-left">Role</th>
                                        <th class="px-10 py-5 text-right">Join Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr v-for="u in registeredUsers" :key="u.id" class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="h-10 w-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold">{{ u.name.charAt(0) }}</div>
                                                <div>
                                                    <p class="font-bold text-slate-900 leading-none">{{ u.name }}</p>
                                                    <p class="text-xs text-slate-400 mt-1">{{ u.email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6">
                                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-tighter">{{ u.role }}</span>
                                        </td>
                                        <td class="px-10 py-6 text-right text-xs text-slate-400 font-bold">
                                            {{ new Date(u.created_at).toLocaleDateString() }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </transition>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <Link 
                        v-if="user.role === 'admin'"
                        :href="route('students.index')" 
                        class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group"
                    >
                        <div class="h-14 w-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-all">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                        </div>
                        <h4 class="text-xl font-black text-slate-900">Student Directory</h4>
                        <p class="text-sm text-slate-400 mt-2">Manage student profiles and info.</p>
                    </Link>

                    <Link 
                        v-if="user.role === 'admin'"
                        :href="route('attendance.index')" 
                        class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group"
                    >
                        <div class="h-14 w-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        </div>
                        <h4 class="text-xl font-black text-slate-900">Mark Attendance</h4>
                        <p class="text-sm text-slate-400 mt-2">Record daily student logs.</p>
                    </Link>

                    <Link 
                        :href="user.role === 'admin' ? route('attendance.history') : route('attendance.my')" 
                        class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group"
                    >
                        <div class="h-14 w-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h4 class="text-xl font-black text-slate-900">Attendance History</h4>
                        <p class="text-sm text-slate-400 mt-2">
                             {{ user.role === 'admin' ? 'Review system-wide trends.' : 'Check your personal records.' }}
                        </p>
                    </Link>

                    <Link 
    v-if="user.role === 'admin' || user.role === 'teacher' || user.role === 'student'"
    :href="route('marks.index')" 
    class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group"
>
    <div class="h-14 w-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-rose-600 group-hover:text-white transition-all">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
        </svg>
    </div>
    <h4 class="text-xl font-black text-slate-900">
        {{ user.role === 'student' ? 'My Grades' : 'Student Marks' }}
    </h4>
    <p class="text-sm text-slate-400 mt-2">
        {{ user.role === 'teacher' ? 'Update and manage student scores.' : 'View academic performance results.' }}
    </p>
</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>