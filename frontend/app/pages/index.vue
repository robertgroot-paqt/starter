<template>
    <div>
        <span> hi {{ user }}</span>

        <button v-if="user" @click="logout">logout</button>
        <NuxtLink v-else to="login">login</NuxtLink>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    sanctum: {
        excluded: true,
    },
});

const user = useSanctumUser();

const { logout } = useSanctumAuth();
</script>
