<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240813130530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient ADD category_ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF78708DE4C5C7 FOREIGN KEY (category_ingredient_id) REFERENCES category_ingredient (id)');
        $this->addSql('CREATE INDEX IDX_6BAF78708DE4C5C7 ON ingredient (category_ingredient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF78708DE4C5C7');
        $this->addSql('DROP INDEX IDX_6BAF78708DE4C5C7 ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP category_ingredient_id');
    }
}
