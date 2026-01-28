"use client"

import { useState } from "react"
import Image from "next/image"
import Link from "next/link"
import { Navbar } from "@/components/navbar"
import { Footer } from "@/components/footer"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Badge } from "@/components/ui/badge"
import { Input } from "@/components/ui/input"
import {
  Send,
  Search,
  MoreVertical,
  Phone,
  Home,
  CheckCheck,
  Clock,
} from "lucide-react"

interface Message {
  id: string
  text: string
  sender: "user" | "other"
  timestamp: Date
  read: boolean
}

interface Conversation {
  id: string
  contact: {
    name: string
    avatar: string
    online: boolean
  }
  property: {
    id: string
    title: string
    image: string
    price: number
  }
  messages: Message[]
  unread: number
  lastMessage: string
  lastMessageTime: Date
}

const mockConversations: Conversation[] = [
  {
    id: "conv1",
    contact: {
      name: "María García",
      avatar: "",
      online: true,
    },
    property: {
      id: "1",
      title: "Casa Amplia en Centro",
      image: "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&auto=format&fit=crop&q=60",
      price: 4500,
    },
    messages: [
      {
        id: "m1",
        text: "Hola, estoy interesado en la casa. ¿Sigue disponible?",
        sender: "user",
        timestamp: new Date("2026-01-25T10:30:00"),
        read: true,
      },
      {
        id: "m2",
        text: "¡Hola! Sí, la casa sigue disponible. ¿Te gustaría agendar una visita?",
        sender: "other",
        timestamp: new Date("2026-01-25T10:35:00"),
        read: true,
      },
      {
        id: "m3",
        text: "Sí, me gustaría verla. ¿Qué días tiene disponibles?",
        sender: "user",
        timestamp: new Date("2026-01-25T10:40:00"),
        read: true,
      },
      {
        id: "m4",
        text: "Puedo mostrártela el sábado a las 11am o el domingo a las 3pm. ¿Cuál te conviene más?",
        sender: "other",
        timestamp: new Date("2026-01-25T11:00:00"),
        read: false,
      },
    ],
    unread: 1,
    lastMessage: "Puedo mostrártela el sábado a las 11am o el domingo a las 3pm...",
    lastMessageTime: new Date("2026-01-25T11:00:00"),
  },
  {
    id: "conv2",
    contact: {
      name: "Roberto Hernández",
      avatar: "",
      online: false,
    },
    property: {
      id: "2",
      title: "Departamento Moderno",
      image: "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&auto=format&fit=crop&q=60",
      price: 3200,
    },
    messages: [
      {
        id: "m1",
        text: "Buenas tardes, ¿el departamento incluye servicios?",
        sender: "user",
        timestamp: new Date("2026-01-24T15:00:00"),
        read: true,
      },
      {
        id: "m2",
        text: "Buenas tardes. Incluye agua y mantenimiento. La luz y el internet van por cuenta del inquilino.",
        sender: "other",
        timestamp: new Date("2026-01-24T15:30:00"),
        read: true,
      },
    ],
    unread: 0,
    lastMessage: "Incluye agua y mantenimiento. La luz y el internet...",
    lastMessageTime: new Date("2026-01-24T15:30:00"),
  },
  {
    id: "conv3",
    contact: {
      name: "Ana López",
      avatar: "",
      online: true,
    },
    property: {
      id: "3",
      title: "Cuarto Amueblado",
      image: "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&auto=format&fit=crop&q=60",
      price: 1800,
    },
    messages: [
      {
        id: "m1",
        text: "¿El cuarto tiene baño propio o es compartido?",
        sender: "user",
        timestamp: new Date("2026-01-23T09:00:00"),
        read: true,
      },
      {
        id: "m2",
        text: "Es baño compartido con otro cuarto, pero siempre está limpio.",
        sender: "other",
        timestamp: new Date("2026-01-23T09:15:00"),
        read: true,
      },
      {
        id: "m3",
        text: "Entiendo, gracias. ¿Cuánto piden de depósito?",
        sender: "user",
        timestamp: new Date("2026-01-23T09:20:00"),
        read: true,
      },
      {
        id: "m4",
        text: "Un mes de depósito y uno de renta por adelantado.",
        sender: "other",
        timestamp: new Date("2026-01-23T09:25:00"),
        read: true,
      },
    ],
    unread: 0,
    lastMessage: "Un mes de depósito y uno de renta por adelantado.",
    lastMessageTime: new Date("2026-01-23T09:25:00"),
  },
]

export default function MessagesPage() {
  const [userMode, setUserMode] = useState<"inquilino" | "propietario">("inquilino")
  const [selectedConversation, setSelectedConversation] = useState<Conversation | null>(
    mockConversations[0]
  )
  const [newMessage, setNewMessage] = useState("")
  const [conversations, setConversations] = useState(mockConversations)
  const [searchQuery, setSearchQuery] = useState("")

  const handleModeSwitch = () => {
    setUserMode(userMode === "inquilino" ? "propietario" : "inquilino")
  }

  const handleSendMessage = () => {
    if (!newMessage.trim() || !selectedConversation) return

    const message: Message = {
      id: `m${Date.now()}`,
      text: newMessage,
      sender: "user",
      timestamp: new Date(),
      read: false,
    }

    setConversations((prev) =>
      prev.map((conv) =>
        conv.id === selectedConversation.id
          ? {
              ...conv,
              messages: [...conv.messages, message],
              lastMessage: newMessage,
              lastMessageTime: new Date(),
            }
          : conv
      )
    )

    setSelectedConversation((prev) =>
      prev
        ? {
            ...prev,
            messages: [...prev.messages, message],
            lastMessage: newMessage,
            lastMessageTime: new Date(),
          }
        : null
    )

    setNewMessage("")
  }

  const filteredConversations = conversations.filter(
    (conv) =>
      conv.contact.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      conv.property.title.toLowerCase().includes(searchQuery.toLowerCase())
  )

  const formatTime = (date: Date) => {
    const now = new Date()
    const diffDays = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60 * 24))

    if (diffDays === 0) {
      return date.toLocaleTimeString("es-MX", { hour: "2-digit", minute: "2-digit" })
    } else if (diffDays === 1) {
      return "Ayer"
    } else if (diffDays < 7) {
      return date.toLocaleDateString("es-MX", { weekday: "short" })
    } else {
      return date.toLocaleDateString("es-MX", { day: "numeric", month: "short" })
    }
  }

  return (
    <div className="flex min-h-screen flex-col">
      <Navbar
        isLoggedIn={true}
        onLoginClick={() => {}}
        userMode={userMode}
        onModeSwitch={handleModeSwitch}
      />

      <main className="flex flex-1 overflow-hidden">
        <div className="container mx-auto flex h-[calc(100vh-64px-80px)] max-w-6xl gap-0 p-0 lg:gap-4 lg:p-4">
          {/* Conversations List */}
          <div
            className={`w-full flex-shrink-0 border-r border-border bg-card lg:w-80 lg:rounded-xl lg:border ${
              selectedConversation ? "hidden lg:block" : "block"
            }`}
          >
            <div className="flex h-full flex-col">
              <div className="border-b border-border p-4">
                <h1 className="mb-4 text-xl font-bold text-card-foreground">Mensajes</h1>
                <div className="relative">
                  <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                  <Input
                    placeholder="Buscar conversación..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="pl-10"
                  />
                </div>
              </div>

              <div className="flex-1 overflow-y-auto">
                {filteredConversations.length === 0 ? (
                  <div className="flex h-full items-center justify-center p-4 text-center text-muted-foreground">
                    No hay conversaciones
                  </div>
                ) : (
                  filteredConversations.map((conv) => (
                    <button
                      key={conv.id}
                      onClick={() => setSelectedConversation(conv)}
                      className={`flex w-full items-start gap-3 border-b border-border p-4 text-left transition-colors hover:bg-muted/50 ${
                        selectedConversation?.id === conv.id ? "bg-muted/50" : ""
                      }`}
                    >
                      <div className="relative">
                        <Avatar>
                          <AvatarImage src={conv.contact.avatar || "/placeholder.svg"} />
                          <AvatarFallback>
                            {conv.contact.name
                              .split(" ")
                              .map((n) => n[0])
                              .join("")}
                          </AvatarFallback>
                        </Avatar>
                        {conv.contact.online && (
                          <span className="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-card bg-green-500" />
                        )}
                      </div>
                      <div className="min-w-0 flex-1">
                        <div className="flex items-center justify-between">
                          <span className="font-medium text-card-foreground">
                            {conv.contact.name}
                          </span>
                          <span className="text-xs text-muted-foreground">
                            {formatTime(conv.lastMessageTime)}
                          </span>
                        </div>
                        <p className="truncate text-sm text-muted-foreground">
                          {conv.property.title}
                        </p>
                        <p className="truncate text-sm text-muted-foreground">
                          {conv.lastMessage}
                        </p>
                      </div>
                      {conv.unread > 0 && (
                        <Badge className="bg-accent text-accent-foreground">
                          {conv.unread}
                        </Badge>
                      )}
                    </button>
                  ))
                )}
              </div>
            </div>
          </div>

          {/* Chat Area */}
          {selectedConversation ? (
            <div className="flex flex-1 flex-col bg-card lg:rounded-xl lg:border lg:border-border">
              {/* Chat Header */}
              <div className="flex items-center justify-between border-b border-border p-4">
                <div className="flex items-center gap-3">
                  <Button
                    variant="ghost"
                    size="sm"
                    className="lg:hidden"
                    onClick={() => setSelectedConversation(null)}
                  >
                    Volver
                  </Button>
                  <Avatar>
                    <AvatarImage src={selectedConversation.contact.avatar || "/placeholder.svg"} />
                    <AvatarFallback>
                      {selectedConversation.contact.name
                        .split(" ")
                        .map((n) => n[0])
                        .join("")}
                    </AvatarFallback>
                  </Avatar>
                  <div>
                    <h2 className="font-semibold text-card-foreground">
                      {selectedConversation.contact.name}
                    </h2>
                    <p className="text-sm text-muted-foreground">
                      {selectedConversation.contact.online ? "En línea" : "Desconectado"}
                    </p>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <Button variant="ghost" size="icon">
                    <Phone className="h-5 w-5" />
                  </Button>
                  <Button variant="ghost" size="icon">
                    <MoreVertical className="h-5 w-5" />
                  </Button>
                </div>
              </div>

              {/* Property Reference */}
              <Link
                href={`/propiedad/${selectedConversation.property.id}`}
                className="flex items-center gap-3 border-b border-border bg-muted/30 p-3 transition-colors hover:bg-muted/50"
              >
                <div className="relative h-12 w-16 overflow-hidden rounded-lg">
                  <Image
                    src={selectedConversation.property.image || "/placeholder.svg"}
                    alt={selectedConversation.property.title}
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="flex-1">
                  <p className="font-medium text-card-foreground">
                    {selectedConversation.property.title}
                  </p>
                  <p className="text-sm text-accent">
                    ${selectedConversation.property.price.toLocaleString()}/mes
                  </p>
                </div>
                <Home className="h-5 w-5 text-muted-foreground" />
              </Link>

              {/* Messages */}
              <div className="flex-1 overflow-y-auto p-4">
                <div className="space-y-4">
                  {selectedConversation.messages.map((message) => (
                    <div
                      key={message.id}
                      className={`flex ${message.sender === "user" ? "justify-end" : "justify-start"}`}
                    >
                      <div
                        className={`max-w-[75%] rounded-2xl px-4 py-2 ${
                          message.sender === "user"
                            ? "bg-primary text-primary-foreground"
                            : "bg-muted text-card-foreground"
                        }`}
                      >
                        <p>{message.text}</p>
                        <div
                          className={`mt-1 flex items-center justify-end gap-1 text-xs ${
                            message.sender === "user"
                              ? "text-primary-foreground/70"
                              : "text-muted-foreground"
                          }`}
                        >
                          <span>
                            {message.timestamp.toLocaleTimeString("es-MX", {
                              hour: "2-digit",
                              minute: "2-digit",
                            })}
                          </span>
                          {message.sender === "user" &&
                            (message.read ? (
                              <CheckCheck className="h-3 w-3" />
                            ) : (
                              <Clock className="h-3 w-3" />
                            ))}
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              {/* Message Input */}
              <div className="border-t border-border p-4">
                <form
                  onSubmit={(e) => {
                    e.preventDefault()
                    handleSendMessage()
                  }}
                  className="flex items-center gap-2"
                >
                  <Input
                    placeholder="Escribe un mensaje..."
                    value={newMessage}
                    onChange={(e) => setNewMessage(e.target.value)}
                    className="flex-1"
                  />
                  <Button
                    type="submit"
                    size="icon"
                    className="bg-primary text-primary-foreground hover:bg-accent"
                    disabled={!newMessage.trim()}
                  >
                    <Send className="h-5 w-5" />
                  </Button>
                </form>
              </div>
            </div>
          ) : (
            <div className="hidden flex-1 items-center justify-center bg-card text-muted-foreground lg:flex lg:rounded-xl lg:border lg:border-border">
              Selecciona una conversación para comenzar
            </div>
          )}
        </div>
      </main>
    </div>
  )
}
