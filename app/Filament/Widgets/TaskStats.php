<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total de Tarefas', Task::count())
                ->description('Todas as tarefas registadas')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),

            Stat::make('Tarefas Pendentes', Task::where('concluida', false)->count())
                ->description('Ainda por fazer')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Vencem Hoje', Task::whereDate('due_date', now())->count())
                ->description('Prazo termina hoje')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
