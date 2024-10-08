<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808144713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD id_category_id INT NOT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A545015 FOREIGN KEY (id_category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_49BB6390A545015 ON recette (id_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A545015');
        $this->addSql('DROP INDEX IDX_49BB6390A545015 ON recette');
        $this->addSql('ALTER TABLE recette DROP id_category_id');
    }
}
