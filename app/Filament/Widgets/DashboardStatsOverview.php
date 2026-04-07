<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user && $user->role === 'author') {
            return [
                Stat::make('Total Artikel Saya', \App\Models\Article::where('author_id', $user->id)->count())
                    ->description('Semua artikel yang Anda tulis')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('primary'),
                Stat::make('Artikel Draf', \App\Models\Article::where('author_id', $user->id)->where('status', \App\Enums\ArticleStatus::DRAF)->count())
                    ->description('Artikel yang masih berupa draf')
                    ->descriptionIcon('heroicon-m-pencil-square')
                    ->color('warning'),
                Stat::make('Artikel Terbit', \App\Models\Article::where('author_id', $user->id)->where('status', \App\Enums\ArticleStatus::PUBLISH)->count())
                    ->description('Artikel yang sudah tayang')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
            ];
        }

        return [
            Stat::make('Total Artikel', \App\Models\Article::count())
                ->description('Total seluruh artikel di sistem')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Dokumen Publikasi', \App\Models\Publication::count())
                ->description('Total dokumen publik')
                ->descriptionIcon('heroicon-m-document-arrow-down')
                ->color('success'),
            Stat::make('Bidang / Departemen', \App\Models\Departement::count())
                ->description('Total unit kerja terdaftar')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info'),
        ];
    }
}
