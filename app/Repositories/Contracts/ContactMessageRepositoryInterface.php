<?php

namespace App\Repositories\Contracts;

use App\Models\ContactMessage;

interface ContactMessageRepositoryInterface
{
    /**
     * @param  array{name: string, email: string, subject: string, message: string}  $data
     */
    public function create(array $data): ContactMessage;
}
