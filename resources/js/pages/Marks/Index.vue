```vue
<script setup>
import { useForm, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    students: Array,
    subjects: Array,
    marks: Array
})

/* Logged in user */
const page = usePage()
const user = computed(() => page.props.auth?.user)

/* Track editing mark */
const editingId = ref(null)

/* Form */
const form = useForm({
    student_id: '',
    subject_id: '',
    score: '',
    term: 'Term 1'
})

/* Submit form */
const submit = () => {

    if (editingId.value) {
        form.put(route('marks.update', editingId.value), {
            onSuccess: () => {
                form.reset()
                editingId.value = null
            }
        })
    } else {
        form.post(route('marks.store'), {
            onSuccess: () => {
                form.reset()
            }
        })
    }

}

/* Delete mark */
const deleteMark = (id) => {

    if (confirm('Are you sure you want to delete this mark?')) {
        router.delete(route('marks.destroy', id))
    }

}

/* Edit mark */
const editMark = (mark) => {

    editingId.value = mark.id

    form.student_id = mark.student_id
    form.subject_id = mark.subject_id
    form.score = mark.score
    form.term = mark.term

}

/* Cancel editing */
const cancelEdit = () => {

    editingId.value = null
    form.reset()

}
</script>


<template>
<div class="p-8 bg-gray-100 min-h-screen">

<div class="max-w-5xl mx-auto">

<h1 class="text-2xl font-bold mb-6">
Student Mark Management
</h1>


<!-- MARK FORM -->
<div v-if="user?.role === 'teacher'" class="bg-white p-6 rounded-lg shadow-md mb-8">

<form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

<!-- Student -->
<div>
<label class="block text-sm font-medium text-gray-700">Student</label>

<select
v-model="form.student_id"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
>

<option value="" disabled>Select Student</option>

<option
v-for="student in students"
:key="student.id"
:value="student.id"
>
{{ student.name }}
</option>

</select>
</div>


<!-- Subject -->
<div>
<label class="block text-sm font-medium text-gray-700">Subject</label>

<select
v-model="form.subject_id"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
>

<option value="" disabled>Select Subject</option>

<option
v-for="subject in subjects"
:key="subject.id"
:value="subject.id"
>
{{ subject.name }}
</option>

</select>
</div>


<!-- Score -->
<div>
<label class="block text-sm font-medium text-gray-700">Score</label>

<input
type="number"
v-model="form.score"
placeholder="0-100"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>
</div>


<!-- Buttons -->
<div class="flex gap-2">

<button
type="submit"
:disabled="form.processing"
class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
>
{{ editingId ? 'Update' : 'Save' }}
</button>

<button
v-if="editingId"
type="button"
@click="cancelEdit"
class="bg-gray-500 text-white px-4 py-2 rounded-md"
>
Cancel
</button>

</div>

</form>

</div>


<!-- MARK TABLE -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">

<table class="min-w-full divide-y divide-gray-200">

<thead class="bg-gray-50">

<tr>

<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
Student
</th>

<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
Subject
</th>

<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
Score
</th>

<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
Status
</th>

<th
v-if="user?.role === 'teacher'"
class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"
>
Actions
</th>

</tr>

</thead>


<tbody class="divide-y divide-gray-200">


<!-- EMPTY MESSAGE -->
<tr v-if="marks.length === 0">

<td colspan="5" class="text-center py-6 text-gray-500">
No marks recorded yet
</td>

</tr>


<!-- MARK ROWS -->
<tr
v-for="mark in marks"
:key="mark.id"
>

<td class="px-6 py-4">
{{ mark.student?.name || 'Unknown' }}
</td>

<td class="px-6 py-4">
{{ mark.subject?.name || 'Unknown' }}
</td>

<td class="px-6 py-4 font-bold">
{{ mark.score }}
</td>


<td class="px-6 py-4">

<span
:class="mark.score >= 50
? 'bg-green-100 text-green-800'
: 'bg-red-100 text-red-800'"
class="px-2 py-1 rounded text-xs"
>

{{ mark.score >= 50 ? 'Pass' : 'Fail' }}

</span>

</td>


<td
v-if="user?.role === 'teacher'"
class="px-6 py-4 text-right"
>

<button
@click="editMark(mark)"
class="text-indigo-600 hover:text-indigo-900 mr-3"
>
Edit
</button>

<button
@click="deleteMark(mark.id)"
class="text-red-600 hover:text-red-900"
>
Delete
</button>

</td>

</tr>

</tbody>

</table>

</div>

</div>

</div>
</template>
```
