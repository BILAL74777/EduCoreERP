<script setup>
const props = defineProps({
    form: Object,
    errors: Object,
    editing: Boolean,
});

const emit = defineEmits([
    "submit",
    "clear-error",
    "close",
]);

function onImageChange(event) {
    const file = event.target.files[0];

    if (file) {
        props.form.image = file;
    }
}
</script>

<template>
    <form @submit.prevent="emit('submit')">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Image -->
            <div class="md:col-span-2">
                <label class="block mb-2">
                    Profile Image
                </label>

                <input
                    type="file"
                    accept="image/*"
                    @change="onImageChange"
                    class="w-full border rounded-lg px-3 py-2"
                />

                <p
                    v-if="errors.image"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.image[0] }}
                </p>
            </div>

            <!-- Name -->
            <div>
                <label class="block mb-2">
                    Name
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    class="w-full border rounded-lg px-3 py-2"
                    :class="{
                        'border-red-500 bg-red-50': errors.name
                    }"
                    @input="emit('clear-error','name')"
                />

                <p
                    v-if="errors.name"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.name[0] }}
                </p>
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-2">
                    Email
                </label>

                <input
                    v-model="form.email"
                    type="email"
                    class="w-full border rounded-lg px-3 py-2"
                    :class="{
                        'border-red-500 bg-red-50': errors.email
                    }"
                    @input="emit('clear-error','email')"
                />

                <p
                    v-if="errors.email"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.email[0] }}
                </p>
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-2">
                    Password
                </label>

                <input
                    v-model="form.password"
                    type="password"
                    class="w-full border rounded-lg px-3 py-2"
                    :class="{
                        'border-red-500 bg-red-50': errors.password
                    }"
                    @input="emit('clear-error','password')"
                />

                <p
                    v-if="editing"
                    class="text-xs text-gray-500 mt-1"
                >
                    Leave blank to keep current password.
                </p>

                <p
                    v-if="errors.password"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.password[0] }}
                </p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block mb-2">
                    Confirm Password
                </label>

                <input
                    v-model="form.password_confirmation"
                    type="password"
                    class="w-full border rounded-lg px-3 py-2"
                />
            </div>
 
            <!-- Status -->
            <div>
                <label class="block mb-2">
                    Status
                </label>

                <select
                    v-model="form.status"
                    class="w-full border rounded-lg px-3 py-2"
                >
                    <option :value="1">
                        Active
                    </option>

                    <option :value="0">
                        Inactive
                    </option>
                </select>

                <p
                    v-if="errors.status"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.status[0] }}
                </p>
            </div>

        </div>
    </form>
</template>