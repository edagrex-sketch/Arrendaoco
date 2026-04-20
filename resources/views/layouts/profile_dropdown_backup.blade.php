                                    <!-- Dropdown de Perfil -->
                                    <div x-show="openProfile"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden" x-cloak>
                                        <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50">
                                            <p class="text-xs font-black text-brand-dark uppercase tracking-widest">{{ Auth::user()->roles->first()->nombre ?? 'Usuario' }}</p>
                                            <p class="text-sm font-medium text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                        <div class="py-2">
                                            <a href="{{ route('perfil.index') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-40">
                                                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                                                </svg>
                                                Mi Perfil
                                            </a>

                                            <a href="{{ route('chats.index') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-40">
                                                    <path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.413 1.874 3.413 3.826v6.015c0 1.952-1.491 3.576-3.413 3.826a42.94 42.94 0 01-6.337.408 42.94 42.94 0 01-6.337-.408C3.413 16.324 1.922 14.7 1.922 12.748V6.756c0-1.952 1.491-3.576 3.413-3.826z" />
                                                    <path d="M12 21.75c1.108 0 2.128-.316 3.033-.865a7.316 7.316 0 001.311.238 41.229 41.229 0 00-4.344-5.597 41.229 41.229 0 00-4.344 5.597c.414-.047.852-.127 1.311-.238.905.549 1.925.865 3.033.865z" />
                                                </svg>
                                                Mis Mensajes
                                            </a>

                                            <a href="{{ route('favoritos.index') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-40 text-red-500">
                                                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                                </svg>
                                                Mis Favoritos
                                            </a>
                                            
                                            <!-- Opción para Arrendadores/Propietarios solamente -->
                                            @if (Auth::user()->tieneRol('propietario'))
                                            <a href="{{ route('inmuebles.index') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-40">
                                                    <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.69-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.06 1.06l8.69-8.69z" />
                                                    <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.751a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z" />
                                                </svg>
                                                Mis Propiedades
                                            </a>
                                            @endif

                                            @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                                            <a href="{{ route('inmuebles.mis_rentas') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-40">
                                                    <path d="M4.5 3.75a3 3 0 00-3 3v10.5a3 3 0 003 3h15a3 3 0 003-3V6.75a3 3 0 00-3-3h-15zm4.125 3a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zm-3.873 8.703a4.126 4.126 0 017.746 0 .75.75 0 01-.351.92 7.47 7.47 0 01-3.522.877 7.47 7.47 0 01-3.522-.877.75.75 0 01-.351-.92zM15 8.25a.75.75 0 000 1.5h3.75a.75.75 0 000-1.5H15zM14.25 12a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H15a.75.75 0 01-.75-.75zM14.25 15a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H15a.75.75 0 01-.75-.75z" />
                                                </svg>
                                                Mi Renta
                                            </a>
                                            @endunless

                                            <div class="h-px bg-gray-50 my-2"></div>
                                            
                                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="button" onclick="confirmLogout()"
                                                    class="w-full flex items-center gap-3 px-5 py-3 text-[15px] text-red-600 hover:bg-red-50 font-medium transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 flex-shrink-0">
                                                        <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9a.75.75 0 01-1.5 0V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                                    </svg>
                                                    Cerrar Sesión
                                                </button>
                                            </form>
                                        </div>
                                    </div>
