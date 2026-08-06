<script setup>
const props = defineProps({
    form: Object,

    errors: Object,
});
</script>

<template>
    <div class="space-y-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Personal Information
            </h2>

            <p class="text-sm text-gray-500">Student personal details</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <!-- Name -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Student Name
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.name"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.name[0] }}
                </p>
            </div>

            <!-- Form B -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Form B
                </label>

                <input
                    v-model="form.form_b"
                    type="text"
                    placeholder="Form B number"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.form_b"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.form_b[0] }}
                </p>
            </div>

            <!-- Father Name -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Father Name
                </label>

                <input
                    v-model="form.father_name"
                    type="text"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.father_name"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.father_name[0] }}
                </p>
            </div>

            <!-- Father CNIC -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Father CNIC
                </label>

                <input
                    v-model="form.father_cnic"
                    type="text"
                    placeholder="xxxxx-xxxxxxx-x"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.father_cnic"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.father_cnic[0] }}
                </p>
            </div>

            <!-- Occupation -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Father Occupation
                </label>

                <input
                    v-model="form.father_occupation"
                    type="text"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.father_occupation"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.father_occupation[0] }}
                </p>
            </div>

            <!-- Gender -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Gender
                </label>

                <select
                    v-model="form.gender"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >
                    <option value="Male">Male</option>

                    <option value="Female">Female</option>

                    <option value="Other">Other</option>
                </select>

                 <p
                    v-if="errors.gender"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.gender[0] }}
                </p>
            </div>

            <!-- DOB -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Date of Birth
                </label>

                <input
                    v-model="form.date_of_birth"
                    type="date"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                  <p
                    v-if="errors.date_of_birth"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.date_of_birth[0] }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <!-- Religion -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Religion
                </label>

                <input
                    v-model="form.religion"
                    type="text"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.religion"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.religion[0] }}
                </p>
            </div>

            <!-- Tribe -->

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Tribe / Cast
                </label>

                <input
                    v-model="form.tribe_cast"
                    type="text"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.tribe_cast"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.tribe_cast[0] }}
                </p>
            </div>
            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Contact #
                </label>

                <input
                    v-model="form.contact_number"
                    type="text"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.contact_number"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.contact_number[0] }}
                </p>
            </div>

            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Guardian Contact #
                </label>

                <input
                    v-model="form.guardian_contact_number"
                    type="text"
                    class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
                <p
                    v-if="errors.guardian_contact_number"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.guardian_contact_number[0] }}
                </p>
            </div>
            <div>
            <label
                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
                Address
            </label>

            <textarea
                v-model="form.address"
                rows="4"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                placeholder="Address..."
            ></textarea>
            <p
                    v-if="errors.address"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.address[0] }}
                </p>
        </div>
        </div>
    </div>
</template>
