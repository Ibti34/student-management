<script setup>
import AppLayout from '../../layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
  classes: Array,
})

const form = useForm({
  name: '',
  email: '',
  age: '',
  phone: '',
  university: '',
  department: '',
  year_of_study: '',
  role: 'student',
  school_class_id: '',
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
    .post('/students')
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4">
      <div class="bg-white shadow-xl rounded-2xl w-full max-w-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
          Create Student
        </h2>

        <form @submit.prevent="submit" class="space-y-6">
          <div v-if="Object.keys(form.errors).length" class="bg-red-100 p-4 mb-4 rounded-lg text-sm text-red-700">
            Please fix the highlighted fields.
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Name</label>
            <input v-model="form.name" type="text" placeholder="Enter student name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" :class="{ 'border-red-500': form.errors.name }" />
            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
            <input v-model="form.email" type="email" placeholder="Enter student email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" :class="{ 'border-red-500': form.errors.email }" />
            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-600 mb-2">Age</label>
              <input v-model="form.age" type="number" placeholder="Enter age" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" :class="{ 'border-red-500': form.errors.age }" />
              <div v-if="form.errors.age" class="text-red-500 text-xs mt-1">{{ form.errors.age }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-600 mb-2">Phone</label>
              <input v-model="form.phone" type="text" placeholder="Enter phone number" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" :class="{ 'border-red-500': form.errors.phone }" />
              <div v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Program / Cohort</label>
            <select v-model="form.school_class_id" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.school_class_id }">
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
            <label class="block text-sm font-medium text-gray-600 mb-2">University</label>
            <select v-model="form.university" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 outline-none" :class="{ 'border-red-500': form.errors.university }">
              <option value="">Select University</option>
              <option value="Addis Ababa University">Addis Ababa University</option>
              <option value="Adama Science and Technology University">Adama Science and Technology University</option>
              <option value="Jimma University">Jimma University</option>
              <option value="Bahir Dar University">Bahir Dar University</option>
              <option value="Hawassa University">Hawassa University</option>
              <option value="Mekelle University">Mekelle University</option>
              <option value="Dire Dawa University">Dire Dawa University</option>
              <option value="Debre Berhan University">Debre Berhan University</option>
              <option value="Arba Minch University">Arba Minch University</option>
              <option value="Wolaita Sodo University">Wolaita Sodo University</option>
            </select>
            <div v-if="form.errors.university" class="text-red-500 text-xs mt-1">{{ form.errors.university }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Department</label>
            <select v-model="form.department" class="w-full border rounded-lg px-4 py-3" :class="{ 'border-red-500': form.errors.department }">
              <option value="">Select Department</option>
              <option value="Software Engineering">Software Engineering</option>
              <option value="Computer Science">Computer Science</option>
              <option value="Information Systems">Information Systems</option>
              <option value="UI/UX Design">UI/UX Design</option>
            </select>
            <div v-if="form.errors.department" class="text-red-500 text-xs mt-1">{{ form.errors.department }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Year of Study</label>
            <select v-model="form.year_of_study" class="w-full border rounded-lg px-4 py-3" :class="{ 'border-red-500': form.errors.year_of_study }">
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

          <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition shadow-md" :disabled="form.processing">
            {{ form.processing ? 'Saving...' : 'Save Student' }}
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
