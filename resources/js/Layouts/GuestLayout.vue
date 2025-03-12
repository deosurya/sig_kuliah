<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0 bg-background"
    >
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-white" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white bg-opacity-60 backdrop-filter backdrop-blur-lg px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>

<style scoped>
.bg-background {
    background-image: url("https://images5.alphacoders.com/131/1311994.jpeg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    z-index: 0;
}

.bg-background::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); /* 40% opacity white overlay */
    z-index: 1;
}

.bg-background > * {
    position: relative;
    z-index: 2;
}
</style>
