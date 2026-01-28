"use client"

import { useState } from "react"
import Image from "next/image"
import Link from "next/link"
import { useParams } from "next/navigation"
import { Navbar } from "@/components/navbar"
import { Footer } from "@/components/footer"
import { AuthModal } from "@/components/auth-modal"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import {
  ArrowLeft,
  MapPin,
  Bed,
  Bath,
  Square,
  Droplets,
  Zap,
  Wifi,
  Car,
  MessageCircle,
  Star,
  Calendar,
  Shield,
  FileText,
} from "lucide-react"

const mockProperty = {
  id: "1",
  title: "Casa Amplia en Centro",
  price: 4500,
  location: "Centro, Ocosingo",
  address: "Calle Hidalgo #45, Centro, Ocosingo, Chiapas",
  category: "casa",
  bedrooms: 3,
  bathrooms: 2,
  area: 120,
  description:
    "Hermosa casa ubicada en el corazón de Ocosingo. Cuenta con amplios espacios, iluminación natural y acabados de primera calidad. Ideal para familias. Incluye cochera techada para 2 vehículos y patio trasero con área de lavado.",
  images: [
    "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&auto=format&fit=crop&q=60",
    "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&auto=format&fit=crop&q=60",
    "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=60",
  ],
  amenities: {
    water: true,
    electricity: true,
    wifi: false,
    parking: true,
  },
  owner: {
    name: "María García",
    avatar: "",
    rating: 4.8,
    properties: 3,
    verified: true,
  },
  reviews: [
    {
      id: "r1",
      author: "Juan Pérez",
      avatar: "",
      rating: 5,
      date: "2025-10-15",
      comment:
        "Excelente propiedad, muy limpia y bien ubicada. La dueña siempre atenta a cualquier situación.",
      verified: true,
    },
    {
      id: "r2",
      author: "Ana López",
      avatar: "",
      rating: 4,
      date: "2025-06-20",
      comment:
        "Buena casa, amplia y cómoda. Solo sugeriría mejorar la presión del agua en el segundo piso.",
      verified: true,
    },
  ],
}

export default function PropertyDetailPage() {
  const params = useParams()
  const [isLoggedIn, setIsLoggedIn] = useState(true)
  const [showAuthModal, setShowAuthModal] = useState(false)
  const [userMode, setUserMode] = useState<"inquilino" | "propietario">("inquilino")
  const [selectedImage, setSelectedImage] = useState(0)

  const handleContactOwner = () => {
    if (!isLoggedIn) {
      setShowAuthModal(true)
    }
  }

  const handleLogin = () => {
    setIsLoggedIn(true)
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

      <main className="flex-1 px-4 py-8">
        <div className="container mx-auto max-w-6xl">
          {/* Back Button */}
          <Link
            href="/"
            className="mb-6 inline-flex items-center gap-2 text-muted-foreground hover:text-foreground"
          >
            <ArrowLeft className="h-4 w-4" />
            Volver a búsqueda
          </Link>

          <div className="grid gap-8 lg:grid-cols-3">
            {/* Main Content */}
            <div className="lg:col-span-2">
              {/* Image Gallery */}
              <div className="mb-6 overflow-hidden rounded-xl">
                <div className="relative aspect-video">
                  <Image
                    src={mockProperty.images[selectedImage] || "/placeholder.svg"}
                    alt={mockProperty.title}
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="mt-2 flex gap-2">
                  {mockProperty.images.map((img, idx) => (
                    <button
                      key={img}
                      onClick={() => setSelectedImage(idx)}
                      className={`relative aspect-video w-24 overflow-hidden rounded-lg border-2 ${
                        selectedImage === idx ? "border-accent" : "border-transparent"
                      }`}
                    >
                      <Image src={img || "/placeholder.svg"} alt="" fill className="object-cover" />
                    </button>
                  ))}
                </div>
              </div>

              {/* Property Info */}
              <div className="mb-6">
                <div className="mb-2 flex flex-wrap items-center gap-2">
                  <Badge className="capitalize">{mockProperty.category}</Badge>
                  <Badge variant="outline" className="flex items-center gap-1">
                    <Shield className="h-3 w-3" />
                    Verificado
                  </Badge>
                </div>
                <h1 className="mb-2 text-3xl font-bold text-foreground">
                  {mockProperty.title}
                </h1>
                <div className="flex items-center gap-1 text-muted-foreground">
                  <MapPin className="h-4 w-4" />
                  <span>{mockProperty.address}</span>
                </div>
              </div>

              {/* Stats */}
              <div className="mb-6 grid grid-cols-3 gap-4">
                <Card>
                  <CardContent className="flex flex-col items-center p-4">
                    <Bed className="mb-2 h-6 w-6 text-accent" />
                    <span className="text-2xl font-bold">{mockProperty.bedrooms}</span>
                    <span className="text-sm text-muted-foreground">Recámaras</span>
                  </CardContent>
                </Card>
                <Card>
                  <CardContent className="flex flex-col items-center p-4">
                    <Bath className="mb-2 h-6 w-6 text-accent" />
                    <span className="text-2xl font-bold">{mockProperty.bathrooms}</span>
                    <span className="text-sm text-muted-foreground">Baños</span>
                  </CardContent>
                </Card>
                <Card>
                  <CardContent className="flex flex-col items-center p-4">
                    <Square className="mb-2 h-6 w-6 text-accent" />
                    <span className="text-2xl font-bold">{mockProperty.area}</span>
                    <span className="text-sm text-muted-foreground">m²</span>
                  </CardContent>
                </Card>
              </div>

              {/* Description */}
              <Card className="mb-6">
                <CardContent className="p-6">
                  <h2 className="mb-4 text-xl font-semibold text-card-foreground">
                    Descripción
                  </h2>
                  <p className="leading-relaxed text-muted-foreground">
                    {mockProperty.description}
                  </p>
                </CardContent>
              </Card>

              {/* Amenities */}
              <Card className="mb-6">
                <CardContent className="p-6">
                  <h2 className="mb-4 text-xl font-semibold text-card-foreground">
                    Servicios Incluidos
                  </h2>
                  <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div
                      className={`flex items-center gap-2 ${
                        mockProperty.amenities.water
                          ? "text-foreground"
                          : "text-muted-foreground line-through"
                      }`}
                    >
                      <Droplets className="h-5 w-5" />
                      <span>Agua</span>
                    </div>
                    <div
                      className={`flex items-center gap-2 ${
                        mockProperty.amenities.electricity
                          ? "text-foreground"
                          : "text-muted-foreground line-through"
                      }`}
                    >
                      <Zap className="h-5 w-5" />
                      <span>Luz</span>
                    </div>
                    <div
                      className={`flex items-center gap-2 ${
                        mockProperty.amenities.wifi
                          ? "text-foreground"
                          : "text-muted-foreground line-through"
                      }`}
                    >
                      <Wifi className="h-5 w-5" />
                      <span>Internet</span>
                    </div>
                    <div
                      className={`flex items-center gap-2 ${
                        mockProperty.amenities.parking
                          ? "text-foreground"
                          : "text-muted-foreground line-through"
                      }`}
                    >
                      <Car className="h-5 w-5" />
                      <span>Estacionamiento</span>
                    </div>
                  </div>
                </CardContent>
              </Card>

              {/* Reviews */}
              <Card>
                <CardContent className="p-6">
                  <div className="mb-4 flex items-center justify-between">
                    <h2 className="text-xl font-semibold text-card-foreground">
                      Reseñas Verificadas
                    </h2>
                    <div className="flex items-center gap-1">
                      <Star className="h-5 w-5 fill-yellow-400 text-yellow-400" />
                      <span className="font-semibold">
                        {mockProperty.owner.rating}
                      </span>
                      <span className="text-muted-foreground">
                        ({mockProperty.reviews.length} reseñas)
                      </span>
                    </div>
                  </div>

                  <div className="space-y-4">
                    {mockProperty.reviews.map((review) => (
                      <div
                        key={review.id}
                        className="border-b border-border pb-4 last:border-0 last:pb-0"
                      >
                        <div className="mb-2 flex items-start justify-between">
                          <div className="flex items-center gap-3">
                            <Avatar>
                              <AvatarImage src={review.avatar || "/placeholder.svg"} />
                              <AvatarFallback>
                                {review.author
                                  .split(" ")
                                  .map((n) => n[0])
                                  .join("")}
                              </AvatarFallback>
                            </Avatar>
                            <div>
                              <div className="flex items-center gap-2">
                                <span className="font-medium">{review.author}</span>
                                {review.verified && (
                                  <Badge variant="outline" className="text-xs">
                                    Inquilino Verificado
                                  </Badge>
                                )}
                              </div>
                              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                                <Calendar className="h-3 w-3" />
                                <span>
                                  {new Date(review.date).toLocaleDateString("es-MX", {
                                    year: "numeric",
                                    month: "long",
                                  })}
                                </span>
                              </div>
                            </div>
                          </div>
                          <div className="flex items-center gap-1">
                            {[...Array(5)].map((_, i) => (
                              <Star
                                key={i}
                                className={`h-4 w-4 ${
                                  i < review.rating
                                    ? "fill-yellow-400 text-yellow-400"
                                    : "text-muted-foreground"
                                }`}
                              />
                            ))}
                          </div>
                        </div>
                        <p className="text-muted-foreground">{review.comment}</p>
                      </div>
                    ))}
                  </div>
                </CardContent>
              </Card>
            </div>

            {/* Sidebar */}
            <div className="lg:col-span-1">
              <div className="sticky top-24">
                <Card>
                  <CardContent className="p-6">
                    <div className="mb-4 text-center">
                      <span className="text-3xl font-bold text-accent">
                        ${mockProperty.price.toLocaleString()}
                      </span>
                      <span className="text-muted-foreground">/mes</span>
                    </div>

                    <div className="mb-6 border-t border-b border-border py-4">
                      <div className="flex items-center gap-4">
                        <Avatar className="h-14 w-14">
                          <AvatarImage src={mockProperty.owner.avatar || "/placeholder.svg"} />
                          <AvatarFallback>
                            {mockProperty.owner.name
                              .split(" ")
                              .map((n) => n[0])
                              .join("")}
                          </AvatarFallback>
                        </Avatar>
                        <div>
                          <div className="flex items-center gap-2">
                            <span className="font-semibold">
                              {mockProperty.owner.name}
                            </span>
                            {mockProperty.owner.verified && (
                              <Shield className="h-4 w-4 text-accent" />
                            )}
                          </div>
                          <div className="flex items-center gap-2 text-sm text-muted-foreground">
                            <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                            <span>{mockProperty.owner.rating}</span>
                            <span>·</span>
                            <span>{mockProperty.owner.properties} propiedades</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <Link href={`/rentar/${params.id}`} className="block">
                      <Button className="mb-3 w-full gap-2 bg-accent text-accent-foreground hover:bg-accent/90">
                        <FileText className="h-5 w-5" />
                        Solicitar Renta
                      </Button>
                    </Link>

                    <Link href="/mensajes">
                      <Button
                        variant="outline"
                        className="mb-3 w-full gap-2 bg-transparent"
                      >
                        <MessageCircle className="h-5 w-5" />
                        Contactar Propietario
                      </Button>
                    </Link>

                    <p className="text-center text-xs text-muted-foreground">
                      Inicia el proceso de renta o envía un mensaje al propietario
                    </p>
                  </CardContent>
                </Card>
              </div>
            </div>
          </div>
        </div>
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
