"use client"

import { useState, Suspense } from "react"
import Image from "next/image"
import Link from "next/link"
import { useSearchParams } from "next/navigation"
import { Navbar } from "@/components/navbar"
import { Footer } from "@/components/footer"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Progress } from "@/components/ui/progress"
import {
  MapPin,
  Calendar,
  CreditCard,
  MessageCircle,
  FileText,
  AlertCircle,
  CheckCircle,
  Clock,
  Home,
  Phone,
  Mail,
  Star,
  Download,
  Bell,
  Plus,
} from "lucide-react"

// Mock data for renter view
const mockRentalInquillino = {
  id: "rental1",
  property: {
    id: "1",
    title: "Casa Amplia en Centro",
    address: "Calle Hidalgo #45, Centro, Ocosingo, Chiapas",
    image: "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&auto=format&fit=crop&q=60",
    price: 4500,
  },
  owner: {
    name: "María García",
    avatar: "",
    phone: "919-123-4567",
    email: "maria@ejemplo.com",
    rating: 4.8,
  },
  contract: {
    startDate: "2026-01-01",
    endDate: "2026-12-31",
    monthlyRent: 4500,
    deposit: 4500,
    paymentDay: 5,
  },
  status: "active",
  payments: [
    { id: "p1", month: "Enero 2026", amount: 4500, status: "paid", date: "2026-01-03" },
    { id: "p2", month: "Febrero 2026", amount: 4500, status: "pending", dueDate: "2026-02-05" },
  ],
}

// Mock data for owner view
const mockPropertiesPropietario = [
  {
    id: "prop1",
    property: {
      id: "1",
      title: "Casa Amplia en Centro",
      address: "Calle Hidalgo #45, Centro, Ocosingo, Chiapas",
      image: "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&auto=format&fit=crop&q=60",
      price: 4500,
    },
    tenant: {
      name: "Juan Pérez",
      avatar: "",
      phone: "919-987-6543",
      email: "juan@ejemplo.com",
      rating: 4.5,
    },
    contract: {
      startDate: "2026-01-01",
      endDate: "2026-12-31",
      monthlyRent: 4500,
    },
    status: "active",
    payments: [
      { id: "p1", month: "Enero 2026", amount: 4500, status: "paid", date: "2026-01-03" },
      { id: "p2", month: "Febrero 2026", amount: 4500, status: "pending", dueDate: "2026-02-05" },
    ],
  },
  {
    id: "prop2",
    property: {
      id: "4",
      title: "Casa con Jardín",
      address: "Colonia Nueva #23, Ocosingo, Chiapas",
      image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&auto=format&fit=crop&q=60",
      price: 5500,
    },
    tenant: null,
    status: "available",
    views: 24,
    inquiries: 5,
  },
]

function MiRentaContent() {
  const searchParams = useSearchParams()
  const showSuccess = searchParams.get("success") === "true"
  const [userMode, setUserMode] = useState<"inquilino" | "propietario">("inquilino")

  const handleModeSwitch = () => {
    setUserMode(userMode === "inquilino" ? "propietario" : "inquilino")
  }

  const getStatusBadge = (status: string) => {
    switch (status) {
      case "active":
        return <Badge className="bg-green-500 text-white">Activa</Badge>
      case "pending":
        return <Badge className="bg-yellow-500 text-white">Pendiente</Badge>
      case "available":
        return <Badge className="bg-blue-500 text-white">Disponible</Badge>
      case "paid":
        return <Badge className="bg-green-500 text-white">Pagado</Badge>
      default:
        return <Badge variant="outline">{status}</Badge>
    }
  }

  const getPaymentStatusIcon = (status: string) => {
    switch (status) {
      case "paid":
        return <CheckCircle className="h-5 w-5 text-green-500" />
      case "pending":
        return <Clock className="h-5 w-5 text-yellow-500" />
      case "overdue":
        return <AlertCircle className="h-5 w-5 text-red-500" />
      default:
        return null
    }
  }

  const contractProgress = () => {
    const start = new Date(mockRentalInquillino.contract.startDate)
    const end = new Date(mockRentalInquillino.contract.endDate)
    const now = new Date()
    const total = end.getTime() - start.getTime()
    const elapsed = now.getTime() - start.getTime()
    return Math.min(Math.max((elapsed / total) * 100, 0), 100)
  }

  return (
    <div className="flex min-h-screen flex-col">
      <Navbar
        isLoggedIn={true}
        onLoginClick={() => {}}
        userMode={userMode}
        onModeSwitch={handleModeSwitch}
      />

      <main className="flex-1 bg-background px-4 py-8">
        <div className="container mx-auto max-w-6xl">
          {/* Success Alert */}
          {showSuccess && (
            <div className="mb-6 flex items-center gap-3 rounded-lg border border-green-500/20 bg-green-500/10 p-4">
              <CheckCircle className="h-5 w-5 text-green-600" />
              <div>
                <p className="font-medium text-green-800">Solicitud enviada exitosamente</p>
                <p className="text-sm text-green-700">
                  El propietario revisará tu solicitud y te contactará pronto.
                </p>
              </div>
            </div>
          )}

          <div className="mb-8 flex items-center justify-between">
            <div>
              <h1 className="text-3xl font-bold text-foreground">
                {userMode === "inquilino" ? "Mi Renta" : "Mis Propiedades"}
              </h1>
              <p className="text-muted-foreground">
                {userMode === "inquilino"
                  ? "Gestiona tu contrato de renta y pagos"
                  : "Administra tus propiedades e inquilinos"}
              </p>
            </div>
            {userMode === "propietario" && (
              <Button className="bg-primary text-primary-foreground hover:bg-accent">
                <Plus className="mr-2 h-4 w-4" />
                Publicar Propiedad
              </Button>
            )}
          </div>

          {/* INQUILINO VIEW */}
          {userMode === "inquilino" && (
            <div className="grid gap-6 lg:grid-cols-3">
              {/* Main Content */}
              <div className="space-y-6 lg:col-span-2">
                {/* Property Card */}
                <Card>
                  <CardContent className="p-0">
                    <div className="flex flex-col sm:flex-row">
                      <div className="relative h-48 w-full sm:h-auto sm:w-48">
                        <Image
                          src={mockRentalInquillino.property.image || "/placeholder.svg"}
                          alt={mockRentalInquillino.property.title}
                          fill
                          className="rounded-t-lg object-cover sm:rounded-l-lg sm:rounded-tr-none"
                        />
                      </div>
                      <div className="flex-1 p-6">
                        <div className="mb-2 flex items-center justify-between">
                          <h2 className="text-xl font-semibold text-card-foreground">
                            {mockRentalInquillino.property.title}
                          </h2>
                          {getStatusBadge(mockRentalInquillino.status)}
                        </div>
                        <div className="mb-4 flex items-center gap-1 text-muted-foreground">
                          <MapPin className="h-4 w-4" />
                          <span className="text-sm">{mockRentalInquillino.property.address}</span>
                        </div>
                        <div className="flex items-center justify-between">
                          <div>
                            <p className="text-2xl font-bold text-accent">
                              ${mockRentalInquillino.property.price.toLocaleString()}
                            </p>
                            <p className="text-sm text-muted-foreground">/mes</p>
                          </div>
                          <Link href={`/propiedad/${mockRentalInquillino.property.id}`}>
                            <Button variant="outline" size="sm">
                              <Home className="mr-2 h-4 w-4" />
                              Ver Propiedad
                            </Button>
                          </Link>
                        </div>
                      </div>
                    </div>
                  </CardContent>
                </Card>

                {/* Contract Progress */}
                <Card>
                  <CardHeader>
                    <CardTitle className="flex items-center gap-2">
                      <Calendar className="h-5 w-5 text-accent" />
                      Progreso del Contrato
                    </CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div className="mb-4">
                      <Progress value={contractProgress()} className="h-3" />
                    </div>
                    <div className="flex justify-between text-sm">
                      <div>
                        <p className="text-muted-foreground">Inicio</p>
                        <p className="font-medium">
                          {new Date(mockRentalInquillino.contract.startDate).toLocaleDateString(
                            "es-MX",
                            { day: "numeric", month: "short", year: "numeric" }
                          )}
                        </p>
                      </div>
                      <div className="text-right">
                        <p className="text-muted-foreground">Fin</p>
                        <p className="font-medium">
                          {new Date(mockRentalInquillino.contract.endDate).toLocaleDateString(
                            "es-MX",
                            { day: "numeric", month: "short", year: "numeric" }
                          )}
                        </p>
                      </div>
                    </div>
                  </CardContent>
                </Card>

                {/* Payments */}
                <Card>
                  <CardHeader>
                    <CardTitle className="flex items-center gap-2">
                      <CreditCard className="h-5 w-5 text-accent" />
                      Historial de Pagos
                    </CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div className="space-y-4">
                      {mockRentalInquillino.payments.map((payment) => (
                        <div
                          key={payment.id}
                          className="flex items-center justify-between rounded-lg border border-border p-4"
                        >
                          <div className="flex items-center gap-3">
                            {getPaymentStatusIcon(payment.status)}
                            <div>
                              <p className="font-medium text-card-foreground">{payment.month}</p>
                              <p className="text-sm text-muted-foreground">
                                {payment.status === "paid"
                                  ? `Pagado el ${new Date(payment.date!).toLocaleDateString("es-MX")}`
                                  : `Vence el ${new Date(payment.dueDate!).toLocaleDateString("es-MX")}`}
                              </p>
                            </div>
                          </div>
                          <div className="flex items-center gap-4">
                            <span className="font-semibold">
                              ${payment.amount.toLocaleString()} MXN
                            </span>
                            {payment.status === "pending" && (
                              <Button size="sm" className="bg-accent text-accent-foreground">
                                Pagar
                              </Button>
                            )}
                          </div>
                        </div>
                      ))}
                    </div>
                  </CardContent>
                </Card>
              </div>

              {/* Sidebar */}
              <div className="space-y-6">
                {/* Owner Info */}
                <Card>
                  <CardHeader>
                    <CardTitle className="text-lg">Propietario</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div className="flex items-center gap-4">
                      <Avatar className="h-14 w-14">
                        <AvatarImage src={mockRentalInquillino.owner.avatar || "/placeholder.svg"} />
                        <AvatarFallback>
                          {mockRentalInquillino.owner.name
                            .split(" ")
                            .map((n) => n[0])
                            .join("")}
                        </AvatarFallback>
                      </Avatar>
                      <div>
                        <p className="font-semibold text-card-foreground">
                          {mockRentalInquillino.owner.name}
                        </p>
                        <div className="flex items-center gap-1 text-sm text-muted-foreground">
                          <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                          <span>{mockRentalInquillino.owner.rating}</span>
                        </div>
                      </div>
                    </div>
                    <div className="mt-4 space-y-2">
                      <div className="flex items-center gap-2 text-sm text-muted-foreground">
                        <Phone className="h-4 w-4" />
                        <span>{mockRentalInquillino.owner.phone}</span>
                      </div>
                      <div className="flex items-center gap-2 text-sm text-muted-foreground">
                        <Mail className="h-4 w-4" />
                        <span>{mockRentalInquillino.owner.email}</span>
                      </div>
                    </div>
                    <Link href="/mensajes" className="mt-4 block">
                      <Button className="w-full bg-transparent" variant="outline">
                        <MessageCircle className="mr-2 h-4 w-4" />
                        Enviar Mensaje
                      </Button>
                    </Link>
                  </CardContent>
                </Card>

                {/* Quick Actions */}
                <Card>
                  <CardHeader>
                    <CardTitle className="text-lg">Acciones Rápidas</CardTitle>
                  </CardHeader>
                  <CardContent className="space-y-2">
                    <Button variant="outline" className="w-full justify-start bg-transparent">
                      <FileText className="mr-2 h-4 w-4" />
                      Ver Contrato
                    </Button>
                    <Button variant="outline" className="w-full justify-start bg-transparent">
                      <Download className="mr-2 h-4 w-4" />
                      Descargar Recibos
                    </Button>
                    <Button variant="outline" className="w-full justify-start bg-transparent">
                      <AlertCircle className="mr-2 h-4 w-4" />
                      Reportar Problema
                    </Button>
                  </CardContent>
                </Card>

                {/* Next Payment Reminder */}
                <Card className="border-accent/20 bg-accent/5">
                  <CardContent className="p-4">
                    <div className="flex items-start gap-3">
                      <Bell className="h-5 w-5 text-accent" />
                      <div>
                        <p className="font-medium text-card-foreground">Próximo pago</p>
                        <p className="text-sm text-muted-foreground">
                          Tu renta de Febrero vence el día 5. Monto: $4,500 MXN
                        </p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              </div>
            </div>
          )}

          {/* PROPIETARIO VIEW */}
          {userMode === "propietario" && (
            <div className="space-y-6">
              {/* Stats Overview */}
              <div className="grid gap-4 sm:grid-cols-3">
                <Card>
                  <CardContent className="p-4">
                    <div className="flex items-center gap-3">
                      <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-accent/10">
                        <Home className="h-5 w-5 text-accent" />
                      </div>
                      <div>
                        <p className="text-2xl font-bold">2</p>
                        <p className="text-sm text-muted-foreground">Propiedades</p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
                <Card>
                  <CardContent className="p-4">
                    <div className="flex items-center gap-3">
                      <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-green-500/10">
                        <CheckCircle className="h-5 w-5 text-green-500" />
                      </div>
                      <div>
                        <p className="text-2xl font-bold">1</p>
                        <p className="text-sm text-muted-foreground">Rentadas</p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
                <Card>
                  <CardContent className="p-4">
                    <div className="flex items-center gap-3">
                      <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-accent/10">
                        <CreditCard className="h-5 w-5 text-accent" />
                      </div>
                      <div>
                        <p className="text-2xl font-bold">$4,500</p>
                        <p className="text-sm text-muted-foreground">Ingresos/mes</p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              </div>

              {/* Properties List */}
              <Tabs defaultValue="all" className="w-full">
                <TabsList>
                  <TabsTrigger value="all">Todas</TabsTrigger>
                  <TabsTrigger value="rented">Rentadas</TabsTrigger>
                  <TabsTrigger value="available">Disponibles</TabsTrigger>
                </TabsList>

                <TabsContent value="all" className="mt-6 space-y-4">
                  {mockPropertiesPropietario.map((item) => (
                    <Card key={item.id}>
                      <CardContent className="p-0">
                        <div className="flex flex-col lg:flex-row">
                          <div className="relative h-48 w-full lg:h-auto lg:w-56">
                            <Image
                              src={item.property.image || "/placeholder.svg"}
                              alt={item.property.title}
                              fill
                              className="rounded-t-lg object-cover lg:rounded-l-lg lg:rounded-tr-none"
                            />
                          </div>
                          <div className="flex-1 p-6">
                            <div className="mb-2 flex items-start justify-between">
                              <div>
                                <h3 className="text-lg font-semibold text-card-foreground">
                                  {item.property.title}
                                </h3>
                                <div className="flex items-center gap-1 text-sm text-muted-foreground">
                                  <MapPin className="h-4 w-4" />
                                  <span>{item.property.address}</span>
                                </div>
                              </div>
                              {getStatusBadge(item.status)}
                            </div>

                            <p className="mb-4 text-xl font-bold text-accent">
                              ${item.property.price.toLocaleString()}/mes
                            </p>

                            {item.tenant ? (
                              <div className="flex items-center justify-between rounded-lg bg-muted/50 p-3">
                                <div className="flex items-center gap-3">
                                  <Avatar>
                                    <AvatarImage src={item.tenant.avatar || "/placeholder.svg"} />
                                    <AvatarFallback>
                                      {item.tenant.name
                                        .split(" ")
                                        .map((n) => n[0])
                                        .join("")}
                                    </AvatarFallback>
                                  </Avatar>
                                  <div>
                                    <p className="font-medium">{item.tenant.name}</p>
                                    <p className="text-sm text-muted-foreground">
                                      Inquilino desde{" "}
                                      {new Date(item.contract!.startDate).toLocaleDateString(
                                        "es-MX",
                                        { month: "short", year: "numeric" }
                                      )}
                                    </p>
                                  </div>
                                </div>
                                <div className="flex gap-2">
                                  <Link href="/mensajes">
                                    <Button variant="outline" size="sm">
                                      <MessageCircle className="h-4 w-4" />
                                    </Button>
                                  </Link>
                                  <Button variant="outline" size="sm">
                                    Ver Detalles
                                  </Button>
                                </div>
                              </div>
                            ) : (
                              <div className="flex items-center justify-between rounded-lg bg-muted/50 p-3">
                                <div className="flex items-center gap-4 text-sm text-muted-foreground">
                                  <span>{item.views} visitas</span>
                                  <span>{item.inquiries} consultas</span>
                                </div>
                                <Button size="sm" className="bg-accent text-accent-foreground">
                                  Ver Consultas
                                </Button>
                              </div>
                            )}
                          </div>
                        </div>
                      </CardContent>
                    </Card>
                  ))}
                </TabsContent>

                <TabsContent value="rented" className="mt-6">
                  {mockPropertiesPropietario
                    .filter((p) => p.status === "active")
                    .map((item) => (
                      <Card key={item.id}>
                        <CardContent className="p-6">
                          <p className="font-medium">{item.property.title}</p>
                          <p className="text-sm text-muted-foreground">
                            Inquilino: {item.tenant?.name}
                          </p>
                        </CardContent>
                      </Card>
                    ))}
                </TabsContent>

                <TabsContent value="available" className="mt-6">
                  {mockPropertiesPropietario
                    .filter((p) => p.status === "available")
                    .map((item) => (
                      <Card key={item.id}>
                        <CardContent className="p-6">
                          <p className="font-medium">{item.property.title}</p>
                          <p className="text-sm text-muted-foreground">
                            {item.views} visitas, {item.inquiries} consultas
                          </p>
                        </CardContent>
                      </Card>
                    ))}
                </TabsContent>
              </Tabs>
            </div>
          )}
        </div>
      </main>

      <Footer />
    </div>
  )
}

export default function MiRentaPage() {
  return (
    <Suspense fallback={<div className="flex min-h-screen items-center justify-center">Cargando...</div>}>
      <MiRentaContent />
    </Suspense>
  )
}
