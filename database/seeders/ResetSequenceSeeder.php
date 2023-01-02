<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetSequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::SELECT(DB::raw("CREATE OR REPLACE FUNCTION 'reset_sequence' (tablename text, columnname text, sequence_name text)
        RETURNS 'pg_catalog'.'void' AS
        $body$
            DECLARE
            BEGIN
            EXECUTE 'SELECT setval( ''' || sequence_name  || ''', ' || '(SELECT MAX(' || columnname ||
                ') FROM ' || tablename || ')' || '+1)';
            END;
        $body$  LANGUAGE 'plpgsql';
    SELECT table_name || '_' || column_name || '_seq',
        reset_sequence(table_name, column_name, table_name || '_' || column_name || '_seq')
    FROM information_schema.columns where column_default like 'nextval%';"));
    }
}
