<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const fillAccount = (email, password) => {
    form.email = email;
    form.password = password;
};

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign In - Employee Leave Management System" />
        
        <!-- Import Inter Font for Auth Pages -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <div class="relative flex min-h-screen flex-col justify-center bg-slate-50 py-12 sm:px-6 lg:px-8" style="font-family: 'Inter', sans-serif;">
            <!-- Ambient Subtle Backdrop Glow -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 left-1/2 -z-10 h-[600px] w-[600px] -translate-x-1/2 rounded-full bg-gradient-to-tr from-blue-100/60 via-indigo-50/40 to-transparent blur-3xl"></div>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Branding Header -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-xl font-bold text-white shadow-sm">
                            L
                        </div>
                        <span class="text-2xl font-bold tracking-tight text-slate-900">
                            LeaveHQ
                        </span>
                    </div>

                    <h2 class="mt-6 text-2xl font-semibold tracking-tight text-slate-900">
                        Sign in to your account
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Employee Leave Management System
                    </p>
                </div>



                <!-- Login Form Card -->
                <div class="mt-6 sm:mx-auto sm:w-full">
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div
                            v-if="status"
                            class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700"
                        >
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <!-- Email -->
                            <div>
                                <InputLabel for="email" value="Email address" class="text-sm font-medium text-slate-700" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    placeholder="user@company.com"
                                />
                                <InputError class="mt-1.5" :message="form.errors.email" />
                            </div>

                            <!-- Password -->
                            <div>
                                <InputLabel for="password" value="Password" class="text-sm font-medium text-slate-700" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    v-model="form.password"
                                    required
                                    placeholder="••••••••"
                                />
                                <InputError class="mt-1.5" :message="form.errors.password" />
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="mt-2 w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition disabled:opacity-50"
                            >
                                {{ form.processing ? 'Signing in...' : 'Sign in' }}
                            </button>

                            <div class="pt-5 mt-2 text-center text-sm text-slate-500 border-t border-slate-100 flex justify-center items-center gap-1">
                                <span>Don't have an account?</span>
                                <Link :href="route('register')" class="font-medium text-blue-600 hover:text-blue-500">
                                    Register here
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
