<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appliance
 *
 * @ORM\Table(name="light")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApplianceRepository")
 */
class Light extends Appliance
{

    /**
     * @var string
     *
     * @ORM\Column(name="brightness", type="string", length=255)
     */
    private $brightness;

    /**
     * @return string
     */
    public function getBrightness()
    {
        return $this->brightness;
    }

    /**
     * @param string $brightness
     */
    public function setBrightness($brightness)
    {
        $this->brightness = $brightness;
    }
}

