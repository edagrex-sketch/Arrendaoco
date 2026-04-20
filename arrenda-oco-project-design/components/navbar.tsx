"use client"

import { useState } from "react"
import Image from "next/image"
import Link from "next/link"
import { Menu, X, User, MessageCircle, Home, Building2, LogOut, Settings, LogIn } from "lucide-react"

interface NavbarProps {
  isLoggedIn: boolean
  onLoginClick: () => void
  userMode: "inquilino" | "propietario"
  onModeSwitch: () => void
}

export function Navbar({ isLoggedIn, onLoginClick, userMode, onModeSwitch }: NavbarProps) {
  const [isMenuOpen, setIsMenuOpen] = useState(false)

  const toggleMenu = () => setIsMenuOpen(!isMenuOpen)

  return (
    <header className="sticky top-0 z-50 w-full bg-[#003049] text-white shadow-xl">
      <div className="container mx-auto flex h-20 items-center justify-between px-4 sm:px-6">

        {/* LOGO */}
        <Link href="/" className="flex items-center gap-3 group transition-all">
          <div className="relative h-12 w-12 overflow-hidden rounded-xl bg-white/10 p-1 group-hover:scale-105 transition-transform">
            <Image
              src="/logo1.png"
              alt="ArrendaOco Logo"
              width={48}
              height={48}
              className="object-contain"
            />
          </div>
          <span className="text-2xl font-extrabold tracking-tighter hidden sm:block">ArrendaOco</span>
        </Link>

        {/* NAVEGACIÓN DESKTOP (Se oculta en móvil) */}
        <div className="hidden lg:flex items-center gap-8">
          <Link href="/" className="text-sm font-bold text-white/80 hover:text-white transition-all">Inicio</Link>
          <Link href="/nosotros" className="text-sm font-bold text-white/80 hover:text-white transition-all">Nosotros</Link>
          
          {isLoggedIn ? (
            <div className="flex items-center gap-4">
              {userMode === "propietario" && (
                <Link href="/publicar" className="bg-[#C1121F] text-white px-5 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-red-900/20 hover:scale-105 transition-all">
                  Publicar
                </Link>
              )}
              
              {/* Profile Trigger Mockup */}
              <button 
                onClick={toggleMenu} 
                className="flex items-center gap-3 p-1 rounded-2xl hover:bg-white/5 transition-all outline-none border border-transparent hover:border-white/10"
              >
                 <div className="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center overflow-hidden shrink-0">
                    <User className="w-5 h-5 text-white/70" />
                 </div>
                 <div className="text-left hidden xl:block">
                    <div className="text-xs font-black text-white leading-tight truncate max-w-[120px]">Mi Cuenta</div>
                    <div className="text-[10px] text-white/40 font-bold uppercase tracking-widest">Mi Perfil</div>
                 </div>
              </button>
            </div>
          ) : (
            <div className="flex items-center gap-4">
              <button
                onClick={onLoginClick}
                className="text-sm font-bold text-white hover:text-blue-200 transition-all"
              >
                Entrar
              </button>
              <button
                onClick={onLoginClick}
                className="bg-[#C1121F] text-white px-6 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-red-900/20 hover:scale-105 transition-all"
              >
                Unirme
              </button>
            </div>
          )}
        </div>

        {/* BOTÓN HAMBURGUESA (Solo visible en móvil) */}
        <div className="lg:hidden flex items-center">
          <button 
            onClick={toggleMenu} 
            className="flex items-center justify-center w-12 h-12 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all shadow-inner relative z-[60]"
          >
            {isMenuOpen ? <X className="h-7 w-7" /> : <Menu className="h-7 w-7" />}
          </button>
        </div>
      </div>

      {/* MENÚ DESPLEGABLE MÓVIL (Dropdown Full Width) */}
      {isMenuOpen && (
        <div className="lg:hidden fixed inset-x-0 top-20 bg-[#003049] border-b border-white/10 shadow-2xl z-[55] overflow-y-auto max-h-[85vh] p-4 animate-in slide-in-from-top-4 duration-300">
           <div className="grid grid-cols-1 gap-2">
                <Link href="/" onClick={toggleMenu} className="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 text-white font-black text-lg transition-all border border-transparent hover:border-white/10">
                    <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-[#669BBC]"><Home className="w-6 h-6" /></div>
                    Inicio
                </Link>

                {isLoggedIn ? (
                    <>
                        <Link href="/mi-renta" onClick={toggleMenu} className="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 text-white font-black text-lg transition-all border border-transparent hover:border-white/10">
                            <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400"><Building2 className="w-6 h-6" /></div>
                            {userMode === "inquilino" ? "Mi Renta" : "Mis Propiedades"}
                        </Link>
                        
                        <Link href="/mensajes" onClick={toggleMenu} className="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 text-white font-black text-lg transition-all border border-transparent hover:border-white/10">
                            <div className="flex items-center gap-4">
                                <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-indigo-400"><MessageCircle className="w-6 h-6" /></div>
                                Mensajes
                            </div>
                        </Link>

                        <div className="h-px bg-white/10 my-4 mx-4" />

                        <div className="px-4 py-2">
                             <div className="grid grid-cols-2 gap-3">
                                <Link href="/perfil" onClick={toggleMenu} className="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl bg-white/5 text-white text-xs font-black uppercase tracking-wider hover:bg-white/10 transition-all border border-white/5">
                                    <Settings className="w-5 h-5 text-gray-400" />
                                    Ajustes
                                </Link>
                                <button className="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl bg-red-500/10 text-red-500 text-xs font-black uppercase tracking-wider hover:bg-red-500 hover:text-white transition-all border border-red-500/20">
                                    <LogOut className="w-5 h-5" />
                                    Salir
                                </button>
                             </div>
                        </div>

                        <button
                            onClick={() => { onModeSwitch(); toggleMenu(); }}
                            className="flex items-center justify-center gap-2 p-4 mt-2 text-sm font-bold text-blue-300 border border-blue-300/20 rounded-2xl bg-blue-300/5 transition-all active:scale-95"
                        >
                            Cambiar a modo {userMode === "inquilino" ? "Propietario" : "Inquilino"}
                        </button>
                    </>
                ) : (
                    <div className="px-4 py-2 grid grid-cols-2 gap-4 mt-2">
                        <button 
                            onClick={() => { onLoginClick(); toggleMenu(); }}
                            className="flex items-center justify-center p-4 rounded-2xl bg-white/10 text-white font-black uppercase tracking-widest text-xs border border-white/10 transition-all"
                        >
                            Entrar
                        </button>
                        <button 
                            onClick={() => { onLoginClick(); toggleMenu(); }}
                            className="flex items-center justify-center p-4 rounded-2xl bg-[#C1121F] text-white font-black uppercase tracking-widest text-xs shadow-lg transition-all"
                        >
                            Unirme
                        </button>
                    </div>
                )}
           </div>
        </div>
      )}
    </header>
  )
}