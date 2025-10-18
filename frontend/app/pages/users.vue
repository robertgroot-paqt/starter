<template>
{{ status }}


  <UTable :data="data" class="flex-1" />
</template>

<script setup lang="ts">
import { UserApi } from '~/data/generated';


const {data, status} = useAsyncData('users', () => UserApi.index());
</script>