<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321135340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Базовая таблица access control list';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TYPE access_type AS ENUM (\'rw\',\'ro\');
        ');
        $this->addSql('
            create table if not exists acl
            (
                id serial constraint acl_pk primary key,
                user_id uuid not null constraint acl_users_uuid_fk references users,
                path ltree not null,
                type access_type not null
            );
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table acl;');
        $this->addSql('drop type access_type;');
    }
}
