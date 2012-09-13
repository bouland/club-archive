<?php 

namespace Aueio\ClubBundle\Factory;

use Symfony\Component\Security\Core\SecurityContextInterface;

class PlayerFactory
{
    private $context;

    public function __construct(SecurityContextInterface $context)
    {
        $this->context = $context;
    }

    public function get()
    {
        if (null === $token = $this->context->getToken()) {
            return null;
        }

        if (!is_object($player = $token->getUser())) {
            return null;
        }

        return $player;
    }
}
