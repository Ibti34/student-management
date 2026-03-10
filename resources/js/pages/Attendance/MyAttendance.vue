<script setup>
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    attendance: Object,      // Keyed by day number from Controller
    daysInMonth: Number,    
    currentMonth: String,   
    currentYear: Number,
    studentName: String
})

/**
 * Safer data lookup.
 * This ensures that even if 'day' is a number and the 
 * key is a string (or vice versa), it finds the record.
 */
const getStatus = (day) => {
    // Check if the day exists in the attendance object
    const record = props.attendance[day] || props.attendance[String(day).padStart(2, '0')] || props.attendance[Number(day)];
    return record ? record.status : null;
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-full mx-auto bg-gray-50 min-h-screen">
            
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">My Attendance</h1>
                    <p class="text-gray-600 font-medium">{{ studentName }}</p>
                </div>
                <div class="text-sm font-semibold bg-white px-4 py-2 border rounded shadow-sm text-blue-600">
                    {{ currentMonth }} {{ currentYear }}
                </div>
            </div>

            <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase font-bold text-xs">
                            <tr>
                                <th class="p-4 border-b min-w-[150px]">Status</th>
                                <th v-for="day in daysInMonth" :key="day" class="p-2 border-b border-l text-center w-10">
                                    {{ day }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-bold text-gray-700 border-r bg-gray-50">
                                    Attendance
                                </td>
                                
                                <td v-for="day in daysInMonth" :key="day" class="p-2 border-l text-center h-12">
                                    <template v-let="status = getStatus(day)">
                                        <span v-if="getStatus(day) === 'present'" class="text-green-600 font-black text-lg">P</span>
                                        <span v-else-if="getStatus(day) === 'late'" class="text-yellow-500 font-black text-lg">L</span>
                                        <span v-else-if="getStatus(day) === 'absent'" class="text-red-600 font-black text-lg">A</span>
                                        <span v-else class="text-gray-200">-</span>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-6 text-sm">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Present (P)</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Late (L)</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Absent (A)</div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Ensure columns have a consistent width */
th, td {
    min-width: 35px;
}
</style>