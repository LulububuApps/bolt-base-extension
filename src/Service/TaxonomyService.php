<?php

declare(strict_types=1);

namespace Lulububu\BaseExtension\Service;

use Bolt\Entity\Content;
use Bolt\Entity\Taxonomy;
use Doctrine\ORM\EntityManager;

/**
 * Class TaxonomyService
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Service
 */
class TaxonomyService
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * TaxonomyService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array|null
     */
    public function getMenus()
    {
        $taxonomyRepository = $this->entityManager->getRepository(Taxonomy::class);
        $queryBuilder       = $taxonomyRepository->createQueryBuilder('t', 't.slug');

        $queryBuilder
            ->where('t.type = :menus')
            ->setParameter('menus', 'menus')
        ;
        $query   = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }
}