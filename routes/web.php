<?php

use App\Livewire\Pages\ContactPage;
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\NewsDetail;
use App\Livewire\Pages\NewsIndex;
use App\Livewire\Pages\OrganizationStructure;
use App\Livewire\Pages\ProfilePage;
use App\Livewire\Pages\PublicationIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/berita', NewsIndex::class)->name('berita.index');
Route::get('/berita/{article:slug}', NewsDetail::class)->name('berita.show');
Route::get('/publikasi', PublicationIndex::class)->name('publikasi.index');
Route::get('/profil', ProfilePage::class)->name('profil');
Route::get('/profil/struktur-organisasi', OrganizationStructure::class)->name('profil.struktur');
Route::get('/kontak', ContactPage::class)->name('kontak');
