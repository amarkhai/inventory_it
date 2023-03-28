<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328014833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Функции + триггер для предупреждения сохранения
        неверного значения path, в котором повторяются id узлов.
        Также это предупреждает переполнение стека при переписывании
        path дочерних узлов функцией update_children_items_path()';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE OR REPLACE FUNCTION anyarray_uniq(with_array anyarray)
                RETURNS anyarray AS
            $BODY$
            DECLARE
                loop_offset integer;
                return_array with_array%TYPE := \'{}\';
            BEGIN
                IF with_array IS NULL THEN
                    return NULL;
                END IF;
            
                IF with_array = \'{}\' THEN
                    return return_array;
                END IF;
                
                FOR loop_offset IN ARRAY_LOWER(with_array, 1)..ARRAY_UPPER(with_array, 1) LOOP
                        IF with_array[loop_offset] IS NULL THEN
                            IF NOT EXISTS(
                                    SELECT 1
                                    FROM UNNEST(return_array) AS s(a)
                                    WHERE a IS NULL
                                ) THEN
                                return_array = ARRAY_APPEND(return_array, with_array[loop_offset]);
                            END IF;            
                        ELSEIF NOT(with_array[loop_offset] = ANY(return_array)) 
                        OR NOT(NULL IS DISTINCT FROM (with_array[loop_offset] = ANY(return_array))) THEN
                            return_array = ARRAY_APPEND(return_array, with_array[loop_offset]);
                        END IF;
                    END LOOP;
            
                RETURN return_array;
            END;
            $BODY$ LANGUAGE plpgsql;
        ');

        $this->addSql('
            CREATE OR REPLACE FUNCTION prevent_wrong_items_nesting() RETURNS TRIGGER AS $prevent_wrong_items_nesting$
                DECLARE
                    pathArray varchar ARRAY;
                BEGIN
                    pathArray := string_to_array(NEW.path::text, \'.\');
            
                IF (cardinality(anyarray_uniq(pathArray)) != cardinality(pathArray)) THEN
                    RAISE EXCEPTION \'Wrong path value %. Cycle detected.\', NEW.path;
                end if;
                RETURN NEW;
            END;
            $prevent_wrong_items_nesting$ LANGUAGE plpgsql;
        ');

        $this->addSql('
            CREATE TRIGGER tr_prevent_wrong_items_nesting
                BEFORE
                    INSERT OR
                    UPDATE OF path ON items
                FOR EACH ROW
            EXECUTE FUNCTION prevent_wrong_items_nesting();
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop trigger tr_prevent_wrong_items_nesting on items;');
        $this->addSql('drop function prevent_wrong_items_nesting();');
        $this->addSql('drop function anyarray_uniq(anyarray);');
    }
}
