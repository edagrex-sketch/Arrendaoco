"use client"

import Image from "next/image"
import Link from "next/link"
import { Button } from "@/components/ui/button"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import { User, LogIn, Menu, MessageCircle, Home, Plus } from "lucide-react"

interface NavbarProps {
  isLoggedIn: boolean
  onLoginClick: () => void
  userMode: "inquilino" | "propietario"
  onModeSwitch: () => void
}

export function Navbar({ isLoggedIn, onLoginClick, userMode, onModeSwitch }: NavbarProps) {
  return (
    <header className="sticky top-0 z-50 w-full bg-primary">
      <div className="container mx-auto flex h-16 items-center justify-between px-4">
        <div className="flex items-center gap-3">
          <Image
            src="/logo.jpeg"
            alt="ArrendaOco Logo"
            width={40}
            height={40}
            className="rounded-md bg-primary-foreground"
          />
          <span className="text-xl font-semibold text-primary-foreground">ArrendaOco</span>
        </div>

        <div className="flex items-center gap-4">
          {isLoggedIn && userMode === "propietario" && (
            <Link href="/publicar">
              <Button
                variant="secondary"
                className="flex items-center gap-2"
              >
                <Plus className="h-4 w-4" />
                <span className="hidden sm:inline">Publicar Propiedad</span>
                <span className="sm:hidden">Publicar</span>
              </Button>
            </Link>
          )}

          {isLoggedIn ? (
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button variant="ghost" className="flex items-center gap-2 text-primary-foreground hover:bg-accent hover:text-accent-foreground">
                  <Menu className="h-5 w-5" />
                  <div className="flex h-8 w-8 items-center justify-center rounded-full bg-primary-foreground text-primary">
                    <User className="h-5 w-5" />
                  </div>
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end" className="w-56">
                <DropdownMenuItem asChild className="font-medium">
                  <Link href="/">
                    <Home className="mr-2 h-4 w-4" />
                    Inicio
                  </Link>
                </DropdownMenuItem>
                <DropdownMenuItem asChild>
                  <Link href="/mensajes">
                    <MessageCircle className="mr-2 h-4 w-4" />
                    Mis Mensajes
                  </Link>
                </DropdownMenuItem>
                <DropdownMenuItem asChild>
                  <Link href="/mi-renta">
                    {userMode === "inquilino" ? "Mi Renta" : "Mis Propiedades"}
                  </Link>
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem onClick={onModeSwitch} className="cursor-pointer font-medium text-accent">
                  {userMode === "inquilino" ? "Cambiar a Propietario" : "Cambiar a Inquilino"}
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem className="text-destructive">
                  Cerrar Sesión
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          ) : (
            <Button
              onClick={onLoginClick}
              variant="secondary"
              className="flex items-center gap-2"
            >
              <LogIn className="h-4 w-4" />
              Iniciar Sesión
            </Button>
          )}
        </div>
      </div>
    </header>
  )
}
