<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Service\Utils\IdToUuidMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210715154840 extends IdToUuidMigration
{
    public function postUp(Schema $schema): void
    {
        $this->migrate('books');
        $this->migrate('categories');
    }
}
