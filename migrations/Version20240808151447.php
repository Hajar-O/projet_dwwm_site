<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808151447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD id_user_id INT DEFAULT NULL, ADD id_recette_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C2CBBAF3E FOREIGN KEY (id_recette_id) REFERENCES recette (id)');
        $this->addSql('CREATE INDEX IDX_9474526C79F37AE5 ON comment (id_user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C2CBBAF3E ON comment (id_recette_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C79F37AE5');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C2CBBAF3E');
        $this->addSql('DROP INDEX IDX_9474526C79F37AE5 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C2CBBAF3E ON comment');
        $this->addSql('ALTER TABLE comment DROP id_user_id, DROP id_recette_id');
    }
}
