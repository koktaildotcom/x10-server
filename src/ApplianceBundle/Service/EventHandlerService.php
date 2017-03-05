<?php
namespace ApplianceBundle\Service;

use ApplianceBundle\Service\Observer\AbstractObserver;

class EventHandlerService extends AbstractSubject
{
    /** @var AbstractObserver[]  */
    private $observers = array();

    public function attach(AbstractObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(AbstractObserver $observer)
    {
        foreach ($this->observers as $key => $class) {
            if ($class === $observer) {
                unset($this->observers[$key]);
            }
        }
    }

    public function trigger()
    {
        foreach ($this->observers as $obs) {
            $obs->update($this);
        }
    }
}