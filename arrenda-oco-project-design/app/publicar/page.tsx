"use client"

import React from "react"
import Phone from "lucide-react"

import { useState } from "react"
import Image from "next/image"
import Link from "next/link"
import { useRouter } from "next/navigation"
import { Navbar } from "@/components/navbar"
import { Footer } from "@/components/footer"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { Checkbox } from "@/components/ui/checkbox"
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group"
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card"
import {
  ArrowLeft,
  Home,
  MapPin,
  Ruler,
  LayoutGrid,
  Users,
  Zap,
  ScrollText,
  ImageIcon,
  CheckCircle,
  Upload,
  X,
  Info,
} from "lucide-react"

export default function PublicarInmueblePage() {
  const router = useRouter()
  const [userMode, setUserMode] = useState<"inquilino" | "propietario">("propietario")
  const [currentStep, setCurrentStep] = useState(1)
  const [uploadedImages, setUploadedImages] = useState<string[]>([])
  const [mainImageIndex, setMainImageIndex] = useState(0)

  // Form state
  const [formData, setFormData] = useState({
    // 1. Información general
    tipoInmueble: "",
    nombre: "",
    descripcion: "",
    precioRenta: "",
    deposito: "",
    disponibleDesde: "",
    estatus: "disponible",

    // 2. Ubicación
    estado: "Chiapas",
    municipio: "Ocosingo",
    colonia: "",
    calle: "",
    numeroExterior: "",
    referencias: "",

    // 3. Dimensiones
    tipoMedicion: "metros",
    metrosCuadrados: "",
    largo: "",
    ancho: "",
    tieneAreasExternas: false,
    areaExterna: "",
    largoExterno: "",
    anchoExterno: "",

    // 4. Distribución (solo casa/depto)
    habitaciones: "",
    banos: "",
    pisos: "",

    // 5. Zonas comunes
    sala: false,
    cocina: false,
    comedor: false,
    areaLavado: false,
    cochera: false,
    observacionesZonas: "",

    // 6. Servicios
    agua: false,
    luz: false,
    internet: false,
    gas: false,
    basura: false,
    cable: false,
    mantenimiento: false,
    otrosServicios: "",

    // 7. Reglas
    mascotas: "no",
    fumar: "no",
    usoExclusivo: "",
    reglasAdicionales: "",

    // 9. Confirmación
    aceptaTerminos: false,
    nombreContacto: "",
    telefono: "",
    whatsapp: false,
    horarioContacto: "",
  })

  const steps = [
    { number: 1, title: "Información", icon: Home },
    { number: 2, title: "Ubicación", icon: MapPin },
    { number: 3, title: "Dimensiones", icon: Ruler },
    { number: 4, title: "Distribución", icon: LayoutGrid },
    { number: 5, title: "Zonas", icon: Users },
    { number: 6, title: "Servicios", icon: Zap },
    { number: 7, title: "Reglas", icon: ScrollText },
    { number: 8, title: "Imágenes", icon: ImageIcon },
    { number: 9, title: "Contacto", icon: Phone },
    { number: 10, title: "Confirmar", icon: CheckCircle },
  ]

  const handleInputChange = (field: string, value: string | boolean) => {
    setFormData((prev) => ({ ...prev, [field]: value }))
  }

  const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = e.target.files
    if (files) {
      const newImages = Array.from(files).map((file) => URL.createObjectURL(file))
      setUploadedImages((prev) => [...prev, ...newImages].slice(0, 10))
    }
  }

  const removeImage = (index: number) => {
    setUploadedImages((prev) => prev.filter((_, i) => i !== index))
    if (mainImageIndex === index) {
      setMainImageIndex(0)
    } else if (mainImageIndex > index) {
      setMainImageIndex((prev) => prev - 1)
    }
  }

  const handleSubmit = () => {
    // Simular guardado
    alert("¡Propiedad publicada exitosamente!")
    router.push("/mi-renta")
  }

  const nextStep = () => {
    // Skip step 4 if tipo is "cuarto"
    if (currentStep === 3 && formData.tipoInmueble === "cuarto") {
      setCurrentStep(5)
    } else {
      setCurrentStep((prev) => Math.min(prev + 1, 9))
    }
  }

  const prevStep = () => {
    // Skip step 4 if tipo is "cuarto"
    if (currentStep === 5 && formData.tipoInmueble === "cuarto") {
      setCurrentStep(3)
    } else {
      setCurrentStep((prev) => Math.max(prev - 1, 1))
    }
  }

  return (
    <div className="min-h-screen bg-background">
      <Navbar
        isLoggedIn={true}
        userMode={userMode}
        onModeSwitch={() => setUserMode(userMode === "inquilino" ? "propietario" : "inquilino")}
        onLoginClick={() => {}}
      />

      <main className="container mx-auto px-4 py-8">
        {/* Header */}
        <div className="mb-8">
          <Link
            href="/mi-renta"
            className="mb-4 inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground"
          >
            <ArrowLeft className="h-4 w-4" />
            Volver a Mis Propiedades
          </Link>
          <h1 className="text-3xl font-bold text-foreground">Publicar Inmueble</h1>
          <p className="mt-2 text-muted-foreground">
            Completa todos los campos para publicar tu propiedad en ArrendaOco
          </p>
        </div>

        {/* Progress Steps */}
        <div className="mb-8 overflow-x-auto">
          <div className="flex min-w-max gap-2">
            {steps.map((step) => {
              const Icon = step.icon
              const isActive = currentStep === step.number
              const isCompleted = currentStep > step.number
              const isSkipped = step.number === 4 && formData.tipoInmueble === "cuarto"

              if (isSkipped) return null

              return (
                <button
                  key={step.number}
                  onClick={() => {
                    if (step.number === 4 && formData.tipoInmueble === "cuarto") return
                    setCurrentStep(step.number)
                  }}
                  className={`flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors ${
                    isActive
                      ? "bg-primary text-primary-foreground"
                      : isCompleted
                        ? "bg-accent/20 text-accent"
                        : "bg-card text-muted-foreground hover:bg-muted"
                  }`}
                >
                  <Icon className="h-4 w-4" />
                  <span className="hidden sm:inline">{step.title}</span>
                  <span className="sm:hidden">{step.number}</span>
                </button>
              )
            })}
          </div>
        </div>

        {/* Form Content */}
        <Card className="mx-auto max-w-3xl">
          {/* Step 1: Información General */}
          {currentStep === 1 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Home className="h-5 w-5 text-primary" />
                  Información General del Inmueble
                </CardTitle>
                <CardDescription>Datos básicos sobre la propiedad</CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-2">
                  <Label htmlFor="tipoInmueble">Tipo de inmueble *</Label>
                  <Select
                    value={formData.tipoInmueble}
                    onValueChange={(value) => handleInputChange("tipoInmueble", value)}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecciona el tipo" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="casa">Casa</SelectItem>
                      <SelectItem value="departamento">Departamento</SelectItem>
                      <SelectItem value="cuarto">Cuarto</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="nombre">Nombre del inmueble *</Label>
                  <Input
                    id="nombre"
                    placeholder='Ej. "Cuarto cerca de la UTSelva"'
                    value={formData.nombre}
                    onChange={(e) => handleInputChange("nombre", e.target.value)}
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="descripcion">Descripción</Label>
                  <Textarea
                    id="descripcion"
                    placeholder="Describe el inmueble, reglas, ambiente, etc."
                    rows={4}
                    value={formData.descripcion}
                    onChange={(e) => handleInputChange("descripcion", e.target.value)}
                  />
                </div>

                <div className="grid gap-4 sm:grid-cols-2">
                  <div className="space-y-2">
                    <Label htmlFor="precioRenta">Precio de renta mensual *</Label>
                    <div className="relative">
                      <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        $
                      </span>
                      <Input
                        id="precioRenta"
                        type="number"
                        placeholder="0"
                        className="pl-7"
                        value={formData.precioRenta}
                        onChange={(e) => handleInputChange("precioRenta", e.target.value)}
                      />
                    </div>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="deposito">Depósito (opcional)</Label>
                    <div className="relative">
                      <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        $
                      </span>
                      <Input
                        id="deposito"
                        type="number"
                        placeholder="0"
                        className="pl-7"
                        value={formData.deposito}
                        onChange={(e) => handleInputChange("deposito", e.target.value)}
                      />
                    </div>
                  </div>
                </div>

                <div className="grid gap-4 sm:grid-cols-2">
                  <div className="space-y-2">
                    <Label htmlFor="disponibleDesde">Disponible desde *</Label>
                    <Input
                      id="disponibleDesde"
                      type="date"
                      value={formData.disponibleDesde}
                      onChange={(e) => handleInputChange("disponibleDesde", e.target.value)}
                    />
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="estatus">Estatus</Label>
                    <Select
                      value={formData.estatus}
                      onValueChange={(value) => handleInputChange("estatus", value)}
                    >
                      <SelectTrigger>
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="disponible">Disponible</SelectItem>
                        <SelectItem value="ocupado">Ocupado</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                </div>
              </CardContent>
            </>
          )}

          {/* Step 2: Ubicación */}
          {currentStep === 2 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <MapPin className="h-5 w-5 text-primary" />
                  Ubicación
                </CardTitle>
                <CardDescription>
                  Información clave para búsquedas y filtros
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid gap-4 sm:grid-cols-2">
                  <div className="space-y-2">
                    <Label htmlFor="estado">Estado</Label>
                    <Select
                      value={formData.estado}
                      onValueChange={(value) => handleInputChange("estado", value)}
                    >
                      <SelectTrigger>
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="Chiapas">Chiapas</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="municipio">Municipio</Label>
                    <Select
                      value={formData.municipio}
                      onValueChange={(value) => handleInputChange("municipio", value)}
                    >
                      <SelectTrigger>
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="Ocosingo">Ocosingo</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="colonia">Colonia / Barrio *</Label>
                  <Input
                    id="colonia"
                    placeholder="Ej. Centro, San Caralampio"
                    value={formData.colonia}
                    onChange={(e) => handleInputChange("colonia", e.target.value)}
                  />
                </div>

                <div className="grid gap-4 sm:grid-cols-3">
                  <div className="space-y-2 sm:col-span-2">
                    <Label htmlFor="calle">Calle *</Label>
                    <Input
                      id="calle"
                      placeholder="Nombre de la calle"
                      value={formData.calle}
                      onChange={(e) => handleInputChange("calle", e.target.value)}
                    />
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="numeroExterior">No. Exterior</Label>
                    <Input
                      id="numeroExterior"
                      placeholder="S/N"
                      value={formData.numeroExterior}
                      onChange={(e) => handleInputChange("numeroExterior", e.target.value)}
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="referencias">Referencias</Label>
                  <Textarea
                    id="referencias"
                    placeholder="Frente a..., cerca de..., a dos cuadras de..."
                    rows={3}
                    value={formData.referencias}
                    onChange={(e) => handleInputChange("referencias", e.target.value)}
                  />
                </div>
              </CardContent>
            </>
          )}

          {/* Step 3: Dimensiones */}
          {currentStep === 3 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Ruler className="h-5 w-5 text-primary" />
                  Dimensiones del Inmueble
                </CardTitle>
                <CardDescription>Medidas generales y áreas externas</CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-4">
                  <Label>Tipo de medición</Label>
                  <RadioGroup
                    value={formData.tipoMedicion}
                    onValueChange={(value) => handleInputChange("tipoMedicion", value)}
                    className="flex gap-4"
                  >
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="metros" id="metros" />
                      <Label htmlFor="metros" className="cursor-pointer">
                        Metros cuadrados
                      </Label>
                    </div>
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="largo-ancho" id="largo-ancho" />
                      <Label htmlFor="largo-ancho" className="cursor-pointer">
                        Largo x Ancho
                      </Label>
                    </div>
                  </RadioGroup>
                </div>

                {formData.tipoMedicion === "metros" ? (
                  <div className="space-y-2">
                    <Label htmlFor="metrosCuadrados">Metros cuadrados totales</Label>
                    <div className="relative">
                      <Input
                        id="metrosCuadrados"
                        type="number"
                        placeholder="0"
                        value={formData.metrosCuadrados}
                        onChange={(e) => handleInputChange("metrosCuadrados", e.target.value)}
                      />
                      <span className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        m²
                      </span>
                    </div>
                  </div>
                ) : (
                  <div className="grid gap-4 sm:grid-cols-2">
                    <div className="space-y-2">
                      <Label htmlFor="largo">Largo</Label>
                      <div className="relative">
                        <Input
                          id="largo"
                          type="number"
                          placeholder="0"
                          value={formData.largo}
                          onChange={(e) => handleInputChange("largo", e.target.value)}
                        />
                        <span className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                          m
                        </span>
                      </div>
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="ancho">Ancho</Label>
                      <div className="relative">
                        <Input
                          id="ancho"
                          type="number"
                          placeholder="0"
                          value={formData.ancho}
                          onChange={(e) => handleInputChange("ancho", e.target.value)}
                        />
                        <span className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                          m
                        </span>
                      </div>
                    </div>
                  </div>
                )}

                <div className="border-t pt-6">
                  <div className="flex items-center space-x-2">
                    <Checkbox
                      id="tieneAreasExternas"
                      checked={formData.tieneAreasExternas}
                      onCheckedChange={(checked) =>
                        handleInputChange("tieneAreasExternas", checked as boolean)
                      }
                    />
                    <Label htmlFor="tieneAreasExternas" className="cursor-pointer font-medium">
                      ¿Tiene áreas externas?
                    </Label>
                  </div>

                  {formData.tieneAreasExternas && (
                    <div className="mt-4 space-y-4 rounded-lg bg-muted/50 p-4">
                      <div className="space-y-2">
                        <Label htmlFor="areaExterna">Tipo de área externa</Label>
                        <Select
                          value={formData.areaExterna}
                          onValueChange={(value) => handleInputChange("areaExterna", value)}
                        >
                          <SelectTrigger>
                            <SelectValue placeholder="Selecciona" />
                          </SelectTrigger>
                          <SelectContent>
                            <SelectItem value="jardin">Jardín</SelectItem>
                            <SelectItem value="patio">Patio</SelectItem>
                            <SelectItem value="ambos">Ambos</SelectItem>
                          </SelectContent>
                        </Select>
                      </div>

                      <div className="grid gap-4 sm:grid-cols-2">
                        <div className="space-y-2">
                          <Label htmlFor="largoExterno">Largo</Label>
                          <div className="relative">
                            <Input
                              id="largoExterno"
                              type="number"
                              placeholder="0"
                              value={formData.largoExterno}
                              onChange={(e) => handleInputChange("largoExterno", e.target.value)}
                            />
                            <span className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                              m
                            </span>
                          </div>
                        </div>
                        <div className="space-y-2">
                          <Label htmlFor="anchoExterno">Ancho</Label>
                          <div className="relative">
                            <Input
                              id="anchoExterno"
                              type="number"
                              placeholder="0"
                              value={formData.anchoExterno}
                              onChange={(e) => handleInputChange("anchoExterno", e.target.value)}
                            />
                            <span className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                              m
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              </CardContent>
            </>
          )}

          {/* Step 4: Distribución (solo casa/depto) */}
          {currentStep === 4 && formData.tipoInmueble !== "cuarto" && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <LayoutGrid className="h-5 w-5 text-primary" />
                  Distribución Interna
                </CardTitle>
                <CardDescription>
                  Número de habitaciones, baños y pisos
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid gap-6 sm:grid-cols-3">
                  <div className="space-y-2">
                    <Label htmlFor="habitaciones">Número de habitaciones</Label>
                    <Input
                      id="habitaciones"
                      type="number"
                      min="1"
                      placeholder="0"
                      value={formData.habitaciones}
                      onChange={(e) => handleInputChange("habitaciones", e.target.value)}
                    />
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="banos">Número de baños</Label>
                    <Input
                      id="banos"
                      type="number"
                      min="1"
                      placeholder="0"
                      value={formData.banos}
                      onChange={(e) => handleInputChange("banos", e.target.value)}
                    />
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="pisos">Número de pisos</Label>
                    <Input
                      id="pisos"
                      type="number"
                      min="1"
                      placeholder="1"
                      value={formData.pisos}
                      onChange={(e) => handleInputChange("pisos", e.target.value)}
                    />
                  </div>
                </div>

                <div className="rounded-lg bg-blue-50 p-4">
                  <div className="flex gap-2">
                    <Info className="h-5 w-5 shrink-0 text-blue-600" />
                    <p className="text-sm text-blue-800">
                      Esta sección solo aplica para casas y departamentos. Los cuartos
                      individuales no requieren esta información.
                    </p>
                  </div>
                </div>
              </CardContent>
            </>
          )}

          {/* Step 5: Zonas Comunes */}
          {currentStep === 5 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Users className="h-5 w-5 text-primary" />
                  Zonas en Común
                </CardTitle>
                <CardDescription>
                  {formData.tipoInmueble === "cuarto"
                    ? "Especialmente importante para cuartos compartidos"
                    : "Áreas compartidas de la propiedad"}
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid gap-4 sm:grid-cols-2">
                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="sala"
                      checked={formData.sala}
                      onCheckedChange={(checked) => handleInputChange("sala", checked as boolean)}
                    />
                    <Label htmlFor="sala" className="cursor-pointer">
                      Sala
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="cocina"
                      checked={formData.cocina}
                      onCheckedChange={(checked) =>
                        handleInputChange("cocina", checked as boolean)
                      }
                    />
                    <Label htmlFor="cocina" className="cursor-pointer">
                      Cocina
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="comedor"
                      checked={formData.comedor}
                      onCheckedChange={(checked) =>
                        handleInputChange("comedor", checked as boolean)
                      }
                    />
                    <Label htmlFor="comedor" className="cursor-pointer">
                      Comedor
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="areaLavado"
                      checked={formData.areaLavado}
                      onCheckedChange={(checked) =>
                        handleInputChange("areaLavado", checked as boolean)
                      }
                    />
                    <Label htmlFor="areaLavado" className="cursor-pointer">
                      Área de lavado
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4 sm:col-span-2">
                    <Checkbox
                      id="cochera"
                      checked={formData.cochera}
                      onCheckedChange={(checked) =>
                        handleInputChange("cochera", checked as boolean)
                      }
                    />
                    <Label htmlFor="cochera" className="cursor-pointer">
                      Cochera / Estacionamiento
                    </Label>
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="observacionesZonas">Observaciones de uso</Label>
                  <Textarea
                    id="observacionesZonas"
                    placeholder='Ej. "Cocina compartida con 2 personas", "Estacionamiento para 1 auto"'
                    rows={3}
                    value={formData.observacionesZonas}
                    onChange={(e) => handleInputChange("observacionesZonas", e.target.value)}
                  />
                </div>
              </CardContent>
            </>
          )}

          {/* Step 6: Servicios */}
          {currentStep === 6 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Zap className="h-5 w-5 text-primary" />
                  Servicios Incluidos
                </CardTitle>
                <CardDescription>
                  Selecciona los servicios que están incluidos en la renta
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="grid gap-4 sm:grid-cols-2">
                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="agua"
                      checked={formData.agua}
                      onCheckedChange={(checked) => handleInputChange("agua", checked as boolean)}
                    />
                    <Label htmlFor="agua" className="cursor-pointer">
                      Agua
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="luz"
                      checked={formData.luz}
                      onCheckedChange={(checked) => handleInputChange("luz", checked as boolean)}
                    />
                    <Label htmlFor="luz" className="cursor-pointer">
                      Luz / Electricidad
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="internet"
                      checked={formData.internet}
                      onCheckedChange={(checked) =>
                        handleInputChange("internet", checked as boolean)
                      }
                    />
                    <Label htmlFor="internet" className="cursor-pointer">
                      Internet / WiFi
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="gas"
                      checked={formData.gas}
                      onCheckedChange={(checked) => handleInputChange("gas", checked as boolean)}
                    />
                    <Label htmlFor="gas" className="cursor-pointer">
                      Gas
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="basura"
                      checked={formData.basura}
                      onCheckedChange={(checked) =>
                        handleInputChange("basura", checked as boolean)
                      }
                    />
                    <Label htmlFor="basura" className="cursor-pointer">
                      Recolección de basura
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4">
                    <Checkbox
                      id="cable"
                      checked={formData.cable}
                      onCheckedChange={(checked) => handleInputChange("cable", checked as boolean)}
                    />
                    <Label htmlFor="cable" className="cursor-pointer">
                      Cable / Streaming
                    </Label>
                  </div>

                  <div className="flex items-center space-x-3 rounded-lg border p-4 sm:col-span-2">
                    <Checkbox
                      id="mantenimiento"
                      checked={formData.mantenimiento}
                      onCheckedChange={(checked) =>
                        handleInputChange("mantenimiento", checked as boolean)
                      }
                    />
                    <Label htmlFor="mantenimiento" className="cursor-pointer">
                      Mantenimiento
                    </Label>
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="otrosServicios">Otros servicios</Label>
                  <Input
                    id="otrosServicios"
                    placeholder="Ej. Limpieza semanal, vigilancia, etc."
                    value={formData.otrosServicios}
                    onChange={(e) => handleInputChange("otrosServicios", e.target.value)}
                  />
                </div>
              </CardContent>
            </>
          )}

          {/* Step 7: Reglas */}
          {currentStep === 7 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <ScrollText className="h-5 w-5 text-primary" />
                  Reglas del Inmueble
                </CardTitle>
                <CardDescription>
                  Define las reglas y restricciones de la propiedad
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-4">
                  <Label>¿Se permiten mascotas?</Label>
                  <RadioGroup
                    value={formData.mascotas}
                    onValueChange={(value) => handleInputChange("mascotas", value)}
                    className="flex gap-4"
                  >
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="si" id="mascotas-si" />
                      <Label htmlFor="mascotas-si" className="cursor-pointer">
                        Sí
                      </Label>
                    </div>
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="no" id="mascotas-no" />
                      <Label htmlFor="mascotas-no" className="cursor-pointer">
                        No
                      </Label>
                    </div>
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="negociable" id="mascotas-negociable" />
                      <Label htmlFor="mascotas-negociable" className="cursor-pointer">
                        Negociable
                      </Label>
                    </div>
                  </RadioGroup>
                </div>

                <div className="space-y-4">
                  <Label>¿Se permite fumar?</Label>
                  <RadioGroup
                    value={formData.fumar}
                    onValueChange={(value) => handleInputChange("fumar", value)}
                    className="flex gap-4"
                  >
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="si" id="fumar-si" />
                      <Label htmlFor="fumar-si" className="cursor-pointer">
                        Sí
                      </Label>
                    </div>
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="no" id="fumar-no" />
                      <Label htmlFor="fumar-no" className="cursor-pointer">
                        No
                      </Label>
                    </div>
                    <div className="flex items-center space-x-2">
                      <RadioGroupItem value="exterior" id="fumar-exterior" />
                      <Label htmlFor="fumar-exterior" className="cursor-pointer">
                        Solo en exteriores
                      </Label>
                    </div>
                  </RadioGroup>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="usoExclusivo">Uso exclusivo</Label>
                  <Select
                    value={formData.usoExclusivo}
                    onValueChange={(value) => handleInputChange("usoExclusivo", value)}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecciona una opción" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="cualquiera">Sin restricción</SelectItem>
                      <SelectItem value="familiar">Familiar</SelectItem>
                      <SelectItem value="estudiantes">Estudiantes</SelectItem>
                      <SelectItem value="individual">Individual</SelectItem>
                      <SelectItem value="profesionistas">Profesionistas</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="reglasAdicionales">Reglas adicionales</Label>
                  <Textarea
                    id="reglasAdicionales"
                    placeholder="Otras reglas o restricciones importantes..."
                    rows={4}
                    value={formData.reglasAdicionales}
                    onChange={(e) => handleInputChange("reglasAdicionales", e.target.value)}
                  />
                </div>
              </CardContent>
            </>
          )}

          {/* Step 8: Imágenes */}
          {currentStep === 8 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <ImageIcon className="h-5 w-5 text-primary" />
                  Imágenes del Inmueble
                </CardTitle>
                <CardDescription>
                  Sube hasta 10 fotos. La primera será la imagen principal.
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="rounded-lg border-2 border-dashed border-muted-foreground/25 p-8 text-center">
                  <input
                    type="file"
                    id="images"
                    multiple
                    accept="image/png,image/jpeg,image/jpg"
                    className="hidden"
                    onChange={handleImageUpload}
                  />
                  <label htmlFor="images" className="cursor-pointer">
                    <Upload className="mx-auto h-12 w-12 text-muted-foreground" />
                    <p className="mt-4 font-medium">Haz clic para subir imágenes</p>
                    <p className="mt-1 text-sm text-muted-foreground">
                      PNG, JPG hasta 10 imágenes
                    </p>
                  </label>
                </div>

                {uploadedImages.length > 0 && (
                  <div className="space-y-4">
                    <Label>Imágenes subidas ({uploadedImages.length}/10)</Label>
                    <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                      {uploadedImages.map((src, index) => (
                        <div
                          key={index}
                          className={`group relative aspect-square overflow-hidden rounded-lg border-2 ${
                            mainImageIndex === index
                              ? "border-primary"
                              : "border-transparent"
                          }`}
                        >
                          <Image
                            src={src || "/placeholder.svg"}
                            alt={`Imagen ${index + 1}`}
                            fill
                            className="object-cover"
                          />
                          <div className="absolute inset-0 flex items-center justify-center gap-2 bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                            <Button
                              size="sm"
                              variant="secondary"
                              onClick={() => setMainImageIndex(index)}
                              className="h-8 text-xs"
                            >
                              {mainImageIndex === index ? "Principal" : "Hacer principal"}
                            </Button>
                            <Button
                              size="icon"
                              variant="destructive"
                              onClick={() => removeImage(index)}
                              className="h-8 w-8"
                            >
                              <X className="h-4 w-4" />
                            </Button>
                          </div>
                          {mainImageIndex === index && (
                            <span className="absolute left-2 top-2 rounded bg-primary px-2 py-1 text-xs text-primary-foreground">
                              Principal
                            </span>
                          )}
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </CardContent>
            </>
          )}

          {/* Step 9: Confirmación */}
          {currentStep === 9 && (
            <>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <CheckCircle className="h-5 w-5 text-primary" />
                  Confirmación
                </CardTitle>
                <CardDescription>Revisa la información antes de publicar</CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-4 rounded-lg bg-muted/50 p-4">
                  <h3 className="font-semibold">Resumen de tu publicación</h3>

                  <div className="grid gap-3 text-sm">
                    <div className="flex justify-between">
                      <span className="text-muted-foreground">Tipo:</span>
                      <span className="font-medium capitalize">{formData.tipoInmueble || "-"}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-muted-foreground">Nombre:</span>
                      <span className="font-medium">{formData.nombre || "-"}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-muted-foreground">Precio mensual:</span>
                      <span className="font-medium">
                        {formData.precioRenta
                          ? `$${Number(formData.precioRenta).toLocaleString()}`
                          : "-"}
                      </span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-muted-foreground">Ubicación:</span>
                      <span className="font-medium">
                        {formData.colonia
                          ? `${formData.colonia}, ${formData.municipio}`
                          : "-"}
                      </span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-muted-foreground">Imágenes:</span>
                      <span className="font-medium">{uploadedImages.length} fotos</span>
                    </div>
                  </div>
                </div>

                <div className="flex items-start space-x-3 rounded-lg border p-4">
                  <Checkbox
                    id="aceptaTerminos"
                    checked={formData.aceptaTerminos}
                    onCheckedChange={(checked) =>
                      handleInputChange("aceptaTerminos", checked as boolean)
                    }
                  />
                  <div>
                    <Label htmlFor="aceptaTerminos" className="cursor-pointer">
                      Acepto que la información proporcionada es correcta
                    </Label>
                    <p className="mt-1 text-sm text-muted-foreground">
                      Al publicar, confirmas que eres el propietario o tienes autorización
                      para rentar este inmueble.
                    </p>
                  </div>
                </div>
              </CardContent>
            </>
          )}

          {/* Navigation Buttons */}
          <div className="flex justify-between border-t p-6">
            <Button
              variant="outline"
              onClick={prevStep}
              disabled={currentStep === 1}
            >
              Anterior
            </Button>

            {currentStep < 9 ? (
              <Button onClick={nextStep} className="bg-primary text-primary-foreground">
                Siguiente
              </Button>
            ) : (
              <Button
                onClick={handleSubmit}
                disabled={!formData.aceptaTerminos}
                className="bg-accent text-accent-foreground hover:bg-accent/90"
              >
                Publicar Inmueble
              </Button>
            )}
          </div>
        </Card>
      </main>

      <Footer />
    </div>
  )
}
