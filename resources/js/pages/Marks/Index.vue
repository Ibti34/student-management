<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
  students: Array,
  subjects: Array,
  user_role: String,
  selectedStudent: Object,
  reportData: Object,
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

const editMark = (course) => {
  editingId.value = course.id
  showForm.value = true
  form.student_id = course.student_id
  form.subject_id = course.subject_id
  form.score = course.score
  form.semester = course.semester
  form.academic_year = course.academic_year
}

const deleteMark = (markId) => {
  if (!confirm('Delete this result?')) return

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

const reportPrompt = computed(() => {
  if (props.user_role === 'student') {
    return 'Select an academic year and semester to view your grade report.'
  }

  return 'Select a student, academic year, and semester to view the grade report.'
})

const statusClass = (status) => {
  if (status === "Dean's List") return 'text-emerald-600'
  if (status === 'Good Standing') return 'text-slate-900'
  return 'text-rose-600'
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-slate-50 px-4 py-8">
      <div class="mx-auto max-w-5xl space-y-6">
        <section class="rounded-[2rem] bg-white p-8 shadow-sm ring-1 ring-slate-100">
          <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500">Academic Results</p>
              <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Student Examination Grade Report</h1>
              <p class="mt-2 max-w-2xl text-sm text-slate-500">{{ reportPrompt }}</p>
            </div>

            <button
              v-if="canManageMarks"
              @click="showForm ? resetForm() : openCreateForm()"
              class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
              {{ showForm ? 'Close Editor' : 'Add Result' }}
            </button>
          </div>
        </section>

        <div v-if="page.props.flash?.success" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
          {{ page.props.flash.success }}
        </div>

        <section class="rounded-[2rem] bg-white p-8 shadow-sm ring-1 ring-slate-100">
          <h2 class="text-2xl font-black text-slate-900">Get Grade Report</h2>
          <p class="mt-2 text-sm text-slate-500">Choose the academic period first, then read one clean report below.</p>

          <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div v-if="canManageMarks">
              <label class="mb-2 block text-sm font-semibold text-slate-700">Student</label>
              <select v-model="filterForm.student_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">Select student</option>
                <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name }}</option>
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Academic Year</label>
              <select v-model="filterForm.academic_year" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">Select academic year</option>
                <option v-for="academicYear in availableAcademicYears" :key="academicYear" :value="academicYear">{{ academicYear }}</option>
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Semester</label>
              <select v-model="filterForm.semester" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="">Select semester</option>
                <option v-for="semester in availableSemesters" :key="semester" :value="semester">{{ semester }}</option>
              </select>
            </div>
          </div>

          <div class="mt-6 flex flex-wrap gap-3">
            <button @click="applyFilters" class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
              Get My Grade Report
            </button>
            <button @click="clearFilters" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
              Reset
            </button>
          </div>
        </section>

        <section v-if="canManageMarks && showForm" class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
          <div class="mb-5 flex items-start justify-between gap-4">
            <div>
              <h2 class="text-xl font-black text-slate-900">{{ editingId ? 'Edit Result' : 'Add Result' }}</h2>
              <p class="text-sm text-slate-500">Update one course result and then come back to the report.</p>
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
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Course</label>
              <select v-model="form.subject_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" :class="{ 'border-rose-400': form.errors.subject_id }">
                <option value="" disabled>Select course</option>
                <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                  {{ subject.code ? `${subject.code} - ${subject.name}` : subject.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Score</label>
              <input v-model="form.score" type="number" min="0" max="100" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" placeholder="0-100" />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Semester</label>
              <select v-model="form.semester" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400">
                <option value="Semester 1">Semester 1</option>
                <option value="Semester 2">Semester 2</option>
                <option value="Summer">Summer</option>
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700">Academic Year</label>
              <input v-model="form.academic_year" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-400" placeholder="2026/2027" />
            </div>

            <div class="md:col-span-2 xl:col-span-5 flex gap-3 pt-2">
              <button type="submit" :disabled="form.processing" class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60">
                {{ editingId ? 'Update Result' : 'Save Result' }}
              </button>
            </div>
          </form>
        </section>

        <section v-if="reportData" class="rounded-[2rem] bg-white p-8 shadow-sm ring-1 ring-slate-100">
          <div class="border-b border-slate-100 pb-6">
            <h2 class="text-2xl font-black text-slate-900">
              {{ filterForm.semester || 'Selected Semester' }} ({{ filterForm.academic_year || 'Selected Year' }})
            </h2>
            <div class="mt-4 grid gap-2 text-sm text-slate-600">
              <div><span class="font-semibold text-slate-900">Institute:</span> {{ reportData.student.university }}</div>
              <div><span class="font-semibold text-slate-900">Department:</span> {{ reportData.student.department }}</div>
              <div><span class="font-semibold text-slate-900">Program:</span> {{ reportData.student.program }}</div>
              <div><span class="font-semibold text-slate-900">Student:</span> {{ reportData.student.name }}</div>
            </div>
          </div>

          <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full text-left text-sm">
              <thead class="bg-slate-50 text-sm font-black text-slate-600">
                <tr>
                  <th class="px-4 py-3">#</th>
                  <th class="px-4 py-3">Course Code</th>
                  <th class="px-4 py-3">Course Title</th>
                  <th class="px-4 py-3">Credit</th>
                  <th class="px-4 py-3">Grade</th>
                  <th class="px-4 py-3">Grade Point</th>
                  <th v-if="canManageMarks" class="px-4 py-3">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(course, index) in reportData.current.courses" :key="course.id">
                  <td class="px-4 py-3 font-semibold text-slate-900">{{ index + 1 }}</td>
                  <td class="px-4 py-3 text-slate-700">{{ course.subject.code || '-' }}</td>
                  <td class="px-4 py-3 font-medium text-slate-900">{{ course.subject.name }}</td>
                  <td class="px-4 py-3 text-slate-700">{{ course.subject.credit_hours ?? 0 }}</td>
                  <td class="px-4 py-3 font-bold text-slate-900">{{ course.letter_grade }}</td>
                  <td class="px-4 py-3 text-slate-700">{{ course.weighted_points }}</td>
                  <td v-if="canManageMarks" class="px-4 py-3">
                    <div class="flex flex-col items-start gap-2">
                      <button @click="editMark(course)" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
                        Edit
                      </button>
                      <button @click="deleteMark(course.id)" class="text-sm font-semibold text-rose-600 transition hover:text-rose-800">
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
              <tfoot class="bg-slate-50 font-black text-slate-900">
                <tr>
                  <td class="px-4 py-3" colspan="3">TOTAL</td>
                  <td class="px-4 py-3">{{ reportData.current.credit_taken }}</td>
                  <td class="px-4 py-3"></td>
                  <td class="px-4 py-3">{{ reportData.current.gp_earned }}</td>
                  <td v-if="canManageMarks" class="px-4 py-3"></td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="mt-8 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 p-5">
              <h3 class="text-2xl font-black text-slate-900">Previous</h3>
              <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>Credit Taken:</span><span class="font-semibold">{{ reportData.previous.credit_taken }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>GP Earned:</span><span class="font-semibold">{{ reportData.previous.gp_earned }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>CGPA:</span><span class="font-semibold">{{ reportData.previous.cgpa }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>Status:</span><span class="font-semibold" :class="statusClass(reportData.previous.status)">{{ reportData.previous.status }}</span></div>
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-5">
              <h3 class="text-2xl font-black text-slate-900">This Semester</h3>
              <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>Credit Taken:</span><span class="font-semibold">{{ reportData.current.credit_taken }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>GP Earned:</span><span class="font-semibold">{{ reportData.current.gp_earned }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>SGPA:</span><span class="font-semibold">{{ reportData.current.sgpa }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>CGPA:</span><span class="font-semibold">{{ reportData.current.cgpa }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>Status:</span><span class="font-semibold" :class="statusClass(reportData.current.status)">{{ reportData.current.status }}</span></div>
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-5">
              <h3 class="text-2xl font-black text-slate-900">Cumulative Academic Status</h3>
              <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>Total Credit Taken:</span><span class="font-semibold">{{ reportData.cumulative.credit_taken }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>Total GP Earned:</span><span class="font-semibold">{{ reportData.cumulative.gp_earned }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>CGPA:</span><span class="font-semibold">{{ reportData.cumulative.cgpa }}</span></div>
                <div class="flex justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3"><span>Status:</span><span class="font-semibold" :class="statusClass(reportData.cumulative.status)">{{ reportData.cumulative.status }}</span></div>
              </div>
            </div>
          </div>
        </section>

        <section v-else class="rounded-[2rem] bg-white p-10 text-center text-sm text-slate-500 shadow-sm ring-1 ring-slate-100">
          Choose the student, academic year, and semester above to generate a simple grade report.
        </section>
      </div>
    </div>
  </AppLayout>
</template>
