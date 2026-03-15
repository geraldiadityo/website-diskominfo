<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ArticleStatus: string implements HasLabel, HasColor
{
    case DRAF = 'draf';
    case PENDING_REVIEW = 'pending_review';
    case CHANGES_REQUESTED = 'change_requested';
    case PUBLISH = 'publish';
    case ARCHIVED = 'archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAF => 'Draf',
            self::PENDING_REVIEW => 'Menggun Review',
            self::CHANGES_REQUESTED => 'Perlu Revisi',
            self::PUBLISH => 'Terbit',
            self::ARCHIVED => 'Diarsipkan'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAF => 'gray',
            self::PENDING_REVIEW => 'warning',
            self::CHANGES_REQUESTED => 'danger',
            self::PUBLISH => 'success',
            self::ARCHIVED => 'info'
        };
    }
}
