<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808143820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette_ingredient ADD id_recette_id INT NOT NULL, ADD id_ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A92CBBAF3E FOREIGN KEY (id_recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE recette_ingredient ADD CONSTRAINT FK_17C041A92D1731E9 FOREIGN KEY (id_ingredient_id) REFERENCES ingredients (id)');
        $this->addSql('CREATE INDEX IDX_17C041A92CBBAF3E ON recette_ingredient (id_recette_id)');
        $this->addSql('CREATE INDEX IDX_17C041A92D1731E9 ON recette_ingredient (id_ingredient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette_ingredient DROP FOREIGN KEY FK_17C041A92CBBAF3E');
        $this->addSql('ALTER TABLE recette_ingredient DROP FOREIGN KEY FK_17C041A92D1731E9');
        $this->addSql('DROP INDEX IDX_17C041A92CBBAF3E ON recette_ingredient');
        $this->addSql('DROP INDEX IDX_17C041A92D1731E9 ON recette_ingredient');
        $this->addSql('ALTER TABLE recette_ingredient DROP id_recette_id, DROP id_ingredient_id');
    }
}
