<?php
namespace ApplianceBundle\Repository;

use ApplianceBundle\Entity\Appliance;

/**
 * ApplianceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApplianceRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return array|Appliance[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param $code
     *
     * @return null|Appliance
     */
    public function getAppliance($code)
    {
        return $this->findOneBy(['code' => $code]);
    }
}
