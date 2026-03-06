<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Doctor;
use App\Models\User;

class DoctorTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Doctor::query()
            ->select('doctors.*')
            ->with(['user', 'speciality']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [

            Column::make("ID", "id")
                ->sortable(),

            Column::make("Doctor")
                ->sortable(function (Builder $query, string $direction) {
                    return $query->orderBy(
                        User::select('name')
                            ->whereColumn('users.id', 'doctors.user_id')
                            ->limit(1),
                        $direction
                    );
                })
                ->label(fn ($row) =>
                    $row->user?->name ?? 'N/A'
                ),

            Column::make("Email")
                ->sortable(function (Builder $query, string $direction) {
                    return $query->orderBy(
                        User::select('email')
                            ->whereColumn('users.id', 'doctors.user_id')
                            ->limit(1),
                        $direction
                    );
                })
                ->label(fn ($row) =>
                    $row->user?->email ?? 'N/A'
                ),

            Column::make("Especialidad")
                ->label(fn ($row) =>
                    $row->speciality?->name ?? 'N/A'
                ),

            Column::make("Cédula")
                ->sortable()
                ->label(fn ($row) =>
                    $row->medical_license_number ?? 'N/A'
                ),

            Column::make("Fecha creación", "created_at")
                ->sortable()
                ->label(fn ($row) =>
                    $row->created_at
                        ? $row->created_at->format('d/m/Y')
                        : 'N/A'
                ),

            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.doctors.actions', [
                        'doctor' => $row
                    ]);
                }),
        ];
    }
}