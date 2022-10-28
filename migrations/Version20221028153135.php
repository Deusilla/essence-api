<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221028153135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rebuild base map model';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE world_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cell_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE map_cells_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE map_impacts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE map_resources_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE map_snapshots_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE map_worlds_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE map_cells (id INT NOT NULL, world_id INT NOT NULL, x INT NOT NULL, y INT NOT NULL, compound JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D53784B48925311C ON map_cells (world_id)');
        $this->addSql('CREATE TABLE map_impacts (id INT NOT NULL, name VARCHAR(40) NOT NULL, slug VARCHAR(40) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE map_resources (id INT NOT NULL, name VARCHAR(40) NOT NULL, slug VARCHAR(40) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A0EACF3989D9B62 ON map_resources (slug)');
        $this->addSql('CREATE TABLE map_snapshots (id INT NOT NULL, cell_id INT NOT NULL, sum_id INT DEFAULT NULL, type VARCHAR(20) NOT NULL, turn INT NOT NULL, compound JSONB NOT NULL, impacts JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E8F90160CB39D93A ON map_snapshots (cell_id)');
        $this->addSql('CREATE INDEX IDX_E8F901605D0A828F ON map_snapshots (sum_id)');
        $this->addSql('CREATE INDEX IDX_E8F90160CB39D93A5D0A828F8BDE16F0 ON map_snapshots (cell_id, sum_id, compound)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E8F90160CB39D93A202015478CDE5729 ON map_snapshots (cell_id, turn, type)');
        $this->addSql('CREATE TABLE map_worlds (id INT NOT NULL, name VARCHAR(40) NOT NULL, width INT NOT NULL, height INT NOT NULL, turn INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE map_cells ADD CONSTRAINT FK_D53784B48925311C FOREIGN KEY (world_id) REFERENCES map_worlds (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE map_snapshots ADD CONSTRAINT FK_E8F90160CB39D93A FOREIGN KEY (cell_id) REFERENCES map_cells (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE map_snapshots ADD CONSTRAINT FK_E8F901605D0A828F FOREIGN KEY (sum_id) REFERENCES map_snapshots (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cell DROP CONSTRAINT fk_cb8787e28925311c');
        $this->addSql('DROP TABLE world');
        $this->addSql('DROP TABLE cell');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE map_cells_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE map_impacts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE map_resources_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE map_snapshots_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE map_worlds_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE world_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cell_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE world (id INT NOT NULL, name VARCHAR(40) NOT NULL, width INT NOT NULL, height INT NOT NULL, turn INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cell (id INT NOT NULL, world_id INT NOT NULL, x INT NOT NULL, y INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_cb8787e28925311c ON cell (world_id)');
        $this->addSql('ALTER TABLE cell ADD CONSTRAINT fk_cb8787e28925311c FOREIGN KEY (world_id) REFERENCES world (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE map_cells DROP CONSTRAINT FK_D53784B48925311C');
        $this->addSql('ALTER TABLE map_snapshots DROP CONSTRAINT FK_E8F90160CB39D93A');
        $this->addSql('ALTER TABLE map_snapshots DROP CONSTRAINT FK_E8F901605D0A828F');
        $this->addSql('DROP TABLE map_cells');
        $this->addSql('DROP TABLE map_impacts');
        $this->addSql('DROP TABLE map_resources');
        $this->addSql('DROP TABLE map_snapshots');
        $this->addSql('DROP TABLE map_worlds');
    }
}
