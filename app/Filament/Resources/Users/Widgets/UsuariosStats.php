<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class UsuariosStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalUsuarios = User::count();
        
        // Verificar si existe la columna user_status_id antes de usar la relación
        $hasUserStatusColumn = Schema::hasColumn('users', 'user_status_id');
        
        if ($hasUserStatusColumn) {
            $usuariosActivos = User::whereHas('userStatus', function ($query) {
                $query->where('name', 'like', '%activo%');
            })->count();
        } else {
            // Si no existe la columna, usar todos los usuarios como activos
            $usuariosActivos = $totalUsuarios;
        }
        
        // Usuarios conectados (últimos 15 minutos)
        $usuariosConectados = User::where('updated_at', '>=', now()->subMinutes(15))->count();

        return [
            Stat::make('Total de Usuarios', $totalUsuarios)
                ->description('Usuarios registrados en el sistema')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->icon('heroicon-o-user-group'),
            
            Stat::make('Usuarios Activos', $usuariosActivos)
                ->description('Usuarios con estado activo')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->icon('heroicon-o-user-circle'),
            
            Stat::make('Usuarios Conectados', $usuariosConectados)
                ->description('Activos en los últimos 15 minutos')
                ->descriptionIcon('heroicon-o-signal')
                ->color('info')
                ->icon('heroicon-o-wifi'),
        ];
    }
}

