<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819122039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient ADD measure_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE135DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE135DA37D00 ON recipe_ingredient (measure_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE135DA37D00');
        $this->addSql('DROP INDEX IDX_22D1FE135DA37D00 ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient DROP measure_id');
    }
}
