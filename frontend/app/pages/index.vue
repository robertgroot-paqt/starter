<template>
    <div>
        <span> hi {{ user }}</span>

        <UButton v-if="user" @click="logout">logout</UButton>
        <UButton v-else to="login">login</UButton>
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
