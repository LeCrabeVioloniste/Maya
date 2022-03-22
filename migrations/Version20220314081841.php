<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314081841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recette_produit (recette_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_EDDD365D89312FE9 (recette_id), INDEX IDX_EDDD365DF347EFB (produit_id), PRIMARY KEY(recette_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette_produit ADD CONSTRAINT FK_EDDD365D89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_produit ADD CONSTRAINT FK_EDDD365DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B957889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
        $this->addSql('CREATE INDEX IDX_F804D3B957889920 ON employe (fonction_id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recette_produit');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B957889920');
        $this->addSql('DROP INDEX IDX_F804D3B957889920 ON employe');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('DROP INDEX IDX_29A5EC27BCF5E72D ON produit');
    }
}
