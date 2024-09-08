<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240908211708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question ADD hash VARCHAR(255) DEFAULT \'\' NOT NULL');
        $this->addSql('ALTER TABLE question ALTER content SET DEFAULT \'\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE question DROP hash');
        $this->addSql('ALTER TABLE question ALTER content DROP DEFAULT');
    }
}
