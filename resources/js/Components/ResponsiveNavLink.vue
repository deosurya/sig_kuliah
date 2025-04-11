<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? "block w-full ps-3 pe-4 py-2 border-l-4 border-gray-300 text-start text-base font-medium text-gray-200 bg-gray-700 focus:outline-none focus:text-gray-100 focus:bg-gray-600 focus:border-gray-500 transition duration-150 ease-in-out"
        : "block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-indigo-300 hover:text-indigo-200 hover:bg-indigo-700 hover:border-indigo-500 focus:outline-none focus:text-indigo-200 focus:bg-indigo-700 focus:border-indigo-500 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
