<?php

namespace Core\Session;

use Aura\Auth\Session\SessionInterface;
use Aura\Session\Session;

class AuraSession implements SessionInterface
{
    protected $fwsession;

    public function __construct(Session $fwsession)
    {
        $this->fwsession = $fwsession;
    }

    public function start()
    {
        return $this->fwsession->start();
    }

    public function resume()
    {
        if ($this->fwsession->isStarted()) {
            return true;
        }

        if ($this->fwsession->isResumable()) {
            return $this->fwsession->resume();
        }

        return false;
    }

    public function regenerateId()
    {
        return $this->fwsession->regenerateId();
    }
}
