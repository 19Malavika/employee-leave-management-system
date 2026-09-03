<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    role: 'employee',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Employee Registration - LeaveHQ Portal" />
        
        <!-- Import Inter Font for Auth Pages -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <div class="relative flex min-h-screen flex-col justify-center bg-slate-50 py-12 sm:px-6 lg:px-8" style="font-family: 'Inter', sans-serif;">
            <!-- Subtle Ambient Background Glows -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 left-1/2 -z-10 h-[600px] w-[600px] -translate-x-1/2 rounded-full bg-gradient-to-tr from-blue-100/60 via-indigo-50/40 to-transparent blur-3xl"></div>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Brand Header -->
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
                        Create your account
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Employee Leave Management System
                    </p>
                </div>

                <!-- Registration Form Card -->
                <div class="mt-8 sm:mx-auto sm:w-full">
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <form @submit.prevent="submit" class="space-y-5">
                            <!-- Full Name -->
                            <div>
                                <InputLabel for="name" value="Full name" class="text-sm font-medium text-slate-700" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    placeholder="e.g. Alex Rivera"
                                />
                                <InputError class="mt-1.5" :message="form.errors.name" />
                            </div>

                            <!-- Corporate Email -->
                            <div>
                                <InputLabel for="email" value="Email address" class="text-sm font-medium text-slate-700" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    v-model="form.email"
                                    required
                                    placeholder="alex@company.com"
                                />
                                <InputError class="mt-1.5" :message="form.errors.email" />
                            </div>

                            <!-- User Role Selection -->
                            <div>
                                <InputLabel value="Select Role" class="text-sm font-medium text-slate-700 mb-1.5" />
                                <div class="grid grid-cols-3 gap-3">
                                    <button
                                        type="button"
                                        @click="form.role = 'employee'"
                                        :class="form.role === 'employee' ? 'bg-blue-50 border-blue-600 text-blue-700 ring-1 ring-blue-600' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50'"
                                        class="rounded-lg border py-2.5 px-3 text-sm font-medium transition flex flex-col items-center gap-1.5 shadow-sm"
                                    >
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" :class="form.role === 'employee' ? 'text-blue-600' : 'text-slate-400'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Employee
                                    </button>
                                    <button
                                        type="button"
                                        @click="form.role = 'manager'"
                                        :class="form.role === 'manager' ? 'bg-blue-50 border-blue-600 text-blue-700 ring-1 ring-blue-600' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50'"
                                        class="rounded-lg border py-2.5 px-3 text-sm font-medium transition flex flex-col items-center gap-1.5 shadow-sm"
                                    >
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" :class="form.role === 'manager' ? 'text-blue-600' : 'text-slate-400'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Manager
                                    </button>
                                    <button
                                        type="button"
                                        @click="form.role = 'admin'"
                                        :class="form.role === 'admin' ? 'bg-blue-50 border-blue-600 text-blue-700 ring-1 ring-blue-600' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50'"
                                        class="rounded-lg border py-2.5 px-3 text-sm font-medium transition flex flex-col items-center gap-1.5 shadow-sm"
                                    >
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" :class="form.role === 'admin' ? 'text-blue-600' : 'text-slate-400'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        Admin
                                    </button>
                                </div>
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

                            <!-- Confirm Password -->
                            <div>
                                <InputLabel for="password_confirmation" value="Confirm password" class="text-sm font-medium text-slate-700" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1.5 block w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    v-model="form.password_confirmation"
                                    required
                                    placeholder="••••••••"
                                />
                                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="mt-2 w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition disabled:opacity-50"
                            >
                                {{ form.processing ? 'Creating account...' : 'Create account' }}
                            </button>

                            <div class="pt-5 mt-2 text-center text-sm text-slate-500 border-t border-slate-100 flex justify-center items-center gap-1">
                                <span>Already have an account?</span>
                                <Link :href="route('login')" class="font-medium text-blue-600 hover:text-blue-500">
                                    Sign in
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
