<template>
    <UCard>
        <UButton @click="() => refresh()">reload</UButton>
        <UTable
            :loading="status === 'pending'"
            :data="data?.data"
            class="flex-1"
            :columns="columns"
        />
    </UCard>
</template>

<script setup lang="ts">
import type { TableColumn } from "@nuxt/ui";
import { UserApi, type UserData } from "~/data/generated";

const { data, status, refresh } = useAsyncData("users", () => UserApi.index());

const columns: TableColumn<{ data: UserData }>[] = [
    {
        accessorKey: "data.id",
        header: "#",
    },
    {
        accessorKey: "data.name",
        header: "Name",
    },
];
</script>
