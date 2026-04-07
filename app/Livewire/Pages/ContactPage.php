<?php

namespace App\Livewire\Pages;

use App\Repositories\Contracts\ContactMessageRepositoryInterface;
use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactPage extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|max:5000')]
    public string $message = '';

    public bool $submitted = false;

    public function submit(ContactMessageRepositoryInterface $contactRepo): void
    {
        $this->validate();

        $contactRepo->create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->submitted = true;
    }

    public function render(
        FaqRepositoryInterface $faqRepo,
        SiteSettingRepositoryInterface $settingsRepo,
    ) {
        $contact = $settingsRepo->getContact();

        return view('livewire.pages.contact-page', [
            'faqs' => $faqRepo->getActive(),
            'contactEmail' => $contact['email'],
            'contactPhone' => $contact['phone'],
            'contactAddress' => $contact['address'],
        ])->layout('components.layouts.public', ['title' => 'Hubungi Kami']);
    }
}
