<template>
    <UPageHero>
        <template #title> This is a public page! </template>
    </UPageHero>
</template>

<script lang="ts" setup>
definePageMeta({
    sanctum: {
        excluded: true,
    },
    layout: "public",
});
</script>
