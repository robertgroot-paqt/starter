<template>
    <div>
        <UHeader title="Hi!">
            <template #right>
                <UButton to="login">Login</UButton>

                <UColorModeSelect />
            </template>
        </UHeader>
        <slot />
    </div>
</template>
