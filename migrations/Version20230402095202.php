<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402095202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Уникальный индекс на items path';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            alter table items add constraint items_pk2 unique (path);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            alter table items drop constraint items_pk2;
        ');
    }
}
