<template>
    <div class="flex flex-col items-center justify-center gap-4 p-4 h-dvh ">
        <UPageCard class="w-full max-w-md">
            <UAuthForm
                :schema="schema"
                title="Login"
                description="Enter your credentials to access your account."
                icon="i-lucide-user"
                :fields="fields"
                :loading="sumbitting"
                @submit="onSubmit"
            />
        </UPageCard>
    </div>
</template>

<script setup lang="ts">
import * as z from "zod";
import type { AuthFormField, FormSubmitEvent } from "@nuxt/ui";
import type { SessionCreateData } from "~/data/generated";

definePageMeta({
    sanctum: {
        guestOnly: true,
    },
    layout: "public",
});

const fields: AuthFormField[] = [
    {
        name: "email",
        type: "email",
        label: "Email",
        placeholder: "Enter your email",
        required: true,
        defaultValue: "admin@paqt.dev"
    },
    {
        name: "password",
        label: "Password",
        type: "password",
        placeholder: "Enter your password",
        required: true,
        defaultValue: "admin"
    },
];
const sumbitting = ref(false);

const schema = z.object({
    email: z.email("Invalid email"),
    password: z.string("Password is required"),
});

type Schema = z.output<typeof schema>;

async function onSubmit(payload: FormSubmitEvent<Schema>) {
    const { login } = useSanctumAuth();

    const credentials = {
        email: payload.data.email,
        password: payload.data.password,
        remember: true,
    } satisfies SessionCreateData;

    sumbitting.value = true;
    try {
        await login(credentials);
    } finally {
        sumbitting.value = false;
    }
}
</script>
