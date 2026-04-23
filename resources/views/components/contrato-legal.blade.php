@props(['inmueble', 'contrato' => null])

@php
    $arrendador = optional($inmueble->propietario)->nombre ?? 'El Arrendador';
    
    // Si hay contrato usamos los datos del contrato, sino estimamos para la vista de pago
    if ($contrato) {
        $inquilino = optional($contrato->inquilino)->nombre ?? 'El Inquilino';
        $fechaCelebracionStr = \Carbon\Carbon::parse($contrato->created_at)->locale('es')->translatedFormat('d \d\e F \d\e\l Y');
        $fechaInicioStr = \Carbon\Carbon::parse($contrato->fecha_inicio)->locale('es')->translatedFormat('d \d\e F \d\e\l Y');
        $plazoStr = $contrato->plazo;
    } else {
        $inquilino = auth()->check() ? auth()->user()->nombre : 'El Inquilino';
        $fechaCelebracionStr = \Carbon\Carbon::now()->locale('es')->translatedFormat('d \d\e F \d\e\l Y');
        $fechaInicioStr = 'la fecha seleccionada en el registro';
        $plazoStr = 'el plazo acordado al procesar el pago';
    }

    $renta = number_format($inmueble->renta_mensual, 2);
    $deposito = number_format($inmueble->deposito ?? 0, 2);
@endphp

<div style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; line-height: 1.6; color: #333; text-align: justify; word-wrap: break-word;">
    
    <h3 style="text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 20px; text-transform: uppercase;">
        CONTRATO DE ARRENDAMIENTO TEMPORAL
    </h3>

    <p>
        El presente contrato de arrendamiento temporal (en adelante, el "Contrato") es celebrado en <strong>{{ $inmueble->ciudad ?? 'Ocosingo, Chiapas' }}</strong> en fecha <strong>{{ $fechaCelebracionStr }}</strong>.
    </p>

    <h4 style="font-weight: bold; font-size: 15px; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
        ENTRE
    </h4>
    
    <p>
        <strong>{{ $arrendador }}</strong>, con copia de credencial oficial adjunta al presente, actuando en su propio nombre y derecho. De aquí en adelante el “Arrendador”.
    </p>
    
    <p style="text-align: center; margin: 10px 0; color: #666;">- Y -</p>
    
    <p>
        <strong>{{ $inquilino }}</strong>, con copia de credencial oficial adjunta al presente, actuando en su propio nombre y derecho. De aquí en adelante el “Inquilino”.
    </p>

    <p style="margin-top: 15px;">
        Estos serán considerados individualmente como la “Parte” y conjuntamente como las “Partes”. En virtud de lo anterior, las Partes deciden suscribir este Contrato, el cual se regirá de conformidad con lo indicado en las siguientes Cláusulas:
    </p>


    <h4 style="text-align: center; font-weight: bold; font-size: 16px; margin-top: 30px; margin-bottom: 20px; text-transform: uppercase;">
        CLÁUSULAS
    </h4>

    <p style="font-weight: bold; margin-top: 20px; margin-bottom: 5px;">1. OBJETO DEL CONTRATO Y FINALIDAD DE USO</p>
    <ul style="margin-left: 20px; margin-bottom: 15px; padding-left: 15px;">
        <li style="margin-bottom: 8px;">Mediante este Contrato, el Arrendador acepta alquilar al Inquilino la propiedad localizada en <strong>{{ $inmueble->direccion }}</strong>, en <strong>{{ $inmueble->ciudad }}, {{ $inmueble->estado }}</strong>, C.P. {{ $inmueble->codigo_postal }}.</li>
        <li style="margin-bottom: 8px;">La propiedad arrendada comprende <strong>{{ $inmueble->metros ?? 'N/A' }}</strong> metros cuadrados, con {{ $inmueble->habitaciones ?? 'N/A' }} habitación(es) y 
            @php
                $textoBanos = [];
                if ($inmueble->banos > 0) $textoBanos[] = $inmueble->banos . ' baño(s) completo(s)';
                if ($inmueble->medios_banos > 0) $textoBanos[] = $inmueble->medios_banos . ' medio(s) baño(s)';
            @endphp
            {{ count($textoBanos) > 0 ? implode(' y ', $textoBanos) : '0 baños' }}.
        </li>
        <li style="margin-bottom: 8px;">La propiedad se destinará única y exclusivamente con fines habitacionales, sin que el Inquilino pueda utilizarla para una finalidad diferente (por ejemplo, local comercial o bodega) sin permiso expreso por escrito por parte del Arrendador.</li>
    </ul>

    <p style="font-weight: bold; margin-top: 20px; margin-bottom: 5px;">2. CONDICIONES DEL INMUEBLE Y ENTREGABLES</p>
    <ul style="margin-left: 20px; margin-bottom: 15px; padding-left: 15px;">
        <li style="margin-bottom: 8px;"><strong>Cerradura e ingreso:</strong> El inmueble {{ $inmueble->tiene_cerradura_propia ? 'cuenta con cerradura propia independiente' : 'comparte acceso principal' }}.</li>
        <li style="margin-bottom: 8px;"><strong>Estacionamiento:</strong> El alquiler {{ $inmueble->tiene_estacionamiento ? 'SÍ' : 'NO' }} incluye un cajón de estacionamiento asignado.</li>
        <li style="margin-bottom: 8px;"><strong>Mobiliario:</strong> El estado del mobiliario es declarado como "{{ ucfirst(str_replace('_', ' ', $inmueble->estado_mobiliario ?? 'No especificado')) }}".
            @if($inmueble->mobiliarios && $inmueble->mobiliarios->count() > 0)
                El inventario inicial incluye: {{ $inmueble->mobiliarios->pluck('nombre')->join(', ') }}.
            @endif
        </li>
        @if($inmueble->zonasComunes && $inmueble->zonasComunes->count() > 0)
            <li style="margin-bottom: 8px;"><strong>Zonas comunes:</strong> El Inquilino tiene acceso a las siguientes zonas compartidas bajo normas de buena voluntad: {{ $inmueble->zonasComunes->pluck('nombre')->join(', ') }}.</li>
        @endif
    </ul>

    <p style="font-weight: bold; margin-top: 20px; margin-bottom: 5px;">3. DURACIÓN Y RENTA</p>
    <ul style="margin-left: 20px; margin-bottom: 15px; padding-left: 15px;">
        <li style="margin-bottom: 8px;">Este Contrato tendrá una duración de <strong>{{ $plazoStr }}</strong> a contar de manera oficial a partir del <strong>{{ $fechaInicioStr }}</strong>.</li>
        <li style="margin-bottom: 8px;">Por la ocupación del inmueble, el Inquilino se compromete a pagar mensualmente al Arrendador la cantidad de <strong>${{ $renta }} MXN</strong> (la "Renta").</li>
        <li style="margin-bottom: 8px;">Este pago deberá realizarse oportunamente según se acuerde en las políticas de ArrendaOco, dentro del plazo establecido para cada ciclo mensual.</li>
        @if((float)$inmueble->deposito > 0)
            <li style="margin-bottom: 8px;">Se estipula la entrega inicial de <strong>${{ $deposito }} MXN</strong> por concepto de Depósito en Garantía para asegurar el cumplimiento del presente instrumento, no siendo este saldo acreditable para el último mes de renta sin previo acuerdo.</li>
        @endif
    </ul>

    <p style="font-weight: bold; margin-top: 20px; margin-bottom: 5px;">4. RÉGIMEN DE SERVICIOS</p>
    <p style="margin-bottom: 10px;">En cuanto a los servicios básicos ligados a la propiedad, las Partes acuerdan la siguiente distribución de pagos y responsabilidades:</p>
    <ul style="margin-left: 20px; margin-bottom: 15px; padding-left: 15px;">
        @php
            $serviciosArrendador = $inmueble->servicios ? $inmueble->servicios->where('paga', 'arrendador')->pluck('servicio')->toArray() : [];
            $serviciosInquilino  = $inmueble->servicios ? $inmueble->servicios->where('paga', 'inquilino')->pluck('servicio')->toArray() : [];
        @endphp
        
        @if(count($serviciosArrendador) > 0)
            <li style="margin-bottom: 8px;"><strong>Servicios cubiertos por el Arrendador:</strong> {{ implode(', ', $serviciosArrendador) }}.</li>
        @endif
        @if(count($serviciosInquilino) > 0)
            <li style="margin-bottom: 8px;"><strong>Servicios a cuenta del Inquilino:</strong> {{ implode(', ', $serviciosInquilino) }}.</li>
        @endif
        @if(count($serviciosArrendador) == 0 && count($serviciosInquilino) == 0)
            <li style="margin-bottom: 8px;"><em>El mantenimiento, contratación y pago de los servicios no especificados, formarán parte de la negociación directa o quedarán a cargo del Inquilino mientras ocupe el inmueble.</em></li>
        @endif
    </ul>

    <p style="font-weight: bold; margin-top: 20px; margin-bottom: 5px;">5. NORMAS DE CONVIVENCIA Y USO</p>
    <ul style="margin-left: 20px; margin-bottom: 15px; padding-left: 15px;">
        <li style="margin-bottom: 8px;">
            <strong>Mascotas:</strong> 
            @if($inmueble->permite_mascotas)
                El Arrendador autoriza explícitamente la tenencia de mascotas. 
                @if($inmueble->mascotas && $inmueble->mascotas->count() > 0)
                    Tipos admitidos preferentemente: {{ $inmueble->mascotas->pluck('nombre')->join(', ') }}.
                @endif
                El Inquilino debe garantizar la higiene y sanear cualquier daño provocado.
            @else
                No se permite la presencia ni alojamiento de mascotas o animales de compañía dentro de la propiedad en virtud de decisión del propietario.
            @endif
        </li>
        <li style="margin-bottom: 8px;">Queda terminantemente prohibido almacenar materiales peligrosos, inflamables o desarrollar actividades ilícitas que atenten contra la seguridad y la moral del entorno.</li>
    </ul>

    <p style="font-weight: bold; margin-top: 20px; margin-bottom: 5px;">6. CLÁUSULAS ADICIONALES DEL PROPIETARIO</p>
    @if($inmueble->incluir_clausulas && !empty($inmueble->clausulas_extra))
        <p style="margin-left: 20px; margin-bottom: 15px; background: #f9fafa; padding: 10px; border-left: 3px solid #666; font-style: italic;">
            {!! nl2br(e($inmueble->clausulas_extra)) !!}
        </p>
    @else
        <p style="margin-left: 20px; margin-bottom: 15px;">No se estipularon cláusulas especiales adicionales por parte del Arrendador para este contrato.</p>
    @endif

    <p style="font-weight: bold; margin-top: 20px; margin-bottom: 5px;">7. INTERMEDIACIÓN TECNOLÓGICA (EXENCIÓN DE RESPONSABILIDAD ARRENDAOCO)</p>
    <ul style="margin-left: 20px; margin-bottom: 15px; padding-left: 15px;">
        <li style="margin-bottom: 8px;">Ambas Partes reconocen y aceptan que la plataforma <strong>ArrendaOco actúa única y exclusivamente como un intermediario tecnológico</strong> que facilita la conexión entre Propietarios e Inquilinos y la generación de acuerdos para su formalización física, sin adquirir la figura de representante legal, apoderado o responsable solidario de ninguna de las partes.</li>
        <li style="margin-bottom: 8px;">ArrendaOco queda totalmente exonerado de cualquier responsabilidad derivada del estado físico del inmueble, daños ocasionados a la propiedad, impago extendido o discrepancias de convivencia.</li>
        <li style="margin-bottom: 8px;">Cualquier conflicto de índole legal, controversia, denuncia o exigimiento de pago no conciliables de manera pacífica, deberán ser resueltos única y exclusivamente de manera directa entre el Arrendador y el Inquilino mediante diálogo, o en su defecto, ante las instancias o tribunales correspondientes a la jurisdicción de la ubicación de la propiedad.</li>
    </ul>

    <p style="margin-top: 40px; margin-bottom: 20px; text-align: center; font-weight: bold; font-style: italic; font-size: 11px;">
        Leído y aprobado por ambas Partes, quienes plasman su firma autógrafa al calce, este documento constata y ratifica su completa voluntad mutua de apegarse a los términos estipulados.
    </p>

</div>
