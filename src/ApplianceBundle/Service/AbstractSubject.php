<?php
namespace ApplianceBundle\Service;

use ApplianceBundle\Service\Observer\AbstractObserver;

abstract class AbstractSubject
{
    /** @var AbstractObserver[] */
    private $observers = array();

    abstract public function attach(AbstractObserver $observer);

    abstract public function detach(AbstractObserver $observer);

    abstract public function trigger();
}