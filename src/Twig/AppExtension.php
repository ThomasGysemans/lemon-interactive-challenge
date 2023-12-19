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
      new TwigFunction('stringifyEvent', [$this, 'stringifyEvent']),
    ];
  }

  public function hasSubscriber(Event $event, User $user): bool
  {
    return $event->hasSubscriber($user);
  }

  public function stringifyEvent(Event $event): string
  {
    return str_replace("\n", "", $event->stringify()); // necessary
  }
}