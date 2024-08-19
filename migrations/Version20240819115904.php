<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819115904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE133CAF64A');
        $this->addSql('DROP INDEX IDX_22D1FE133CAF64A ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient CHANGE recipe_ingredient_id recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE1359D8A214 ON recipe_ingredient (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('DROP INDEX IDX_22D1FE1359D8A214 ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient CHANGE recipe_id recipe_ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE133CAF64A FOREIGN KEY (recipe_ingredient_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE133CAF64A ON recipe_ingredient (recipe_ingredient_id)');
    }
}
