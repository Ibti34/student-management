<script setup>
import AppLayout from '../../layouts/AppLayout.vue';

defineProps({
    students: Array,
});
</script>

<template>
    <AppLayout>
        <h1 class="mb-4 text-2xl font-bold">Students List</h1>

        <table class="w-full border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Age</th>
                    <th class="border p-2">University</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="student in students" :key="student.id">
                    <td class="border p-2">
                        {{ student.id }}
                    </td>

                    <td class="border p-2">
                        {{ student.name }}
                    </td>

                    <td class="border p-2">
                        {{ student.email }}
                    </td>

                    <td class="border p-2">
                        {{ student.age }}
                    </td>

                    <td class="border p-2">
                        {{ student.university }}
                    </td>
                    <td>
                        <a :href="'/students/' + student.id + '/edit'">
                            Edit
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </AppLayout>
</template>
