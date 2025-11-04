<div class="space-y-4">
    @if($request)
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Usuario</p>
                <p class="text-sm text-gray-900">{{ $request->user?->name ?? 'N/A' }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Vehículo</p>
                <p class="text-sm text-gray-900">{{ $request->vehicle?->plate ?? 'N/A' }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Estado</p>
                <p class="text-sm text-gray-900">{{ $request->requestStatus?->name ?? 'N/A' }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Destino</p>
                <p class="text-sm text-gray-900">{{ $request->destination ?? 'N/A' }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Fecha de Salida</p>
                <p class="text-sm text-gray-900">{{ $request->requested_departure_date?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Fecha de Retorno</p>
                <p class="text-sm text-gray-900">{{ $request->requested_return_date?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
        </div>
        
        @if($request->description)
            <div>
                <p class="text-sm font-medium text-gray-500">Descripción</p>
                <p class="text-sm text-gray-900">{{ $request->description }}</p>
            </div>
        @endif
        
        @if($request->event)
            <div>
                <p class="text-sm font-medium text-gray-500">Evento</p>
                <p class="text-sm text-gray-900">{{ $request->event }}</p>
            </div>
        @endif
    @else
        <p class="text-sm text-gray-500">No se pudo cargar la información de la solicitud.</p>
    @endif
</div>

