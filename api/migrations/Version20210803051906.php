<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803051906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authors (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_author (book_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_9478D34516A2B381 (book_id), INDEX IDX_9478D345F675F31B (author_id), PRIMARY KEY(book_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D34516A2B381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D345F675F31B FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books ADD description VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD read_at DATE DEFAULT NULL, ADD score_value INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_author DROP FOREIGN KEY FK_9478D345F675F31B');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE book_author');
        $this->addSql('ALTER TABLE books DROP description, DROP created_at, DROP read_at, DROP score_value');
    }
}
