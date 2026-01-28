"use client"

import Image from "next/image"
import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { MapPin, Bed, Bath, Square } from "lucide-react"

export interface Property {
  id: string
  title: string
  price: number
  location: string
  category: string
  bedrooms: number
  bathrooms: number
  area: number
  image: string
  featured?: boolean
}

interface PropertyCardProps {
  property: Property
  onViewClick: (propertyId: string) => void
}

export function PropertyCard({ property, onViewClick }: PropertyCardProps) {
  return (
    <Card className="group overflow-hidden bg-card transition-shadow hover:shadow-xl">
      <div className="relative aspect-[4/3] overflow-hidden">
        <Image
          src={property.image || "/placeholder.svg"}
          alt={property.title}
          fill
          className="object-cover transition-transform group-hover:scale-105"
        />
        {property.featured && (
          <Badge className="absolute left-3 top-3 bg-accent text-accent-foreground">
            Destacado
          </Badge>
        )}
        <Badge variant="secondary" className="absolute right-3 top-3 capitalize">
          {property.category}
        </Badge>
      </div>
      <CardContent className="p-4">
        <div className="mb-2 flex items-start justify-between">
          <h3 className="line-clamp-1 text-lg font-semibold text-card-foreground">
            {property.title}
          </h3>
          <span className="text-lg font-bold text-accent">
            ${property.price.toLocaleString()}/mes
          </span>
        </div>
        
        <div className="mb-3 flex items-center gap-1 text-muted-foreground">
          <MapPin className="h-4 w-4" />
          <span className="text-sm">{property.location}</span>
        </div>

        <div className="mb-4 flex items-center gap-4 text-sm text-muted-foreground">
          <div className="flex items-center gap-1">
            <Bed className="h-4 w-4" />
            <span>{property.bedrooms}</span>
          </div>
          <div className="flex items-center gap-1">
            <Bath className="h-4 w-4" />
            <span>{property.bathrooms}</span>
          </div>
          <div className="flex items-center gap-1">
            <Square className="h-4 w-4" />
            <span>{property.area}m²</span>
          </div>
        </div>

        <Button
          onClick={() => onViewClick(property.id)}
          className="w-full bg-primary text-primary-foreground hover:bg-accent"
        >
          Ver Inmueble
        </Button>
      </CardContent>
    </Card>
  )
}
