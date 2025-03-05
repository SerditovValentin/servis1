<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmployeeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {

        $databaseName = DB::connection()->getDatabaseName();
        $tables = DB::select('SHOW TABLES');

        $tableNames = array_map(function($table) use ($databaseName) {
            $table = (array) $table;
            return $table["Tables_in_{$databaseName}"];
        }, $tables);

        return view('employee.index', compact('tableNames'));

    }

    public function show($table)
    {
        $columns = Schema::getColumnListing($table);
        $rows = DB::table($table)->get();
        
        // Prepare data for display
        foreach ($rows as $row) {
            foreach ($row as $key => $value) {
                if (strpos($key, 'id_') === 0) {
                    $relatedTable = substr($key, 3); // Remove 'id_' from field name
                    $relatedTable = preg_replace('/\d+$/', '', $relatedTable); // Remove digits at the end of the table name
                    $relatedRecord = DB::table($relatedTable)->find($value);
                    $relatedRecordArray = (array) $relatedRecord;

                    if (!empty($relatedRecordArray)) {
                        // Convert associative array to array of values
                        $values = array_values($relatedRecordArray);

                        // Get values
                        $firstValue = isset($values[0]) ? $values[0] : ''; // First column (ID)
                        $secondValue = isset($values[1]) ? $values[1] : ''; // Second column (if exists)
                        $thirdValue = isset($values[2]) ? $values[2] : ''; // Third column (if exists)

                        // Combine values if they exist
                        $displayValue = $firstValue;
                        if ($secondValue) {
                            $displayValue .= ' - ' . $secondValue;
                        }
                        if ($thirdValue) {
                            $displayValue .= ' ' . $thirdValue;
                        }

                        // Save prepared value back to the row
                        $row->$key = $displayValue;
                    }
                }
            }
        }

        // Get column types
        $columnTypes = [];
        foreach ($columns as $column) {
            try {
                $columnTypes[$column] = Schema::getColumnType($table, $column);
            } catch (\Doctrine\DBAL\Exception $e) {
                $columnTypes[$column] = 'enum'; // Если ошибка, принудительно указываем тип enum
            }
        }
        
        return view('employee.show', compact('columnTypes', 'table', 'columns', 'rows'));
    }

    public function edit($table, $id)
    {
        $columns = Schema::getColumnListing($table);
        $row = DB::table($table)->find($id);
        $relatedData = [];

        foreach ($columns as $column) {
            if (strpos($column, 'id_') === 0) {
                $relatedTable = substr($column, 3); // Удаление 'id_' из имени поля
                $relatedTable = preg_replace('/\d+$/', '', $relatedTable); // Удаление чисел в конце имени таблицы
                $relatedRecords = DB::table($relatedTable)->get();

                if ($column == 'id_positions') {
                    // Отображение только первых двух полей для id_positions
                    $relatedData[$column] = $relatedRecords->map(function ($relatedRecord) {
                        $relatedValues = array_values((array) $relatedRecord);
                        $displayValue = $relatedValues[0] . ' - ' . $relatedValues[1]; // Первые два поля
                        return [
                            'id' => $relatedRecord->id,
                            'display' => $displayValue
                        ];
                    });
                } else {
                    // Отображение всех полей для других id_
                    $relatedData[$column] = $relatedRecords->map(function ($relatedRecord) {
                        $relatedValues = array_values((array) $relatedRecord);
                        $displayValue = $relatedValues[0] . ' - ' . implode('; ', array_slice($relatedValues, 1)); // Все поля
                        return [
                            'id' => $relatedRecord->id,
                            'display' => $displayValue
                        ];
                    });
                }
            }
        }

        // Get column types
        $columnTypes = [];
        foreach ($columns as $column) {
            try {
                $columnTypes[$column] = Schema::getColumnType($table, $column);
            } catch (\Doctrine\DBAL\Exception $e) {
                $columnTypes[$column] = 'enum'; // Если ошибка, принудительно указываем тип enum
            }
        }

        return view('employee.edit', compact('table', 'columns', 'row', 'relatedData', 'columnTypes'));
    }

    public function update(Request $request, $table, $id)
{
    // Определение правил валидации в зависимости от имени таблицы
    if ($table === 'accounting') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_repair_requests' => ['nullable', 'integer'],
            'id_movement_type' => ['required', 'integer'],
            'amount' => ['required', 'decimal:10,2'],
            'transaction_date' => ['required', 'date'],
        ];
    }
    elseif ($table === 'appliance') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_client' => ['integer'],
            'brand' => ['nullable', 'alpha', 'max:50'],
            'model' => ['nullable', 'alpha', 'max:50'],
            'id_type_equipment' => ['integer'],
            'warranty' => ['boolean'],
        ];
    }
    elseif ($table === 'client') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'surname' => ['nullable', 'alpha', 'max:50'],
            'name' => ['nullable', 'alpha', 'max:50'],
            'patronymic' => ['nullable', 'alpha', 'max:50'],
            'phone' => ['required', 'integer', 'digits:11'],
            'email' => ['email'],
            'address' => ['nullable',],
        ];
    } 
    elseif ($table === 'diagnostics') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_repair_requests'=> ['integer',],
            'id_employee'=> ['required', 'integer'],
            'diagnosis'=> ['string', 'max:65535'],
            'estimated_cost'=> ['required', 'decimal:10,2'],
            'confirmation_status'=> ['required', 'boolean'],
        ];
    }
    elseif ($table === 'employee') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'surname' => ['required', 'alpha', 'max:50'],
            'name' => ['required', 'alpha', 'max:50'],
            'patronymic' => ['nullable', 'alpha', 'max:50'],
            'date_of_birth' => ['required', 'date'],
            'phone_number' => ['required', 'integer', 'digits:11'],
            'id_post' => ['integer'],
            'password' => ['required']
        ];
    }
    elseif ($table === 'movement_type') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'type'=> ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'order') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_supplier'=> ['required', 'integer'],
            'total_amount'=> ['required', 'decimal:10,2'],
            'order_date'=> ['required', 'date'],
            'id_status'=> ['required', 'integer'],
        ];
    }
    elseif ($table === 'payment') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_repair_requests'=> ['required', 'integer'],
            'amount'=> ['required', 'decimal:10,2'],
            'id_payment_method'=> ['required', 'integer'],
            'payment_date_time'=> ['required', 'date'],
        ];
    }
    elseif ($table === 'payment_method') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'method'=> ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'post') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'post' => ['required', 'alpha', 'max:50']
        ];
    }
    elseif ($table === 'repair') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_repair_requests'=> ['required', 'integer'],
            'repair_details'=> ['string', 'max:65535'],
            'repair_date_time'=> ['required', 'date'],
            'id_status'=> ['required', 'integer'],
        ];
    }
    elseif ($table === 'repair_requests') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_client' => ['integer', ''],
            'id_appliance' => ['required', ''],
            'issue_description' => ['string', 'max:65535'],
            'preferred_visit_time' => ['required', 'datetime'],
            'id_status' => ['required', 'integer'],
        ];
    }
    elseif ($table === 'status') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'status' => ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'suppliers') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'name'=> ['string', 'max:100'],
            'inn'=> ['string', 'max:20'],
            'bank'=> ['string', 'max:100'],
            'bik'=> ['string', 'max:20'],
            'account_number'=> ['string', 'max:30'],
            'director_name'=> ['string', 'max:100'],
            'accountant_name'=> ['string', 'max:100'],
        ];
    }
    elseif ($table === 'type_equipment') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'type'=> ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'warehouse') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'name' => ['required','alpha','max:50'],
            'price' => ['required', 'decimal:10,2'],
            'stock_quantity' => ['required','integer'],
        ];
    }
    elseif ($table === 'warehouse_movements') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_warehouse'=> ['required', 'integer'],
            'quantity'=> ['required', 'integer'],
            'id_movement_type'=> ['required', 'integer'],
            'movement_date'=> ['required', 'datetime'],
        ];
    }
    elseif ($table === 'warranties') {
        $rules = [
            'id' => ['integer', 'unique:' . $table . ',id,' . $id],
            'id_repair_requests'=> ['required', 'integer'],
            'warranty_period'=> ['required', 'integer'],
            'warranty_start'=> ['required', 'date'],
        ];
    }

    // Выполнение валидации
    $validatedData = $request->validate($rules);

    $data = $validatedData;

    DB::table($table)->where('id', $id)->update($data);

    return redirect()->route('employee.show', ['table' => $table])->with('success', 'Record updated successfully');
}

    public function destroy($table, $id)
    {
        DB::table($table)->where('id', $id)->delete();

        return redirect()->route('employee.show', ['table' => $table])->with('success', 'Record deleted successfully');
    }


    public function create($table)
{
    $columns = Schema::getColumnListing($table);
    $relatedData = [];

    foreach ($columns as $column) {
        if (strpos($column, 'id_') === 0) {
            $relatedTable = substr($column, 3); // Удаление 'id_' из имени поля
            $relatedTable = preg_replace('/\d+$/', '', $relatedTable); // Удаление чисел в конце имени таблицы
            $relatedRecords = DB::table($relatedTable)->get();
            
            if ($column == 'id_positions') {
                // Отображение только первых двух полей для id_positions
                $relatedData[$column] = $relatedRecords->map(function ($relatedRecord) {
                    $relatedValues = array_values((array) $relatedRecord);
                    $displayValue = $relatedValues[0] . ' - ' . $relatedValues[1]; // Первые два поля
                    return [
                        'id' => $relatedRecord->id,
                        'display' => $displayValue
                    ];
                });
            } else {
                // Отображение всех полей для других id_
                $relatedData[$column] = $relatedRecords->map(function ($relatedRecord) {
                    $relatedValues = array_values((array) $relatedRecord);
                    $displayValue = $relatedValues[0] . ' - ' . implode('; ', array_slice($relatedValues, 1)); // Все поля
                    return [
                        'id' => $relatedRecord->id,
                        'display' => $displayValue
                    ];
                });
            }
        }
    }

    // Get column types
    $columnTypes = [];
    foreach ($columns as $column) {
        try {
            $columnTypes[$column] = Schema::getColumnType($table, $column);
        } catch (\Doctrine\DBAL\Exception $e) {
            $columnTypes[$column] = 'enum'; // Если ошибка, принудительно указываем тип enum
        }
    }

    return view('employee.create', compact('table', 'columns', 'relatedData', 'columnTypes'));
}

    public function store(Request $request, $table)
{
    // Определение правил валидации в зависимости от имени таблицы
    if ($table === 'accounting') {
        $rules = [
            'id_repair_requests' => ['nullable', 'integer'],
            'id_movement_type' => ['required', 'integer'],
            'amount' => ['required', 'decimal:10,2'],
            'transaction_date' => ['required', 'date'],
        ];
    }
    elseif ($table === 'appliance') {
        $rules = [
            'id_client' => ['integer'],
            'brand' => ['nullable', 'alpha', 'max:50'],
            'model' => ['nullable', 'alpha', 'max:50'],
            'id_type_equipment' => ['integer'],
            'warranty' => ['boolean'],
        ];
    }
    elseif ($table === 'client') {
        $rules = [
            'surname' => ['nullable', 'alpha', 'max:50'],
            'name' => ['nullable', 'alpha', 'max:50'],
            'patronymic' => ['nullable', 'alpha', 'max:50'],
            'phone' => ['required', 'integer', 'digits:11'],
            'email' => ['email'],
            'address' => ['nullable',],
        ];
    } 
    elseif ($table === 'diagnostics') {
        $rules = [
            'id_repair_requests'=> ['integer',],
            'id_employee'=> ['required', 'integer'],
            'diagnosis'=> ['string', 'max:65535'],
            'estimated_cost'=> ['required', 'decimal:10,2'],
            'confirmation_status'=> ['required', 'boolean'],
        ];
    }
    elseif ($table === 'employee') {
        $rules = [
            'surname' => ['required', 'alpha', 'max:50'],
            'name' => ['required', 'alpha', 'max:50'],
            'patronymic' => ['nullable', 'alpha', 'max:50'],
            'date_of_birth' => ['required', 'date'],
            'phone_number' => ['required', 'integer', 'digits:11'],
            'id_post' => ['integer'],
            'password' => ['required']
        ];
    }
    elseif ($table === 'movement_type') {
        $rules = [
            'type'=> ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'order') {
        $rules = [
            'id_supplier'=> ['required', 'integer'],
            'total_amount'=> ['required', 'decimal:10,2'],
            'order_date'=> ['required', 'date'],
            'id_status'=> ['required', 'integer'],
        ];
    }
    elseif ($table === 'payment') {
        $rules = [
            'id_repair_requests'=> ['required', 'integer'],
            'amount'=> ['required', 'decimal:10,2'],
            'id_payment_method'=> ['required', 'integer'],
            'payment_date_time'=> ['required', 'date'],
        ];
    }
    elseif ($table === 'payment_method') {
        $rules = [
            'method'=> ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'post') {
        $rules = [
            'post' => ['required', 'alpha', 'max:50']
        ];
    }
    elseif ($table === 'repair') {
        $rules = [
            'id_repair_requests'=> ['required', 'integer'],
            'repair_details'=> ['string', 'max:65535'],
            'repair_date_time'=> ['required', 'date'],
            'id_status'=> ['required', 'integer'],
        ];
    }
    elseif ($table === 'repair_requests') {
        $rules = [
            'id_client' => ['integer', ''],
            'id_appliance' => ['required', ''],
            'issue_description' => ['string', 'max:65535'],
            'preferred_visit_time' => ['required', 'datetime'],
            'id_status' => ['required', 'integer'],
        ];
    }
    elseif ($table === 'status') {
        $rules = [
            'status' => ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'suppliers') {
        $rules = [
            'name'=> ['string', 'max:100'],
            'inn'=> ['string', 'max:20'],
            'bank'=> ['string', 'max:100'],
            'bik'=> ['string', 'max:20'],
            'account_number'=> ['string', 'max:30'],
            'director_name'=> ['string', 'max:100'],
            'accountant_name'=> ['string', 'max:100'],
        ];
    }
    elseif ($table === 'type_equipment') {
        $rules = [
            'type'=> ['alpha', 'max:50'],
        ];
    }
    elseif ($table === 'warehouse') {
        $rules = [
            'name' => ['required','alpha','max:50'],
            'price' => ['required', 'decimal:10,2'],
            'stock_quantity' => ['required','integer'],
        ];
    }
    elseif ($table === 'warehouse_movements') {
        $rules = [
            'id_warehouse'=> ['required', 'integer'],
            'quantity'=> ['required', 'integer'],
            'id_movement_type'=> ['required', 'integer'],
            'movement_date'=> ['required', 'datetime'],
        ];
    }
    elseif ($table === 'warranties') {
        $rules = [
            'id_repair_requests'=> ['required', 'integer'],
            'warranty_period'=> ['required', 'integer'],
            'warranty_start'=> ['required', 'date'],
        ];
    }

    // Выполнение валидации
    $validatedData = $request->validate($rules);

    $data = $validatedData;

    DB::table($table)->insert($data);

    return redirect()->route('employee.show', ['table' => $table])->with('success', 'Record added successfully');
}
   
}
