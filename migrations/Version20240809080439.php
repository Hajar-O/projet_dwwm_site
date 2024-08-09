<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809080439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_9474526C2CBBAF3E ON comment');
        $this->addSql('ALTER TABLE comment CHANGE id_recette_id id_recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD9ED1E33 FOREIGN KEY (id_recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_9474526CD9ED1E33 ON comment (id_recipe_id)');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE132CBBAF3E');
        $this->addSql('DROP INDEX IDX_22D1FE132CBBAF3E ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient CHANGE id_recette_id id_recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13D9ED1E33 FOREIGN KEY (id_recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE13D9ED1E33 ON recipe_ingredient (id_recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD9ED1E33');
        $this->addSql('DROP INDEX IDX_9474526CD9ED1E33 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE id_recipe_id id_recette_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_9474526C2CBBAF3E ON comment (id_recette_id)');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13D9ED1E33');
        $this->addSql('DROP INDEX IDX_22D1FE13D9ED1E33 ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient CHANGE id_recipe_id id_recette_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE132CBBAF3E FOREIGN KEY (id_recette_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE132CBBAF3E ON recipe_ingredient (id_recette_id)');
    }
}
