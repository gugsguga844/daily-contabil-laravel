<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <!-- Left: Brand/visual panel (mirrors Login) -->
        <div class="hidden lg:flex w-1/2 xl:w-2/3 h-full relative bg-gradient-to-br from-primary to-primary-dark text-base-50 p-12">
            <div class="z-10 mt-auto max-w-xl">
                <h1 class="text-4xl font-bold leading-tight">Texto aqui.</h1>
                <p class="mt-4 text-base-100/80">Descrição aqui.</p>
            </div>
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-10 -right-10 h-72 w-72 rounded-full bg-accent/20 blur-3xl"></div>
                <div class="absolute bottom-10 left-10 h-48 w-48 rounded-full bg-accent/10 blur-2xl"></div>
            </div>
        </div>

        <!-- Right: Registration card -->
        <div class="w-full lg:w-1/2 xl:w-1/3 h-full bg-base-50 flex items-center justify-center p-8">
            <div class="w-full max-w-md rounded-2xl bg-base-50 p-8 shadow-xl ring-1 ring-base-200">
                <h2 class="text-2xl font-semibold text-base-800">Crie sua conta</h2>
                <p class="mt-1 text-sm text-base-500">Leva apenas um minuto.</p>

                <form @submit.prevent="submit" class="mt-6 space-y-5">
                    <div>
                        <InputLabel for="name" value="Nome" class="text-base-700" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" class="text-base-700" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Senha" class="text-base-700" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Confirmar Senha" class="text-base-700" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <PrimaryButton
                        class="w-full bg-accent hover:bg-accent-light focus:bg-accent-light focus:ring-accent text-white"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Criar conta
                    </PrimaryButton>
                </form>

                <!-- Footer: login link -->
                <div class="mt-6 space-y-2 text-center text-sm text-base-500">
                    <p>
                        Já tem uma conta?
                        <Link :href="route('login')" class="font-medium text-primary hover:text-primary-light">
                            Entrar
                        </Link>
                    </p>
                    <p>
                        Ao criar uma conta, você concorda com nossos
                        <span class="underline">Termos</span>
                        e
                        <span class="underline">Política de Privacidade</span>
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
