<?php

namespace App\View\Composers;

use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use Illuminate\View\View;

class SiteSettingComposer
{
    public function __construct(
        protected SiteSettingRepositoryInterface $settings,
    ) {}

    public function compose(View $view): void
    {
        $contact = $this->settings->getContact();
        $social = $this->settings->getSocial();
        $jumbotron = $this->settings->getJumbotron();

        $view->with([
            'siteName' => $this->settings->getSiteName(),
            'siteLogo' => $this->settings->getLogo(),
            'siteFavicon' => $this->settings->getFavicon(),
            'siteDescription' => $this->settings->getDescription(),
            'contactEmail' => $contact['email'],
            'contactPhone' => $contact['phone'],
            'contactAddress' => $contact['address'],
            'operationalHours' => $this->settings->getOperationalHours(),
            'socialFacebook' => $social['facebook'],
            'socialInstagram' => $social['instagram'],
            'jumbotron' => $jumbotron,
        ]);
    }
}
