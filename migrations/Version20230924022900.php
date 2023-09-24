<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924022900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friends DROP FOREIGN KEY FK_21EE7069CBCCE20C');
        $this->addSql('ALTER TABLE friends_demand DROP FOREIGN KEY FK_21D82BE249CA8337');
        $this->addSql('ALTER TABLE friends_demand DROP FOREIGN KEY FK_21D82BE2A76ED395');
        $this->addSql('DROP TABLE friends');
        $this->addSql('DROP TABLE friends_demand');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE friends (id INT AUTO_INCREMENT NOT NULL, user_demande_destiny_id INT DEFAULT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_21EE7069CBCCE20C (user_demande_destiny_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE friends_demand (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, friends_id INT DEFAULT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_21D82BE249CA8337 (friends_id), INDEX IDX_21D82BE2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE friends ADD CONSTRAINT FK_21EE7069CBCCE20C FOREIGN KEY (user_demande_destiny_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friends_demand ADD CONSTRAINT FK_21D82BE249CA8337 FOREIGN KEY (friends_id) REFERENCES friends (id)');
        $this->addSql('ALTER TABLE friends_demand ADD CONSTRAINT FK_21D82BE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
