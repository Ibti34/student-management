<script setup>
import { ref } from 'vue'
import AppLayout from '../../layouts/AppLayout.vue'
import { router, Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user 

const props = defineProps({
    students: Object, 
    filters: Object,
    classes: Array,
    isStudentView: Boolean,
})

const search = ref(props.filters?.search || '')
const selectedClassId = ref(props.filters?.class_id || '')

const searchStudent = () => {
    router.get('/students', { search: search.value, class_id: selectedClassId.value }, { preserveState: true })
}

const deleteStudent = (id) => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(`/students/${id}`, { preserveScroll: true })
    }
}
</script>

<template>
<AppLayout>
<div class="p-6 bg-[#f3f4f6] min-h-screen">
    <h1 class="mb-6 text-3xl font-bold text-gray-800">{{ isStudentView ? 'My Information' : 'Students List' }}</h1>

    <div v-if="page.props.flash?.success" class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ page.props.flash.success }}
    </div>

    <div v-if="!isStudentView" class="mb-4 flex flex-wrap gap-2">
        <input v-model="search" @keyup.enter="searchStudent" placeholder="Search..." class="border border-gray-300 px-3 py-2 rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" />
        <select v-model="selectedClassId" class="border border-gray-300 px-3 py-2 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All cohorts</option>
            <option v-for="schoolClass in classes" :key="schoolClass.id" :value="schoolClass.id">
                {{ schoolClass.name }} {{ schoolClass.section }}
            </option>
        </select>
        <button @click="searchStudent" class="bg-black text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-800 transition">Search</button>
    </div>

    <div v-if="!isStudentView" class="mb-4 flex justify-end">
        <Link v-if="user.role === 'admin'" href="/students/create" class="bg-[#2563eb] text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Add Student</Link>
    </div>

    <div class="overflow-x-auto bg-[#D1D5DB] shadow-md rounded-2xl border border-gray-300">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-700 font-bold border-b border-gray-400">
                    <th class="p-4">ID</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Age</th>
                    <th class="p-4">Phone</th>
                    <th class="p-4">Year</th>
                    <th class="p-4">Program / Cohort</th>
                    <th class="p-4">Advisor / Teacher</th>
                    <th class="p-4">University</th>
                    <th class="p-4">Department</th>
                    <th v-if="user.role === 'admin' && !isStudentView" class="p-4 text-center text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white/60">
                <tr v-for="student in students.data" :key="student.id" class="border-t border-gray-300 hover:bg-white/90 transition-colors">
                    <td class="p-4 text-gray-800">{{ student.id }}</td>
                    <td class="p-4 font-bold text-gray-900">{{ student.name }}</td>
                    <td class="p-4 text-gray-700">{{ student.email }}</td>
                    <td class="p-4 text-gray-700">{{ student.age }}</td>
                    <td class="p-4 text-gray-700">{{ student.phone }}</td>
                    <td class="p-4 text-gray-700">{{ student.year_of_study ? `Year ${student.year_of_study}` : 'Not set' }}</td>
                    <td class="p-4 text-gray-700">{{ student.school_class ? `${student.school_class.name} ${student.school_class.section}` : 'Unassigned' }}</td>
                    <td class="p-4 text-gray-700">{{ student.school_class?.homeroom_teacher || 'Not assigned' }}</td>
                    <td class="p-4 text-gray-700">{{ student.university }}</td>
                    <td class="p-4 text-gray-700">{{ student.department }}</td>
                    <td v-if="user.role === 'admin' && !isStudentView" class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <Link :href="route('students.edit', student.id)" class="bg-[#DBEAFE] text-[#1E40AF] px-4 py-1.5 rounded-lg font-bold text-sm hover:bg-blue-200 transition">Edit</Link>
                            <button @click="deleteStudent(student.id)" class="bg-[#FEE2E2] text-[#991B1B] px-4 py-1.5 rounded-lg font-bold text-sm hover:bg-red-200 transition">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="p-6 bg-[#D1D5DB] border-t border-gray-400">
            <div class="flex flex-wrap gap-2 mb-4">
                <Component
                    v-for="(link, index) in students.links"
                    :key="index"
                    :is="link.url ? Link : 'span'" 
                    :href="link.url"
                    v-html="link.label"
                    class="min-w-[40px] h-10 px-3 flex items-center justify-center rounded-lg border border-gray-400 text-sm font-bold shadow-sm transition-all"
                    :class="[
                        link.url ? 'bg-gray-100 text-gray-700 hover:bg-white hover:border-blue-500' : 'bg-gray-200 text-gray-400 cursor-default',
                        link.active ? 'bg-[#2563eb] !text-white border-[#2563eb]' : ''
                    ]"
                />
            </div>
            <div class="text-gray-800 font-bold text-sm">
                Results: {{ students.from || 0 }} - {{ students.to || 0 }} of {{ students.total }}
            </div>
        </div>
    </div>
</div>
</AppLayout>
</template>
