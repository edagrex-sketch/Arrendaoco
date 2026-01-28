"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { Navbar } from "@/components/navbar"
import { SearchBar } from "@/components/search-bar"
import { PropertyCard, type Property } from "@/components/property-card"
import { Footer } from "@/components/footer"
import { AuthModal } from "@/components/auth-modal"

const mockProperties: Property[] = [
  {
    id: "1",
    title: "Casa Amplia en Centro",
    price: 4500,
    location: "Centro, Ocosingo",
    category: "casa",
    bedrooms: 3,
    bathrooms: 2,
    area: 120,
    image: "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&auto=format&fit=crop&q=60",
    featured: true,
  },
  {
    id: "2",
    title: "Departamento Moderno",
    price: 3200,
    location: "Las Margaritas, Ocosingo",
    category: "departamento",
    bedrooms: 2,
    bathrooms: 1,
    area: 75,
    image: "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&auto=format&fit=crop&q=60",
  },
  {
    id: "3",
    title: "Cuarto Amueblado",
    price: 1800,
    location: "Barrio San José, Ocosingo",
    category: "cuarto",
    bedrooms: 1,
    bathrooms: 1,
    area: 25,
    image: "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&auto=format&fit=crop&q=60",
  },
  {
    id: "4",
    title: "Casa con Jardín",
    price: 5500,
    location: "Colonia Nueva, Ocosingo",
    category: "casa",
    bedrooms: 4,
    bathrooms: 2,
    area: 180,
    image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&auto=format&fit=crop&q=60",
    featured: true,
  },
  {
    id: "5",
    title: "Depa cerca del Mercado",
    price: 2800,
    location: "Centro, Ocosingo",
    category: "departamento",
    bedrooms: 2,
    bathrooms: 1,
    area: 65,
    image: "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=60",
  },
  {
    id: "6",
    title: "Local Comercial Céntrico",
    price: 6000,
    location: "Av. Principal, Ocosingo",
    category: "local",
    bedrooms: 0,
    bathrooms: 1,
    area: 50,
    image: "https://images.unsplash.com/photo-1604328698692-f76ea9498e76?w=800&auto=format&fit=crop&q=60",
  },
]

export default function HomePage() {
  const router = useRouter()
  const [isLoggedIn, setIsLoggedIn] = useState(true)
  const [showAuthModal, setShowAuthModal] = useState(false)
  const [userMode, setUserMode] = useState<"inquilino" | "propietario">("inquilino")
  const [pendingPropertyId, setPendingPropertyId] = useState<string | null>(null)

  const handleViewProperty = (propertyId: string) => {
    if (!isLoggedIn) {
      setPendingPropertyId(propertyId)
      setShowAuthModal(true)
    } else {
      router.push(`/propiedad/${propertyId}`)
    }
  }

  const handleLogin = () => {
    setIsLoggedIn(true)
    if (pendingPropertyId) {
      router.push(`/propiedad/${pendingPropertyId}`)
      setPendingPropertyId(null)
    }
  }

  const handleModeSwitch = () => {
    setUserMode(userMode === "inquilino" ? "propietario" : "inquilino")
  }

  return (
    <div className="flex min-h-screen flex-col">
      <Navbar
        isLoggedIn={isLoggedIn}
        onLoginClick={() => setShowAuthModal(true)}
        userMode={userMode}
        onModeSwitch={handleModeSwitch}
      />

      <main className="flex-1">
        {/* Hero Section with Search */}
        <section className="bg-primary/5 px-4 py-12">
          <div className="container mx-auto max-w-4xl">
            <SearchBar />
          </div>
        </section>

        {/* Properties Section */}
        <section className="px-4 py-12">
          <div className="container mx-auto">
            <div className="mb-8 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-foreground">
                Propiedades Disponibles
              </h2>
              <span className="text-muted-foreground">
                {mockProperties.length} resultados
              </span>
            </div>

            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
              {mockProperties.map((property) => (
                <PropertyCard
                  key={property.id}
                  property={property}
                  onViewClick={handleViewProperty}
                />
              ))}
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="bg-primary px-4 py-16">
          <div className="container mx-auto text-center">
            <h2 className="mb-4 text-3xl font-bold text-primary-foreground">
              ¿Tienes una propiedad para rentar?
            </h2>
            <p className="mb-8 text-lg text-primary-foreground/80">
              Publica tu inmueble y encuentra inquilinos confiables con historial verificado
            </p>
            <button
              onClick={() => setShowAuthModal(true)}
              className="rounded-lg bg-card px-8 py-3 font-semibold text-card-foreground transition-colors hover:bg-accent hover:text-accent-foreground"
            >
              Publicar Inmueble
            </button>
          </div>
        </section>
      </main>

      <Footer />

      <AuthModal
        isOpen={showAuthModal}
        onClose={() => setShowAuthModal(false)}
        onLogin={handleLogin}
      />
    </div>
  )
}
