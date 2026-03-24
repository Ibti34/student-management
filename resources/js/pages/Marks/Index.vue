<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
  students: Array,
  subjects: Array,
  marks: Array,
  studentResults: Array,
  user_role: String,
  selectedStudent: Object,
  semesterSummaries: Array,
  transcriptSummary: Object,
  filters: Object,
  availableSemesters: Array,
  availableAcademicYears: Array,
})

const page = usePage()
const editingId = ref(null)
const showForm = ref(false)
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
      preserveScroll: true,
      onSuccess: () => resetForm(),
    })
    return
  }

  form.transform(() => payload).post(route('marks.store'), {
    preserveScroll: true,
    onSuccess: () => resetForm(),
  })
}

const editMark = (course, semester) => {
  editingId.value = course.id
  showForm.value = true
  form.student_id = course.student_id
  form.subject_id = course.subject_id
  form.score = course.score
  form.semester = semester.semester
  form.academic_year = semester.academic_year
}

const deleteMark = (markId) => {
  if (!confirm('Delete this result?')) {
    return
  }

  router.delete(route('marks.destroy', markId), {
    preserveScroll: true,
  })
}

const resetForm = () => {
  form.reset()
  form.semester = filterForm.semester || 'Semester 1'
  form.academic_year = filterForm.academic_year || defaultAcademicYear
  editingId.value = null
  showForm.value = false
  form.clearErrors()
}

const openCreateForm = () => {
  editingId.value = null
  form.reset()
  form.semester = filterForm.semester || 'Semester 1'
  form.academic_year = filterForm.academic_year || defaultAcademicYear
  showForm.value = true
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

const gradeToneClass = (grade) => {
  if (grade.startsWith('A')) return 'text-emerald-700'
  if (grade.startsWith('B') || grade.startsWith('C')) return 'text-slate-900'
  return 'text-rose-700'
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-slate-50 px-4 py-8">
      <div class="mx-auto max-w-7xl space-y-6">
        <section class="rounded-[2rem] bg-white p-8 shadow-sm ring-1 ring-slate-100">
          <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500">Academic Results</p>
              <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">
                {{ user_role === 'student' ? 'My Transcript' : 'Student Results Overview' }}
              </h1>
              <p class="mt-2 max-w-2xl text-sm text-slate-500">
                See each student once, then open each semester to view courses, grades, and cumulative performance.
              </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
              <div v-if="selectedStudent" class="rounded-2xl bg-slate-50 px-5 py-4 text-sm text-slate-600">
                <div class="font-bold text-slate-900">{{ selectedStudent.name }}</div>
                <div>{{ selectedStudent.program }}</div>
                <div>{{ selectedStudent.advisor }}</div>
              </div>

              <button
                v-if="canManageMarks"
                @click="showForm ? resetForm() : openCreateForm()"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
              >
                {{ showForm ? 'Close Editor' : 'Add Result' }}
              </button>
            </div>
          </div>
        </section>

        <div v-if="page.props.flash?.success" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
          {{ page.props.flash.success }}
        </div>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
          <div class="rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">GPA</p>
            <p class="mt-2 text-3xl font-black text-slate-900">{{ transcriptSummary?.gpa ?? '0.00' }}</p>
          </div>
          <div class="rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Average</p>
            <p class="mt-2 text-3xl font-black text-slate-900">{{ transcriptSummary?.average_score ?? '0.00' }}%</p>
          </div>
          <div class="rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Credits</p>
            <p class="mt-2 text-3xl font-black text-slate-900">{{ transcriptSummary?.credits ?? 0 }}</p>
          </div>
          <div class="rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Passed</p>
            <p class="mt-2 text-3xl font-black text-slate-900">{{ transcriptSummary?.passed_courses ?? 0 }}/{{ transcriptSummary?.courses ?? 0 }}</p>
          </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-end">
            <div v-if="canManageMarks" class="w-full lg:max-w-xs">
              <label class="mb-2 block text-sm font-semibold text-slate-700">Student</label>
              <select v-model="filterForm.student_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">All students</option>
                <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name }}</option>
              </select>
            </div>

            <div class="w-full lg:max-w-xs">
              <label class="mb-2 block text-sm font-semibold text-slate-700">Semester</label>
              <select v-model="filterForm.semester" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">All semesters</option>
                <option v-for="semester in availableSemesters" :key="semester" :value="semester">{{ semester }}</option>
              </select>
            </div>

            <div class="w-full lg:max-w-xs">
              <label class="mb-2 block text-sm font-semibold text-slate-700">Academic Year</label>
              <select v-model="filterForm.academic_year" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">All academic years</option>
                <option v-for="academicYear in availableAcademicYears" :key="academicYear" :value="academicYear">{{ academicYear }}</option>
              </select>
            </div>

            <div class="flex gap-3 lg:ml-auto">
              <button @click="applyFilters" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                Apply
              </button>
              <button @click="clearFilters" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                Reset
              </button>
            </div>
          </div>
        </section>

        <section v-if="canManageMarks && showForm" class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-5 flex items-start justify-between gap-4">
            <div>
              <h2 class="text-xl font-black text-slate-900">{{ editingId ? 'Edit Result' : 'Add Result' }}</h2>
              <p class="text-sm text-slate-500">Update one course result, then return to the grouped student overview.</p>
            </div>

            <button @click="resetForm" class="text-sm font-semibold text-slate-500 transition hover:text-slate-900">
              Cancel
            </button>
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

            <div class="md:col-span-2 xl:col-span-5 flex gap-3 pt-2">
              <button type="submit" :disabled="form.processing" class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60">
                {{ editingId ? 'Update Result' : 'Save Result' }}
              </button>
            </div>
          </form>
        </section>

        <section class="space-y-5">
          <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <h2 class="text-xl font-black text-slate-900">Student Results</h2>
            <p class="text-sm text-slate-500">
              Each student shows cumulative performance at the top, then every semester with the courses taken in that period.
            </p>
          </div>

          <div v-if="studentResults?.length" class="space-y-5">
            <article v-for="result in studentResults" :key="result.student?.id" class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
              <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                  <h3 class="text-2xl font-black text-slate-900">{{ result.student?.name || 'Student' }}</h3>
                  <p v-if="result.student?.email" class="mt-1 text-sm text-slate-500">{{ result.student.email }}</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                  <div class="rounded-2xl bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Cumulative GPA</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ result.cumulative_gpa }}</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Average</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ result.cumulative_average }}%</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Credits</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ result.total_credits }}</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 px-4 py-3">
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Courses</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ result.total_courses }}</p>
                  </div>
                </div>
              </div>

              <div class="mt-6 grid gap-4 xl:grid-cols-2">
                <section v-for="semester in result.semesters" :key="`${result.student?.id}-${semester.academic_year}-${semester.semester}`" class="rounded-[1.5rem] border border-slate-200 p-5">
                  <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                      <h4 class="text-lg font-black text-slate-900">{{ semester.semester }}</h4>
                      <p class="text-sm text-slate-500">{{ semester.academic_year }}</p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center">
                      <div class="rounded-xl bg-slate-50 px-3 py-2">
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">GPA</div>
                        <div class="mt-1 font-bold text-slate-900">{{ semester.gpa }}</div>
                      </div>
                      <div class="rounded-xl bg-slate-50 px-3 py-2">
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Avg</div>
                        <div class="mt-1 font-bold text-slate-900">{{ semester.average_score }}%</div>
                      </div>
                      <div class="rounded-xl bg-slate-50 px-3 py-2">
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Credits</div>
                        <div class="mt-1 font-bold text-slate-900">{{ semester.credits }}</div>
                      </div>
                    </div>
                  </div>

                  <div class="mt-4 overflow-hidden rounded-2xl border border-slate-100">
                    <table class="min-w-full text-left text-sm">
                      <thead class="bg-slate-50 text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                        <tr>
                          <th class="px-4 py-3">Course</th>
                          <th class="px-4 py-3">Grade</th>
                          <th class="px-4 py-3">Score</th>
                          <th class="px-4 py-3">Status</th>
                          <th v-if="canManageMarks" class="px-4 py-3">Actions</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-100">
                        <tr v-for="course in semester.courses" :key="course.id">
                          <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900">{{ course.subject_name }}</div>
                            <div class="mt-1 text-xs text-slate-400">
                              <span v-if="course.subject_code">{{ course.subject_code }}</span>
                              <span v-if="course.subject_code && course.credit_hours"> - </span>
                              <span v-if="course.credit_hours">{{ course.credit_hours }} credits</span>
                              <span v-if="!course.subject_code && !course.credit_hours">Course details not set</span>
                            </div>
                          </td>
                          <td class="px-4 py-3">
                            <div class="font-bold" :class="gradeToneClass(course.letter_grade)">{{ course.letter_grade }}</div>
                            <div class="text-xs text-slate-400">{{ course.grade_points.toFixed(2) }} GPA</div>
                          </td>
                          <td class="px-4 py-3 font-semibold text-slate-900">{{ course.score }}%</td>
                          <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" :class="overallStatusClass(course.status_label)">
                              {{ course.status_label }}
                            </span>
                          </td>
                          <td v-if="canManageMarks" class="px-4 py-3">
                            <div class="flex flex-col items-start gap-2">
                              <button @click="editMark(course, semester)" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
                                Edit
                              </button>
                              <button @click="deleteMark(course.id)" class="text-sm font-semibold text-rose-600 transition hover:text-rose-800">
                                Delete
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </section>
              </div>
            </article>
          </div>

          <div v-else class="rounded-[2rem] bg-white p-10 text-center text-sm text-slate-500 shadow-sm ring-1 ring-slate-100">
            No results found for the current filters yet.
          </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-4">
            <h2 class="text-xl font-black text-slate-900">Semester Summary</h2>
            <p class="text-sm text-slate-500">Quick GPA snapshots across the filtered result set.</p>
          </div>

          <div v-if="semesterSummaries?.length" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
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
        </section>
      </div>
    </div>
  </AppLayout>
</template>
