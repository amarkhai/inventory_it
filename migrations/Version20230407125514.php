<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407125514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Автоматический created_at и переименование password в password_hash';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('alter table users rename column password to password_hash;');
        $this->addSql('alter table users alter column created_at set default now();');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('alter table users alter column created_at drop default;');
        $this->addSql('alter table users rename column password_hash to password;');
    }
}
