@php
    $user = auth()->user();
    $sections = $user->getSectionNames();
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-6">
        <!-- Hajj & Umrah Section -->
        @if(in_array('hajj', $sections))
        <li>
            <div class="flex items-center gap-2 px-2 mb-3">
                <div class="h-6 w-1 rounded-full bg-gradient-to-b from-amber-400 to-amber-600"></div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-500">Hajj & Umrah</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('admin.hajj.dashboard') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.dashboard') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.dashboard') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                            </svg>
                        </span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.packages.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.packages') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.packages') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                            </svg>
                        </span>
                        Packages
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.bookings.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.bookings') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.bookings') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </span>
                        Bookings
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.articles.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.articles') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.articles') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                            </svg>
                        </span>
                        Articles
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.team.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.team') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.team') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </span>
                        Team
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.testimonials.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.testimonials') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.testimonials') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </span>
                        Testimonials
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.inquiries.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.inquiries') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.inquiries') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        Inquiries
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.faqs.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.faqs') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.faqs') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                        </span>
                        FAQs
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hajj.settings.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hajj.settings') ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.hajj.settings') ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        Settings
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <!-- Typing Services Section -->
        @if(in_array('typing', $sections))
        <li>
            <div class="flex items-center gap-2 px-2 mb-3">
                <div class="h-6 w-1 rounded-full bg-gradient-to-b from-purple-400 to-purple-600"></div>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-400">Typing Services</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('admin.typing.dashboard') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.typing.dashboard') ? 'bg-gradient-to-r from-purple-500/20 to-purple-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.typing.dashboard') ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                            </svg>
                        </span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.typing.services.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.typing.services') ? 'bg-gradient-to-r from-purple-500/20 to-purple-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.typing.services') ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </span>
                        Services
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.typing.family-visa.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.typing.family-visa') ? 'bg-gradient-to-r from-purple-500/20 to-purple-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.typing.family-visa') ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </span>
                        Family Visa
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.typing.inquiries.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.typing.inquiries') ? 'bg-gradient-to-r from-purple-500/20 to-purple-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.typing.inquiries') ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        Inquiries
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.typing.settings.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.typing.settings') ? 'bg-gradient-to-r from-purple-500/20 to-purple-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.typing.settings') ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        Settings
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <!-- Global Admin Section -->
        <li class="mt-auto">
            <div class="flex items-center gap-2 px-2 mb-3">
                <div class="h-6 w-1 rounded-full bg-gradient-to-b from-green-400 to-green-600"></div>
                <span class="text-xs font-bold uppercase tracking-wider text-green-400">Management</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-gradient-to-r from-green-500/20 to-green-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-green-500/20 text-green-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </span>
                        Manage Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.office-locations.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.office-locations') ? 'bg-gradient-to-r from-green-500/20 to-green-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.office-locations') ? 'bg-green-500/20 text-green-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </span>
                        Office Locations
                    </a>
                </li>
            </ul>
        </li>

        <!-- Super Admin Section -->
        @if($user->isSuperAdmin())
        <li>
            <div class="flex items-center gap-2 px-2 mb-3">
                <div class="h-6 w-1 rounded-full bg-gradient-to-b from-red-400 to-red-600"></div>
                <span class="text-xs font-bold uppercase tracking-wider text-red-400">System</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('admin.admins.index') }}"
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.admins') ? 'bg-gradient-to-r from-red-500/20 to-red-600/10 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ str_starts_with($currentRoute, 'admin.admins') ? 'bg-red-500/20 text-red-400' : 'bg-slate-700/50 text-slate-400 group-hover:bg-slate-600/50 group-hover:text-slate-300' }} transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </span>
                        Manage Admins
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</nav>
