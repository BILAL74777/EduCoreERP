<script setup>
const props = defineProps({
    form: Object,
    errors: Object,

    faculties: Array,
    disciplines: Array,
    classes: Array,
    sessions: Array,
});

const clearError = (field) => {
    if (props.errors?.[field]) {
        delete props.errors[field];
    }
};
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->

        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Enrollment Information
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Assign the student to a faculty, discipline, class and academic
                session.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <!-- Faculty -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Faculty
                </label>

                <select
                    v-model="form.faculty_id"
                    @change="clearError('faculty_id')"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 text-sm dark:bg-gray-800 dark:text-white',
                        errors.faculty_id
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                >
                    <option value="">Select Faculty</option>

                    <option
                        v-for="faculty in faculties"
                        :key="faculty.id"
                        :value="faculty.id"
                    >
                        {{ faculty.faculty }}
                    </option>
                </select>

                <p v-if="errors.faculty_id" class="mt-1 text-sm text-red-600">
                    {{ errors.faculty_id[0] }}
                </p>
            </div>

            <!-- Discipline -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Discipline
                </label>

                <select
                    v-model="form.discipline_id"
                    @change="clearError('discipline_id')"
                    :disabled="!form.faculty_id"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 text-sm dark:bg-gray-800 dark:text-white',
                        'disabled:cursor-not-allowed disabled:bg-gray-100 dark:disabled:bg-gray-900',
                        errors.discipline_id
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                >
                    <option value="">Select Discipline</option>

                    <option
                        v-for="discipline in disciplines"
                        :key="discipline.id"
                        :value="discipline.id"
                    >
                        {{ discipline.discipline }}
                    </option>
                </select>

                <p
                    v-if="errors.discipline_id"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.discipline_id[0] }}
                </p>
            </div>

            <!-- Academic Class -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Academic Class
                </label>

                <select
                    v-model="form.class_id"
                    @change="clearError('class_id')"
                    :disabled="!form.discipline_id"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 text-sm dark:bg-gray-800 dark:text-white',
                        'disabled:cursor-not-allowed disabled:bg-gray-100 dark:disabled:bg-gray-900',
                        errors.class_id
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                >
                    <option value="">Select Class</option>

                    <option
                        v-for="academicClass in classes"
                        :key="academicClass.id"
                        :value="academicClass.id"
                    >
                        {{ academicClass.name }}
                    </option>
                </select>

                <p v-if="errors.class_id" class="mt-1 text-sm text-red-600">
                    {{ errors.class_id[0] }}
                </p>
            </div>

            <!-- Academic Session -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Academic Session
                </label>

                <select
                    v-model="form.session_id"
                    @change="clearError('session_id')"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 text-sm dark:bg-gray-800 dark:text-white',
                        errors.session_id
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                >
                    <option value="">Select Session</option>

                    <option
                        v-for="session in sessions"
                        :key="session.id"
                        :value="session.id"
                    >
                        {{ session.session_name }}
                    </option>
                </select>

                <p v-if="errors.session_id" class="mt-1 text-sm text-red-600">
                    {{ errors.session_id[0] }}
                </p>
            </div>

            <!-- Roll Number -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Roll Number
                </label>

                <input
                    v-model="form.roll_no"
                    @input="clearError('roll_no')"
                    type="text"
                    placeholder="Enter Roll Number"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 text-sm dark:bg-gray-800 dark:text-white',
                        errors.roll_no
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                />

                <p v-if="errors.roll_no" class="mt-1 text-sm text-red-600">
                    {{ errors.roll_no[0] }}
                </p>
            </div>

            <!-- Section -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Section
                </label>

                <select
                    v-model="form.section"
                    @change="clearError('section')"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 text-sm dark:bg-gray-800 dark:text-white',
                        errors.section
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                >
                    <option value="">Select Section</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                </select>

                <p v-if="errors.section" class="mt-1 text-sm text-red-600">
                    {{ errors.section[0] }}
                </p>
            </div>
        </div>
    </div>
</template>
