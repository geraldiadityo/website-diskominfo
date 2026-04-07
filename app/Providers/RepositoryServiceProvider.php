<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\ContactMessageRepository;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\ContactMessageRepositoryInterface;
use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use App\Repositories\FaqRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\PublicationRepository;
use App\Repositories\SiteSettingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        ArticleRepositoryInterface::class => ArticleRepository::class,
        PublicationRepositoryInterface::class => PublicationRepository::class,
        OrganizationRepositoryInterface::class => OrganizationRepository::class,
        SiteSettingRepositoryInterface::class => SiteSettingRepository::class,
        FaqRepositoryInterface::class => FaqRepository::class,
        ContactMessageRepositoryInterface::class => ContactMessageRepository::class,
    ];
}
