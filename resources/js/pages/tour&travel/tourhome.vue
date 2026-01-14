<template>
    <div>
        <!-- ✅ LOADER (Plane flying in circle) -->
        <div :class="['preloader', loaded ? 'preloader-hidden' : '']">
            <div class="loader">
                <span v-for="i in 20" :key="i" :style="{ '--i': i }"></span>

                <div class="loader-plane">
                    <!-- Plane SVG (no font awesome required) -->
                    <svg
                        class="plane"
                        width="42"
                        height="42"
                        viewBox="0 0 24 24"
                        fill="none"
                        aria-hidden="true"
                    >
                        <path
                            d="M21 16V14L13 9V3.5C13 2.67 12.33 2 11.5 2C10.67 2 10 2.67 10 3.5V9L2 14V16L10 13.5V19L8 20.5V22L11.5 21L15 22V20.5L13 19V13.5L21 16Z"
                            fill="currentColor"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <!-- HERO -->
        <section class="relative min-h-[92vh] w-full overflow-hidden bg-black">
            <img
                :src="heroImage"
                alt="Hero background"
                class="absolute inset-0 h-full w-full object-cover"
            />
            <div class="absolute inset-0 bg-black/45"></div>
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/25 to-black/60"
            ></div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="flex min-h-[92vh] flex-col items-center justify-center text-center"
                >
                    <p
                        class="mb-4 text-sm font-medium tracking-wide text-white/80 sm:text-base"
                    >
                        Travel Smart, Travel Better
                    </p>

                    <h1
                        class="text-4xl leading-[1.05] font-extrabold tracking-tight text-white sm:text-6xl lg:text-7xl"
                    >
                        Find Your Next Stay
                    </h1>

                    <!-- Search Bar (Glass) -->
                    <div
                        class="mt-10 w-full max-w-5xl rounded-3xl border border-white/10 bg-white/10 shadow-[0_20px_60px_rgba(0,0,0,0.35)] backdrop-blur-xl"
                    >
                        <div
                            class="grid grid-cols-1 gap-4 p-5 md:grid-cols-12 md:p-6"
                        >
                            <div class="md:col-span-3">
                                <label
                                    class="mb-2 block text-left text-xs font-semibold text-white/80"
                                    >Destination</label
                                >
                                <div class="relative">
                                    <span
                                        class="absolute top-1/2 left-3 -translate-y-1/2 text-white/70"
                                        >📍</span
                                    >
                                    <select
                                        v-model="filters.destination"
                                        class="w-full appearance-none rounded-2xl border border-white/15 bg-black/20 px-10 py-3.5 text-white transition outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                    >
                                        <option value="" class="text-black">
                                            All Location
                                        </option>
                                        <option value="cox" class="text-black">
                                            Cox's Bazar
                                        </option>
                                        <option
                                            value="sylhet"
                                            class="text-black"
                                        >
                                            Sylhet
                                        </option>
                                        <option
                                            value="saint"
                                            class="text-black"
                                        >
                                            Saint Martin
                                        </option>
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-white/70"
                                        >▾</span
                                    >
                                </div>
                            </div>

                            <div class="md:col-span-3">
                                <label
                                    class="mb-2 block text-left text-xs font-semibold text-white/80"
                                    >Trip Types</label
                                >
                                <div class="relative">
                                    <span
                                        class="absolute top-1/2 left-3 -translate-y-1/2 text-white/70"
                                        >🚴</span
                                    >
                                    <select
                                        v-model="filters.tripType"
                                        class="w-full appearance-none rounded-2xl border border-white/15 bg-black/20 px-10 py-3.5 text-white transition outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                    >
                                        <option value="" class="text-black">
                                            Activities
                                        </option>
                                        <option
                                            value="family"
                                            class="text-black"
                                        >
                                            Family
                                        </option>
                                        <option
                                            value="honeymoon"
                                            class="text-black"
                                        >
                                            Honeymoon
                                        </option>
                                        <option
                                            value="adventure"
                                            class="text-black"
                                        >
                                            Adventure
                                        </option>
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-white/70"
                                        >▾</span
                                    >
                                </div>
                            </div>

                            <div class="md:col-span-3">
                                <label
                                    class="mb-2 block text-left text-xs font-semibold text-white/80"
                                    >Duration</label
                                >
                                <div class="relative">
                                    <span
                                        class="absolute top-1/2 left-3 -translate-y-1/2 text-white/70"
                                        >🕒</span
                                    >
                                    <select
                                        v-model="filters.duration"
                                        class="w-full appearance-none rounded-2xl border border-white/15 bg-black/20 px-10 py-3.5 text-white transition outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                    >
                                        <option value="" class="text-black">
                                            3 Days - 10 Days
                                        </option>
                                        <option value="1-3" class="text-black">
                                            1 - 3 Days
                                        </option>
                                        <option value="3-7" class="text-black">
                                            3 - 7 Days
                                        </option>
                                        <option value="7-14" class="text-black">
                                            7 - 14 Days
                                        </option>
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-white/70"
                                        >▾</span
                                    >
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="mb-2 block text-left text-xs font-semibold text-white/80"
                                    >Budget</label
                                >
                                <div class="relative">
                                    <span
                                        class="absolute top-1/2 left-3 -translate-y-1/2 text-white/70"
                                        >💵</span
                                    >
                                    <select
                                        v-model="filters.budget"
                                        class="w-full appearance-none rounded-2xl border border-white/15 bg-black/20 px-10 py-3.5 text-white transition outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                    >
                                        <option value="" class="text-black">
                                            $699 - $2,000
                                        </option>
                                        <option
                                            value="0-500"
                                            class="text-black"
                                        >
                                            $0 - $500
                                        </option>
                                        <option
                                            value="500-1500"
                                            class="text-black"
                                        >
                                            $500 - $1,500
                                        </option>
                                        <option
                                            value="1500-5000"
                                            class="text-black"
                                        >
                                            $1,500 - $5,000
                                        </option>
                                    </select>
                                    <span
                                        class="absolute top-1/2 right-3 -translate-y-1/2 text-white/70"
                                        >▾</span
                                    >
                                </div>
                            </div>

                            <div class="flex items-end md:col-span-1">
                                <button
                                    @click="onSearch"
                                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-600 px-4 py-3.5 font-semibold text-white shadow-[0_12px_30px_rgba(234,88,12,0.35)] transition hover:bg-orange-500 active:scale-[0.98]"
                                >
                                    <span>🔍</span>
                                    <span class="hidden md:inline">Search</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center gap-2">
                        <span
                            class="h-2.5 w-2.5 rounded-full bg-white/30"
                        ></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-white"></span>
                        <span
                            class="h-2.5 w-2.5 rounded-full bg-white/30"
                        ></span>
                    </div>
                </div>
            </div>
        </section>

        <!-- PROMO BANNERS -->
        <section class="bg-[#fbf6ef] py-14">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <!-- Card 1 -->
                    <div
                        class="group relative min-h-[420px] overflow-hidden rounded-3xl bg-gradient-to-br from-sky-700 via-sky-600 to-sky-800 shadow-[0_18px_50px_rgba(0,0,0,0.10)]"
                    >
                        <img
                            :src="promoCards[0].image"
                            alt="Promo 1"
                            class="absolute inset-0 h-full w-full object-cover opacity-40 transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                        />
                        <div class="absolute inset-0 bg-black/10"></div>

                        <div class="relative z-10 flex h-full flex-col p-8">
                            <p class="text-sm font-semibold text-white/90">
                                Book Today
                            </p>

                            <h3
                                class="mt-3 text-3xl leading-tight font-extrabold text-white"
                            >
                                Save Up to
                                <span class="text-white">30%</span> on <br />
                                Next Adventure!
                            </h3>

                            <div class="mt-6">
                                <button class="btn-cta">
                                    <span>Book Now</span>
                                    <span aria-hidden="true" class="btn-icon"
                                        >↗</span
                                    >
                                </button>
                            </div>

                            <img
                                :src="promoCards[0].person"
                                alt="People"
                                class="absolute right-4 bottom-0 w-[70%] max-w-[320px] drop-shadow-2xl transition-transform duration-700 ease-out group-hover:translate-y-1 group-hover:scale-[1.02]"
                            />
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="group relative min-h-[420px] overflow-hidden rounded-3xl bg-gradient-to-br from-orange-200 via-orange-300 to-orange-200 shadow-[0_18px_50px_rgba(0,0,0,0.10)]"
                    >
                        <div class="relative z-10 flex h-full flex-col p-8">
                            <p class="text-sm font-semibold text-slate-800/80">
                                Book Today
                            </p>

                            <h3
                                class="mt-3 text-3xl leading-tight font-extrabold text-slate-900"
                            >
                                Fly High
                                <span class="rounded-lg bg-white/70 px-2 py-1"
                                    >30% OFF</span
                                >
                                on <br />
                                Air Tickets!
                            </h3>

                            <div class="mt-6">
                                <button class="btn-cta">
                                    <span>Explore Flights</span>
                                    <span aria-hidden="true" class="btn-icon"
                                        >↗</span
                                    >
                                </button>
                            </div>

                            <img
                                :src="promoCards[1].person"
                                alt="People"
                                class="absolute right-0 bottom-0 w-[78%] max-w-[340px] drop-shadow-2xl transition-transform duration-700 ease-out group-hover:translate-y-1 group-hover:scale-[1.02]"
                            />
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="group relative min-h-[420px] overflow-hidden rounded-3xl bg-gradient-to-br from-sky-700 via-sky-600 to-sky-800 shadow-[0_18px_50px_rgba(0,0,0,0.10)]"
                    >
                        <img
                            :src="promoCards[2].image"
                            alt="Promo 3"
                            class="absolute inset-0 h-full w-full object-cover opacity-80 transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                        />
                        <div class="absolute inset-0 bg-black/15"></div>

                        <div class="relative z-10 flex h-full flex-col p-8">
                            <p class="text-sm font-semibold text-white/90">
                                Book Today
                            </p>

                            <h3
                                class="mt-3 text-3xl leading-tight font-extrabold text-white"
                            >
                                Explore the World <br />
                                with <span class="text-white">20% OFF</span>
                            </h3>

                            <div class="mt-6">
                                <button class="btn-cta">
                                    <span>Book Now</span>
                                    <span aria-hidden="true" class="btn-icon"
                                        >↗</span
                                    >
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TRIP TYPE SLIDER -->
        <section class="bg-[#fbf6ef] pb-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="pt-6 pb-10 text-center">
                    <p
                        class="text-sm font-semibold tracking-wide text-orange-600"
                    >
                        Trip Type
                    </p>
                    <h2
                        class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl"
                    >
                        Pick Your Travel Mood
                    </h2>
                    <p class="mt-4 text-slate-500">
                        Discover the most loved travel spots around the world.
                    </p>
                </div>

                <!-- Slider -->
                <div class="relative">
                    <div class="overflow-hidden">
                        <div
                            class="flex gap-7 will-change-transform"
                            :style="{
                                transform: `translateX(-${tripCurrentOffset}px)`,
                                transition: tripIsDragging
                                    ? 'none'
                                    : 'transform 520ms cubic-bezier(0.22, 1, 0.36, 1)',
                            }"
                            ref="tripTrack"
                        >
                            <button
                                v-for="item in tripTypes"
                                :key="item.key"
                                class="group relative h-[120px] w-[300px] shrink-0 overflow-hidden rounded-2xl shadow-[0_18px_45px_rgba(0,0,0,0.12)] sm:w-[340px]"
                                @click="selectTrip(item)"
                            >
                                <img
                                    :src="item.image"
                                    :alt="item.title"
                                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.05]"
                                />
                                <div
                                    class="absolute inset-0 bg-black/35 transition-colors group-hover:bg-black/30"
                                ></div>

                                <div
                                    class="relative z-10 flex h-full w-full items-center justify-center px-6"
                                >
                                    <p
                                        class="text-xl font-extrabold text-white drop-shadow sm:text-2xl"
                                    >
                                        {{ item.title }}
                                        <span class="opacity-90"
                                            >({{ item.count }})</span
                                        >
                                    </p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Divider line like screenshot -->
                    <div class="mt-10 border-t border-orange-300/50"></div>

                    <!-- Controls -->
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button
                            class="h-11 w-11 rounded-xl border border-orange-300 bg-transparent text-orange-700 transition hover:bg-orange-50 active:scale-[0.98]"
                            @click="tripPrev"
                            :disabled="tripAtStart"
                        >
                            ‹
                        </button>
                        <button
                            class="h-11 w-11 rounded-xl bg-orange-700 text-white transition hover:bg-orange-600 active:scale-[0.98]"
                            @click="tripNext"
                            :disabled="tripAtEnd"
                        >
                            ›
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ✅ POPULAR DESTINATION (Slider) -->
        <section class="bg-[#fbf6ef] pb-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-start justify-between gap-4 pt-4">
                    <div>
                        <p
                            class="text-sm font-semibold tracking-wide text-orange-600"
                        >
                            Destinations
                        </p>
                        <h2
                            class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl"
                        >
                            Popular Destination
                        </h2>
                    </div>

                    <a
                        href="#"
                        class="mt-2 inline-flex items-center gap-2 rounded-xl border border-orange-300/60 px-4 py-2 text-orange-700 transition hover:bg-orange-50"
                    >
                        View All <span aria-hidden="true">↗</span>
                    </a>
                </div>

                <div class="relative mt-10">
                    <div class="overflow-hidden">
                        <div
                            class="flex gap-7 will-change-transform"
                            :style="{
                                transform: `translateX(-${destCurrentOffset}px)`,
                                transition: destIsDragging
                                    ? 'none'
                                    : 'transform 520ms cubic-bezier(0.22, 1, 0.36, 1)',
                            }"
                            ref="destTrack"
                        >
                            <div
                                v-for="item in destinations"
                                :key="item.key"
                                class="group relative h-[340px] w-[280px] shrink-0 overflow-hidden rounded-2xl shadow-[0_18px_45px_rgba(0,0,0,0.12)] sm:w-[300px] lg:w-[320px]"
                            >
                                <img
                                    :src="item.image"
                                    :alt="item.title"
                                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.05]"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/30 to-black/0"
                                ></div>

                                <div
                                    class="absolute right-0 bottom-0 left-0 p-5"
                                >
                                    <p
                                        class="text-xl font-extrabold text-white drop-shadow"
                                    >
                                        {{ item.title }}
                                    </p>

                                    <div
                                        class="mt-3 inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-white/90 backdrop-blur-md"
                                    >
                                        <span>🏷️</span>
                                        <span class="text-sm font-semibold"
                                            >{{ item.packages }} Packages
                                            Available</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-between gap-6">
                        <div class="h-px flex-1 bg-orange-300/50"></div>

                        <div class="flex items-center gap-3">
                            <button
                                class="h-11 w-11 rounded-xl border border-orange-300 bg-transparent text-orange-700 transition hover:bg-orange-50 active:scale-[0.98]"
                                @click="destPrev"
                                :disabled="destAtStart"
                            >
                                ‹
                            </button>
                            <button
                                class="h-11 w-11 rounded-xl bg-orange-700 text-white transition hover:bg-orange-600 active:scale-[0.98]"
                                @click="destNext"
                                :disabled="destAtEnd"
                            >
                                ›
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ✅ WHY CHOOSE US (Trusted section) -->
        <section class="bg-[#fbf6ef] pb-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12"
                >
                    <!-- Left: image stack -->
                    <div class="relative lg:col-span-6">
                        <!-- main card -->
                        <div
                            class="relative overflow-hidden rounded-3xl shadow-[0_18px_50px_rgba(0,0,0,0.10)]"
                        >
                            <img
                                :src="whyChoose.mainImage"
                                alt="Adventure Travel"
                                class="h-[520px] w-full object-cover"
                            />
                            <div class="absolute inset-0 bg-black/25"></div>
                            <div
                                class="absolute top-10 right-8 left-8 text-center"
                            >
                                <p
                                    class="text-4xl font-extrabold tracking-wide text-white/90"
                                >
                                    ADVENTURE TRAVEL
                                </p>
                                <p class="mt-3 font-semibold text-white/85">
                                    Trusted by thousands of travelers
                                </p>
                            </div>

                            <div
                                class="absolute top-7 left-7 rounded-2xl border border-white/15 bg-white/15 px-4 py-2 backdrop-blur"
                            >
                                <p
                                    class="font-extrabold tracking-wide text-white"
                                >
                                    TOURIZA.
                                </p>
                            </div>
                        </div>

                        <!-- smaller card overlay -->
                        <div
                            class="absolute -bottom-10 left-1/2 w-[88%] -translate-x-1/2 overflow-hidden rounded-3xl shadow-[0_22px_65px_rgba(0,0,0,0.18)] sm:w-[72%] md:w-[60%]"
                        >
                            <img
                                :src="whyChoose.subImage"
                                alt="Travel Couple"
                                class="h-[320px] w-full object-cover"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/10 to-transparent"
                            ></div>
                            <div class="absolute bottom-6 left-6">
                                <p
                                    class="text-5xl font-extrabold tracking-widest text-white opacity-70"
                                >
                                    TRAVEL
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: content -->
                    <div class="pt-12 lg:col-span-6 lg:pt-0 lg:pl-6">
                        <p
                            class="text-sm font-semibold tracking-wide text-orange-600"
                        >
                            Why Choose Us
                        </p>

                        <h2
                            class="mt-3 text-3xl leading-tight font-extrabold tracking-tight text-slate-900 sm:text-5xl"
                        >
                            Trusted By Thousands Of
                            <br class="hidden sm:block" />
                            Travelers Worldwide
                        </h2>

                        <p class="mt-5 leading-relaxed text-slate-600">
                            We go the extra mile to make your travel experience
                            smooth, safe, and unforgettable. Discover why
                            thousands of travelers trust us for their journeys
                            around the world.
                        </p>

                        <div class="mt-8 space-y-5">
                            <div
                                class="flex items-center gap-4 rounded-2xl border border-orange-300/50 bg-white/30 px-6 py-5 backdrop-blur"
                            >
                                <div
                                    class="grid h-14 w-14 place-items-center rounded-2xl border border-orange-200 bg-orange-50 text-orange-700"
                                >
                                    <svg
                                        width="26"
                                        height="26"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M12 2L15 8L22 9L17 14L18 22L12 19L6 22L7 14L2 9L9 8L12 2Z"
                                            stroke="currentColor"
                                            stroke-width="1.6"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-lg font-extrabold text-slate-900"
                                    >
                                        Expertly Crafted Tours
                                    </p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Handpicked packages by real travel
                                        experts
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-4 rounded-2xl border border-orange-300/50 bg-white/30 px-6 py-5 backdrop-blur"
                            >
                                <div
                                    class="grid h-14 w-14 place-items-center rounded-2xl border border-orange-200 bg-orange-50 text-orange-700"
                                >
                                    <svg
                                        width="26"
                                        height="26"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M12 8V12L15 14"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M12 22C17.523 22 22 17.523 22 12C22 6.477 17.523 2 12 2C6.477 2 2 6.477 2 12C2 17.523 6.477 22 12 22Z"
                                            stroke="currentColor"
                                            stroke-width="1.6"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-lg font-extrabold text-slate-900"
                                    >
                                        24/7 Customer Support
                                    </p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Got a question or facing an issue? No
                                        worries!
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button
                            class="mt-8 inline-flex items-center justify-center rounded-xl bg-orange-700 px-6 py-3 font-semibold text-white shadow-[0_12px_30px_rgba(194,65,12,0.25)] transition hover:bg-orange-600 active:scale-[0.98]"
                        >
                            Talk to Our Team
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ✅ NEW SECTION 1 (AFTER TRUSTED): STATS STRIP -->
        <section class="bg-[#fbf6ef] pt-16 pb-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="s in stats"
                        :key="s.label"
                        class="flex items-center gap-5 rounded-2xl border border-orange-300/35 bg-[#fff6ee] px-7 py-6 shadow-[0_14px_40px_rgba(0,0,0,0.06)]"
                    >
                        <div
                            class="grid h-14 w-14 place-items-center rounded-2xl border border-orange-300/35 bg-[#fffaf5] text-[#6b3a16]"
                        >
                            <!-- icon -->
                            <component :is="s.icon" class="h-8 w-8" />
                        </div>

                        <div>
                            <p class="text-3xl font-extrabold text-[#6b3a16]">
                                {{ s.value }}
                            </p>
                            <p
                                class="mt-1 text-sm font-semibold text-[#6b3a16]/80"
                            >
                                {{ s.label }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ✅ NEW SECTION 2 (AFTER TRUSTED): GALLERY SLIDER AUTO -->
        <section class="bg-[#fbf6ef] pb-20 pt-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <p class="text-sm font-semibold tracking-wide text-orange-700">Gallery</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-[#5d320c] sm:text-4xl lg:text-5xl">
                        Experience Through Images
                    </h2>
                    <p class="mt-3 text-slate-600">
                        Discover some of our finest work captured through visuals
                    </p>
                </div>
                <div class="overflow-hidden">
                    <div
                        class="flex gap-4 will-change-transform"
                        :style="{
                            transform: `translateX(-${galleryCurrentOffset}px)`,
                            transition:
                                'transform 520ms cubic-bezier(0.22, 1, 0.36, 1)',
                        }"
                        ref="galleryTrack"
                    >
                        <div
                            v-for="(img, idx) in [
                                ...galleryImages,
                                ...galleryImages,
                            ]"
                            :key="`${img.key}-${idx}`"
                            class="shrink-0"
                            :style="{ width: 'calc((100% - (16px * 4)) / 5)' }"
                        >
                            <div
                                class="group rounded-0 relative m-0 h-[400px] overflow-hidden bg-white shadow-none"
                            >
                                <img
                                    :src="img.src"
                                    :alt="img.alt"
                                    class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.02]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ✅ NEW SECTION 3 (AFTER TRUSTED): PARTNER LOGOS ROW -->
        <section class="bg-[#fbf6ef] pb-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-wrap items-center justify-center gap-x-14 gap-y-8 opacity-90"
                >
                    <img
                        v-for="l in partnerLogos"
                        :key="l.alt"
                        :src="l.src"
                        :alt="l.alt"
                        class="h-10 w-auto object-contain grayscale transition hover:grayscale-0"
                        loading="lazy"
                    />
                </div>
            </div>
        </section>

        <!-- ✅ TESTIMONIALS SECTION -->
        <section class="bg-gradient-to-b from-[#fbf6ef] to-[#fef8f1] py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm font-semibold tracking-wide text-orange-600">
                        Testimonials
                    </p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-[#5d320c] sm:text-4xl lg:text-5xl">
                        What Our Travelers Say
                    </h2>
                    <p class="mt-3 text-slate-600">
                        Hear from our happy travelers around the world
                    </p>
                </div>

                <div class="relative mt-16">
                    <div class="overflow-hidden">
                        <div
                            class="flex gap-8 will-change-transform"
                            :style="{
                                transform: `translateX(-${testimonialCurrentOffset}px)`,
                                transition: 'transform 520ms cubic-bezier(0.22, 1, 0.36, 1)',
                            }"
                            ref="testimonialTrack"
                        >
                            <div
                                v-for="item in testimonials"
                                :key="item.id"
                                class="shrink-0 w-full sm:w-[500px] lg:w-[900px]"
                            >
                                <div class="flex flex-col items-center gap-8 lg:flex-row lg:gap-12">
                                    <div class="shrink-0">
                                        <img
                                            :src="item.image"
                                            :alt="item.name"
                                            class="h-[280px] w-[280px] rounded-3xl object-cover shadow-[0_20px_60px_rgba(0,0,0,0.15)]"
                                        />
                                    </div>

                                    <div class="flex-1 text-center lg:text-left">
                                        <svg class="h-10 w-10 text-orange-600 opacity-50 mx-auto lg:mx-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                                        </svg>
                                        <p class="mt-6 text-lg leading-relaxed text-[#5d320c] sm:text-xl lg:text-2xl">
                                            {{ item.quote }}
                                        </p>
                                        <div class="mt-6">
                                            <p class="text-lg font-bold text-orange-700">
                                                {{ item.name }}
                                            </p>
                                            <p class="mt-1 text-sm text-slate-500">
                                                {{ item.location }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex items-center justify-center gap-4">
                        <button
                            class="h-12 w-12 rounded-xl border-2 border-orange-600 bg-white text-orange-700 transition hover:bg-orange-50 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                            @click="testimonialPrev"
                            :disabled="testimonialAtStart"
                        >
                            ‹
                        </button>
                        <button
                            class="h-12 w-12 rounded-xl border-2 border-orange-600 bg-orange-600 text-white transition hover:bg-orange-700 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                            @click="testimonialNext"
                            :disabled="testimonialAtEnd"
                        >
                            ›
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ✅ BLOG SECTION -->
        <section class="bg-[#fef8f1] py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold tracking-wide text-orange-600">
                            Our Blog
                        </p>
                        <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-[#5d320c] sm:text-4xl lg:text-5xl">
                            Travel Stories & Guides
                        </h2>
                        <p class="mt-3 text-slate-600">
                            Read exciting travel stories, expert guides, and helpful tips.
                        </p>
                    </div>

                    <a
                        href="#"
                        class="mt-2 inline-flex items-center gap-2 rounded-xl border border-orange-300/60 px-4 py-2 text-orange-700 transition hover:bg-orange-50"
                    >
                        View All <span aria-hidden="true">↗</span>
                    </a>
                </div>

                <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <article
                        v-for="post in blogPosts"
                        :key="post.id"
                        class="group relative overflow-hidden rounded-2xl bg-white shadow-[0_18px_45px_rgba(0,0,0,0.08)] transition hover:shadow-[0_22px_55px_rgba(0,0,0,0.12)]"
                    >
                        <div class="relative h-[280px] overflow-hidden">
                            <img
                                :src="post.image"
                                :alt="post.title"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                            <div class="absolute top-4 left-4 flex items-center gap-2">
                                <img
                                    :src="post.authorImage"
                                    :alt="post.author"
                                    class="h-8 w-8 rounded-full border-2 border-white object-cover"
                                />
                                <div class="text-xs font-semibold text-white">
                                    <p>Post By</p>
                                    <p>{{ post.author }}</p>
                                </div>
                            </div>

                            <div class="absolute bottom-4 left-4 flex items-center gap-4 text-xs font-medium text-white">
                                <span>📅 {{ post.date }}</span>
                                <span>• {{ post.readTime }}</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold leading-tight text-[#5d320c] transition group-hover:text-orange-700">
                                {{ post.title }}
                            </h3>
                            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-slate-600">
                                {{ post.excerpt }}
                            </p>

                            <a
                                href="#"
                                class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-orange-600 transition hover:text-orange-700"
                            >
                                Read More
                                <span aria-hidden="true" class="transition-transform group-hover:translate-x-1">→</span>
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import TourTravelLayout from '@/layouts/TourTravelLayout.vue';
import { computed, h, onBeforeUnmount, onMounted, reactive, ref } from 'vue';

defineOptions({ layout: TourTravelLayout });

const loaded = ref(false);

// Example: hide loader after page is mounted
onMounted(() => {
    setTimeout(() => {
        loaded.value = true;
    }, 900);
});

/**
 * HERO IMAGE
 */
const heroImage = '/images/hero.jpg';

/**
 * PROMO CARD IMAGES
 */
const promoCards = [
    {
        image: '/images/promo/promo1-bg.jpg',
        person: '/images/promo/promo1-people.png',
    },
    {
        person: '/images/promo/promo2-people.png',
    },
    {
        image: '/images/promo/promo3-bg.jpg',
    },
];

/**
 * Trip Types (Slider)
 */
const tripTypes = [
    {
        key: 'beach',
        title: 'Beach Holiday',
        count: '01',
        image: '/images/trips/beach.jpg',
    },
    {
        key: 'adventure',
        title: 'Adventure',
        count: '04',
        image: '/images/trips/adventure.jpg',
    },
    {
        key: 'honeymoon',
        title: 'Honeymoon',
        count: '05',
        image: '/images/trips/honeymoon.jpg',
    },
    {
        key: 'trekking',
        title: 'Trekking',
        count: '03',
        image: '/images/trips/trekking.jpg',
    },
    {
        key: 'camping',
        title: 'Camping',
        count: '02',
        image: '/images/trips/camping.jpg',
    },
    {
        key: 'city',
        title: 'City Break',
        count: '06',
        image: '/images/trips/city.jpg',
    },
];

/**
 * Popular Destinations (Slider)
 */
const destinations = [
    {
        key: 'maldives',
        title: 'Maldives, Asia',
        packages: '07',
        image: '/images/destinations/maldives.jpg',
    },
    {
        key: 'santorini',
        title: 'Santorini, Greece',
        packages: '02',
        image: '/images/destinations/santorini.jpg',
    },
    {
        key: 'london',
        title: 'London, UK',
        packages: '09',
        image: '/images/destinations/london.jpg',
    },
    {
        key: 'bali',
        title: 'Bali, Indonesia',
        packages: '12',
        image: '/images/destinations/bali.jpg',
    },
    {
        key: 'paris',
        title: 'Paris, France',
        packages: '05',
        image: '/images/destinations/paris.jpg',
    },
    {
        key: 'dubai',
        title: 'Dubai, UAE',
        packages: '08',
        image: '/images/destinations/dubai.jpg',
    },
];

/**
 * Why Choose Us Images
 */
const whyChoose = {
    mainImage: '/images/why/why-main.jpg',
    subImage: '/images/why/why-couple.jpg',
};

const filters = reactive({
    destination: '',
    tripType: '',
    duration: '',
    budget: '',
});

const onSearch = () => {
    console.log('Search with:', { ...filters });
};

const selectTrip = (item) => {
    filters.tripType = item.key;
};

/* =========================================================
   ✅ Generic slider helper (works for BOTH sections)
========================================================= */
function useSlider({ itemsLength, trackRef, cardWidth, gap }) {
    const index = ref(0);
    const visibleCount = ref(1);

    const step = computed(() => cardWidth.value + gap);

    const updateVisible = () => {
        const el = trackRef.value?.parentElement;
        if (!el) return;
        const containerWidth = el.clientWidth;
        const count = Math.max(
            1,
            Math.floor((containerWidth + gap) / (cardWidth.value + gap)),
        );
        visibleCount.value = count;

        const maxIdx = Math.max(0, itemsLength.value - visibleCount.value);
        if (index.value > maxIdx) index.value = maxIdx;
    };

    const atStart = computed(() => index.value <= 0);
    const atEnd = computed(
        () =>
            index.value >= Math.max(0, itemsLength.value - visibleCount.value),
    );

    const currentOffset = computed(() => index.value * step.value);

    const next = () => {
        if (!atEnd.value) index.value += 1;
    };
    const prev = () => {
        if (!atStart.value) index.value -= 1;
    };

    const onResize = () => updateVisible();

    onMounted(() => {
        updateVisible();
        window.addEventListener('resize', onResize, { passive: true });
    });
    onBeforeUnmount(() => {
        window.removeEventListener('resize', onResize);
    });

    return { index, visibleCount, atStart, atEnd, currentOffset, next, prev };
}

/* =========================================================
   Trip Type Slider State
========================================================= */
const tripTrack = ref(null);
const tripIsDragging = ref(false);

const tripCardWidth = ref(340); // matches sm:w-[340px]
const gapPx = 28; // gap-7 => 28px

const tripSlider = useSlider({
    itemsLength: computed(() => tripTypes.length),
    trackRef: tripTrack,
    cardWidth: tripCardWidth,
    gap: gapPx,
});

const tripAtStart = tripSlider.atStart;
const tripAtEnd = tripSlider.atEnd;
const tripCurrentOffset = tripSlider.currentOffset;
const tripNext = tripSlider.next;
const tripPrev = tripSlider.prev;

/* =========================================================
   Destination Slider State
========================================================= */
const destTrack = ref(null);
const destIsDragging = ref(false);
const destCardWidth = ref(320);

const destSlider = useSlider({
    itemsLength: computed(() => destinations.length),
    trackRef: destTrack,
    cardWidth: destCardWidth,
    gap: gapPx,
});

const destAtStart = destSlider.atStart;
const destAtEnd = destSlider.atEnd;
const destCurrentOffset = destSlider.currentOffset;
const destNext = destSlider.next;
const destPrev = destSlider.prev;

/* =========================================================
   ✅ NEW: Stats + Gallery + Logos data + Gallery slider state
========================================================= */
// simple inline SVG icon components (no extra libs)
const SmileIcon = {
    render() {
        return h(
            'svg',
            { viewBox: '0 0 24 24', fill: 'none', class: 'h-8 w-8' },
            [
                h('path', {
                    d: 'M8 14s1.5 2 4 2 4-2 4-2',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                    'stroke-linecap': 'round',
                }),
                h('path', {
                    d: 'M9 10h.01M15 10h.01',
                    stroke: 'currentColor',
                    'stroke-width': '2.2',
                    'stroke-linecap': 'round',
                }),
                h('path', {
                    d: 'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                }),
            ],
        );
    },
};

const TicketIcon = {
    render() {
        return h(
            'svg',
            { viewBox: '0 0 24 24', fill: 'none', class: 'h-8 w-8' },
            [
                h('path', {
                    d: 'M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 1 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 1 0 0-4V7Z',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                }),
                h('path', {
                    d: 'M9 9h6M9 12h6M9 15h6',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                    'stroke-linecap': 'round',
                }),
            ],
        );
    },
};

const GlobeIcon = {
    render() {
        return h(
            'svg',
            { viewBox: '0 0 24 24', fill: 'none', class: 'h-8 w-8' },
            [
                h('path', {
                    d: 'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                }),
                h('path', {
                    d: 'M2 12h20',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                    'stroke-linecap': 'round',
                }),
                h('path', {
                    d: 'M12 2c3 3 3 17 0 20',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                    'stroke-linecap': 'round',
                }),
                h('path', {
                    d: 'M12 2c-3 3-3 17 0 20',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                    'stroke-linecap': 'round',
                }),
            ],
        );
    },
};

const BadgeIcon = {
    render() {
        return h(
            'svg',
            { viewBox: '0 0 24 24', fill: 'none', class: 'h-8 w-8' },
            [
                h('path', {
                    d: 'M12 2l2.2 4.5 5 .7-3.6 3.5.9 5-4.5-2.4-4.5 2.4.9-5L4.8 7.2l5-.7L12 2Z',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                }),
                h('path', {
                    d: 'M6 22l2.2-3.3M18 22l-2.2-3.3',
                    stroke: 'currentColor',
                    'stroke-width': '1.6',
                    'stroke-linecap': 'round',
                }),
            ],
        );
    },
};

const stats = [
    { value: '71k+', label: 'Happy Travelers', icon: SmileIcon },
    { value: '250+', label: 'Tour Packages', icon: TicketIcon },
    { value: '50+', label: 'Destinations Covered', icon: GlobeIcon },
    { value: '10+', label: 'Years of Experience', icon: BadgeIcon },
];

const galleryImages = [
    { key: 'g1', src: '/images/gallery/g1.jpg', alt: 'Gallery 1' },
    { key: 'g2', src: '/images/gallery/g2.jpg', alt: 'Gallery 2' },
    { key: 'g3', src: '/images/gallery/g3.jpg', alt: 'Gallery 3' },
    { key: 'g4', src: '/images/gallery/g4.jpg', alt: 'Gallery 4' },
    { key: 'g5', src: '/images/gallery/g5.jpg', alt: 'Gallery 5' },
];

// Gallery slider state - auto slide with 5 visible images
const galleryTrack = ref(null);
const galleryIndex = ref(0);
const galleryAutoInterval = ref(null);
const visibleGalleryCount = 5;

// Gallery auto-slider - each card width is screen width / 5
const galleryCardWidth = computed(() => {
    const gapSize = 16; // gap-4 = 16px
    const totalGap = gapSize * (visibleGalleryCount - 1);
    if (typeof window !== 'undefined') {
        return (window.innerWidth - totalGap) / visibleGalleryCount;
    }
    return (1024 - totalGap) / visibleGalleryCount;
});

const galleryCurrentOffset = computed(() => {
    const gapSize = 16;
    return galleryIndex.value * (galleryCardWidth.value + gapSize);
});

const galleryNext = () => {
    galleryIndex.value += 1;
    if (galleryIndex.value >= galleryImages.length) {
        galleryIndex.value = 0;
    }
};

const startAutoSlide = () => {
    galleryAutoInterval.value = setInterval(() => {
        galleryNext();
    }, 4000); // Change image every 4 seconds
};

const stopAutoSlide = () => {
    if (galleryAutoInterval.value) {
        clearInterval(galleryAutoInterval.value);
        galleryAutoInterval.value = null;
    }
};

onMounted(() => {
    startAutoSlide();
});

onBeforeUnmount(() => {
    stopAutoSlide();
});

const partnerLogos = [
    { src: '/images/partners/p1.png', alt: 'Partner 1' },
    { src: '/images/partners/p2.png', alt: 'Partner 2' },
    { src: '/images/partners/p3.png', alt: 'Partner 3' },
    { src: '/images/partners/p4.png', alt: 'Partner 4' },
    { src: '/images/partners/p5.png', alt: 'Partner 5' },
];

/* =========================================================
   Testimonials Data & Slider
========================================================= */
const testimonials = [
    {
        id: 1,
        name: 'Sarah M',
        location: 'Sydney, Australia',
        quote: 'Absolutely incredible experience! We explored Bali stress-free, thanks to the amazing team at Travelex.',
        image: '/images/testimonials/person1.jpg',
    },
    {
        id: 2,
        name: 'John D',
        location: 'New York, USA',
        quote: 'The best travel agency I\'ve ever worked with. Professional, friendly, and they made our honeymoon unforgettable!',
        image: '/images/testimonials/person2.jpg',
    },
    {
        id: 3,
        name: 'Emma L',
        location: 'London, UK',
        quote: 'From booking to the trip itself, everything was seamless. Highly recommend for anyone looking for hassle-free travel.',
        image: '/images/testimonials/person3.jpg',
    },
];

const testimonialTrack = ref(null);
const testimonialIndex = ref(0);

const testimonialCardWidth = computed(() => {
    if (typeof window !== 'undefined') {
        if (window.innerWidth >= 1024) return 900;
        if (window.innerWidth >= 640) return 500;
        return window.innerWidth - 32;
    }
    return 900;
});

const testimonialCurrentOffset = computed(() => {
    const gap = 32;
    return testimonialIndex.value * (testimonialCardWidth.value + gap);
});

const testimonialAtStart = computed(() => testimonialIndex.value <= 0);
const testimonialAtEnd = computed(() => testimonialIndex.value >= testimonials.length - 1);

const testimonialNext = () => {
    if (!testimonialAtEnd.value) testimonialIndex.value += 1;
};

const testimonialPrev = () => {
    if (!testimonialAtStart.value) testimonialIndex.value -= 1;
};

/* =========================================================
   Blog Posts Data
========================================================= */
const blogPosts = [
    {
        id: 1,
        title: 'Top 10 Essential Travel Tips For Your First Abroad Journey',
        excerpt: 'Planning your first international trip? Here are the essential tips you need to know before you go.',
        image: '/images/blog/blog1.jpg',
        author: 'Reachthemes',
        authorImage: '/images/blog/author1.jpg',
        date: '14 May, 2025',
        readTime: '5 Min Read',
    },
    {
        id: 2,
        title: 'International Travel Checklist: What You Must Not Forget',
        excerpt: 'Never forget important items again with our comprehensive travel checklist for international trips.',
        image: '/images/blog/blog2.jpg',
        author: 'Reachthemes',
        authorImage: '/images/blog/author2.jpg',
        date: '17 May, 2025',
        readTime: '8 Min Read',
    },
    {
        id: 3,
        title: 'Budget Travel Tips: How To Explore More By Spending Less',
        excerpt: 'Discover proven strategies to travel more while staying within your budget. Travel smart, not expensive.',
        image: '/images/blog/blog3.jpg',
        author: 'Reachthemes',
        authorImage: '/images/blog/author3.jpg',
        date: '12 May, 2025',
        readTime: '6 Min Read',
    },
];
</script>

<style scoped>
/* CTA Button hover animation (for Book Now buttons) */
.btn-cta {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border-radius: 14px;
    padding: 12px 18px;
    background: rgba(255, 255, 255, 0.95);
    color: #0f172a;
    font-weight: 700;
    font-size: 14px;
    transition:
        transform 220ms ease,
        box-shadow 220ms ease,
        background 220ms ease;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
}
.btn-cta::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 16px;
    background: linear-gradient(
        90deg,
        rgba(255, 255, 255, 0.15),
        rgba(255, 255, 255, 0)
    );
    opacity: 0;
    transition: opacity 220ms ease;
}
.btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 34px rgba(0, 0, 0, 0.16);
    background: rgba(255, 255, 255, 1);
}
.btn-cta:hover::after {
    opacity: 1;
}
.btn-cta:active {
    transform: translateY(0);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
}
.btn-icon {
    display: inline-block;
    transform: translateX(0);
    transition: transform 220ms ease;
}
.btn-cta:hover .btn-icon {
    transform: translateX(3px) translateY(-1px);
}

/* Disable nav buttons style */
button:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

/* Same behavior as: body.loaded .preloader { opacity:0; visibility:hidden } */
.preloader-hidden {
    opacity: 0;
    visibility: hidden;
    transition: all 0.6s ease;
}

.preloader {
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100%;
    background: #ffffff; /* var(--color-white) */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}

.loader {
    position: relative;
    width: 90px;
    height: 90px;
}

.loader span {
    position: absolute;
    inset: 0;
    transform: rotate(calc(18deg * var(--i)));
}

.loader span::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 10px;
    height: 10px;
    background: #c96b2c; /* var(--color-primary) */
    border-radius: 50%;
    transform: scale(0);
    animation: loader 2s linear infinite;
    animation-delay: calc(0.1s * var(--i));
}

@keyframes loader {
    0% {
        transform: scale(0);
    }
    10% {
        transform: scale(1.2);
    }
    80%,
    100% {
        transform: scale(0);
    }
}

.loader-plane {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    animation: loader-rotating 2s linear infinite;
    animation-delay: -1s;
}

@keyframes loader-rotating {
    0% {
        transform: rotate(10deg);
    }
    100% {
        transform: rotate(370deg);
    }
}

/* plane position similar to your fontawesome placement */
.plane {
    position: absolute;
    top: 53px;
    left: 58px;
    color: #c96b2c; /* var(--color-primary) */
    transform: rotate(135deg);
}
</style>
