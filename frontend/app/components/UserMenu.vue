<script setup lang="ts">
import type { DropdownMenuItem } from "@nuxt/ui";
import type { UserData } from "~/data/generated";

defineProps<{
    collapsed?: boolean;
}>();

const colorMode = useColorMode();
const appConfig = useAppConfig();
const { user, logout } = useSanctumAuth<ApiResponse<UserData>>();

const colors = [
    "red",
    "orange",
    "amber",
    "yellow",
    "lime",
    "green",
    "emerald",
    "teal",
    "cyan",
    "sky",
    "blue",
    "indigo",
    "violet",
    "purple",
    "fuchsia",
    "pink",
    "rose",
];
const neutrals = ["slate", "gray", "zinc", "neutral", "stone"];

const userItem = computed(() => ({
    name: user.value?.data.name,
    avatar: {
        src: "https://github.com/robertgroot-paqt.png",
        alt: user.value?.data.name,
    },
}));

const items = computed<DropdownMenuItem[][]>(() => [
    [
        {
            type: "label",
            label: userItem.value.name,
            avatar: userItem.value.avatar,
        },
    ],
    [
        {
            label: "Theme",
            icon: "i-lucide-palette",
            children: [
                {
                    label: "Primary",
                    slot: "chip",
                    chip: appConfig.ui.colors.primary,
                    content: {
                        align: "center",
                        collisionPadding: 16,
                    },
                    children: colors.map((color) => ({
                        label: color,
                        chip: color,
                        slot: "chip",
                        checked: appConfig.ui.colors.primary === color,
                        type: "checkbox",
                        onSelect: (e) => {
                            e.preventDefault();

                            appConfig.ui.colors.primary = color;
                        },
                    })),
                },
                {
                    label: "Neutral",
                    slot: "chip",
                    chip:
                        appConfig.ui.colors.neutral === "neutral"
                            ? "old-neutral"
                            : appConfig.ui.colors.neutral,
                    content: {
                        align: "end",
                        collisionPadding: 16,
                    },
                    children: neutrals.map((color) => ({
                        label: color,
                        chip: color === "neutral" ? "old-neutral" : color,
                        slot: "chip",
                        type: "checkbox",
                        checked: appConfig.ui.colors.neutral === color,
                        onSelect: (e) => {
                            e.preventDefault();

                            appConfig.ui.colors.neutral = color;
                        },
                    })),
                },
            ],
        },
        {
            label: "Appearance",
            icon: "i-lucide-sun-moon",
            children: [
                {
                    label: "System",
                    icon: "i-lucide-computer",
                    type: "checkbox",
                    checked: colorMode.preference === "system",
                    onSelect(e: Event) {
                        e.preventDefault();

                        colorMode.preference = "system";
                    },
                },
                {
                    label: "Light",
                    icon: "i-lucide-sun",
                    type: "checkbox",
                    checked: colorMode.preference === "light",
                    onSelect(e: Event) {
                        e.preventDefault();

                        colorMode.preference = "light";
                    },
                },
                {
                    label: "Dark",
                    icon: "i-lucide-moon",
                    type: "checkbox",
                    checked: colorMode.preference === "dark",
                    onSelect(e: Event) {
                        e.preventDefault();

                        colorMode.preference = "dark";
                    },
                },
            ],
        },
    ],
    [
        {
            type: "link",
            icon: "i-lucide-log-out",
            label: "Logout",
            onSelect(e: Event) {
                e.preventDefault();

                logout();
            },
        },
    ],
]);
</script>

<template>
    <UDropdownMenu
        :items="items"
        :content="{ align: 'center', collisionPadding: 12 }"
        :ui="{
            content: collapsed
                ? 'w-48'
                : 'w-(--reka-dropdown-menu-trigger-width)',
        }"
    >
        <UButton
            v-bind="{
                ...userItem,
                label: collapsed ? undefined : userItem.name,
                trailingIcon: collapsed
                    ? undefined
                    : 'i-lucide-chevrons-up-down',
            }"
            color="neutral"
            variant="ghost"
            block
            :square="collapsed"
            class="data-[state=open]:bg-elevated"
            :ui="{
                trailingIcon: 'text-dimmed',
            }"
        />

        <template #chip-leading="{ item }">
            <div
                class="inline-flex items-center justify-center shrink-0 size-5"
            >
                <span
                    class="rounded-full ring ring-bg bg-(--chip-light) dark:bg-(--chip-dark) size-2"
                    :style="{
                        '--chip-light': `var(--color-${(item as any).chip}-500)`,
                        '--chip-dark': `var(--color-${(item as any).chip}-400)`,
                    }"
                />
            </div>
        </template>
    </UDropdownMenu>
</template>
