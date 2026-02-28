<x-admin.layouts.auth title="Admin Login">
    <form class="space-y-6" action="{{ route('admin.login.submit') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <!-- Email -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
            <div class="relative group">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-slate-400 transition-colors group-focus-within:text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required
                    value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    class="block w-full rounded-xl border-2 py-3 pl-11 pr-4 text-slate-900 shadow-sm transition-all duration-300 placeholder:text-slate-400 focus:ring-4 focus:ring-amber-500/10 sm:text-sm @error('email') border-red-300 bg-red-50/50 focus:border-red-500 @else border-slate-200 bg-white hover:border-slate-300 focus:border-amber-500 @enderror"
                >
            </div>
            @error('email')
                <p class="text-sm text-red-600 flex items-center gap-1.5 font-medium">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
            <div class="relative group" x-data="{ showPassword: false }">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-slate-400 transition-colors group-focus-within:text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <input
                    id="password"
                    name="password"
                    :type="showPassword ? 'text' : 'password'"
                    autocomplete="current-password"
                    required
                    placeholder="••••••••"
                    class="block w-full rounded-xl border-2 py-3 pl-11 pr-12 text-slate-900 shadow-sm transition-all duration-300 placeholder:text-slate-400 focus:ring-4 focus:ring-amber-500/10 sm:text-sm @error('password') border-red-300 bg-red-50/50 focus:border-red-500 @else border-slate-200 bg-white hover:border-slate-300 focus:border-amber-500 @enderror"
                >
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 transition-colors"
                >
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-sm text-red-600 flex items-center gap-1.5 font-medium">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        class="peer sr-only"
                    >
                    <div class="h-5 w-5 rounded-md border-2 border-slate-300 bg-white transition-all duration-200 peer-checked:border-amber-500 peer-checked:bg-amber-500 peer-focus:ring-4 peer-focus:ring-amber-500/20 group-hover:border-slate-400"></div>
                    <svg class="absolute top-0.5 left-0.5 h-4 w-4 text-white opacity-0 transition-opacity peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <span class="text-sm text-slate-700 select-none group-hover:text-slate-900 transition-colors">Remember me</span>
            </label>
        </div>

        <!-- Submit -->
        <div>
            <button
                type="submit"
                :disabled="loading"
                class="relative flex w-full justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-amber-500/30 transition-all duration-300 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl hover:shadow-amber-500/40 hover:scale-[1.02] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:scale-100"
            >
                <svg x-show="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-show="!loading">Sign in to Dashboard</span>
                <span x-show="loading" x-cloak>Signing in...</span>
            </button>
        </div>

        <!-- Security notice -->
        <div class="pt-4 border-t border-slate-200">
            <p class="text-xs text-center text-slate-500 flex items-center justify-center gap-1.5">
                <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                Secure login protected by SSL encryption
            </p>
        </div>
    </form>
</x-admin.layouts.auth>
