<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PublicationStatus: string implements HasLabel, HasColor
{
    case DRAF = 'draf';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';


    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::DRAF => 'Draf',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAF => 'gray',
            self::PUBLISHED => 'success',
            self::ARCHIVED => 'warning',
        };
    }
}
