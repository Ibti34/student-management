<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'

defineProps({
  classes: Array,
})

const user = usePage().props.auth.user

const deleteClass = (id) => {
  if (confirm('Delete this class? Students will remain but become unassigned.')) {
    router.delete(route('classes.destroy', id))
  }
}
</script>

<template>
  <AppLayout>
    <div class="p-6 bg-[#f3f4f6] min-h-screen">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Programs & Cohorts</h1>
          <p class="text-sm text-gray-500 mt-1">Group university students by program and year for attendance and reporting.</p>
        </div>

        <Link v-if="user.role === 'admin'" :href="route('classes.create')" class="bg-[#2563eb] text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
          Add Cohort
        </Link>
      </div>

      <div class="overflow-x-auto bg-white shadow-md rounded-2xl border border-gray-200">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-gray-700 font-bold border-b border-gray-200 bg-gray-50">
              <th class="p-4">Program / Cohort</th>
              <th class="p-4">Academic Year</th>
              <th class="p-4">Academic Advisor</th>
              <th class="p-4">Capacity</th>
              <th class="p-4">Students</th>
              <th v-if="user.role === 'admin'" class="p-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="schoolClass in classes" :key="schoolClass.id" class="border-t border-gray-100 hover:bg-gray-50">
              <td class="p-4 font-semibold text-gray-900">{{ schoolClass.name }} {{ schoolClass.section }}</td>
              <td class="p-4 text-gray-700">{{ schoolClass.academic_year }}</td>
              <td class="p-4 text-gray-700">{{ schoolClass.homeroom_teacher || 'TBD' }}</td>
              <td class="p-4 text-gray-700">{{ schoolClass.capacity || '-' }}</td>
              <td class="p-4 text-gray-700">{{ schoolClass.students_count }}</td>
              <td v-if="user.role === 'admin'" class="p-4 text-center">
                <div class="flex justify-center gap-2">
                  <Link :href="route('classes.edit', schoolClass.id)" class="bg-[#DBEAFE] text-[#1E40AF] px-4 py-1.5 rounded-lg font-bold text-sm hover:bg-blue-200 transition">Edit</Link>
                  <button @click="deleteClass(schoolClass.id)" class="bg-[#FEE2E2] text-[#991B1B] px-4 py-1.5 rounded-lg font-bold text-sm hover:bg-red-200 transition">Delete</button>
                </div>
              </td>
            </tr>
            <tr v-if="!classes.length">
              <td :colspan="user.role === 'admin' ? 6 : 5" class="p-8 text-center text-gray-500">
                No cohorts created yet.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
