# Documento de Casos de Uso - Proyecto ArrendaOco 🏠

Este documento contiene la especificación completa de los Casos de Uso del sistema ArrendaOco, cubriendo la totalidad de los requerimientos funcionales (RF-01 al RF-31).

---

## 1. Módulo de Gestión de Inmuebles (MGI)

| Nombre del caso de uso: | Registrar Inmueble (Alta) |
|:---|:---|
| **ID caso de uso:** | **CU-001** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-08 |
| **Actor primario:** | Propietarios |
| **Descripción:** | El propietario ingresa los datos de una nueva propiedad para su publicación. |
| **Precondición:** | Sesión iniciada como Propietario. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario llena el formulario de alta.<br>2. El usuario pulsa "Publicar". | 3. El sistema valida campos y fotos.<br>4. El sistema guarda el registro y confirma éxito. |
| **Curso Alterno de Eventos** | |
| **A. Error de datos:** | El sistema señala campos vacíos o corruptos. |
| **Post condición:** | El inmueble es visible en el catálogo. |

---

| Nombre del caso de uso: | Visualizar Detalle de Inmueble |
|:---|:---|
| **ID caso de uso:** | **CU-002** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-11 |
| **Actor primario:** | Visitantes / Inquilinos |
| **Descripción:** | Consulta de la ficha técnica y mapa de un inmueble. |
| **Precondición:** | Inmueble registrado. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario selecciona un inmueble. | 2. El sistema recupera fotos, descripción y mapa. |
| **Post condición:** | El usuario visualiza la información completa. |

---

| Nombre del caso de uso: | Modificar Información de Inmueble |
|:---|:---|
| **ID caso de uso:** | **CU-003** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-14 |
| **Actor primario:** | Propietarios |
| **Descripción:** | Actualización de precios, fotos o descripción. |
| **Precondición:** | El inmueble debe pertenecer al usuario. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario edita los campos.<br>2. El usuario pulsa "Guardar". | 3. El sistema valida y actualiza la base de datos. |
| **Post condición:** | Los cambios se reflejan en el portal. |

---

| Nombre del caso de uso: | Eliminar Inmueble (Baja) |
|:---|:---|
| **ID caso de uso:** | **CU-004** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-15 |
| **Actor primario:** | Propietarios / Admin |
| **Descripción:** | Remoción definitiva de una propiedad. |
| **Precondición:** | No debe tener contratos vigentes. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Seleccionar "Eliminar" y confirmar. | 2. El sistema valida estatus y borra el registro. |
| **Post condición:** | El inmueble desaparece del sistema. |

---

## 2. Módulo de Autenticación y Perfiles (MAP)

| Nombre del caso de uso: | Registro de Usuario |
|:---|:---|
| **ID caso de uso:** | **CU-005** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-01 |
| **Actor primario:** | Visitante |
| **Descripción:** | Creación de cuenta nueva. |
| **Precondición:** | Email no registrado previamente. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Ingresar nombre, email y clave. | 2. El sistema valida y crea el perfil. |
| **Post condición:** | Usuario registrado y autenticado. |

---

| Nombre del caso de uso: | Consultar Perfil |
|:---|:---|
| **ID caso de uso:** | **CU-006** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-02 |
| **Actor primario:** | Usuarios Autenticados |
| **Descripción:** | Vista de datos personales. |
| **Precondición:** | Sesión iniciada. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Acceder a "Mi Perfil". | 2. El sistema despliega datos y foto. |
| **Post condición:** | El usuario visualiza su información. |

---

| Nombre del caso de uso: | Modificar Datos de Usuario |
|:---|:---|
| **ID caso de uso:** | **CU-007** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-03 |
| **Actor primario:** | Usuarios Autenticados |
| **Descripción:** | Actualizar nombre, teléfono o foto. |
| **Precondición:** | Sesión iniciada. |
| **Curso Normal de Event de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Editar campos y guardar. | 2. El sistema valida y actualiza. |
| **Post condición:** | Perfil actualizado con éxito. |

---

| Nombre del caso de uso: | Eliminar Cuenta de Usuario |
|:---|:---|
| **ID caso de uso:** | **CU-008** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-05 |
| **Actor primario:** | Usuarios / Admin |
| **Descripción:** | Baja definitiva de la plataforma. |
| **Precondición:** | Sin contratos vigentes. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Solicitar baja y confirmar. | 2. El sistema borra el perfil y cierra sesión. |
| **Post condición:** | Datos removidos de la DB. |

---

## 3. Módulo de Interacción y Reseñas (MIR)

| Nombre del caso de uso: | Registrar Reseña (Alta) |
|:---|:---|
| **ID caso de uso:** | **CU-009** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-16 |
| **Actor primario:** | Inquilinos |
| **Descripción:** | Calificar experiencia post-renta. |
| **Precondición:** | Contrato finalizado. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Calificar con estrellas y texto. | 2. El sistema valida elegibilidad y guarda. |
| **Post condición:** | Reseña visible en el inmueble. |

---

| Nombre del caso de uso: | Visualizar Reseñas |
|:---|:---|
| **ID caso de uso:** | **CU-010** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-17 |
| **Actor primario:** | Público General |
| **Descripción:** | Ver opiniones de la comunidad. |
| **Precondición:** | Inmueble con reseñas. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Ver sección de comentarios. | 2. El sistema lista reseñas y promedio. |
| **Post condición:** | Transparencia de reputación lograda. |

---

| Nombre del caso de uso: | Editar Reseña Propia |
|:---|:---|
| **ID caso de uso:** | **CU-011** |
| **Prioridad:** | Baja |
| **Requisitos:** | RF-18 |
| **Actor primario:** | Inquilinos (Autores) |
| **Descripción:** | Corregir calificación o texto. |
| **Precondición:** | Ser el autor de la reseña. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Editar comentario y guardar. | 2. El sistema actualiza y recalcula ranking. |
| **Post condición:** | Cambios aplicados visualmente. |

---

| Nombre del caso de uso: | Eliminar / Moderar Reseña |
|:---|:---|
| **ID caso de uso:** | **CU-012** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-19 |
| **Actor primario:** | Autor / Admin |
| **Descripción:** | Borrado de comentario. |
| **Precondición:** | Reseña existente. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Borrar y confirmar. | 2. El sistema elimina registro. |
| **Post condición:** | Comentario fuera del sistema. |

---

## 4. Módulo de Contratación (MCF)

| Nombre del caso de uso: | Generar Nuevo Contrato |
|:---|:---|
| **ID caso de uso:** | **CU-013** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-20, RF-21 |
| **Actor primario:** | Propietarios / Admin |
| **Descripción:** | Vinculación legal y financiera. |
| **Precondición:** | Inmueble disponible. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Definir partes, montos y fechas. | 2. El sistema crea contrato y fichas de pago. |
| **Post condición:** | Inmueble pasa a estatus "Rentado". |

---

| Nombre del caso de uso: | Visualizar Detalles de Contrato |
|:---|:---|
| **ID caso de uso:** | **CU-014** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-24 |
| **Actor primario:** | Partes involucradas |
| **Descripción:** | Consulta de estatus y cláusulas. |
| **Precondición:** | Contrato existente. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Ver "Mis Contratos". | 2. El sistema muestra ficha técnica. |
| **Post condición:** | Transparencia de términos lograda. |

---

| Nombre del caso de uso: | Actualizar Términos Contractuales |
|:---|:---|
| **ID caso de uso:** | **CU-015** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-26 |
| **Actor primario:** | Admin |
| **Descripción:** | Ajuste de rentas o vigencias. |
| **Precondición:** | Mutuo acuerdo. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Modificar campos permitidos. | 2. El sistema actualiza y notifica. |
| **Post condición:** | Nuevas condiciones vigentes. |

---

| Nombre del caso de uso: | Finalización de Contrato |
|:---|:---|
| **ID caso de uso:** | **CU-016** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-26 |
| **Actor primario:** | Propietarios / Admin |
| **Descripción:** | Cierre de relación de arrendamiento. |
| **Precondición:** | No adeudos. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Ejecutar finalización. | 2. El sistema libera el inmueble (Disponible). |
| **Post condición:** | Propiedad vuelve al catálogo. |

---

## 5. Módulo de Pagos y Finanzas (MCF)

| Nombre del caso de uso: | Generar Ficha de Pago |
|:---|:---|
| **ID caso de uso:** | **CU-017** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-21 |
| **Actor primario:** | Sistema (Automático) |
| **Descripción:** | Creación de obligación mensual. |
| **Precondición:** | Contrato activo. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. (Automático mensual). | 2. El sistema calcula monto y crea registro. |
| **Post condición:** | Deuda aparece en panel del Inquilino. |

---

| Nombre del caso de uso: | Consultar Historial de Pagos |
|:---|:---|
| **ID caso de uso:** | **CU-018** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-24 |
| **Actor primario:** | Usuarios Autenticados |
| **Descripción:** | Ver balance y recibos. |
| **Precondición:** | Poseer transacciones. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Ver "Mis Movimientos". | 2. El sistema lista cobros y estados. |
| **Post condición:** | Situación financiera clara. |

---

| Nombre del caso de uso: | Validar y Registrar Pago |
|:---|:---|
| **ID caso de uso:** | **CU-019** |
| **Prioridad:** | Crítica |
| **Requisitos:** | RF-22 |
| **Actor primario:** | Propietarios / Admin |
| **Descripción:** | Confirmación de dinero recibido. |
| **Precondición:** | Pago en estatus "Pendiente". |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Ingresar referencia y validar. | 2. El sistema marca como "Pagado" y emite recibo. |
| **Post condición:** | Mensualidad saldada. |

---

| Nombre del caso de uso: | Anular o Corregir Pago |
|:---|:---|
| **ID caso de uso:** | **CU-020** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-22 |
| **Actor primario:** | Admin |
| **Descripción:** | Reversión por error o cheque devuelto. |
| **Precondición:** | Pago validado previamente. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Seleccionar anulación y justificar. | 2. El sistema revierte estatus a Pendiente. |
| **Post condición:** | Deuda reactivada en la DB. |

---

## 6. Módulo IA, Reportes y Avanzados

| Nombre del caso de uso: | Interacción y Recomendación con ROCO |
|:---|:---|
| **ID caso de uso:** | **CU-021** |
| **Prioridad:** | Media-Alta |
| **Requisitos:** | RF-27 |
| **Actor primario:** | Todos los Usuarios |
| **Descripción:** | Chat inteligente para dudas y sugerencias. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Preguntar por inmuebles o procesos. | 2. El sistema procesa vía Gemini y recomienda. |
| **Post condición:** | Usuario asistido por IA. |

---

| Nombre del caso de uso: | Generar Reporte de Usuarios |
|:---|:---|
| **ID caso de uso:** | **CU-022** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-07 |
| **Actor primario:** | Admin |
| **Descripción:** | Listado estadístico de la comunidad. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Filtrar por rol y generar. | 2. El sistema procesa la DB y muestra métricas. |
| **Post condición:** | Visión administrativa de crecimiento. |

---

| Nombre del caso de uso: | Reporte de Popularidad del Inventario |
|:---|:---|
| **ID caso de uso:** | **CU-023** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-31 |
| **Actor primario:** | Admin |
| **Descripción:** | Ranking de favoritos y zonas demandadas. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Ver análisis de inventario. | 2. El sistema suma favoritos y visualizaciones. |
| **Post condición:** | Identificación de mayor rentabilidad. |

---

| Nombre del caso de uso: | Búsqueda y Filtrado Avanzado |
|:---|:---|
| **ID caso de uso:** | **CU-024** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-10 |
| **Actor primario:** | Público General |
| **Descripción:** | Refinar catálogo por precio o zona. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Aplicar filtros. | 2. El sistema actualiza lista con coincidencias. |
| **Post condición:** | Búsqueda precisa lograda. |

---

| Nombre del caso de uso: | Gestionar Lista de Favoritos |
|:---|:---|
| **ID caso de uso:** | **CU-025** |
| **Prioridad:** | Media-Alta |
| **Requisitos:** | RF-12 |
| **Actor primario:** | Inquilinos |
| **Descripción:** | Guardar propiedades de interés. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Pulsar ícono de corazón. | 2. El sistema vincula inmueble al perfil. |
| **Post condición:** | Propiedad guardada en panel personal. |

---

| Nombre del caso de uso: | Generar Recibo de Pago (PDF) |
|:---|:---|
| **ID caso de uso:** | **CU-026** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-25 |
| **Actor primario:** | Usuarios Autenticados |
| **Descripción:** | Descarga física de comprobantes. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Pulsar "Descargar Recibo". | 2. El sistema genera PDF con diseño oficial. |
| **Post condición:** | Documento descargado en dispositivo. |

---

| Nombre del caso de uso: | Visualizar Dashboard Administrativo |
|:---|:---|
| **ID caso de uso:** | **CU-027** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-28 |
| **Actor primario:** | Admin |
| **Descripción:** | Vista panorámica de KPIs globales. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Acceder al dashboard principal. | 2. El sistema carga métricas de ingresos y usuarios. |
| **Post condición:** | Visión estratégica inmediata. |

---

| Nombre del caso de uso: | Moderación de Contenido Global |
|:---|:---|
| **ID caso de uso:** | **CU-028** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-29 |
| **Actor primario:** | Admin |
| **Descripción:** | Filtrar reseñas o anuncios falsos. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Detectar contenido ofensivo. | 2. Ocultar o eliminar registro permanentemente. |
| **Post condición:** | Comunidad segura y confiable. |

---

| Nombre del caso de uso: | Suspender o Activar Usuario |
|:---|:---|
| **ID caso de uso:** | **CU-029** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-04 |
| **Actor primario:** | Admin |
| **Descripción:** | Control de accesos por conducta. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Inhabilitar usuario. | 2. El sistema bloquea login y sesiones activas. |
| **Post condición:** | Acceso restringido con éxito. |

---

| Nombre del caso de uso: | Recuperación de Contraseña |
|:---|:---|
| **ID caso de uso:** | **CU-030** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-06 |
| **Actor primario:** | Todos los Usuarios |
| **Descripción:** | Restablecer seguridad vía Email. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. Solicitar token. | 2. El sistema envía enlace seguro a cuenta registrada. |
| **Post condición:** | Clave actualizada y acceso recuperado. |
