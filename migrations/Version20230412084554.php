<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412084554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('alter table items add created_at timestamptz default now() not null;');
        $this->addSql('alter table items add updated_at timestamptz default now() not null;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('alter table items drop column created_at;');
        $this->addSql('alter table items drop column updated_at;');
    }
}
