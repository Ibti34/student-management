<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    students: Array,
    subjects: Array,
    marks: Array
})

const editingId = ref(null)

const form = useForm({
    student_id: '',
    subject_id: '',
    score: '',
    term: 'Term 1'
})

const submit = () => {
    if (editingId.value) {
        form.put(route('marks.update', editingId.value), {
            onSuccess: () => { form.reset(); editingId.value = null; }
        })
    } else {
        form.post(route('marks.store'), {
            onSuccess: () => form.reset()
        })
    }
}

const editMark = (mark) => {
    editingId.value = mark.id
    form.student_id = mark.student_id
    form.subject_id = mark.subject_id
    form.score = mark.score
}
</script>

<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Student Mark Management</h1>

      <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form @submit.prevent="submit" class="flex flex-wrap gap-4 items-end">
          <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium mb-1">Student</label>
            <select v-model="form.student_id" class="w-full border p-2 rounded-md">
              <option value="" disabled>Select Student</option>
              <option v-for="student in students" :key="student.id" :value="student.id">
                {{ student.name }}
              </option>
            </select>
          </div>

          <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium mb-1">Subject</label>
            <select v-model="form.subject_id" class="w-full border p-2 rounded-md">
              <option value="" disabled>Select Subject</option>
              <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                {{ subject.name }}
              </option>
            </select>
          </div>

          <div class="w-32">
            <label class="block text-sm font-medium mb-1">Score</label>
            <input type="number" v-model="form.score" class="w-full border p-2 rounded-md" placeholder="0-100" />
          </div>

          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            {{ editingId ? 'Update' : 'Save' }}
          </button>
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
              <th class="p-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="mark in marks" :key="mark.id" class="border-t">
              <td class="p-4">{{ mark.student?.name }}</td>
              <td class="p-4">{{ mark.subject?.name }}</td>
              <td class="p-4">{{ mark.score }}</td>
              <td class="p-4">
                <span :class="mark.score >= 50 ? 'text-green-600' : 'text-red-600'" class="font-bold">
                  {{ mark.score >= 50 ? 'PASS' : 'FAIL' }}
                </span>
              </td>
              <td class="p-4">
                <button @click="editMark(mark)" class="text-blue-600 mr-2">Edit</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>