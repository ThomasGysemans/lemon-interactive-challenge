<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Input
{
  public string $type;
  public string $name;
  public string $value = "";
  public string $placeholder;
  public string $autocomplete = "on";
  public bool $spellcheck = false;
  public int $maxLength = 255;
  public int $minLength = 1;
}
