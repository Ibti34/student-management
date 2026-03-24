<script setup>
import { Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '../../layouts/AppLayout.vue'

const props = defineProps({
  student: Object,
  classes: Array,
})

const form = useForm({
  name: props.student.name,
  email: props.student.email,
  age: props.student.age,
  phone: String(props.student.phone ?? ''),
  university: props.student.university,
  department: props.student.department,
  year_of_study: props.student.year_of_study ?? '',
  role: props.student.role,
  school_class_id: props.student.school_class_id ?? '',
})

function submit() {
  form
    .transform((data) => ({
      ...data,
      age: Number(data.age),
      phone: String(data.phone ?? ''),
      year_of_study: data.year_of_study ? Number(data.year_of_study) : null,
      school_class_id: data.school_class_id || null,
    }))
    .put(route('students.update', props.student.id), {
    preserveScroll: true,
    onSuccess: () => router.visit(route('students.index')),
  })
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
      <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
          Edit Student
        </h1>

        <form @submit.prevent="submit" class="space-y-4">
          <div v-if="Object.keys(form.errors).length" class="bg-red-100 p-4 rounded-lg text-sm text-red-700">
            Please fix the highlighted fields before updating the student.
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Name</label>
            <input v-model="form.name" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.name }" />
            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.email }" />
            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-1">Age</label>
              <input v-model="form.age" type="number" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.age }" />
              <div v-if="form.errors.age" class="text-red-500 text-xs mt-1">{{ form.errors.age }}</div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-1">Phone</label>
              <input v-model="form.phone" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.phone }" />
              <div v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Program / Cohort</label>
            <select v-model="form.school_class_id" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.school_class_id }">
              <option value="">{{ classes.length ? 'Assign later' : 'No cohorts yet - create one first' }}</option>
              <option v-for="schoolClass in classes" :key="schoolClass.id" :value="schoolClass.id">
                {{ schoolClass.name }} {{ schoolClass.section }} ({{ schoolClass.academic_year }})
              </option>
            </select>
            <p v-if="!classes.length" class="text-xs text-amber-600 mt-1">
              Create a cohort from the `Cohorts` page first, then come back to assign this student.
            </p>
            <div v-if="form.errors.school_class_id" class="text-red-500 text-xs mt-1">{{ form.errors.school_class_id }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">University</label>
            <input v-model="form.university" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.university }" />
            <div v-if="form.errors.university" class="text-red-500 text-xs mt-1">{{ form.errors.university }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Department</label>
            <input v-model="form.department" type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.department }" />
            <div v-if="form.errors.department" class="text-red-500 text-xs mt-1">{{ form.errors.department }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Year of Study</label>
            <select v-model="form.year_of_study" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.year_of_study }">
              <option value="">Select Year</option>
              <option value="1">Year 1</option>
              <option value="2">Year 2</option>
              <option value="3">Year 3</option>
              <option value="4">Year 4</option>
              <option value="5">Year 5</option>
              <option value="6">Year 6</option>
            </select>
            <div v-if="form.errors.year_of_study" class="text-red-500 text-xs mt-1">{{ form.errors.year_of_study }}</div>
          </div>

          <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300 disabled:bg-blue-300">
            {{ form.processing ? 'Updating...' : 'Update Student' }}
          </button>

          <div class="text-center mt-4">
            <Link href="/students" class="text-sm text-gray-500 hover:underline">Cancel and Go Back</Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
