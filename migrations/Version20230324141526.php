<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230324141526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблиц items и rights';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TYPE access_type AS ENUM (\'rw\',\'ro\');');
        $this->addSql('CREATE TYPE item_status AS ENUM (\'active\',\'deleted\');');
        $this->addSql('
            CREATE TABLE ITEMS (
                    id BIGSERIAL CONSTRAINT items_pk PRIMARY KEY,
                    name VARCHAR(100),
                    description TEXT,
                    owner_id UUID CONSTRAINT items_users_uuid_fk REFERENCES users,
                    path LTREE NOT NULL,
                    status item_status NOT NULL DEFAULT \'active\'
            );
        ');

        $this->addSql('
            CREATE TABLE rights (
                id UUID CONSTRAINT rights_pk PRIMARY KEY,
                item_id BIGINT NOT NULL CONSTRAINT rights_items_id_fk REFERENCES items,
                user_id UUID NOT NULL CONSTRAINT rights_users_uuid_fk REFERENCES users,
                type access_type NOT NULL
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
