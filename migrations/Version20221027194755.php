<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027194755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cell entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE cell_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cell (id INT NOT NULL, world_id INT NOT NULL, x INT NOT NULL, y INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CB8787E28925311C ON cell (world_id)');
        $this->addSql('ALTER TABLE cell ADD CONSTRAINT FK_CB8787E28925311C FOREIGN KEY (world_id) REFERENCES world (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE world ADD turn INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE cell_id_seq CASCADE');
        $this->addSql('ALTER TABLE cell DROP CONSTRAINT FK_CB8787E28925311C');
        $this->addSql('DROP TABLE cell');
        $this->addSql('ALTER TABLE world DROP turn');
    }
}
