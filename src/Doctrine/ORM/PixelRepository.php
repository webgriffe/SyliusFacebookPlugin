<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\Doctrine\ORM;

use Setono\SyliusFacebookPlugin\Model\PixelInterface;
use Setono\SyliusFacebookPlugin\Repository\PixelRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class PixelRepository extends EntityRepository implements PixelRepositoryInterface
{
    public function findEnabledByChannel(ChannelInterface $channel): array
    {
        /** @var PixelInterface[] $result */
        $result = $this->createQueryBuilder('o')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
