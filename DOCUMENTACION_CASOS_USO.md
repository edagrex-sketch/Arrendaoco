# Especificación de Casos de Uso - ArrendaOco 🏠

Este documento detalla los Casos de Uso del sistema **ArrendaOco**, siguiendo estrictamente el formato técnico de flujos Actor-Sistema solicitado para garantizar la trazabilidad y la validación de requerimientos.

---

## 1. Módulo de Gestión de Inmuebles (MGI)

| Nombre del caso de uso: | Registrar Inmueble (Alta) |
|:---|:---|
| **ID caso de uso:** | **CU-001** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-08 |
| **Actor primario:** | Propietarios |
| **Descripción:** | El usuario propietario ingresa los datos de una nueva propiedad para que sea publicada y visible en la plataforma. |
| **Precondición:** | El usuario debe estar registrado y haber iniciado sesión con el rol de Propietario. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario hace clic en el botón "Publicar Inmueble".<br>2. El usuario llena el formulario con los datos (Título, precio, dirección, descripción y fotos).<br>3. El usuario pulsa el botón de "Guardar/Publicar". | 4. El sistema valida que todos los campos obligatorios estén llenos correctamente.<br>5. El sistema guarda la información y vincula el inmueble al perfil del propietario.<br>6. El sistema envía un mensaje de éxito: "Inmueble publicado correctamente". |
| **Curso Alterno de Eventos** | |
| **A. Validación de datos**<br>A1.- Si el sistema detecta que faltan campos obligatorios o que las imágenes superan el tamaño permitido, enviará un mensaje indicando el error específico en cada campo. | |
| **Post condición:** | El inmueble se registra en la base de datos y aparece inmediatamente en el catálogo general. |

---

| Nombre del caso de uso: | Visualizar Detalle de Inmueble |
|:---|:---|
| **ID caso de uso:** | **CU-002** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-11 |
| **Actor primario:** | Inquilinos / Visitantes |
| **Descripción:** | El usuario puede entrar a ver toda la información detallada, fotos y ubicación de una propiedad específica. |
| **Precondición:** | El inmueble debe estar registrado y con estatus "Disponible" en el sistema. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario selecciona un inmueble del catálogo general.<br>2. El usuario hace clic en la tarjeta o botón "Ver más". | 3. El sistema recupera la información completa de la base de datos.<br>4. El sistema despliega la galería de fotos, descripción técnica, precio y mapa de ubicación. |
| **Curso Alterno de Eventos** | |
| **A. Inmueble no disponible**<br>A1.- Si el inmueble ha sido rentado o dado de baja mientras el usuario navegaba, el sistema enviará un mensaje indicando que "La propiedad ya no se encuentra disponible". | |
| **Post condición:** | El usuario visualiza la información completa del inmueble. |

---

| Nombre del caso de uso: | Modificar Información de Inmueble |
|:---|:---|
| **ID caso de uso:** | **CU-003** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-14 |
| **Actor primario:** | Propietarios |
| **Descripción:** | El propietario actualiza los datos, precios o imágenes de una propiedad que ya tiene publicada. |
| **Precondición:** | El inmueble debe pertenecer al usuario que ha iniciado sesión. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario accede a su panel de "Mis Propiedades".<br>2. El usuario selecciona la opción "Editar" en el inmueble deseado.<br>3. El usuario cambia los datos en el formulario y pulsa "Guardar cambios". | 4. El sistema valida que los nuevos datos ingresados sigan las reglas de negocio.<br>5. El sistema actualiza el registro en la base de datos.<br>6. El sistema envía un mensaje de éxito: "Cambios guardados correctamente". |
| **Curso Alterno de Eventos** | |
| **A. Error de validación**<br>A1.- Si el sistema detecta datos incorrectos (ej. precio negativo o formato de imagen inválido), enviará un mensaje indicando el error y no guardará los cambios. | |
| **Post condición:** | Los datos del inmueble se actualizan y son visibles inmediatamente en la plataforma. |

---

| Nombre del caso de uso: | Eliminar Inmueble (Baja) |
|:---|:---|
| **ID caso de uso:** | **CU-004** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-15 |
| **Actor primario:** | Propietarios / Administradores |
| **Descripción:** | El usuario elimina de forma definitiva una de sus propiedades registradas para que ya no figure en el catálogo. |
| **Precondición:** | El inmueble no debe tener contratos de arrendamiento vigentes asociados. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario selecciona la opción "Eliminar" en su inventario.<br>2. El usuario confirma la acción en la ventana de advertencia de seguridad. | 3. El sistema valida que el inmueble no tenga contratos de renta activos.<br>4. El sistema remueve el registro de la base de datos.<br>5. El sistema envía un mensaje de éxito: "Propiedad eliminada correctamente". |
| **Curso Alterno de Eventos** | |
| **A. Restricción por renta**<br>A1.- Si el sistema detecta que el inmueble está ocupado (tiene un contrato activo), enviará un mensaje indicando que no se puede eliminar hasta que el contrato finalice. | |
| **Post condición:** | El inmueble deja de ser visible en la plataforma y se libera de la base de datos operativa. |

---

## 2. Módulo de Autenticación y Perfiles (MAP)

| Nombre del caso de uso: | Registro de Usuario (Alta) |
|:---|:---|
| **ID caso de uso:** | **CU-005** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-01 |
| **Actor primario:** | Visitante |
| **Descripción:** | El visitante crea una cuenta nueva proporcionando sus datos personales para acceder a la plataforma. |
| **Precondición:** | El usuario no debe tener una cuenta previa registrada con el mismo correo electrónico. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario completa el formulario (Nombre, email, contraseña).<br>2. El usuario hace clic en el botón "Registrarse". | 3. El sistema valida que el email sea único y cumpla el formato.<br>4. El sistema crea el perfil del usuario.<br>5. El sistema envía un mensaje de "Bienvenido/Registro exitoso". |
| **Curso Alterno de Eventos** | |
| **A. Validación de datos**<br>A1.- Si el email ya existe o la contraseña no cumple con la seguridad, el sistema enviará un mensaje indicando el error específico. | |
| **Post condición:** | El usuario queda registrado en la base de datos y se inicia su sesión automáticamente. |

---

| Nombre del caso de uso: | Consultar Perfil de Usuario |
|:---|:---|
| **ID caso de uso:** | **CU-006** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-02 |
| **Actor primario:** | Usuarios Autenticados |
| **Descripción:** | El usuario visualiza la información personal de su cuenta (nombre, email, fecha de unión). |
| **Precondición:** | El usuario debe haber iniciado sesión de manera correcta. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario hace clic en el menú "Mi Perfil".<br>2. El usuario visualiza sus datos en la pantalla principal de perfil. | 3. El sistema recupera la información del usuario de la base de datos.<br>4. El sistema despliega los datos y la foto de perfil en la interfaz. |
| **Curso Alterno de Eventos** | |
| **A. Error de carga**<br>A1.- Si hay un problema de conexión al cargar el perfil, el sistema enviará un mensaje indicando "Error al recuperar los datos". | |
| **Post condición:** | El usuario conoce el estado actual de su información en la plataforma. |

---

| Nombre del caso de uso: | Modificar Datos de Usuario |
|:---|:---|
| **ID caso de uso:** | **CU-007** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-03 |
| **Actor primario:** | Usuarios Autenticados |
| **Descripción:** | El usuario actualiza su información personal, como su nombre, teléfono o fotografía de perfil. |
| **Precondición:** | El usuario debe estar dentro de la vista de edición de su perfil. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario cambia sus datos en el formulario.<br>2. El usuario pulsa el botón de "Guardar cambios". | 3. El sistema valida que los nuevos datos sean correctos.<br>4. El sistema actualiza el registro en la base de datos.<br>5. El sistema envía un mensaje de éxito: "Perfil actualizado". |
| **Curso Alterno de Eventos** | |
| **A. Fallo en validación**<br>A1.- Si el usuario intenta subir un archivo que no es imagen o deja campos obligatorios vacíos, el sistema enviará un mensaje indicando el error. | |
| **Post condición:** | Los cambios se guardan y son visibles inmediatamente en la cuenta del usuario. |

---

| Nombre del caso de uso: | Eliminar Cuenta de Usuario |
|:---|:---|
| **ID caso de uso:** | **CU-008** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-05 |
| **Actor primario:** | Usuarios / Administradores |
| **Descripción:** | El usuario solicita la baja definitiva de su cuenta y la eliminación de sus datos del sistema. |
| **Precondición:** | El usuario no debe tener rentas vigentes o deudas pendientes en el sistema. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario selecciona la opción "Eliminar cuenta".<br>2. El usuario confirma la acción respondiendo al mensaje de advertencia. | 3. El sistema verifica que no existan conflictos contractuales activos.<br>4. El sistema borra el perfil y cierra la sesión.<br>5. El sistema envía un mensaje de "Cuenta eliminada correctamente". |
| **Curso Alterno de Eventos** | |
| **A. Restricción por contrato**<br>A1.- Si el sistema detecta que el usuario tiene un contrato de renta activo, enviará un mensaje indicando que no puede ser eliminado hasta finalizar su compromiso. | |
| **Post condición:** | El registro del usuario es removido permanentemente de la base de datos. |

---

## 3. Módulo de Interacción y Reseñas (MIR)

| Nombre del caso de uso: | Registrar Reseña (Alta) |
|:---|:---|
| **ID caso de uso:** | **CU-009** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-16 |
| **Actor primario:** | Inquilinos |
| **Descripción:** | El inquilino califica y comenta su experiencia tras haber finalizado su contrato de arrendamiento en una propiedad. |
| **Precondición:** | El usuario debe haber tenido un contrato previo con el inmueble y no haber reseñado anteriormente ese mismo contrato. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario accede a la sección de "Mis Rentas" o al detalle del inmueble.<br>2. El usuario selecciona la calificación (estrellas) e ingresa su reseña.<br>3. El usuario pulsa el botón "Publicar Reseña". | 4. El sistema valida que el usuario sea elegible para comentar.<br>5. El sistema guarda la reseña en la base de datos.<br>6. El sistema recalcula el promedio de calificación del inmueble.<br>7. El sistema envía un mensaje de "Reseña publicada con éxito". |
| **Curso Alterno de Eventos** | |
| **A. Usuario no elegible**<br>A1.- Si el sistema detecta que el usuario no tiene contratos previos con el inmueble, bloqueará el formulario y mostrará un mensaje de restricción. | |
| **Post condición:** | La reseña es visible públicamente en el perfil del inmueble. |

---

| Nombre del caso de uso: | Visualizar Reseñas y Calificaciones |
|:---|:---|
| **ID caso de uso:** | **CU-010** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-17 |
| **Actor primario:** | Público General / Visitantes |
| **Descripción:** | Visualizar el listado de opiniones y la calificación promedio de una propiedad específica. |
| **Precondición:** | El inmueble debe tener al menos una reseña registrada. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario accede a la ficha técnica de un inmueble.<br>2. El usuario se desplaza a la sección de "Opiniones de la comunidad". | 3. El sistema recupera todas las reseñas asociadas al inmueble.<br>4. El sistema despliega los comentarios, autores y el promedio de estrellas. |
| **Curso Alterno de Eventos** | |
| **A. Inmueble sin reseñas**<br>A1.- Si no hay comentarios aún, el sistema muestra el mensaje: "Aún no hay opiniones para esta propiedad". | |
| **Post condición:** | El usuario obtiene una referencia de la reputación del inmueble. |

---

| Nombre del caso de uso: | Editar Reseña Propia |
|:---|:---|
| **ID caso de uso:** | **CU-011** |
| **Prioridad:** | Baja-Media |
| **Requisitos:** | RF-18 |
| **Actor primario:** | Inquilinos (Autores) |
| **Descripción:** | El autor de una reseña modifica su calificación o el texto de su comentario previamente publicado. |
| **Precondición:** | El usuario debe estar autenticado y ser el propietario original de la reseña. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario localiza su reseña en el detalle del inmueble.<br>2. El usuario pulsa el botón "Editar".<br>3. El usuario modifica los datos y pulsa "Actualizar". | 4. El sistema valida los permisos del autor.<br>5. El sistema actualiza el registro y recalcula el promedio del inmueble.<br>6. El sistema envía un mensaje de éxito: "Reseña actualizada". |
| **Curso Alterno de Eventos** | |
| **A. Intento de edición ajena**<br>A1.- Si un usuario intenta editar una reseña que no le pertenece, el sistema enviará un error 403. | |
| **Post condición:** | Los cambios se reflejan inmediatamente en la vista pública. |

---

| Nombre del caso de uso: | Eliminar / Moderar Reseña |
|:---|:---|
| **ID caso de uso:** | **CU-012** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-19 |
| **Actor primario:** | Autor de la reseña / Administrador |
| **Descripción:** | Remoción definitiva de una reseña del sistema, ya sea por el autor o por moderación administrativa. |
| **Precondición:** | La reseña debe existir en la base de datos. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El actor pulsa el botón de "Eliminar" en la reseña deseada.<br>2. El actor confirma la eliminación en el diálogo de seguridad. | 3. El sistema valida los permisos (Autor o Admin).<br>4. El sistema elimina físicamente el registro.<br>5. El sistema actualiza el ranking del inmueble.<br>6. El sistema envía un mensaje de confirmación. |
| **Curso Alterno de Eventos** | |
| **A. Cancelación de acción**<br>A1.- Si el usuario pulsa "Cancelar" en el diálogo, el sistema cierra la ventana sin realizar cambios. | |
| **Post condición:** | La reseña deja de ser visible y el promedio del inmueble se ajusta automáticamente. |

---

## 4. Módulo de Contratación y Finanzas (MCF)

| Nombre del caso de uso: | Generar Contrato Digital (Alta) |
|:---|:---|
| **ID caso de uso:** | **CU-013** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-20, RF-21 |
| **Actor primario:** | Propietarios / Administradores |
| **Descripción:** | El sistema crea un vínculo legal digital entre un propietario y un inquilino para una propiedad específica, generando automáticamente las fichas de pago. |
| **Precondición:** | El inmueble debe estar en estatus "Disponible" y el inquilino debe estar registrado en la plataforma. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario selecciona el inmueble y el inquilino.<br>2. El usuario ingresa montos, fecha de inicio y duración.<br>3. El usuario pulsa el botón "Crear Contrato". | 4. El sistema valida que no existan deudas previas del inquilino.<br>5. El sistema cambia el estatus del inmueble a "Rentado".<br>6. El sistema genera los registros de pago mensuales.<br>7. El sistema envía un mensaje de "Contrato generado con éxito". |
| **Curso Alterno de Eventos** | |
| **A. Inmueble no disponible**<br>A1.- Si el inmueble ya tiene un contrato activo, el sistema bloqueará la operación y mostrará un mensaje de advertencia. | |
| **Post condición:** | El contrato queda activo y se inicia el ciclo de cobros. |

---

| Nombre del caso de uso: | Visualizar Estado de Cuenta (Read) |
|:---|:---|
| **ID caso de uso:** | **CU-014** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-24 |
| **Actor primario:** | Inquilinos / Propietarios |
| **Descripción:** | El usuario consulta el historial de sus pagos realizados, adeudos actuales y fechas límite. |
| **Precondición:** | El usuario debe tener al menos un contrato (vigente o finalizado) asociado a su cuenta. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario ingresa a la sección "Mis Pagos" o "Estado de Cuenta".<br>2. El usuario visualiza la lista cronológica de movimientos. | 3. El sistema recupera los registros de pagos y recargos vinculados al contrato.<br>4. El sistema presenta de forma clara el balance total y los estatus de cada mes. |
| **Curso Alterno de Eventos** | |
| **A. Sin contratos asociados**<br>A1.- Si el usuario no tiene ninguna renta, el sistema mostrará el mensaje: "Aún no cuentas con registros financieros". | |
| **Post condición:** | El usuario obtiene transparencia sobre su situación financiera en la app. |

---

| Nombre del caso de uso: | Registrar y Validar Pago (Update) |
|:---|:---|
| **ID caso de uso:** | **CU-015** |
| **Prioridad:** | Crítica |
| **Requisitos:** | RF-22 |
| **Actor primario:** | Propietarios / Administradores |
| **Descripción:** | Proceso manual o semiautomático de marcar una mensualidad como "Pagada" tras verificar el comprobante. |
| **Precondición:** | Debe existir un registro de pago en estatus "Pendiente". |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El actor localiza la ficha de pago pendiente.<br>2. El actor confirma la recepción del dinero e ingresa la referencia.<br>3. El actor pulsa el botón "Confirmar Pago". | 4. El sistema actualiza el estatus del cobro a "Completado".<br>5. El sistema registra la fecha y hora exacta de la transacción.<br>6. El sistema envía un mensaje de éxito: "Pago registrado". |
| **Curso Alterno de Eventos** | |
| **A. Monto insuficiente**<br>A1.- Si el pago no cubre el total (incluyendo recargos), el sistema permitirá registrarlo pero mantendrá el estatus como "Pago Parcial". | |
| **Post condición:** | El saldo del inquilino se actualiza y el propietario ve reflejado el ingreso. |

---

| Nombre del caso de uso: | Aplicación Automática de Recargos |
|:---|:---|
| **ID caso de uso:** | **CU-016** |
| **Prioridad:** | Media-Alta |
| **Requisitos:** | RF-23 |
| **Actor primario:** | Sistema (Automático) |
| **Descripción:** | El sistema detecta pagos vencidos y añade penalizaciones financieras según la configuración del contrato. |
| **Precondición:** | La fecha actual debe ser mayor a la fecha límite establecida en la ficha de pago. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| (Acción automática del servidor) | 1. El sistema realiza un barrido diario de pagos pendientes.<br>2. El sistema identifica los registros cuya fecha límite ha expirado.<br>3. El sistema suma el monto de recargo al total pendiente.<br>4. El sistema notifica al inquilino sobre el nuevo saldo. |
| **Curso Alterno de Eventos** | |
| **A. Prórroga administrativa**<br>A1.- Si el administrador marcó una exoneración, el sistema omitirá el cálculo de recargo para ese registro específico. | |
| **Post condición:** | El monto total del pago pendiente se incrementa automáticamente. |

---

## 5. Módulo de Asistente IA (MAI)

| Nombre del caso de uso: | Consultar con Asistente ROCO |
|:---|:---|
| **ID caso de uso:** | **CU-017** |
| **Prioridad:** | Media |
| **Requisitos:** | RF-27 |
| **Actor primario:** | Todos los Usuarios |
| **Descripción:** | El usuario interactúa con la IA para resolver dudas sobre inmuebles, contratos o el uso de la plataforma. |
| **Precondición:** | El usuario debe estar en una vista que contenga el widget de chat. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El usuario abre el chat y redacta su duda.<br>2. El usuario envía el mensaje. | 3. El sistema procesa la consulta mediante la API de Gemini.<br>4. El sistema genera una respuesta personalizada contextualizada al negocio.<br>5. El sistema muestra la respuesta en la burbuja de chat. |
| **Curso Alterno de Eventos** | |
| **A. Error de conexión con IA**<br>A1.- Si la API de Gemini no responde, el sistema enviará un mensaje de contingencia: "Vaya, mi olfato me falla. ¡Intenta preguntarme de nuevo en un momento!". | |
| **Post condición:** | El usuario recibe asistencia inmediata y automatizada. |

---

## 6. Módulo de Administración (MAG)

| Nombre del caso de uso: | Visualizar Dashboard Administrativo |
|:---|:---|
| **ID caso de uso:** | **CU-018** |
| **Prioridad:** | Alta |
| **Requisitos:** | RF-28, RF-29 |
| **Actor primario:** | Administradores |
| **Descripción:** | Vista centralizada de las métricas clave del sistema (Ingresos, usuarios activos, inmuebles). |
| **Precondición:** | El usuario debe tener privilegios de Administrador. |
| **Curso Normal de Eventos** | |
| **Acciones del Actor** | **Acciones del sistema** |
| 1. El admin accede a la ruta "/admin".<br>2. El admin visualiza los gráficos y contadores globales. | 3. El sistema realiza un conteo en tiempo real de la base de datos.<br>4. El sistema renderiza los KPI (Key Performance Indicators) del negocio. |
| **Curso Alterno de Eventos** | |
| **A. Acceso no autorizado**<br>A1.- Si un usuario sin rol de admin intenta entrar, el sistema lo redirecciona al inicio con un mensaje de error. | |
| **Post condición:** | El administrador obtiene una visión clara de la salud operativa de ArrendaOco. |
