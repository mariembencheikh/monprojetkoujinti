<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414100725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD type_recette_id INT NOT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB63909432F9CC FOREIGN KEY (type_recette_id) REFERENCES type_recette (id)');
        $this->addSql('CREATE INDEX IDX_49BB63909432F9CC ON recette (type_recette_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB63909432F9CC');
        $this->addSql('DROP INDEX IDX_49BB63909432F9CC ON recette');
        $this->addSql('ALTER TABLE recette DROP type_recette_id');
    }
}
