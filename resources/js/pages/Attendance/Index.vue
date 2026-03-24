<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    students: Array,
    attendanceRecords: Object, // This comes from your Index method
    selectedDate: String,
    classes: Array,
    selectedClassId: [String, Number, null],
})

// 1. Initialize the form with a proper object structure
const form = useForm({
    date: props.selectedDate || new Date().toISOString().split('T')[0],
    class_id: props.selectedClassId || '',
    // This creates an object like { "1": "present", "2": "late" }
    attendance: props.students.reduce((acc, student) => {
        acc[student.id] = props.attendanceRecords?.[student.id]?.status || ''
        return acc
    }, {})
})

// 2. Refresh page data when the date is changed
watch(() => form.date, (newDate) => {
    router.get(route('attendance.index'), { date: newDate, class_id: form.class_id }, {
        preserveState: true,
        replace: true
    })
})

watch(() => form.class_id, (newClassId) => {
    router.get(route('attendance.index'), { date: form.date, class_id: newClassId }, {
        preserveState: true,
        replace: true
    })
})

// 3. Mark All Present logic
const markAllPresent = () => {
    props.students.forEach(student => {
        form.attendance[student.id] = 'present'
    })
}

// 4. THE SAVE FUNCTION
const submitAttendance = () => {
    form.post(route('attendance.storeBulk'), {
        preserveScroll: true,
        onSuccess: (page) => {
         
            if (page.props.flash.success) {
                alert(page.props.flash.success); // This is the simplest way to see it
            }
            console.log("Saved successfully");
        },
        onError: (errors) => {
            console.error(errors);
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-5xl mx-auto bg-gray-50 min-h-screen">
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Daily Attendance</h1>
                <div class="flex gap-3">
                    <select v-model="form.class_id" class="border border-gray-300 rounded-lg p-2 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        <option value="">All cohorts</option>
                        <option v-for="schoolClass in classes" :key="schoolClass.id" :value="schoolClass.id">
                            {{ schoolClass.name }} {{ schoolClass.section }}
                        </option>
                    </select>
                    <input 
                        type="date" 
                        v-model="form.date" 
                        class="border border-gray-300 rounded-lg p-2 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none" 
                    />
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-end">
                    <button 
                        @click="markAllPresent" 
                        type="button"
                        class="text-xs font-semibold uppercase tracking-wider bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-md text-gray-600 transition"
                    >
                        Mark All Present
                    </button>
                </div>

                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                        <tr>
                            <th class="p-4">Student</th>
                            <th class="p-4 text-center">Present</th>
                            <th class="p-4 text-center">Late</th>
                            <th class="p-4 text-center">Absent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-700">
                                <div>{{ student.name }}</div>
                                <div class="text-xs text-gray-500">{{ student.school_class ? `${student.school_class.name} ${student.school_class.section}` : 'Unassigned' }}</div>
                            </td>
                            
                            <td class="p-4 text-center">
                                <input 
                                    type="radio" 
                                    :name="'student_' + student.id" 
                                    value="present" 
                                    v-model="form.attendance[student.id]"
                                    class="w-5 h-5 cursor-pointer accent-blue-600"
                                />
                            </td>

                            <td class="p-4 text-center">
                                <input 
                                    type="radio" 
                                    :name="'student_' + student.id" 
                                    value="late" 
                                    v-model="form.attendance[student.id]"
                                    class="w-5 h-5 cursor-pointer accent-yellow-500"
                                />
                            </td>

                            <td class="p-4 text-center">
                                <input 
                                    type="radio" 
                                    :name="'student_' + student.id" 
                                    value="absent" 
                                    v-model="form.attendance[student.id]"
                                    class="w-5 h-5 cursor-pointer accent-red-600"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <button
                    @click="submitAttendance"
                    :disabled="form.processing"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-lg shadow-lg hover:shadow-xl transition-all disabled:opacity-50 uppercase tracking-widest text-sm"
                >
                    {{ form.processing ? 'Saving...' : 'Save Attendance' }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>
