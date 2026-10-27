
<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";

const mobileMenuOpen = ref(false);
const currentYear = new Date().getFullYear();

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

const highlights = [
    {
        icon: "fa-solid fa-graduation-cap",
        title: "Quality Education",
        description: "Building strong academic foundations for lifelong success.",
        color: "bg-blue-50 text-blue-700",
        border: "hover:border-blue-300",
    },
    {
        icon: "fa-solid fa-flask",
        title: "Practical Learning",
        description: "Developing real-world skills through practical experiences.",
        color: "bg-teal-50 text-teal-700",
        border: "hover:border-teal-300",
    },
    {
        icon: "fa-solid fa-user-tie",
        title: "Expert Faculty",
        description: "Learning from dedicated educators and experienced mentors.",
        color: "bg-amber-50 text-amber-700",
        border: "hover:border-amber-300",
    },
    {
        icon: "fa-solid fa-lightbulb",
        title: "Future Ready",
        description: "Preparing students for careers, innovation, and leadership.",
        color: "bg-violet-50 text-violet-700",
        border: "hover:border-violet-300",
    },
];

const programs = [
    {
        icon: "fa-solid fa-book-open",
        title: "Intermediate Education",
        subtitle: "Build your academic foundation",
        description:
            "Strengthen your knowledge, develop essential skills, and prepare for higher education.",
        color: "bg-blue-600",
        light: "bg-blue-50",
        text: "text-blue-700",
    },
    {
        icon: "fa-solid fa-microscope",
        title: "Science Education",
        subtitle: "Explore, discover, achieve",
        description:
            "Encourage scientific thinking, analytical skills, and a deeper understanding of the world.",
        color: "bg-teal-600",
        light: "bg-teal-50",
        text: "text-teal-700",
    },
    {
        icon: "fa-solid fa-laptop-code",
        title: "Computer Science",
        subtitle: "Prepare for the digital future",
        description:
            "Explore computing concepts, logical problem-solving, and technology-driven learning.",
        color: "bg-violet-600",
        light: "bg-violet-50",
        text: "text-violet-700",
    },
];

const facilities = [
    {
        icon: "fa-solid fa-book-open-reader",
        title: "Learning Resources",
        description:
            "A learning-focused environment that encourages reading, research, and independent study.",
        color: "bg-blue-100 text-blue-700",
    },
    {
        icon: "fa-solid fa-flask-vial",
        title: "Science Learning",
        description:
            "Opportunities to connect scientific concepts with practical understanding.",
        color: "bg-teal-100 text-teal-700",
    },
    {
        icon: "fa-solid fa-chalkboard-user",
        title: "Modern Teaching",
        description:
            "An educational approach focused on engagement, understanding, and student participation.",
        color: "bg-amber-100 text-amber-700",
    },
    {
        icon: "fa-solid fa-people-group",
        title: "Student Development",
        description:
            "Encouraging teamwork, communication, confidence, and personal growth.",
        color: "bg-violet-100 text-violet-700",
    },
];

const reasons = [
    {
        number: "01",
        title: "Student-Centered Learning",
        description:
            "We encourage curiosity, active participation, and a deeper understanding of every subject.",
        color: "border-blue-500",
        numberColor: "text-blue-600",
    },
    {
        number: "02",
        title: "Academic Excellence",
        description:
            "We emphasize discipline, consistency, and strong academic foundations.",
        color: "border-teal-500",
        numberColor: "text-teal-600",
    },
    {
        number: "03",
        title: "Character Building",
        description:
            "We value responsibility, respect, integrity, and positive personal development.",
        color: "border-amber-500",
        numberColor: "text-amber-600",
    },
];

const socialLinks = [
    {
        icon: "fa-brands fa-facebook-f",
        label: "Facebook",
        url: "https://www.facebook.com/FayazURrahamanOfficial?mibextid=kFxxJD",
    },
    {
        icon: "fa-brands fa-instagram",
        label: "Instagram",
        url: "https://instagram.com",
    },
    {
        icon: "fa-brands fa-x-twitter",
        label: "X",
        url: "https://twitter.com",
    },
    {
        icon: "fa-brands fa-whatsapp",
        label: "WhatsApp",
        url: "https://wa.me/923078527678",
    },
];

const navigation = [
    { label: "Home", href: "#home" },
    { label: "About Us", href: "#about" },
    { label: "Academics", href: "#academics" },
    { label: "Facilities", href: "#facilities" },
    { label: "Contact", href: "#contact" },
];
</script>

<template>
    <Head title="Modern Era College | Science, Technology & Excellence">
        <meta
            name="description"
            content="Discover Modern Era College of Science and Technology in Samar Bagh, Lower Dir, Khyber Pakhtunkhwa. Explore our educational approach, academic programs, and admissions."
        />
        <meta name="theme-color" content="#102B46" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin="anonymous"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700;800&display=swap"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        />
    </Head>

    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-[#26364A] antialiased">
        <!-- Announcement bar -->
        <div class="bg-[#102B46] text-white">
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-2.5 text-center text-xs sm:flex-row sm:px-6 sm:text-left lg:px-8"
            >
                <p class="flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-[#E3B65B]"></i>
                    <span>
                        Welcome to Modern Era College of Science and Technology
                    </span>
                </p>

                <a
                    href="https://wa.me/923078527678"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 font-semibold text-[#F2CD83] transition hover:text-white"
                >
                    <i class="fa-solid fa-phone"></i>
                    Contact Admissions
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Main navigation -->
        <header class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 shadow-sm backdrop-blur-lg">
            <nav
                class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8"
                aria-label="Main navigation"
            >
                <a
                    href="#home"
                    class="flex min-w-0 items-center gap-3"
                    @click="closeMobileMenu"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#102B46] text-xl text-[#E3B65B] shadow-md shadow-blue-950/15"
                    >
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>

                    <div class="min-w-0">
                        <p
                            class="font-display text-base leading-tight font-bold text-[#102B46] sm:text-lg"
                        >
                            Modern Era
                        </p>
                        <p
                            class="mt-1 text-[9px] font-bold tracking-[0.15em] text-[#167D8D] uppercase sm:text-[10px]"
                        >
                            College of Science & Technology
                        </p>
                    </div>
                </a>

                <div class="hidden items-center gap-7 lg:flex">
                    <a
                        v-for="item in navigation"
                        :key="item.label"
                        :href="item.href"
                        class="text-sm font-semibold text-slate-600 transition hover:text-[#167D8D]"
                    >
                        {{ item.label }}
                    </a>

                    <a
                        href="https://docs.google.com/forms/d/e/1FAIpQLSevsvN8mbFNxYMrOXMZjnSuX-NQH_vDGD7EsswUDbQUo9YDBQ/viewform?usp=dialog"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#C99B43] px-5 py-3 text-sm font-bold text-[#102B46] shadow-md shadow-amber-900/10 transition hover:-translate-y-0.5 hover:bg-[#E3B65B]"
                    >
                        Apply Now
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <button
                    type="button"
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-[#102B46] transition hover:bg-slate-50 lg:hidden"
                    :aria-expanded="mobileMenuOpen"
                    aria-label="Toggle navigation menu"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <i
                        :class="
                            mobileMenuOpen
                                ? 'fa-solid fa-xmark text-xl'
                                : 'fa-solid fa-bars text-xl'
                        "
                    ></i>
                </button>
            </nav>

            <!-- Mobile navigation -->
            <div
                v-if="mobileMenuOpen"
                class="border-t border-slate-100 bg-white px-4 py-4 shadow-lg lg:hidden"
            >
                <div class="mx-auto flex max-w-7xl flex-col gap-1">
                    <a
                        v-for="item in navigation"
                        :key="item.label"
                        :href="item.href"
                        class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-[#167D8D]"
                        @click="closeMobileMenu"
                    >
                        {{ item.label }}
                    </a>

                    <a
                        href="https://docs.google.com/forms/d/e/1FAIpQLSevsvN8mbFNxYMrOXMZjnSuX-NQH_vDGD7EsswUDbQUo9YDBQ/viewform?usp=dialog"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-2 flex items-center justify-center gap-2 rounded-xl bg-[#C99B43] px-5 py-3.5 text-sm font-bold text-[#102B46]"
                        @click="closeMobileMenu"
                    >
                        Apply for Admission
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero -->
            <section id="home" class="relative isolate scroll-mt-24 overflow-hidden bg-[#102B46]">
                <div
                    class="absolute inset-0 -z-20 bg-cover bg-center"
                    style="background-image: url('/images/banner.jpg')"
                ></div>

                <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#091D32]/95 via-[#102B46]/85 to-[#102B46]/45"></div>

                <div class="absolute -top-24 right-0 -z-10 h-80 w-80 rounded-full bg-[#167D8D]/20 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 -z-10 h-80 w-80 rounded-full bg-[#D6A64F]/15 blur-3xl"></div>

                <div
                    class="mx-auto grid min-h-[590px] max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 sm:py-24 lg:grid-cols-[1.15fr_0.85fr] lg:px-8 lg:py-28"
                >
                    <div class="max-w-3xl">
                        <div
                            class="mb-7 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold tracking-wide text-white backdrop-blur-sm sm:text-sm"
                        >
                            <span class="h-2 w-2 rounded-full bg-[#E3B65B]"></span>
                            Education · Character · Excellence
                        </div>

                        <h1
                            class="font-display text-4xl leading-[1.13] font-bold tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl"
                        >
                            Empowering Minds.
                            <span class="mt-2 block text-[#E3B65B]">
                                Inspiring Futures.
                            </span>
                        </h1>

                        <p class="mt-7 max-w-2xl text-base leading-8 text-blue-100/90 sm:text-lg">
                            Welcome to Modern Era College of Science and Technology,
                            where education builds knowledge, develops character,
                            and prepares students to meet tomorrow's challenges.
                        </p>

                        <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                            <a
                                href="https://docs.google.com/forms/d/e/1FAIpQLSevsvN8mbFNxYMrOXMZjnSuX-NQH_vDGD7EsswUDbQUo9YDBQ/viewform?usp=dialog"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-3 rounded-xl bg-[#E3B65B] px-7 py-4 text-sm font-bold text-[#102B46] shadow-xl shadow-black/15 transition hover:-translate-y-1 hover:bg-[#F0CA80]"
                            >
                                Begin Your Journey
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a
                                href="#about"
                                class="inline-flex items-center justify-center gap-3 rounded-xl border border-white/30 bg-white/10 px-7 py-4 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20"
                            >
                                Discover Our College
                                <i class="fa-solid fa-arrow-down text-xs"></i>
                            </a>
                        </div>

                        <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-blue-100/90">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-[#E3B65B]"></i>
                                Student-focused learning
                            </span>
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-[#E3B65B]"></i>
                                Academic development
                            </span>
                        </div>
                    </div>

                    <div class="relative mx-auto w-full max-w-md lg:ml-auto">
                        <div class="absolute -inset-4 rounded-[2rem] border border-white/15"></div>

                        <div class="relative overflow-hidden rounded-[1.75rem] border border-white/20 bg-white/10 p-3 shadow-2xl shadow-black/25 backdrop-blur-md">
                            <div
                                class="relative flex min-h-[320px] flex-col justify-between overflow-hidden rounded-[1.3rem] bg-gradient-to-br from-[#167D8D] via-[#126477] to-[#102B46] p-7 sm:min-h-[365px] sm:p-9"
                            >
                                <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full border-[35px] border-white/10"></div>
                                <div class="absolute -bottom-20 -left-10 h-52 w-52 rounded-full bg-[#E3B65B]/15 blur-xl"></div>

                                <div class="relative">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-3xl text-[#F3D28D]">
                                        <i class="fa-solid fa-book-open-reader"></i>
                                    </div>

                                    <p class="mt-7 text-xs font-bold tracking-[0.22em] text-[#F3D28D] uppercase">
                                        Your future starts here
                                    </p>

                                    <h2 class="mt-3 font-display text-3xl leading-snug font-bold text-white sm:text-4xl">
                                        Learn Today.
                                        <span class="block text-[#F3D28D]">
                                            Lead Tomorrow.
                                        </span>
                                    </h2>

                                    <p class="mt-4 text-sm leading-7 text-white/80">
                                        Knowledge opens doors. Curiosity creates
                                        possibilities. Education makes a difference.
                                    </p>
                                </div>

                                <div class="relative mt-8 flex items-center justify-between gap-4 border-t border-white/20 pt-5">
                                    <div>
                                        <p class="text-xs text-white/70">Our commitment</p>
                                        <p class="mt-1 text-sm font-bold text-white">
                                            A brighter future through education
                                        </p>
                                    </div>
                                    <i class="fa-solid fa-arrow-trend-up text-2xl text-[#F3D28D]"></i>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-7 -left-3 hidden items-center gap-3 rounded-2xl bg-white p-4 shadow-xl sm:flex sm:-left-8">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600">
                                <i class="fa-solid fa-lightbulb"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#102B46]">Think. Learn. Grow.</p>
                                <p class="mt-1 text-xs text-slate-500">Your journey matters</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute right-0 bottom-0 left-0 h-1 bg-gradient-to-r from-[#167D8D] via-[#E3B65B] to-[#167D8D]"></div>
            </section>

            <!-- Highlights -->
            <section class="relative z-10 bg-white px-4 py-12 sm:px-6 lg:px-8">
                <div class="mx-auto grid max-w-7xl gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <article
                        v-for="item in highlights"
                        :key="item.title"
                        class="group rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_5px_25px_rgba(16,43,70,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                        :class="item.border"
                    >
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl text-xl transition duration-300 group-hover:scale-110"
                            :class="item.color"
                        >
                            <i :class="item.icon"></i>
                        </div>

                        <h3 class="mt-4 font-display text-lg font-bold text-[#102B46]">
                            {{ item.title }}
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            {{ item.description }}
                        </p>
                    </article>
                </div>
            </section>

            <!-- About -->
            <section id="about" class="scroll-mt-24 bg-[#F5F8FC] px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
                <div class="mx-auto grid max-w-7xl items-center gap-14 lg:grid-cols-2 lg:gap-20">
                    <div class="relative">
                        <div class="absolute -left-4 -top-4 h-24 w-24 rounded-tl-[2rem] border-l-4 border-t-4 border-[#D6A64F]"></div>

                        <div class="relative overflow-hidden rounded-[1.75rem] bg-gradient-to-br from-[#DCECF8] via-[#E8F4F4] to-[#F9EACD] p-6 sm:p-10">
                            <div class="absolute right-0 bottom-0 h-52 w-52 rounded-full bg-white/50 blur-2xl"></div>

                            <div class="relative rounded-2xl border border-white/80 bg-white/80 p-6 shadow-xl shadow-blue-950/5 backdrop-blur sm:p-9">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#102B46] text-2xl text-[#E3B65B]">
                                        <i class="fa-solid fa-school"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold tracking-[0.16em] text-[#167D8D] uppercase">
                                            Our Institution
                                        </p>
                                        <h3 class="mt-1 font-display text-xl font-bold text-[#102B46]">
                                            Education with Purpose
                                        </h3>
                                    </div>
                                </div>

                                <div class="mt-7 space-y-5">
                                    <div class="flex gap-4">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                            <i class="fa-solid fa-book"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#102B46]">Knowledge</h4>
                                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                                Building understanding through meaningful learning.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex gap-4">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                                            <i class="fa-solid fa-seedling"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#102B46]">Growth</h4>
                                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                                Encouraging confidence, curiosity, and independence.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex gap-4">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                            <i class="fa-solid fa-award"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#102B46]">Excellence</h4>
                                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                                Promoting effort, integrity, and achievement.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-5 right-5 rounded-xl bg-[#102B46] px-5 py-4 text-white shadow-xl sm:right-10">
                            <p class="font-display text-lg font-bold">A Purposeful Education</p>
                            <p class="mt-1 text-xs text-blue-100">For learning and life</p>
                        </div>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-4 py-2 text-xs font-bold tracking-wide text-teal-800 uppercase">
                            <span class="h-2 w-2 rounded-full bg-teal-600"></span>
                            About Our College
                        </div>

                        <h2 class="mt-6 font-display text-3xl leading-tight font-bold text-[#102B46] sm:text-4xl lg:text-5xl">
                            More Than Education.
                            <span class="block text-[#167D8D]">A Foundation for Life.</span>
                        </h2>

                        <p class="mt-6 text-base leading-8 text-slate-600">
                            Modern Era College of Science and Technology is committed
                            to creating an environment where students can strengthen
                            their knowledge, discover their abilities, and work
                            towards their ambitions.
                        </p>

                        <p class="mt-4 text-base leading-8 text-slate-600">
                            We believe education should develop both the mind and
                            character. Through purposeful teaching, academic
                            discipline, and a supportive learning environment, we
                            encourage students to become responsible and confident
                            individuals.
                        </p>

                        <div class="mt-8 space-y-4">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-check mt-1 text-lg text-[#167D8D]"></i>
                                <p class="text-sm leading-6 text-slate-600">
                                    <strong class="text-[#102B46]">A culture of learning:</strong>
                                    supporting curiosity, questions, and deeper understanding.
                                </p>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-check mt-1 text-lg text-[#167D8D]"></i>
                                <p class="text-sm leading-6 text-slate-600">
                                    <strong class="text-[#102B46]">Personal development:</strong>
                                    encouraging confidence, respect, and responsibility.
                                </p>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-check mt-1 text-lg text-[#167D8D]"></i>
                                <p class="text-sm leading-6 text-slate-600">
                                    <strong class="text-[#102B46]">Future preparation:</strong>
                                    helping students prepare for further studies and new opportunities.
                                </p>
                            </div>
                        </div>

                        <a
                            href="#academics"
                            class="mt-9 inline-flex items-center gap-3 rounded-xl bg-[#102B46] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-950/10 transition hover:-translate-y-0.5 hover:bg-[#167D8D]"
                        >
                            Explore Our Academics
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Why choose us -->
            <section class="bg-white px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto max-w-7xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <div class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-xs font-bold tracking-wide text-amber-800 uppercase">
                            <i class="fa-solid fa-star text-amber-600"></i>
                            Why Choose Us
                        </div>

                        <h2 class="mt-5 font-display text-3xl font-bold text-[#102B46] sm:text-4xl lg:text-5xl">
                            An Environment Where
                            <span class="text-[#167D8D]">Students Thrive</span>
                        </h2>

                        <p class="mt-5 text-base leading-8 text-slate-500">
                            The right educational environment helps students
                            develop the knowledge, habits, and confidence they
                            need for the future.
                        </p>
                    </div>

                    <div class="mt-14 grid gap-6 md:grid-cols-3">
                        <article
                            v-for="reason in reasons"
                            :key="reason.number"
                            class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-7 shadow-[0_8px_35px_rgba(16,43,70,0.05)] transition duration-300 hover:-translate-y-2 hover:shadow-xl sm:p-9"
                        >
                            <div
                                class="absolute top-0 right-0 left-0 h-1.5 transition-all duration-300 group-hover:h-2"
                                :class="reason.color.replace('border-', 'bg-')"
                            ></div>

                            <span
                                class="font-display text-5xl font-bold opacity-80"
                                :class="reason.numberColor"
                            >
                                {{ reason.number }}
                            </span>

                            <h3 class="mt-5 font-display text-xl font-bold text-[#102B46]">
                                {{ reason.title }}
                            </h3>

                            <p class="mt-4 text-sm leading-7 text-slate-500">
                                {{ reason.description }}
                            </p>

                            <div class="mt-7 flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-[#102B46] transition duration-300 group-hover:bg-[#102B46] group-hover:text-white">
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- Academics -->
            <section id="academics" class="scroll-mt-24 bg-[#102B46] px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
                <div class="mx-auto max-w-7xl">
                    <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                        <div class="max-w-3xl">
                            <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-bold tracking-wide text-[#F3D28D] uppercase">
                                <i class="fa-solid fa-book-open"></i>
                                Academic Programs
                            </div>

                            <h2 class="mt-5 font-display text-3xl leading-tight font-bold text-white sm:text-4xl lg:text-5xl">
                                Discover Your Path.
                                <span class="block text-[#E3B65B]">Shape Your Future.</span>
                            </h2>
                        </div>

                        <p class="max-w-lg text-sm leading-7 text-blue-100/80 sm:text-base">
                            Explore learning pathways designed to develop your
                            knowledge, sharpen your thinking, and prepare you
                            for your next academic step.
                        </p>
                    </div>

                    <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <article
                            v-for="program in programs"
                            :key="program.title"
                            class="group overflow-hidden rounded-2xl bg-white shadow-xl shadow-black/10 transition duration-300 hover:-translate-y-2 hover:shadow-2xl"
                        >
                            <div
                                class="relative flex h-36 items-center justify-center overflow-hidden"
                                :class="program.color"
                            >
                                <div class="absolute -right-8 -bottom-16 h-44 w-44 rounded-full border-[24px] border-white/10"></div>
                                <div class="absolute -top-12 -left-8 h-32 w-32 rounded-full bg-white/10"></div>

                                <div class="relative flex h-20 w-20 items-center justify-center rounded-2xl border border-white/30 bg-white/15 text-4xl text-white shadow-lg backdrop-blur-sm transition duration-300 group-hover:scale-110">
                                    <i :class="program.icon"></i>
                                </div>
                            </div>

                            <div class="p-7">
                                <span
                                    class="inline-flex rounded-full px-3 py-1.5 text-[11px] font-bold tracking-wide uppercase"
                                    :class="[program.light, program.text]"
                                >
                                    {{ program.subtitle }}
                                </span>

                                <h3 class="mt-4 font-display text-xl font-bold text-[#102B46]">
                                    {{ program.title }}
                                </h3>

                                <p class="mt-3 text-sm leading-7 text-slate-500">
                                    {{ program.description }}
                                </p>

                                <a
                                    href="https://wa.me/923078527678"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-6 inline-flex items-center gap-2 text-sm font-bold transition hover:gap-3"
                                    :class="program.text"
                                >
                                    Ask About This Program
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                    </div>

                    <p class="mt-7 text-center text-xs leading-6 text-blue-100/60">
                        Contact the college to confirm currently available
                        programs, subject combinations, and eligibility requirements.
                    </p>
                </div>
            </section>

            <!-- Facilities -->
            <section id="facilities" class="scroll-mt-24 bg-[#F5F8FC] px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
                <div class="mx-auto max-w-7xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-2 text-xs font-bold tracking-wide text-blue-800 uppercase">
                            <i class="fa-solid fa-building-columns"></i>
                            Learning Environment
                        </div>

                        <h2 class="mt-5 font-display text-3xl font-bold text-[#102B46] sm:text-4xl lg:text-5xl">
                            Supporting Every
                            <span class="text-[#167D8D]">Step of Learning</span>
                        </h2>

                        <p class="mt-5 text-base leading-8 text-slate-500">
                            A positive learning environment encourages exploration,
                            strengthens academic habits, and helps students
                            develop beyond the classroom.
                        </p>
                    </div>

                    <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        <article
                            v-for="facility in facilities"
                            :key="facility.title"
                            class="group rounded-2xl border border-white bg-white p-6 shadow-[0_5px_25px_rgba(16,43,70,0.04)] transition duration-300 hover:-translate-y-1 hover:border-slate-200 hover:shadow-xl sm:p-7"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl text-2xl transition duration-300 group-hover:rotate-3 group-hover:scale-110"
                                :class="facility.color"
                            >
                                <i :class="facility.icon"></i>
                            </div>

                            <h3 class="mt-5 font-display text-lg font-bold text-[#102B46]">
                                {{ facility.title }}
                            </h3>

                            <p class="mt-3 text-sm leading-7 text-slate-500">
                                {{ facility.description }}
                            </p>

                            <div class="mt-5 h-1 w-10 rounded-full bg-[#D6A64F] transition-all duration-300 group-hover:w-16"></div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- Admissions CTA -->
            <section class="relative isolate overflow-hidden bg-[#167D8D] px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#167D8D] via-[#126477] to-[#102B46]"></div>
                <div class="absolute -top-32 -right-20 -z-10 h-80 w-80 rounded-full border-[50px] border-white/5"></div>
                <div class="absolute -bottom-40 left-0 -z-10 h-96 w-96 rounded-full bg-[#E3B65B]/10 blur-3xl"></div>

                <div class="relative mx-auto flex max-w-7xl flex-col items-start justify-between gap-9 lg:flex-row lg:items-center">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold tracking-wide text-[#F3D28D] uppercase">
                            <i class="fa-solid fa-paper-plane"></i>
                            Admissions
                        </div>

                        <h2 class="mt-5 font-display text-3xl leading-tight font-bold text-white sm:text-4xl lg:text-5xl">
                            Your Future Deserves
                            <span class="block text-[#F3D28D]">a Great Beginning.</span>
                        </h2>

                        <p class="mt-5 max-w-2xl text-base leading-8 text-white/80">
                            Take the next step in your educational journey.
                            Contact our college to learn about admissions,
                            available programs, and the application process.
                        </p>
                    </div>

                    <div class="flex w-full shrink-0 flex-col gap-3 sm:w-auto sm:flex-row lg:flex-col">
                        <a
                            href="https://docs.google.com/forms/d/e/1FAIpQLSevsvN8mbFNxYMrOXMZjnSuX-NQH_vDGD7EsswUDbQUo9YDBQ/viewform?usp=dialog"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-3 rounded-xl bg-[#E3B65B] px-7 py-4 text-sm font-bold text-[#102B46] shadow-xl transition hover:-translate-y-1 hover:bg-[#F5D28B]"
                        >
                            Apply for Admission
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        <a
                            href="https://wa.me/923078527678"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-3 rounded-xl border border-white/30 bg-white/10 px-7 py-4 text-sm font-bold text-white transition hover:bg-white/20"
                        >
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            Talk to Us
                        </a>
                    </div>
                </div>
            </section>

            <!-- Contact -->
            <section id="contact" class="scroll-mt-24 bg-white px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto max-w-7xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <div class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-4 py-2 text-xs font-bold tracking-wide text-teal-800 uppercase">
                            <i class="fa-solid fa-comments"></i>
                            Get in Touch
                        </div>

                        <h2 class="mt-5 font-display text-3xl font-bold text-[#102B46] sm:text-4xl">
                            We Are Here to
                            <span class="text-[#167D8D]">Help You</span>
                        </h2>

                        <p class="mt-4 text-base leading-8 text-slate-500">
                            Have questions about the college or admissions?
                            Reach out to us through any of the following channels.
                        </p>
                    </div>

                    <div class="mt-12 grid gap-5 md:grid-cols-3">
                        <article class="rounded-2xl border border-blue-100 bg-blue-50/70 p-7 transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-xl text-white shadow-lg shadow-blue-600/20">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <h3 class="mt-5 font-display text-lg font-bold text-[#102B46]">
                                Visit Our College
                            </h3>
                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                Samar Bagh, Lower Dir, Khyber Pakhtunkhwa, Pakistan
                            </p>
                        </article>

                        <article class="rounded-2xl border border-teal-100 bg-teal-50/70 p-7 transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-600 text-xl text-white shadow-lg shadow-teal-600/20">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <h3 class="mt-5 font-display text-lg font-bold text-[#102B46]">
                                Email Us
                            </h3>
                            <p class="mt-2 break-words text-sm leading-7 text-slate-600">
                                Questions and enquiries are welcome.
                            </p>
                            <a
                                href="mailto:mecst.edu@gmail.com"
                                class="mt-3 inline-block text-sm font-bold text-teal-800 hover:underline"
                            >
                                mecst.edu@gmail.com
                            </a>
                        </article>

                        <article class="rounded-2xl border border-amber-100 bg-amber-50/70 p-7 transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 text-xl text-white shadow-lg shadow-amber-600/20">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <h3 class="mt-5 font-display text-lg font-bold text-[#102B46]">
                                Contact Us
                            </h3>
                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                Message us to ask about admissions and programs.
                            </p>
                            <a
                                href="https://wa.me/923078527678"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-3 inline-block text-sm font-bold text-amber-800 hover:underline"
                            >
                                Chat on WhatsApp
                            </a>
                        </article>
                    </div>

                    <div class="mt-8 rounded-2xl border border-slate-100 bg-[#F5F8FC] p-6 sm:flex sm:items-center sm:justify-between sm:gap-6 sm:p-8">
                        <div>
                            <h3 class="font-display text-xl font-bold text-[#102B46]">
                                Connect with Our Community
                            </h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Follow our social channels for college updates and announcements.
                            </p>
                        </div>

                        <div class="mt-5 flex gap-3 sm:mt-0">
                            <a
                                v-for="social in socialLinks"
                                :key="social.label"
                                :href="social.url"
                                :aria-label="social.label"
                                :title="social.label"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-[#102B46] shadow-sm transition hover:-translate-y-1 hover:border-[#167D8D] hover:bg-[#167D8D] hover:text-white"
                            >
                                <i :class="social.icon"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-[#091D32] text-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-[1.3fr_0.7fr_1fr]">
                    <div>
                        <a href="#home" class="inline-flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-xl text-[#E3B65B]">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <p class="font-display text-lg font-bold">
                                    Modern Era College
                                </p>
                                <p class="mt-1 text-[10px] font-bold tracking-[0.12em] text-[#E3B65B] uppercase">
                                    Science & Technology
                                </p>
                            </div>
                        </a>

                        <p class="mt-5 max-w-md text-sm leading-7 text-blue-100/70">
                            Committed to education, character development, and
                            preparing students for a world of opportunity.
                        </p>

                        <div class="mt-6 flex gap-3">
                            <a
                                v-for="social in socialLinks"
                                :key="social.label"
                                :href="social.url"
                                :aria-label="social.label"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-blue-100 transition hover:border-[#E3B65B] hover:bg-[#E3B65B] hover:text-[#102B46]"
                            >
                                <i :class="social.icon"></i>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-display text-lg font-bold">Quick Links</h3>
                        <div class="mt-5 flex flex-col items-start gap-3">
                            <a
                                v-for="item in navigation"
                                :key="item.label"
                                :href="item.href"
                                class="text-sm text-blue-100/70 transition hover:translate-x-1 hover:text-[#E3B65B]"
                            >
                                {{ item.label }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-display text-lg font-bold">Contact Information</h3>

                        <div class="mt-5 space-y-4">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-location-dot mt-1 text-[#E3B65B]"></i>
                                <p class="text-sm leading-6 text-blue-100/70">
                                    Samar Bagh, Lower Dir, Khyber Pakhtunkhwa, Pakistan
                                </p>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-envelope mt-1 text-[#E3B65B]"></i>
                                <a
                                    href="mailto:mecst.edu@gmail.com"
                                    class="break-all text-sm text-blue-100/70 transition hover:text-[#E3B65B]"
                                >
                                    mecst.edu@gmail.com
                                </a>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="fa-brands fa-whatsapp mt-1 text-[#E3B65B]"></i>
                                <a
                                    href="https://wa.me/923078527678"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-sm text-blue-100/70 transition hover:text-[#E3B65B]"
                                >
                                    Contact via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 border-t border-white/10 pt-6">
                    <div class="flex flex-col items-center justify-between gap-3 text-center sm:flex-row sm:text-left">
                        <p class="text-xs leading-6 text-blue-100/50">
                            © {{ currentYear }} Modern Era College of Science and Technology.
                            All rights reserved.
                        </p>

                        <a
                            href="#home"
                            class="inline-flex items-center gap-2 text-xs font-semibold text-blue-100/70 transition hover:text-[#E3B65B]"
                        >
                            Back to top
                            <i class="fa-solid fa-arrow-up"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Floating WhatsApp -->
        <a
            href="https://wa.me/923078527678"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Contact the college on WhatsApp"
            title="Chat with us on WhatsApp"
            class="fixed right-5 bottom-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-2xl text-white shadow-xl shadow-green-950/20 transition hover:scale-110 hover:bg-[#1EBE5D]"
        >
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    </div>
</template>

<style>
html {
    scroll-behavior: smooth;
    scroll-padding-top: 90px;
}

body {
    margin: 0;
    font-family: "DM Sans", sans-serif;
}

.font-display {
    font-family: "Playfair Display", serif;
}

::selection {
    background: #167d8d;
    color: white;
}
</style>
