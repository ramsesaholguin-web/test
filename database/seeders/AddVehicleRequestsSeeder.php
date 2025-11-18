<?php

namespace Database\Seeders;

use App\Models\RequestStatus;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AddVehicleRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos existentes
        $users = User::all();
        $vehicles = Vehicle::all();
        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
        $rejectedStatus = RequestStatus::where('name', 'Rejected')->first();
        $completedStatus = RequestStatus::where('name', 'Completed')->first();

        if ($users->isEmpty() || $vehicles->isEmpty()) {
            $this->command->error('No users or vehicles found. Please seed them first.');
            return;
        }

        if (!$pendingStatus || !$approvedStatus || !$rejectedStatus) {
            $this->command->error('Request statuses not found. Please run RequestStatusSeeder first.');
            return;
        }

        // Datos de ejemplo para las 12 solicitudes
        $requests = [
            // Solicitudes PENDIENTES (4)
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(3)->setTime(9, 0),
                'requested_return_date' => now()->addDays(5)->setTime(18, 0),
                'destination' => 'Ciudad de México',
                'event' => 'Reunión de Negocios',
                'description' => 'Viaje de negocios para reunión con cliente importante en la CDMX',
                'request_status_id' => $pendingStatus->id,
                'creation_date' => now()->subDays(2),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(7)->setTime(8, 30),
                'requested_return_date' => now()->addDays(9)->setTime(17, 0),
                'destination' => 'Guadalajara',
                'event' => 'Conferencia Tecnológica',
                'description' => 'Asistencia a conferencia tecnológica en Guadalajara',
                'request_status_id' => $pendingStatus->id,
                'creation_date' => now()->subDays(1),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(10)->setTime(10, 0),
                'requested_return_date' => now()->addDays(12)->setTime(16, 0),
                'destination' => 'Monterrey',
                'event' => 'Capacitación',
                'description' => 'Viaje para capacitación del equipo en oficina de Monterrey',
                'request_status_id' => $pendingStatus->id,
                'creation_date' => now()->subHours(12),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(15)->setTime(7, 0),
                'requested_return_date' => now()->addDays(18)->setTime(20, 0),
                'destination' => 'Playa del Carmen',
                'event' => 'Retiro de Equipo',
                'description' => 'Retiro de equipo de trabajo en Playa del Carmen',
                'request_status_id' => $pendingStatus->id,
                'creation_date' => now()->subHours(6),
                'belongsTo' => 'Company',
            ],

            // Solicitudes APROBADAS (4)
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->subDays(5)->setTime(8, 0),
                'requested_return_date' => now()->subDays(3)->setTime(19, 0),
                'destination' => 'Puebla',
                'event' => 'Visita a Cliente',
                'description' => 'Visita a cliente importante en Puebla - ya completado',
                'request_status_id' => $approvedStatus->id,
                'approval_date' => now()->subDays(7),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Aprobado por gerente. Cliente importante.',
                'creation_date' => now()->subDays(8),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(2)->setTime(9, 30),
                'requested_return_date' => now()->addDays(4)->setTime(18, 30),
                'destination' => 'Querétaro',
                'event' => 'Expo Comercial',
                'description' => 'Participación en exposición comercial en Querétaro',
                'request_status_id' => $approvedStatus->id,
                'approval_date' => now()->subDays(2),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Aprobado - evento importante para la empresa',
                'creation_date' => now()->subDays(3),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(6)->setTime(7, 0),
                'requested_return_date' => now()->addDays(8)->setTime(17, 0),
                'destination' => 'Toluca',
                'event' => 'Inspección de Instalaciones',
                'description' => 'Inspección de nuevas instalaciones en Toluca',
                'request_status_id' => $approvedStatus->id,
                'approval_date' => now()->subDays(1),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Aprobado - viaje necesario para inspección',
                'creation_date' => now()->subDays(2),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(11)->setTime(8, 0),
                'requested_return_date' => now()->addDays(13)->setTime(19, 0),
                'destination' => 'León',
                'event' => 'Auditoría',
                'description' => 'Viaje para realizar auditoría interna en sucursal de León',
                'request_status_id' => $approvedStatus->id,
                'approval_date' => now()->subHours(8),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Aprobado por director financiero',
                'creation_date' => now()->subDays(1),
                'belongsTo' => 'Company',
            ],

            // Solicitudes RECHAZADAS (2)
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(4)->setTime(10, 0),
                'requested_return_date' => now()->addDays(6)->setTime(18, 0),
                'destination' => 'Tijuana',
                'event' => 'Reunión de Ventas',
                'description' => 'Reunión de ventas en Tijuana',
                'request_status_id' => $rejectedStatus->id,
                'approval_date' => now()->subDays(2),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Rechazado: El vehículo ya está asignado para esas fechas. Por favor solicita otras fechas.',
                'creation_date' => now()->subDays(3),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->addDays(8)->setTime(9, 0),
                'requested_return_date' => now()->addDays(14)->setTime(17, 0),
                'destination' => 'Cancún',
                'event' => 'Evento de Marketing',
                'description' => 'Viaje largo para evento de marketing en Cancún',
                'request_status_id' => $rejectedStatus->id,
                'approval_date' => now()->subDays(1),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Rechazado: El rango de fechas excede el límite permitido para este tipo de viaje.',
                'creation_date' => now()->subDays(2),
                'belongsTo' => 'Company',
            ],

            // Solicitudes COMPLETADAS (2) - si existe el estado
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->subDays(10)->setTime(8, 0),
                'requested_return_date' => now()->subDays(8)->setTime(20, 0),
                'destination' => 'Mérida',
                'event' => 'Entrega de Proyecto',
                'description' => 'Viaje para entrega de proyecto en Mérida - completado exitosamente',
                'request_status_id' => $completedStatus ? $completedStatus->id : $approvedStatus->id,
                'approval_date' => now()->subDays(12),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Aprobado y completado exitosamente',
                'creation_date' => now()->subDays(13),
                'belongsTo' => 'Company',
            ],
            [
                'user_id' => $users->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'requested_departure_date' => now()->subDays(15)->setTime(7, 30),
                'requested_return_date' => now()->subDays(13)->setTime(19, 30),
                'destination' => 'Veracruz',
                'event' => 'Instalación de Equipo',
                'description' => 'Instalación de equipo técnico en Veracruz - completado',
                'request_status_id' => $completedStatus ? $completedStatus->id : $approvedStatus->id,
                'approval_date' => now()->subDays(17),
                'approved_by' => $users->random()->id,
                'approval_note' => 'Proyecto completado satisfactoriamente',
                'creation_date' => now()->subDays(18),
                'belongsTo' => 'Company',
            ],
        ];

        $created = 0;
        foreach ($requests as $requestData) {
            try {
                VehicleRequest::create($requestData);
                $created++;
            } catch (\Exception $e) {
                $this->command->warn("Error creating request: " . $e->getMessage());
            }
        }

        $this->command->info("Successfully created {$created} vehicle requests!");
        $this->command->info("Pending: " . VehicleRequest::where('request_status_id', $pendingStatus->id)->count());
        $this->command->info("Approved: " . VehicleRequest::where('request_status_id', $approvedStatus->id)->count());
        $this->command->info("Rejected: " . VehicleRequest::where('request_status_id', $rejectedStatus->id)->count());
    }
}

