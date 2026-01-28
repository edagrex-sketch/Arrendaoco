import Image from "next/image"
import { Mail, Phone, MapPin } from "lucide-react"

export function Footer() {
  return (
    <footer className="bg-primary text-primary-foreground">
      <div className="container mx-auto px-4 py-12">
        <div className="grid gap-8 md:grid-cols-4">
          <div>
            <div className="mb-4 flex items-center gap-3">
              <Image
                src="/logo.jpeg"
                alt="ArrendaOco Logo"
                width={40}
                height={40}
                className="rounded-md bg-primary-foreground"
              />
              <span className="text-xl font-semibold">ArrendaOco</span>
            </div>
            <p className="text-sm text-primary-foreground/80">
              Tu plataforma de confianza para encontrar y rentar propiedades en Ocosingo.
            </p>
          </div>

          <div>
            <h4 className="mb-4 font-semibold">Explorar</h4>
            <ul className="space-y-2 text-sm text-primary-foreground/80">
              <li><a href="#" className="hover:text-primary-foreground">Buscar Propiedades</a></li>
              <li><a href="#" className="hover:text-primary-foreground">Publicar Inmueble</a></li>
              <li><a href="#" className="hover:text-primary-foreground">Como Funciona</a></li>
            </ul>
          </div>

          <div>
            <h4 className="mb-4 font-semibold">Legal</h4>
            <ul className="space-y-2 text-sm text-primary-foreground/80">
              <li><a href="#" className="hover:text-primary-foreground">Términos de Servicio</a></li>
              <li><a href="#" className="hover:text-primary-foreground">Política de Privacidad</a></li>
              <li><a href="#" className="hover:text-primary-foreground">Aviso Legal</a></li>
            </ul>
          </div>

          <div>
            <h4 className="mb-4 font-semibold">Contacto</h4>
            <ul className="space-y-2 text-sm text-primary-foreground/80">
              <li className="flex items-center gap-2">
                <Mail className="h-4 w-4" />
                <span>contacto@arrendaoco.com</span>
              </li>
              <li className="flex items-center gap-2">
                <Phone className="h-4 w-4" />
                <span>+52 919 123 4567</span>
              </li>
              <li className="flex items-center gap-2">
                <MapPin className="h-4 w-4" />
                <span>Ocosingo, Chiapas</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-8 border-t border-primary-foreground/20 pt-8 text-center text-sm text-primary-foreground/60">
          <p>2026 ArrendaOco. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  )
}
