"use client"

import { useState } from "react"
import Image from "next/image"
import Link from "next/link"
import { useParams, useRouter } from "next/navigation"
import { Navbar } from "@/components/navbar"
import { Footer } from "@/components/footer"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { Badge } from "@/components/ui/badge"
import { Checkbox } from "@/components/ui/checkbox"
import {
  ArrowLeft,
  ArrowRight,
  MapPin,
  Check,
  FileText,
  CreditCard,
  Home,
  Calendar,
  Shield,
  AlertCircle,
} from "lucide-react"

const mockProperty = {
  id: "1",
  title: "Casa Amplia en Centro",
  price: 4500,
  deposit: 4500,
  location: "Centro, Ocosingo",
  address: "Calle Hidalgo #45, Centro, Ocosingo, Chiapas",
  image: "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&auto=format&fit=crop&q=60",
  owner: {
    name: "María García",
    phone: "919-123-4567",
  },
}

const steps = [
  { id: 1, title: "Información Personal", icon: FileText },
  { id: 2, title: "Términos del Contrato", icon: Home },
  { id: 3, title: "Pago Inicial", icon: CreditCard },
  { id: 4, title: "Confirmación", icon: Check },
]

export default function RentalProcessPage() {
  const params = useParams()
  const router = useRouter()
  const [userMode, setUserMode] = useState<"inquilino" | "propietario">("inquilino")
  const [currentStep, setCurrentStep] = useState(1)
  const [formData, setFormData] = useState({
    fullName: "",
    phone: "",
    email: "",
    occupation: "",
    monthlyIncome: "",
    references: "",
    moveInDate: "",
    contractDuration: "12",
    acceptTerms: false,
    acceptRules: false,
    paymentMethod: "transfer",
  })

  const handleModeSwitch = () => {
    setUserMode(userMode === "inquilino" ? "propietario" : "inquilino")
  }

  const handleInputChange = (field: string, value: string | boolean) => {
    setFormData((prev) => ({ ...prev, [field]: value }))
  }

  const nextStep = () => {
    if (currentStep < 4) {
      setCurrentStep(currentStep + 1)
    }
  }

  const prevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1)
    }
  }

  const handleSubmit = () => {
    router.push("/mi-renta?success=true")
  }

  const totalInitialPayment = mockProperty.price + mockProperty.deposit

  return (
    <div className="flex min-h-screen flex-col">
      <Navbar
        isLoggedIn={true}
        onLoginClick={() => {}}
        userMode={userMode}
        onModeSwitch={handleModeSwitch}
      />

      <main className="flex-1 bg-background px-4 py-8">
        <div className="container mx-auto max-w-4xl">
          {/* Back Button */}
          <Link
            href={`/propiedad/${params.id}`}
            className="mb-6 inline-flex items-center gap-2 text-muted-foreground hover:text-foreground"
          >
            <ArrowLeft className="h-4 w-4" />
            Volver a la propiedad
          </Link>

          {/* Property Summary */}
          <Card className="mb-8">
            <CardContent className="flex items-center gap-4 p-4">
              <div className="relative h-20 w-28 flex-shrink-0 overflow-hidden rounded-lg">
                <Image
                  src={mockProperty.image || "/placeholder.svg"}
                  alt={mockProperty.title}
                  fill
                  className="object-cover"
                />
              </div>
              <div className="flex-1">
                <h2 className="font-semibold text-card-foreground">{mockProperty.title}</h2>
                <div className="flex items-center gap-1 text-sm text-muted-foreground">
                  <MapPin className="h-4 w-4" />
                  <span>{mockProperty.location}</span>
                </div>
              </div>
              <div className="text-right">
                <p className="text-2xl font-bold text-accent">
                  ${mockProperty.price.toLocaleString()}
                </p>
                <p className="text-sm text-muted-foreground">/mes</p>
              </div>
            </CardContent>
          </Card>

          {/* Progress Steps */}
          <div className="mb-8">
            <div className="flex items-center justify-between">
              {steps.map((step, index) => (
                <div key={step.id} className="flex flex-1 items-center">
                  <div className="flex flex-col items-center">
                    <div
                      className={`flex h-10 w-10 items-center justify-center rounded-full border-2 transition-colors ${
                        currentStep >= step.id
                          ? "border-accent bg-accent text-accent-foreground"
                          : "border-muted-foreground bg-background text-muted-foreground"
                      }`}
                    >
                      {currentStep > step.id ? (
                        <Check className="h-5 w-5" />
                      ) : (
                        <step.icon className="h-5 w-5" />
                      )}
                    </div>
                    <span
                      className={`mt-2 text-center text-xs ${
                        currentStep >= step.id ? "font-medium text-foreground" : "text-muted-foreground"
                      }`}
                    >
                      {step.title}
                    </span>
                  </div>
                  {index < steps.length - 1 && (
                    <div
                      className={`mx-2 h-0.5 flex-1 ${
                        currentStep > step.id ? "bg-accent" : "bg-muted"
                      }`}
                    />
                  )}
                </div>
              ))}
            </div>
          </div>

          {/* Step Content */}
          <Card>
            <CardContent className="p-6">
              {/* Step 1: Personal Information */}
              {currentStep === 1 && (
                <div className="space-y-6">
                  <div>
                    <h3 className="mb-4 text-xl font-semibold text-card-foreground">
                      Información Personal
                    </h3>
                    <p className="mb-6 text-muted-foreground">
                      Completa tus datos para generar el contrato de arrendamiento.
                    </p>
                  </div>

                  <div className="grid gap-4 sm:grid-cols-2">
                    <div className="space-y-2">
                      <Label htmlFor="fullName">Nombre completo</Label>
                      <Input
                        id="fullName"
                        placeholder="Juan Pérez López"
                        value={formData.fullName}
                        onChange={(e) => handleInputChange("fullName", e.target.value)}
                      />
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="phone">Teléfono</Label>
                      <Input
                        id="phone"
                        placeholder="919-123-4567"
                        value={formData.phone}
                        onChange={(e) => handleInputChange("phone", e.target.value)}
                      />
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="email">Correo electrónico</Label>
                      <Input
                        id="email"
                        type="email"
                        placeholder="juan@ejemplo.com"
                        value={formData.email}
                        onChange={(e) => handleInputChange("email", e.target.value)}
                      />
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="occupation">Ocupación</Label>
                      <Input
                        id="occupation"
                        placeholder="Ingeniero, Comerciante, etc."
                        value={formData.occupation}
                        onChange={(e) => handleInputChange("occupation", e.target.value)}
                      />
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="monthlyIncome">Ingreso mensual aproximado</Label>
                      <Input
                        id="monthlyIncome"
                        placeholder="$15,000"
                        value={formData.monthlyIncome}
                        onChange={(e) => handleInputChange("monthlyIncome", e.target.value)}
                      />
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="moveInDate">Fecha deseada de mudanza</Label>
                      <Input
                        id="moveInDate"
                        type="date"
                        value={formData.moveInDate}
                        onChange={(e) => handleInputChange("moveInDate", e.target.value)}
                      />
                    </div>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="references">Referencias personales</Label>
                    <Textarea
                      id="references"
                      placeholder="Nombre y teléfono de 2 referencias personales..."
                      value={formData.references}
                      onChange={(e) => handleInputChange("references", e.target.value)}
                      rows={3}
                    />
                  </div>
                </div>
              )}

              {/* Step 2: Contract Terms */}
              {currentStep === 2 && (
                <div className="space-y-6">
                  <div>
                    <h3 className="mb-4 text-xl font-semibold text-card-foreground">
                      Términos del Contrato
                    </h3>
                    <p className="mb-6 text-muted-foreground">
                      Revisa y acepta los términos del contrato de arrendamiento.
                    </p>
                  </div>

                  <Card className="border-accent/20 bg-accent/5">
                    <CardContent className="p-4">
                      <div className="grid gap-4 sm:grid-cols-2">
                        <div>
                          <p className="text-sm text-muted-foreground">Renta mensual</p>
                          <p className="text-lg font-semibold text-foreground">
                            ${mockProperty.price.toLocaleString()} MXN
                          </p>
                        </div>
                        <div>
                          <p className="text-sm text-muted-foreground">Depósito</p>
                          <p className="text-lg font-semibold text-foreground">
                            ${mockProperty.deposit.toLocaleString()} MXN
                          </p>
                        </div>
                        <div>
                          <p className="text-sm text-muted-foreground">Duración del contrato</p>
                          <p className="text-lg font-semibold text-foreground">12 meses</p>
                        </div>
                        <div>
                          <p className="text-sm text-muted-foreground">Fecha de pago</p>
                          <p className="text-lg font-semibold text-foreground">
                            Día 1-5 de cada mes
                          </p>
                        </div>
                      </div>
                    </CardContent>
                  </Card>

                  <div className="rounded-lg border border-border p-4">
                    <h4 className="mb-3 font-medium text-card-foreground">
                      Resumen del Contrato
                    </h4>
                    <ul className="space-y-2 text-sm text-muted-foreground">
                      <li className="flex items-start gap-2">
                        <Check className="mt-0.5 h-4 w-4 text-accent" />
                        El arrendador se compromete a entregar la propiedad en condiciones habitables.
                      </li>
                      <li className="flex items-start gap-2">
                        <Check className="mt-0.5 h-4 w-4 text-accent" />
                        El inquilino se compromete a pagar la renta puntualmente.
                      </li>
                      <li className="flex items-start gap-2">
                        <Check className="mt-0.5 h-4 w-4 text-accent" />
                        El depósito será devuelto al término del contrato, descontando posibles daños.
                      </li>
                      <li className="flex items-start gap-2">
                        <Check className="mt-0.5 h-4 w-4 text-accent" />
                        Se requiere aviso de 30 días para terminar el contrato anticipadamente.
                      </li>
                      <li className="flex items-start gap-2">
                        <Check className="mt-0.5 h-4 w-4 text-accent" />
                        No se permiten mascotas sin autorización previa del propietario.
                      </li>
                    </ul>
                  </div>

                  <div className="space-y-3">
                    <div className="flex items-start gap-3">
                      <Checkbox
                        id="acceptTerms"
                        checked={formData.acceptTerms}
                        onCheckedChange={(checked) =>
                          handleInputChange("acceptTerms", checked as boolean)
                        }
                      />
                      <Label htmlFor="acceptTerms" className="leading-relaxed">
                        He leído y acepto los términos y condiciones del contrato de arrendamiento.
                      </Label>
                    </div>
                    <div className="flex items-start gap-3">
                      <Checkbox
                        id="acceptRules"
                        checked={formData.acceptRules}
                        onCheckedChange={(checked) =>
                          handleInputChange("acceptRules", checked as boolean)
                        }
                      />
                      <Label htmlFor="acceptRules" className="leading-relaxed">
                        Acepto las reglas de convivencia y políticas de la propiedad.
                      </Label>
                    </div>
                  </div>
                </div>
              )}

              {/* Step 3: Payment */}
              {currentStep === 3 && (
                <div className="space-y-6">
                  <div>
                    <h3 className="mb-4 text-xl font-semibold text-card-foreground">
                      Pago Inicial
                    </h3>
                    <p className="mb-6 text-muted-foreground">
                      Realiza el pago inicial para asegurar la propiedad.
                    </p>
                  </div>

                  <Card className="border-accent bg-accent/5">
                    <CardContent className="p-4">
                      <div className="space-y-3">
                        <div className="flex justify-between">
                          <span className="text-muted-foreground">Primera renta</span>
                          <span className="font-medium">
                            ${mockProperty.price.toLocaleString()} MXN
                          </span>
                        </div>
                        <div className="flex justify-between">
                          <span className="text-muted-foreground">Depósito de garantía</span>
                          <span className="font-medium">
                            ${mockProperty.deposit.toLocaleString()} MXN
                          </span>
                        </div>
                        <div className="border-t border-border pt-3">
                          <div className="flex justify-between">
                            <span className="text-lg font-semibold text-foreground">Total a pagar</span>
                            <span className="text-lg font-bold text-accent">
                              ${totalInitialPayment.toLocaleString()} MXN
                            </span>
                          </div>
                        </div>
                      </div>
                    </CardContent>
                  </Card>

                  <div className="space-y-4">
                    <Label className="text-base font-medium">Método de pago</Label>
                    <div className="grid gap-3 sm:grid-cols-2">
                      <button
                        onClick={() => handleInputChange("paymentMethod", "transfer")}
                        className={`rounded-lg border-2 p-4 text-left transition-colors ${
                          formData.paymentMethod === "transfer"
                            ? "border-accent bg-accent/5"
                            : "border-border hover:border-accent/50"
                        }`}
                      >
                        <CreditCard className="mb-2 h-6 w-6 text-accent" />
                        <p className="font-medium">Transferencia bancaria</p>
                        <p className="text-sm text-muted-foreground">
                          CLABE y datos de cuenta
                        </p>
                      </button>
                      <button
                        onClick={() => handleInputChange("paymentMethod", "cash")}
                        className={`rounded-lg border-2 p-4 text-left transition-colors ${
                          formData.paymentMethod === "cash"
                            ? "border-accent bg-accent/5"
                            : "border-border hover:border-accent/50"
                        }`}
                      >
                        <Home className="mb-2 h-6 w-6 text-accent" />
                        <p className="font-medium">Pago en efectivo</p>
                        <p className="text-sm text-muted-foreground">
                          Directamente al propietario
                        </p>
                      </button>
                    </div>
                  </div>

                  {formData.paymentMethod === "transfer" && (
                    <Card>
                      <CardContent className="p-4">
                        <h4 className="mb-3 font-medium text-card-foreground">
                          Datos para transferencia
                        </h4>
                        <div className="space-y-2 text-sm">
                          <div className="flex justify-between">
                            <span className="text-muted-foreground">Banco</span>
                            <span className="font-medium">BBVA</span>
                          </div>
                          <div className="flex justify-between">
                            <span className="text-muted-foreground">Beneficiario</span>
                            <span className="font-medium">{mockProperty.owner.name}</span>
                          </div>
                          <div className="flex justify-between">
                            <span className="text-muted-foreground">CLABE</span>
                            <span className="font-mono font-medium">012345678901234567</span>
                          </div>
                          <div className="flex justify-between">
                            <span className="text-muted-foreground">Concepto</span>
                            <span className="font-medium">Renta + Depósito - {mockProperty.title}</span>
                          </div>
                        </div>
                      </CardContent>
                    </Card>
                  )}

                  <div className="flex items-start gap-3 rounded-lg border border-yellow-500/20 bg-yellow-500/5 p-4">
                    <AlertCircle className="mt-0.5 h-5 w-5 text-yellow-600" />
                    <div className="text-sm">
                      <p className="font-medium text-yellow-800">Importante</p>
                      <p className="text-yellow-700">
                        El propietario confirmará la recepción del pago y te contactará para la
                        entrega de llaves y firma del contrato físico.
                      </p>
                    </div>
                  </div>
                </div>
              )}

              {/* Step 4: Confirmation */}
              {currentStep === 4 && (
                <div className="space-y-6 text-center">
                  <div className="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-accent/10">
                    <Check className="h-10 w-10 text-accent" />
                  </div>

                  <div>
                    <h3 className="mb-2 text-2xl font-bold text-card-foreground">
                      ¡Solicitud Enviada!
                    </h3>
                    <p className="text-muted-foreground">
                      Tu solicitud de renta ha sido enviada al propietario. Te contactaremos
                      pronto para finalizar el proceso.
                    </p>
                  </div>

                  <Card className="text-left">
                    <CardContent className="p-4">
                      <h4 className="mb-3 font-medium text-card-foreground">Resumen</h4>
                      <div className="space-y-2 text-sm">
                        <div className="flex justify-between">
                          <span className="text-muted-foreground">Propiedad</span>
                          <span className="font-medium">{mockProperty.title}</span>
                        </div>
                        <div className="flex justify-between">
                          <span className="text-muted-foreground">Inquilino</span>
                          <span className="font-medium">{formData.fullName || "Usuario"}</span>
                        </div>
                        <div className="flex justify-between">
                          <span className="text-muted-foreground">Renta mensual</span>
                          <span className="font-medium">
                            ${mockProperty.price.toLocaleString()} MXN
                          </span>
                        </div>
                        <div className="flex justify-between">
                          <span className="text-muted-foreground">Fecha de inicio</span>
                          <span className="font-medium">
                            {formData.moveInDate
                              ? new Date(formData.moveInDate).toLocaleDateString("es-MX")
                              : "Por confirmar"}
                          </span>
                        </div>
                      </div>
                    </CardContent>
                  </Card>

                  <div className="flex items-center justify-center gap-2 text-sm text-muted-foreground">
                    <Shield className="h-4 w-4" />
                    <span>Tu información está protegida</span>
                  </div>
                </div>
              )}

              {/* Navigation Buttons */}
              <div className="mt-8 flex justify-between border-t border-border pt-6">
                {currentStep > 1 && currentStep < 4 ? (
                  <Button variant="outline" onClick={prevStep}>
                    <ArrowLeft className="mr-2 h-4 w-4" />
                    Anterior
                  </Button>
                ) : (
                  <div />
                )}

                {currentStep < 3 && (
                  <Button
                    onClick={nextStep}
                    className="bg-primary text-primary-foreground hover:bg-accent"
                  >
                    Siguiente
                    <ArrowRight className="ml-2 h-4 w-4" />
                  </Button>
                )}

                {currentStep === 3 && (
                  <Button
                    onClick={nextStep}
                    className="bg-accent text-accent-foreground hover:bg-accent/90"
                  >
                    Confirmar Pago
                    <Check className="ml-2 h-4 w-4" />
                  </Button>
                )}

                {currentStep === 4 && (
                  <Button
                    onClick={handleSubmit}
                    className="w-full bg-primary text-primary-foreground hover:bg-accent"
                  >
                    Ir a Mi Renta
                    <ArrowRight className="ml-2 h-4 w-4" />
                  </Button>
                )}
              </div>
            </CardContent>
          </Card>
        </div>
      </main>

      <Footer />
    </div>
  )
}
