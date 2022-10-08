<?php

namespace App\Repository;

use App\Entity\Page;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 *
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    const BATCH_SIZE = 1000;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function countAll(): mixed
    {
        $queryBuilder = $this->createQueryBuilder('pages')
            ->select('COUNT(1)');

        return $queryBuilder->getQuery()->getResult()[0][1];
    }

    /**
     * @throws Exception
     */
    public function insert(array $pages): void
    {
        $connection = $this->getEntityManager()->getConnection();

        $pageFormat = '("%s", "%s")'; // url, createdAt

        $values = [];

        foreach ($pages as $index => $page ) {
            $values[] = sprintf(
                            $pageFormat,
                            $page->getUrl(),
                            $page->getCreatedAt()->format(DateTimeInterface::RFC3339)
                        );

            if (($index % self::BATCH_SIZE) === 0) {
                $connection->executeQuery($this->buildQuery($values));
                $values = [];
            }
        }

        if($values){
            $connection->executeQuery($this->buildQuery($values));
        }
    }

    private function buildQuery(array $values): string {
        $query = <<<QUERY
                    INSERT IGNORE INTO page (url, created_at)
                    VALUES %s
                QUERY;

        return sprintf($query, implode(", ", $values));
    }
}
