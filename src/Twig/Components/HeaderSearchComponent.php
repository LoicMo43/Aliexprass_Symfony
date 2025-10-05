<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('header_search', template: 'components/header_search.html.twig')]
final class HeaderSearchComponent
{
    #[ExposeInTemplate(name: 'endpoint')]
    public string $endpoint;

    #[ExposeInTemplate(name: 'placeholder')]
    public string $placeholder = 'Rechercher';
}