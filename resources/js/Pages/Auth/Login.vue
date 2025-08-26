<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <!-- Left: Brand/visual panel -->
        <div class="hidden lg:flex w-1/2 xl:w-2/3 h-full relative bg-gradient-to-br from-primary to-primary-dark text-base-50 p-12">
            <div class="z-10 mt-auto max-w-xl">
                <h1 class="text-4xl font-bold leading-tight">Algum texto aqui.</h1>
                <p class="mt-4 text-base-100/80">Alguma descrição aqui, talvez imagem</p>
            </div>
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-10 -right-10 h-72 w-72 rounded-full bg-accent/20 blur-3xl"></div>
                <div class="absolute bottom-10 left-10 h-48 w-48 rounded-full bg-accent/10 blur-2xl"></div>
            </div>
        </div>

        <!-- Right: Auth card -->
        <div class="w-full lg:w-1/2 xl:w-1/3 h-full bg-base-50 flex items-center justify-center p-8">
            <div class="w-full max-w-md rounded-2xl bg-base-50 p-8 shadow-xl ring-1 ring-base-200">
                <h2 class="text-2xl font-semibold text-base-800">Bem-vindo(a) de volta</h2>
                <p class="mt-1 text-sm text-base-500">Faça login para continuar</p>

                <div v-if="status" class="mt-4 rounded-md bg-accent/10 p-3 text-sm text-primary ring-1 ring-accent/20">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-6 space-y-5">
                    <div>
                        <InputLabel for="email" value="Email" class="text-base-700" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
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
                            autocomplete="current-password"
                        />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-base-600">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span>Lembrar-me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium text-primary hover:text-primary-light focus:underline"
                        >
                            Esqueceu sua senha?
                        </Link>
                    </div>

                    <PrimaryButton
                        class="w-full bg-accent hover:bg-accent-light focus:bg-accent-light focus:ring-accent text-white"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Entrar
                    </PrimaryButton>
                </form>

                <!-- Footer: signup and terms -->
                <div class="mt-6 space-y-2 text-center text-sm text-base-500">
                    <p>
                        Não tem uma conta?
                        <Link :href="route('register')" class="font-medium text-primary hover:text-primary-light">
                            Criar conta
                        </Link>
                    </p>
                    <p>
                        Ao fazer login, você concorda com nossos
                        <span class="underline">Termos</span>
                        e
                        <span class="underline">Política de Privacidade</span>
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
