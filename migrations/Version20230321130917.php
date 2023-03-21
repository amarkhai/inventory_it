<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321130917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Базовая таблица items';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            create table if not exists items (
                    id bigserial constraint items_pk primary key,
                    name varchar(100),
                    owner_id uuid constraint items_users_uuid_fk references users,
                    path ltree not null
            );
        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table items;');
    }
}
