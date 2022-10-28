<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027190452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add flat world entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE world_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE world (id INT NOT NULL, name VARCHAR(40) NOT NULL, width INT NOT NULL, height INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE world_id_seq CASCADE');
        $this->addSql('DROP TABLE world');
    }
}
