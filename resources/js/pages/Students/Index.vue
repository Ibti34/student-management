<script setup>
import { ref } from 'vue'
import AppLayout from '../../layouts/AppLayout.vue'
import { router, Link } from '@inertiajs/vue3'

defineProps({
    students: Array,
})

//  search variable 
const search = ref('')

//  search function 
const searchStudent = () => {
    router.get('/students', 
        { search: search.value }, 
        { preserveState: true }
    )
}

//  delete function 
const deleteStudent = (id) => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(`/students/${id}`)
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6">

            <h1 class="mb-6 text-3xl font-bold text-gray-800">
                Students List
            </h1>
<div class="mb-4 flex gap-2">
    <input
        v-model="search"
        placeholder="Search by name or email..."
        class="border px-3 py-2 rounded w-64"
    />

    <button
        @click="searchStudent"
        class="bg-blue-500 text-white px-4 py-2 rounded"
    >
        Search
    </button>
</div>
            <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Age</th>
                            <th class="p-3">University</th>
                             <th class="p-3">department</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="student in students"
                            :key="student.id"
                            class="border-t hover:bg-gray-50"
                        >
                            <td class="p-3">{{ student.id }}</td>
                            <td class="p-3 font-medium">{{ student.name }}</td>
                            <td class="p-3">{{ student.email }}</td>
                            <td class="p-3">{{ student.age }}</td>
                            <td class="p-3">{{ student.university }}</td>
                             <td class="p-3">{{ student.department }}</td>


                            <td class="p-3 text-center space-x-2">
                                <!-- Edit -->
                                <Link
                                    :href="`/students/${student.id}/edit`"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm"
                                >
                                    Edit
                                </Link>

                                <!-- Delete -->
                                <button
                                    @click="deleteStudent(student.id)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AppLayout>
</template>