<template>
    hi {{ user }}

    <button v-if="user" @click="logout">logout</button>
    <NuxtLink v-else to="login">login</NuxtLink>

    {{ result }}
</template>

<script setup lang="ts">
import { UserApi } from '~/data/generated';

definePageMeta({
    sanctum: {
        excluded: true,
    },
});

const user = useSanctumUser();

const { logout } = useSanctumAuth();

const result = await UserApi.index({include: ['roles'], includeOperations: ['*'], sorts: ['-name', 'name']});
</script>
