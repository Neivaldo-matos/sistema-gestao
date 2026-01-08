<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class RelatoriosEstatisticos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Relatórios';

    protected static ?string $title = 'Relatórios Estatísticos';

    protected static string $view = 'filament.pages.relatorios-estatisticos';

    // Aqui dizemos a pagina para carregar os widgets que já foram criados
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\TaskStats::class, // O contador de ontem
            \App\Filament\Widgets\TashChart::class, // O gráfico que criamos agora
        ];
    }

    // Navegação - agrupamento de menu
    protected static ?string $navigationGroup = 'Ánalise';
}
