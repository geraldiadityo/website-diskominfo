<?php

namespace App\Livewire\Pages;

use App\Models\Announcement;
use Livewire\Component;

class AnnouncementPage extends Component
{
    public function render()
    {
        return view('livewire.pages.announcement-page', [
            'announcements' => Announcement::query()->active()->ordered()->get(),
        ])->layout('components.layouts.public', ['title' => 'Pengumuman']);
    }
}
