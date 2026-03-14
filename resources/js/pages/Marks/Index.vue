<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  students: Array,
  subjects: Array,
  marks: Array,
  user_role: String,
})

const editingId = ref(null)

const form = useForm({
  student_id: '',
  subject_id: '',
  score: '',
  term: 'Term 1',
})

const submit = () => {
  if (editingId.value) {
    form.put(route('marks.update', editingId.value), {
      onSuccess: () => resetForm(),
    })
  } else {
    form.post(route('marks.store'), {
      onSuccess: () => resetForm(),
    })
  }
}

const editMark = (mark) => {
  editingId.value = mark.id
  form.student_id = mark.student_id
  form.subject_id = mark.subject_id
  form.score = mark.score
  form.term = mark.term
}

const resetForm = () => {
  form.reset()
  editingId.value = null
}
</script>

<template>
  <AppLayout>
    <div class="p-6 bg-gray-50 min-h-screen">
      <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Student Mark Management</h1>

        <div v-if="user_role === 'teacher'" class="bg-white p-6 rounded-lg shadow mb-6">
          <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
              <label class="block text-sm font-medium text-gray-700">Student</label>
              <select v-model="form.student_id" class="w-full border p-2 rounded-md" :class="{'border-red-500': form.errors.student_id}">
                <option value="" disabled>Select Student</option>
                <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name }}</option>
              </select>
              <div v-if="form.errors.student_id" class="text-red-500 text-xs mt-1">{{ form.errors.student_id }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Subject</label>
              <select v-model="form.subject_id" class="w-full border p-2 rounded-md" :class="{'border-red-500': form.errors.subject_id}">
                <option value="" disabled>Select Subject</option>
                <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
              </select>
              <div v-if="form.errors.subject_id" class="text-red-500 text-xs mt-1">{{ form.errors.subject_id }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Score</label>
              <input type="number" v-model="form.score" class="w-full border p-2 rounded-md" placeholder="0-100" :class="{'border-red-500': form.errors.score}"/>
              <div v-if="form.errors.score" class="text-red-500 text-xs mt-1">{{ form.errors.score }}</div>
            </div>

            <div class="flex gap-2">
              <button type="submit" :disabled="form.processing" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50">
                {{ editingId ? 'Update' : 'Save' }}
              </button>
              <button v-if="editingId" type="button" @click="resetForm" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md">
                Cancel
              </button>
            </div>
          </form>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="w-full text-left">
            <thead class="bg-gray-100 font-bold text-xs uppercase">
              <tr>
                <th class="p-4">Student</th>
                <th class="p-4">Subject</th>
                <th class="p-4">Score</th>
                <th class="p-4">Status</th>
                <th v-if="user_role === 'teacher'" class="p-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="mark in marks" :key="mark.id" class="border-t">
                <td class="p-4">{{ mark.student?.name || '—' }}</td>
                <td class="p-4">{{ mark.subject?.name || '—' }}</td>
                <td class="p-4">{{ mark.score }}</td>
                <td class="p-4">
                  <span :class="mark.score >= 50 ? 'text-green-600' : 'text-red-600'" class="font-bold">
                    {{ mark.score >= 50 ? 'PASS' : 'FAIL' }}
                  </span>
                </td>
                <td v-if="user_role === 'teacher'" class="p-4">
                  <button @click="editMark(mark)" class="text-blue-600 mr-2 hover:underline">Edit</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>