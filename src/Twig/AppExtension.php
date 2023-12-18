<?php

namespace App\Twig;

use App\Entity\Event;
use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
  public function getFunctions(): array
  {
    return [
      new TwigFunction('hasSubscriber', [$this, 'hasSubscriber']),
    ];
  }

  public function hasSubscriber(Event $event, User $user): bool
  {
    return $event->hasSubscriber($user);
  }
}