<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { router, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
  students: Array,
  subjects: Array,
  marks: Array,
  user_role: String,
  selectedStudent: Object,
  semesterSummaries: Array,
  transcriptSummary: Object,
  filters: Object,
  availableSemesters: Array,
  availableAcademicYears: Array,
})

const editingId = ref(null)
const canManageMarks = computed(() => ['admin', 'teacher'].includes(props.user_role))
const currentAcademicYear = new Date().getFullYear()
const defaultAcademicYear = `${currentAcademicYear}/${currentAcademicYear + 1}`

const filterForm = useForm({
  student_id: props.filters?.student_id ?? '',
  semester: props.filters?.semester ?? '',
  academic_year: props.filters?.academic_year ?? '',
})

const form = useForm({
  student_id: '',
  subject_id: '',
  score: '',
  semester: props.filters?.semester || 'Semester 1',
  academic_year: props.filters?.academic_year || defaultAcademicYear,
})

const submit = () => {
  const payload = {
    ...form.data(),
    score: Number(form.score),
  }

  if (editingId.value) {
    form.transform(() => payload).put(route('marks.update', editingId.value), {
      onSuccess: () => resetForm(),
    })
    return
  }

  form.transform(() => payload).post(route('marks.store'), {
    onSuccess: () => resetForm(),
  })
}

const editMark = (mark) => {
  editingId.value = mark.id
  form.student_id = mark.student_id
  form.subject_id = mark.subject_id
  form.score = mark.score
  form.semester = mark.semester
  form.academic_year = mark.academic_year
}

const resetForm = () => {
  form.reset()
  form.semester = filterForm.semester || 'Semester 1'
  form.academic_year = filterForm.academic_year || defaultAcademicYear
  editingId.value = null
  form.clearErrors()
}

const applyFilters = () => {
  router.get(route(props.user_role === 'student' ? 'marks.my' : 'marks.index'), filterForm.data(), {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  })
}

const clearFilters = () => {
  filterForm.student_id = ''
  filterForm.semester = ''
  filterForm.academic_year = ''
  applyFilters()
}

const overallStatusClass = (label) => {
  return label === 'PASS'
    ? 'bg-emerald-100 text-emerald-700'
    : 'bg-rose-100 text-rose-700'
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-slate-50 px-4 py-8">
      <div class="mx-auto max-w-7xl space-y-8">
        <section class="rounded-[2rem] bg-white p-8 shadow-sm ring-1 ring-slate-100">
          <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500">Academic Results</p>
              <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">
                {{ user_role === 'student' ? 'My Transcript' : 'Results & GPA Tracking' }}
              </h1>
              <p class="mt-2 max-w-2xl text-sm text-slate-500">
                View semester results, GPA, course performance, and transcript details in one place.
              </p>
            </div>

            <div v-if="selectedStudent" class="rounded-2xl bg-slate-50 px-5 py-4 text-sm text-slate-600">
              <div class="font-bold text-slate-900">{{ selectedStudent.name }}</div>
              <div>{{ selectedStudent.program }}</div>
              <div>{{ selectedStudent.advisor }}</div>
            </div>
          </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          <div class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Cumulative GPA</p>
            <p class="mt-3 text-4xl font-black text-slate-900">{{ transcriptSummary?.gpa ?? '0.00' }}</p>
          </div>
          <div class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Average Score</p>
            <p class="mt-3 text-4xl font-black text-slate-900">{{ transcriptSummary?.average_score ?? '0.00' }}%</p>
          </div>
          <div class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Credits Earned</p>
            <p class="mt-3 text-4xl font-black text-slate-900">{{ transcriptSummary?.credits ?? 0 }}</p>
          </div>
          <div class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Passed Courses</p>
            <p class="mt-3 text-4xl font-black text-slate-900">
              {{ transcriptSummary?.passed_courses ?? 0 }}/{{ transcriptSummary?.courses ?? 0 }}
            </p>
          </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-4">
            <h2 class="text-xl font-black text-slate-900">Transcript Filters</h2>
            <p class="text-sm text-slate-500">Focus the transcript by student, semester, or academic year.</p>
          </div>

          <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div v-if="canManageMarks">
              <label class="mb-2 block text-sm font-semibold text-slate-700">Student</label>
              <select v-model="filterForm.student_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">All students</option>
                <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name }}</option>
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Semester</label>
              <select v-model="filterForm.semester" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">All semesters</option>
                <option v-for="semester in availableSemesters" :key="semester" :value="semester">{{ semester }}</option>
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Academic Year</label>
              <select v-model="filterForm.academic_year" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">All academic years</option>
                <option v-for="academicYear in availableAcademicYears" :key="academicYear" :value="academicYear">{{ academicYear }}</option>
              </select>
            </div>

            <div class="flex items-end gap-3">
              <button @click="applyFilters" class="flex-1 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                Apply
              </button>
              <button @click="clearFilters" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                Reset
              </button>
            </div>
          </div>
        </section>

        <section v-if="canManageMarks" class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-4">
            <h2 class="text-xl font-black text-slate-900">{{ editingId ? 'Update Result' : 'Add Result' }}</h2>
            <p class="text-sm text-slate-500">Capture course results with semester and academic year for GPA tracking.</p>
          </div>

          <form @submit.prevent="submit" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Student</label>
              <select v-model="form.student_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" :class="{ 'border-rose-400': form.errors.student_id }">
                <option value="" disabled>Select student</option>
                <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name }}</option>
              </select>
              <p v-if="form.errors.student_id" class="mt-1 text-xs text-rose-600">{{ form.errors.student_id }}</p>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Course</label>
              <select v-model="form.subject_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" :class="{ 'border-rose-400': form.errors.subject_id }">
                <option value="" disabled>Select course</option>
                <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                  {{ subject.code ? `${subject.code} - ${subject.name}` : subject.name }}
                </option>
              </select>
              <p v-if="form.errors.subject_id" class="mt-1 text-xs text-rose-600">{{ form.errors.subject_id }}</p>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Score</label>
              <input v-model="form.score" type="number" min="0" max="100" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" :class="{ 'border-rose-400': form.errors.score }" placeholder="0-100" />
              <p v-if="form.errors.score" class="mt-1 text-xs text-rose-600">{{ form.errors.score }}</p>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Semester</label>
              <select v-model="form.semester" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" :class="{ 'border-rose-400': form.errors.semester }">
                <option value="Semester 1">Semester 1</option>
                <option value="Semester 2">Semester 2</option>
                <option value="Summer">Summer</option>
              </select>
              <p v-if="form.errors.semester" class="mt-1 text-xs text-rose-600">{{ form.errors.semester }}</p>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Academic Year</label>
              <input v-model="form.academic_year" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" :class="{ 'border-rose-400': form.errors.academic_year }" placeholder="2026/2027" />
              <p v-if="form.errors.academic_year" class="mt-1 text-xs text-rose-600">{{ form.errors.academic_year }}</p>
            </div>

            <div class="md:col-span-2 xl:col-span-5 flex flex-wrap gap-3 pt-2">
              <button type="submit" :disabled="form.processing" class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60">
                {{ editingId ? 'Update Result' : 'Save Result' }}
              </button>
              <button v-if="editingId" type="button" @click="resetForm" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                Cancel
              </button>
            </div>
          </form>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.2fr_2fr]">
          <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <div class="mb-4">
              <h2 class="text-xl font-black text-slate-900">Semester Summary</h2>
              <p class="text-sm text-slate-500">Track average score and GPA across each semester.</p>
            </div>

            <div v-if="semesterSummaries?.length" class="space-y-3">
              <div v-for="semester in semesterSummaries" :key="`${semester.academic_year}-${semester.semester}`" class="rounded-2xl bg-slate-50 p-4">
                <div class="flex items-start justify-between gap-4">
                  <div>
                    <p class="text-sm font-bold text-slate-900">{{ semester.semester }}</p>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ semester.academic_year }}</p>
                  </div>
                  <div class="rounded-xl bg-indigo-100 px-3 py-2 text-sm font-bold text-indigo-700">
                    GPA {{ semester.gpa }}
                  </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-slate-600">
                  <div>Courses: <span class="font-semibold text-slate-900">{{ semester.courses }}</span></div>
                  <div>Credits: <span class="font-semibold text-slate-900">{{ semester.credits }}</span></div>
                  <div>Average: <span class="font-semibold text-slate-900">{{ semester.average_score }}%</span></div>
                  <div>Passes: <span class="font-semibold text-slate-900">{{ semester.passes }}</span></div>
                </div>
              </div>
            </div>
            <div v-else class="rounded-2xl border border-dashed border-slate-200 p-6 text-sm text-slate-500">
              No transcript data yet. Add course results to start generating semester summaries.
            </div>
          </div>

          <div class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-5">
              <h2 class="text-xl font-black text-slate-900">Transcript Records</h2>
              <p class="text-sm text-slate-500">Detailed course-by-course results with grade letters and points.</p>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                  <tr>
                    <th class="px-6 py-4">Student</th>
                    <th class="px-6 py-4">Course</th>
                    <th class="px-6 py-4">Credits</th>
                    <th class="px-6 py-4">Semester</th>
                    <th class="px-6 py-4">Year</th>
                    <th class="px-6 py-4">Score</th>
                    <th class="px-6 py-4">Grade</th>
                    <th class="px-6 py-4">Points</th>
                    <th class="px-6 py-4">Status</th>
                    <th v-if="canManageMarks" class="px-6 py-4">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="mark in marks" :key="mark.id" class="bg-white">
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ mark.student?.name || '-' }}</td>
                    <td class="px-6 py-4">
                      <div class="font-semibold text-slate-900">{{ mark.subject?.name || '-' }}</div>
                      <div class="text-xs text-slate-400">{{ mark.subject?.code || 'Course code pending' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ mark.subject?.credit_hours ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ mark.semester }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ mark.academic_year }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ mark.score }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ mark.letter_grade }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ mark.grade_points.toFixed(2) }}</td>
                    <td class="px-6 py-4">
                      <span class="rounded-full px-3 py-1 text-xs font-bold" :class="overallStatusClass(mark.status_label)">
                        {{ mark.status_label }}
                      </span>
                    </td>
                    <td v-if="canManageMarks" class="px-6 py-4">
                      <button @click="editMark(mark)" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
                        Edit
                      </button>
                    </td>
                  </tr>
                  <tr v-if="!marks.length">
                    <td :colspan="canManageMarks ? 10 : 9" class="px-6 py-10 text-center text-sm text-slate-500">
                      No results found for the current filters yet.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>
