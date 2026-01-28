"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { Search, MapPin, DollarSign, Home } from "lucide-react"

export function SearchBar() {
  const [location, setLocation] = useState("")
  const [priceRange, setPriceRange] = useState("")
  const [category, setCategory] = useState("")

  return (
    <div className="w-full rounded-xl bg-card p-6 shadow-lg">
      <h2 className="mb-4 text-center text-2xl font-semibold text-card-foreground">
        Encuentra tu próximo hogar en Ocosingo
      </h2>
      <div className="flex flex-col gap-4 md:flex-row">
        <div className="relative flex-1">
          <MapPin className="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
          <Input
            placeholder="Ubicación (ej: Centro, Las Margaritas...)"
            value={location}
            onChange={(e) => setLocation(e.target.value)}
            className="pl-10"
          />
        </div>
        
        <div className="relative w-full md:w-48">
          <DollarSign className="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
          <Select value={priceRange} onValueChange={setPriceRange}>
            <SelectTrigger className="pl-10">
              <SelectValue placeholder="Precio" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="0-2000">$0 - $2,000</SelectItem>
              <SelectItem value="2000-4000">$2,000 - $4,000</SelectItem>
              <SelectItem value="4000-6000">$4,000 - $6,000</SelectItem>
              <SelectItem value="6000+">$6,000+</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="relative w-full md:w-48">
          <Home className="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
          <Select value={category} onValueChange={setCategory}>
            <SelectTrigger className="pl-10">
              <SelectValue placeholder="Categoría" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="casa">Casa</SelectItem>
              <SelectItem value="departamento">Departamento</SelectItem>
              <SelectItem value="cuarto">Cuarto</SelectItem>
              <SelectItem value="local">Local Comercial</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <Button className="gap-2 bg-primary text-primary-foreground hover:bg-accent">
          <Search className="h-5 w-5" />
          Buscar
        </Button>
      </div>
    </div>
  )
}
