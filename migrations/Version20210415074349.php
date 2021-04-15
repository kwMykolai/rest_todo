<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415074349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE list_entry (id INT AUTO_INCREMENT NOT NULL, to_do_list_id INT NOT NULL, title VARCHAR(128) NOT NULL, comment VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, close_due DATETIME DEFAULT NULL, INDEX IDX_F2B20973B3AB48EB (to_do_list_id), INDEX title_idx (title), INDEX comment_idx (comment), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_do_list (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, colour VARCHAR(32) DEFAULT NULL, INDEX title_idx (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE list_entry ADD CONSTRAINT FK_F2B20973B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_list (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE list_entry DROP FOREIGN KEY FK_F2B20973B3AB48EB');
        $this->addSql('DROP TABLE list_entry');
        $this->addSql('DROP TABLE to_do_list');
    }
}
