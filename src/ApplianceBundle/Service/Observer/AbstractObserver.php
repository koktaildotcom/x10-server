<?php
namespace ApplianceBundle\Service\Observer;

use ApplianceBundle\Service\AbstractSubject;

abstract class AbstractObserver
{
    abstract function update(AbstractSubject $subject);
}