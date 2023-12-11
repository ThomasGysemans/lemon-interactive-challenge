<?php

namespace App\Twig\Components\Button;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Link
{
  public string $href;
  public string $target = '_self';
}
