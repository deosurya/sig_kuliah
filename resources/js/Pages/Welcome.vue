<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import MainContent from "@/Layouts/MainContent.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img
            id="background"
            class="w-full h-full object-cover fixed top-0 left-0 opacity-40"
            src="https://images5.alphacoders.com/131/1311994.jpeg"
        />
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-4 lg:grid-cols-3"
                >
                    <div
                        class="flex lg:col-start-2 lg:justify-center justify-center items-center gap-6 text-white/90"
                    >
                        <ApplicationLogo
                            class="block h-16 w-auto fill-current"
                        />
                        <p
                            class="text-5xl font-extrabold"
                            style="font-family: 'Alfa Slab One', display"
                        >
                            SIG
                        </p>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-4">
                    <!-- main card -->
                    <MainContent />
                </main>

                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    <div class="flex flex-col gap-2">
                        <p>Sistem Informasi Geografis</p>
                        <p>&copy; 2025. Claudio Surya. 2205551008</p>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
