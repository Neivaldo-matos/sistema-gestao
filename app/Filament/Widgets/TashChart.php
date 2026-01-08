<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TashChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        //Pega as tarefas concluidas no útlimos 7 dias
            $data = \App\Models\Task::where('concluida', true)
                ->where('updated_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(updated_at) as date, count(*) as gty')
                ->groupBy('date')
                ->pluck('gty', 'date');
        return [
            'datasets' => [
                [
                    'label' => 'Tarefas Concluídas',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                ],
            ],
            'labels' => $data->keys()->toArray(),           
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // pode ser 'bar' para barras
    }

    public static bool $isLazy = false;

    // comando para os widgets não aparecer no dashboard automaticamente
    protected static bool $isDiscovered = false;
}
