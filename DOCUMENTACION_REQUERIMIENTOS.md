# Especificación de Requerimientos Funcionales - ArrendaOco 🏠

Este documento detalla los requerimientos funcionales del sistema **ArrendaOco**, estructurados bajo el estándar de fichas técnicas para asegurar un desarrollo preciso de los módulos del sistema.

---

## 1. Módulo de Autenticación y Perfiles (MAP)

| Campo | Detalle |
|:---|:---|
| **ID:** | RF-01 |
| **Nombre:** | **Registro de Usuarios (Create)** |
| **Descripción:** | El sistema permitirá la creación de nuevas cuentas de usuario para acceder a las funcionalidades de la plataforma. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Validar obligatoriedad de: nombre, email y contraseña.<br>• Verificar que el correo no esté registrado previamente.<br>• Asignación automática de rol de "Inquilino" y estatus "Activo". |
| **Dependencias:** | Base de datos de usuarios |
| **Notas adicionales:** | Soporta carga de foto de perfil opcional. |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | RF-02 |
| **Nombre:** | **Consulta y Perfil (Read)** |
| **Descripción:** | El sistema permitirá visualizar la información del perfil del usuario autenticado o del listado administrativo. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • El usuario podrá ver sus datos personales en su panel.<br>• El administrador podrá listar todos los usuarios registrados.<br>• Visualización clara de rol y estatus actual. |
| **Dependencias:** | RF-01 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | RF-03 |
| **Nombre:** | **Actualización de Información (Update)** |
| **Descripción:** | El sistema permitirá modificar los datos básicos de la cuenta del usuario. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Edición de nombre, foto de perfil y cambio de contraseña.<br>• Validación de formato de datos antes de guardar.<br>• Solo el administrador podrá modificar el rol asignado. |
| **Dependencias:** | RF-01, RF-02 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | RF-04 |
| **Nombre:** | **Suspensión y Activación (Estatus)** |
| **Descripción:** | El sistema permitirá cambiar el estado operativo de un usuario (Activo/Inactivo) sin eliminar su información. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • El administrador podrá marcar a un usuario como "Inactivo" para restringir su acceso.<br>• Un usuario "Inactivo" no podrá iniciar sesión ni realizar operaciones.<br>• El sistema debe permitir revertir el estado a "Activo" en cualquier momento. |
| **Dependencias:** | RF-01, RF-02 |
| **Notas adicionales:** | Útil para inhabilitar usuarios por falta de pago o conducta inapropiada. |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | RF-05 |
| **Nombre:** | **Eliminación Definitiva (Delete)** |
| **Descripción:** | El sistema permitirá borrar permanentemente el registro de un usuario de la base de datos. |
| **Prioridad:** | Media-Alta |
| **Criterios de aceptación:** | • Solo el administrador (o el propio usuario previa validación) puede ejecutar la baja definitiva.<br>• El sistema debe verificar que no existan contratos activos vinculados antes de proceder.<br>• Se requiere confirmación de seguridad para evitar borrados accidentales. |
| **Dependencias:** | RF-01, RF-04 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | RF-06 |
| **Nombre:** | **Recuperación de Credenciales** |
| **Descripción:** | El sistema permitirá a los usuarios restablecer su contraseña en caso de olvido mediante un flujo seguro por correo electrónico. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • El usuario debe ingresar su correo electrónico registrado.<br>• El sistema enviará un token de un solo uso con vigencia limitada al correo proporcionado.<br>• El usuario podrá definir una nueva contraseña tras validar el token. |
| **Dependencias:** | RF-01, Servidor de Correo (SMTP) |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | RF-07 |
| **Nombre:** | **Reportes Administrativos de Usuarios** |
| **Descripción:** | El sistema permitirá al administrador generar reportes estadísticos y detallados sobre los usuarios de la plataforma. |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | • Generación de un reporte que clasifique usuarios por rol (Inquilino/Propietario).<br>• Visualización de métricas de crecimiento (usuarios nuevos por mes).<br>• Exportación de la lista de usuarios en formato PDF o Excel. |
| **Dependencias:** | RF-01, RF-02 |
| **Notas adicionales:** | |

---

## 2. Módulo de Gestión de Inmuebles (MGI)

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-08** |
| **Nombre:** | **Alta de Inmuebles (Create)** |
| **Descripción:** | El sistema permitirá a los propietarios registrar nuevas propiedades detallando sus características físicas, técnicas y financieras. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Validar campos obligatorios: título, dirección, precio de renta y depósito.<br>• El inmueble debe quedar vinculado automáticamente al ID del propietario autenticado. |
| **Dependencias:** | RF-01 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-09** |
| **Nombre:** | **Inventario Público (Read - List)** |
| **Descripción:** | Visualización del catálogo general de inmuebles disponibles para renta, accesible para todos los visitantes. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Mostrar tarjetas con información resumida y foto principal.<br>• Implementar paginación activa para optimizar la carga del servidor. |
| **Dependencias:** | RF-08 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-10** |
| **Nombre:** | **Filtros y Búsqueda (Read - Filter)** |
| **Descripción:** | Herramientas avanzadas para que el usuario pueda refinar los resultados del catálogo según sus preferencias. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Filtro por rango de precio, tipo de inmueble y ubicación geográfica.<br>• Los resultados deben actualizarse según los criterios aplicados. |
| **Dependencias:** | RF-09 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-11** |
| **Nombre:** | **Ver Detalle del Inmueble (Read - Detail)** |
| **Descripción:** | Despliegue de la ficha técnica completa, galería multimedia y ubicación exacta de una propiedad específica. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Mostrar galería completa de imágenes, descripción y amenidades.<br>• Visualización de la ubicación exacta en un mapa interactivo. |
| **Dependencias:** | RF-09 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-12** |
| **Nombre:** | **Lista de Favoritos (Read - Favorites)** |
| **Descripción:** | Gestión y consulta de la lista personalizada de inmuebles preferidos del usuario. |
| **Prioridad:** | Media-Alta |
| **Criterios de aceptación:** | • El usuario podrá marcar/desmarcar inmuebles como favoritos.<br>• El sistema debe ofrecer una vista exclusiva con todos los favoritos del usuario. |
| **Dependencias:** | RF-01, RF-11 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-13** |
| **Nombre:** | **Gestión de "Mis Propiedades" (Owner View)** |
| **Descripción:** | Panel privado para que el propietario gestione sus propias publicaciones de inmuebles. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Listado privado de propiedades propias con estatus operativo.<br>• Acceso rápido a las funciones de edición y eliminación. |
| **Dependencias:** | RF-08 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-14** |
| **Nombre:** | **Modificación de Información (Update)** |
| **Descripción:** | El sistema permitirá al propietario actualizar los datos técnicos, financieros o multimedia de un inmueble registrado. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Solo el propietario o el administrador tienen permisos para realizar cambios.<br>• Validación estricta de datos al guardar.<br>• Permitir gestión de la galería de imágenes. |
| **Dependencias:** | RF-11, RF-13 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-15** |
| **Nombre:** | **Baja Definitiva de Inmueble (Delete)** |
| **Descripción:** | Eliminación permanente de un registro de inmueble de la plataforma. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Solicitar confirmación de seguridad.<br>• El sistema debe impedir la eliminación si existen contratos vigentes asociados. |
| **Dependencias:** | RF-08 |
| **Notas adicionales:** | |

---

## 3. Módulo de Interacción y Reseñas (MIR) - CRUD Completo

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-16** |
| **Nombre:** | **Registro de Reseñas (Create)** |
| **Descripción:** | El sistema permitirá a los inquilinos calificar y comentar su experiencia en un inmueble una vez finalizado su contrato. |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | • El usuario debe asignar una calificación (estrellas) y un comentario de texto.<br>• **Restricción:** Solo usuarios que hayan tenido un contrato previo con el inmueble pueden reseñar.<br>• El sistema debe evitar reseñas duplicadas para un mismo contrato. |
| **Dependencias:** | RF-01, RF-11 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-17** |
| **Nombre:** | **Visualización de Reseñas (Read)** |
| **Descripción:** | El sistema mostrará las opiniones de otros usuarios tanto en la ficha del inmueble como en perfiles públicos o paneles administrativos. |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | • Las reseñas serán visibles para cualquier visitante en el detalle del inmueble.<br>• Se debe mostrar el promedio de calificación (rating) del inmueble de forma prominente.<br>• El administrador podrá visualizar todas las reseñas del sistema para fines de moderación. |
| **Dependencias:** | RF-16 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-18** |
| **Nombre:** | **Modificación de Reseñas (Update)** |
| **Descripción:** | El sistema permitirá a los autores corregir o actualizar sus comentarios y calificaciones previamente publicados. |
| **Prioridad:** | Baja-Media |
| **Criterios de aceptación:** | • Solo el autor original de la reseña puede editarla.<br>• La edición debe actualizar el promedio de calificación del inmueble en tiempo real. |
| **Dependencias:** | RF-16, RF-17 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-19** |
| **Nombre:** | **Eliminación de Reseñas (Delete)** |
| **Descripción:** | El sistema permitirá remover reseñas de forma definitiva de la base de datos. |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | • El autor puede eliminar su propia reseña.<br>• El administrador tiene la facultad de eliminar reseñas que incumplan con las normas de la comunidad (moderación).<br>• Al eliminar una reseña, se debe recalcular el promedio de calificación del inmueble. |
| **Dependencias:** | RF-16, RF-17 |
| **Notas adicionales:** | |

---

## 4. Módulo de Contratación y Finanzas (MCF)

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-20** |
| **Nombre:** | **Generación de Contratos Digitales (Create)** |
| **Descripción:** | El sistema permitirá formalizar un arrendamiento vinculando a un propietario, un inquilino y un inmueble específico. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Definir fechas de inicio/fin, renta y depósito.<br>• Cambio automático de estatus a "Rentado" al activar el contrato. |
| **Dependencias:** | RF-01, RF-08 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-21** |
| **Nombre:** | **Gestión de Cobros y Mensualidades (Create)** |
| **Descripción:** | Generación automática de fichas de pago mensuales asociadas a cada contrato vigente. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Creación de registros de pago pendientes por cada mes de vigencia.<br>• Cada pago incluye monto base y fecha límite. |
| **Dependencias:** | RF-20 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-22** |
| **Nombre:** | **Registro y Validación de Pagos (Update)** |
| **Descripción:** | Capacidad de marcar cobros como "Pagados" y almacenar información del comprobante. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Cambio de estatus de "Pendiente" a "Completado".<br>• Registro de fecha exacta y referencia del pago. |
| **Dependencias:** | RF-21 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-23** |
| **Nombre:** | **Cálculo Automático de Recargos** |
| **Descripción:** | Aplicación de penalizaciones financieras por mora en el pago de rentas. |
| **Prioridad:** | Media-Alta |
| **Criterios de aceptación:** | • Comparación automática de fecha límite vs fecha actual.<br>• Sumatoria de recargo configurado al monto total pendiente. |
| **Dependencias:** | RF-21, RF-22 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-24** |
| **Nombre:** | **Generación de Estado de Cuenta (Read)** |
| **Descripción:** | Vista detallada de movimientos financieros asociados a un contrato. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Lista cronológica de pagos y recargos.<br>• Presentación de balances totales. |
| **Dependencias:** | RF-20, RF-21 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-25** |
| **Nombre:** | **Exportación Documental (PDF/Excel)** |
| **Descripción:** | Descarga del estado de cuenta en formatos administrativos externos. |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | • Generación de PDF con diseño profesional.<br>• Exportación de datos a Excel. |
| **Dependencias:** | RF-24 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-26** |
| **Nombre:** | **Finalización y Renovación de Contratos (Update)** |
| **Descripción:** | Gestión del ciclo de vida y cierre de los arrendamientos en el sistema. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Regreso automático del inmueble a estatus "Disponible" al cerrar contraro.<br>• Validación de deuda cero para finalización exitosa. |
| **Dependencias:** | RF-20 |
| **Notas adicionales:** | |

---

## 5. Módulo de Asistencia Inteligente (MAI)

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-27** |
| **Nombre:** | **Asistente Virtual ROCO (Interacción IA)** |
| **Descripción:** | Interfaz de chat inteligente basada en IA para resolver dudas de los usuarios sobre inmuebles y procesos. |
| **Prioridad:** | Media-Alta |
| **Criterios de aceptación:** | • Procesamiento de lenguaje natural mediante la API de Gemini 2.0 Flash.<br>• El asistente debe responder usando el contexto de los inmuebles registrados.<br>• Capacidad de recomendar inmuebles según las preferencias del usuario en el chat. |
| **Dependencias:** | RF-09, RF-11, API de Gemini |
| **Notas adicionales:** | Incluye la personalización estética de la mascota Arrendito. |

---

## 6. Módulo Administrativo Global (MAG)

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-28** |
| **Nombre:** | **Panel de Control Administrativo (Dashboard)** |
| **Descripción:** | Centro de mando para el administrador con vista panorámica del estado de la plataforma. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Visualización de métricas generales (Ventas totales, Usuarios activos, Inmuebles rentados).<br>• Accesos rápidos a la gestión de usuarios, inmuebles y contratos.<br>• Alertas sobre pagos vencidos o contratos por expirar. |
| **Dependencias:** | Todo el sistema |
| **Notas adicionales:** | Protegido por middleware de administrador. |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-29** |
| **Nombre:** | **Moderación de Contenido Global** |
| **Descripción:** | Capacidad del administrador para supervisar y filtrar información inapropiada en la plataforma. |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | • Eliminar reseñas ofensivas o malintencionadas.<br>• Dar de baja inmuebles que no cumplan con las reglas de publicación.<br>• Suspender usuarios con comportamiento irregular detectado. |
| **Dependencias:** | RF-04, RF-15, RF-19 |
| **Notas adicionales:** | |

---

## 7. Módulo de Reportes de Negocio (MRN)

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-30** |
| **Nombre:** | **Reporte Ejecutivo de Ingresos** |
| **Descripción:** | Generación de informes financieros detallados sobre la facturación de la plataforma. |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Desglose por periodos de tiempo (Mes/Año).<br>• Identificación de ingresos por rentas vs ingresos por recargos.<br>• Capacidad de descarga en formatos exportables. |
| **Dependencias:** | RF-22, RF-23 |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RF-31** |
| **Nombre:** | **Reporte de Popularidad del Inventario** |
| **Descripción:** | Análisis de demanda sobre las propiedades listadas en el sistema. |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | • Listado de inmuebles con más "Favoritos" o más vistos.<br>• Estadísticas de disponibilidad por zona geográfica de Ocosingo.<br>• Reporte de tipos de inmuebles más rentados. |
| **Dependencias:** | RF-08, RF-12 |
| **Notas adicionales:** | |

---

# Requerimientos No Funcionales (RNF) 🛠️

Este apartado describe los atributos de calidad y restricciones técnicas que garantizan un rendimiento óptimo, seguridad robusta y una experiencia de usuario premium en **ArrendaOco**.

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-01** |
| **Nombre:** | **Velocidad de Carga** |
| **Descripción:** | Las páginas y las imágenes de los inmuebles deben cargarse en menos de 3 segundos para garantizar una navegación fluida. |
| **Categoría:** | Rendimiento |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | El tiempo de carga de las páginas debe medirse y documentarse, asegurando que el contenido crítico (LCP) sea visible en el tiempo establecido. |
| **Dependencias:** | Optimización del servidor, compresión de imágenes y conexión a internet. |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-02** |
| **Nombre:** | **Seguridad de Datos y Autenticación** |
| **Descripción:** | El sistema debe proteger la información sensible mediante cifrado de extremo a extremo y protocolos de autenticación robustos. |
| **Categoría:** | Seguridad |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | • Uso obligatorio de **HTTPS**.<br>• Contraseñas hash con **BCrypt**.<br>• Protección activa contra Inyección SQL y ataques CSRF. |
| **Dependencias:** | Certificado SSL y seguridad nativa de Laravel. |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-03** |
| **Nombre:** | **Arquitectura Responsiva (Mobile First)** |
| **Descripción:** | La interfáz debe ser 100% adaptable a dispositivos móviles, tablets y computadoras de escritorio. |
| **Categoría:** | Usabilidad |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | El despliegue visual no debe presentar errores de desbordamiento de elementos en pantallas de 360px de ancho en adelante. |
| **Dependencias:** | Tailwind CSS 4 y Flexbox/Grid CSS. |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-04** |
| **Nombre:** | **Disponibilidad del Sistema** |
| **Descripción:** | La plataforma debe estar disponible para los usuarios el mayor tiempo posible para evitar pérdidas operativas. |
| **Categoría:** | Disponibilidad |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | Tiempo de actividad garantizado (Uptime) del **99.5%** mensual, permitiendo solo ventanas de mantenimiento programadas. |
| **Dependencias:** | Infraestructura de Hosting y estabilidad del Servidor. |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-05** |
| **Nombre:** | **Mantenibilidad y Código Limpio** |
| **Descripción:** | El código fuente debe ser fácil de leer, mantener y escalar por otros desarrolladores en el futuro. |
| **Categoría:** | Mantenibilidad |
| **Prioridad:** | Media-Alta |
| **Criterios de aceptación:** | • Cumplimiento de los estándares de codificación **PSR-12**.<br>• Uso del patrón de diseño **MVC**.<br>• Documentación técnica legible en controladores. |
| **Dependencias:** | Estándares de desarrollo de Laravel y PHP 8.2. |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-06** |
| **Nombre:** | **Gestión de Privacidad** |
| **Descripción:** | Los datos personales de los usuarios (teléfonos, direcciones) no deben ser expuestos a terceros sin autorización. |
| **Categoría:** | Privacidad |
| **Prioridad:** | Crítica |
| **Criterios de aceptación:** | Solo el propietario y el administrador pueden ver los datos de contacto directos tras una interacción formal. |
| **Dependencias:** | RF-01, RF-02 y Policies de Eloquent. |
| **Notas adicionales:** | Cumplimiento con leyes de protección de datos personales. |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-07** |
| **Nombre:** | **Optimización de Consultas IA** |
| **Descripción:** | El asistente ROCO debe proporcionar respuestas coherentes y rápidas sin sobrecargar los límites de la API. |
| **Categoría:** | Eficiencia |
| **Prioridad:** | Media |
| **Criterios de aceptación:** | El tiempo de procesamiento de la respuesta de Gemini no debe bloquear la interfaz de usuario (uso de llamadas asíncronas). |
| **Dependencias:** | Latencia de la API de Google Gemini. |
| **Notas adicionales:** | |

---

| Campo | Detalle |
|:---|:---|
| **ID:** | **RNF-08** |
| **Nombre:** | **Escalabilidad del Inventario** |
| **Descripción:** | La base de datos debe soportar el crecimiento del inventario de inmuebles sin degradar el tiempo de consulta. |
| **Categoría:** | Escalabilidad |
| **Prioridad:** | Alta |
| **Criterios de aceptación:** | Soporte para más de **10,000 registros** de inmuebles con tiempos de búsqueda de menos de 1 segundo. |
| **Dependencias:** | Indexación de Base de Datos y optimización de Eloquent. |
| **Notas adicionales:** | |


