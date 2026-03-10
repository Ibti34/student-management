<script setup>
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    students: Array,
    attendance: Object, // This is grouped by student_id from the controller
    daysInMonth: Number,
    currentMonth: String,
    currentYear: Number
})

// Helper function to find a specific day's status for a student
const getStatus = (studentId, day) => {
    const records = props.attendance[studentId];
    if (!records) return null;

    // Convert the 'day' number to a 2-digit string (e.g., 5 becomes "05")
    const searchDay = String(day).padStart(2, '0');

    // Just check the string directly: "2026-03-10" ends with "-10"
    const record = records.find(r => r.date.endsWith(`-${searchDay}`));

    return record ? record.status : null;
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-full mx-auto bg-gray-50 min-h-screen">
            
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Attendance History</h1>
                <div class="text-sm font-medium bg-white px-4 py-2 border rounded shadow-sm">
                    {{ currentMonth }} {{ currentYear }}
                </div>
            </div>

            <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase font-bold text-xs">
                            <tr>
                                <th class="p-3 border-b sticky left-0 bg-gray-100 z-10 min-w-[180px]">Student Name</th>
                                <th v-for="day in daysInMonth" :key="day" class="p-2 border-b border-l text-center w-10">
                                    {{ day }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50 border-b last:border-0">
                                <td class="p-3 font-medium text-gray-900 sticky left-0 bg-white border-r shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {{ student.name }}
                                </td>
                                
                               <td v-for="day in daysInMonth" :key="day" class="p-2 border-l text-center h-10">
    <span v-if="getStatus(student.id, day) === 'present'" class="text-green-600 font-bold">P</span>
    <span v-else-if="getStatus(student.id, day) === 'late'" class="text-yellow-600 font-bold">L</span>
    <span v-else-if="getStatus(student.id, day) === 'absent'" class="text-red-600 font-bold">A</span>
    <span v-else class="text-gray-200">-</span>
</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 flex gap-4 text-xs text-gray-600">
                <div class="flex items-center gap-1"><span class="font-bold text-green-600">P:</span> Present</div>
                <div class="flex items-center gap-1"><span class="font-bold text-yellow-600">L:</span> Late</div>
                <div class="flex items-center gap-1"><span class="font-bold text-red-600">A:</span> Absent</div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Ensure columns don't shrink too much */
th, td {
    min-width: 35px;
}
</style>