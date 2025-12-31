<?php

namespace App\Security\Voter;

use App\Entity\Deal;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

final class DealVoter extends Voter
{
    const VIEW = "DEAL_VIEW";

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW])
            && $subject instanceof Deal;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Deal $deal */
        $deal = $subject;

        return match ($attribute) {
            self::VIEW => $deal->getPrice() < 10000 || $user->isRich(),
            default => false,
        };
    }
}
