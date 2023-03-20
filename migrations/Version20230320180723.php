<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320180723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE expense (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_2D3A8DA6A76ED395 (user_id), INDEX IDX_2D3A8DA612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6A76ED395');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA612469DE2');
        $this->addSql('DROP TABLE expense');
    }
}
