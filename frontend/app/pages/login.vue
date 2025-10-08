<template>
    <div style="display: flex;">
        <input v-model="userName"/>
        <input v-model="password" type="password"/>
        <button @click="doLogin">login</button>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    sanctum: {
        guestOnly: true,
    }
})

const userName = ref('');
const password = ref('');

async function doLogin() {
    const { login } = useSanctumAuth()

    const credentials = {
        email: userName.value,
        password: password.value,
        remember: true,
    }

    await login(credentials)
}
</script>