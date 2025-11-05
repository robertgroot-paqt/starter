<template>
    <UDashboardPanel id="users">
        <template #header>
            <UDashboardNavbar title="Users">
                <template #leading>
                    <UDashboardSidebarCollapse />
                </template>
                <template #right>
                    <UButton @click="() => refresh()">reload</UButton>
                </template>
            </UDashboardNavbar>
        </template>

        <template #body>
            <UTable
                :loading="status === 'pending'"
                :data="data?.data"
                class="flex-1"
                :columns="columns"
            >
            </UTable>
        </template>
    </UDashboardPanel>
</template>

<script setup lang="ts">
import type { TableColumn } from "@nuxt/ui";
import { UserApi, type UserData } from "~/data/generated";

const { data, status, refresh } = useAsyncData("users", () =>
    UserApi.index({ include: ["roles"], includeOperations: ["*"] }),
);

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
