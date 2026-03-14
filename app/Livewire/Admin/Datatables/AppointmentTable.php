<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Appointment;
use App\Models\User;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Appointment::query()
            ->select('appointments.*')
            ->with(['patient.user', 'doctor.user']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Paciente')
                ->label(fn ($row) => $row->patient?->user?->name ?? 'N/A'),

            Column::make('Doctor')
                ->label(fn ($row) => $row->doctor?->user?->name ?? 'N/A'),

            Column::make('Fecha', 'date')
                ->sortable()
                ->searchable(),

            Column::make('Hora inicio', 'start_time')
                ->sortable(),

            Column::make('Hora fin', 'end_time')
                ->sortable(),

            Column::make('Estado', 'status')
                ->sortable()
                ->label(function ($row) {
                    $statusName = match($row->status) {
                        1 => 'Programado',
                        2 => 'Completado',
                        3 => 'Reagendado',
                        4 => 'Cancelado',
                        default => 'Desconocido',
                    };
                    $color = match($row->status) {
                        1 => 'bg-blue-100 text-blue-800',
                        2 => 'bg-green-100 text-green-800',
                        3 => 'bg-yellow-100 text-yellow-800',
                        4 => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800',
                    };

                    return '<span class="px-2 py-1 rounded text-xs font-semibold ' . $color . '">' . $statusName . '</span>';
                })
                ->html(),

            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.appointments.actions', [
                        'appointment' => $row
                    ]);
                }),
        ];
    }
}
