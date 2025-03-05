<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('is_enum')) {
    function is_enum($table, $column) {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        return strpos($type, 'enum') !== false;
    }
}

if (!function_exists('get_enum_values')) {
    function get_enum_values($table, $column) {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum[] = $v;
        }
        return $enum;
    }
}
