<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324141526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Таблица items и rights';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TYPE access_type AS ENUM (\'rw\',\'ro\');');
        $this->addSql('CREATE TYPE item_status AS ENUM (\'active\',\'deleted\');');
        $this->addSql('
            create table if not exists items (
                    id bigserial constraint items_pk primary key,
                    name varchar(100),
                    description text,
                    owner_id uuid constraint items_users_id_fk references users,
                    path ltree not null,
                    status item_status not null default \'active\'
            );
        ');

        $this->addSql('create table rights
            (
                id      uuid
                    constraint rights_pk
                        primary key,
                item_id bigint      not null
                    constraint rights_items_id_fk
                        references items,
                user_id uuid        not null
                    constraint rights_users_id_fk
                        references users,
                type    access_type not null
            );
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table rights;');
        $this->addSql('drop table items;');
        $this->addSql('drop type access_type;');
        $this->addSql('drop type item_status;');
    }
}
