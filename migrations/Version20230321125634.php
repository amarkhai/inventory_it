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
        $this->addSql('create extension if not exists ltree;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
