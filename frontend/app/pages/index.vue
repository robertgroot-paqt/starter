<template>
    <UDashboardPanel id="index">
        <template #header>
            <UDashboardNavbar title="Home">
                <template #leading>
                    <UDashboardSidebarCollapse />
                </template>
            </UDashboardNavbar>
        </template>

        <template #body> Hi {{ user?.data.name }}! </template>
    </UDashboardPanel>
</template>

<script setup lang="ts">
import type { UserData } from "~/data/generated";

const user = useSanctumUser<ApiWrap<UserData>>();
</script>
