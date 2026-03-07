<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Support;
use App\Models\User;

class SupportTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Support::query()
            ->select('supports.*')
            ->with(['user']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [

            Column::make("Id", "id")
                ->sortable(),

            Column::make("Título", "title")
                ->searchable()
                ->sortable(),

            Column::make("Usuario")
                ->sortable(function (Builder $query, string $direction) {
                    return $query->orderBy(
                        User::select('name')
                            ->whereColumn('users.id', 'supports.user_id')
                            ->limit(1),
                        $direction
                    );
                })
                ->label(fn ($row) =>
                    $row->user?->name ?? 'N/A'
                ),

            Column::make("Estado", "status")
                ->sortable(),

            Column::make("Fecha", "created_at")
                ->sortable()
                ->format(fn($value)=>$value ? $value->format('d/m/Y') : 'N/A'),

            Column::make("Acciones")
                ->label(fn($row)=>view('admin.support.actions',['support'=>$row]))
        ];
    }
}
