<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321125634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Включение расширения ltree';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS ltree;');
    }

    public function down(Schema $schema): void
    {
    }
}
