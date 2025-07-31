<?php

namespace App\Imports;

use App\Models\Budget;
use App\Models\SubBudget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class BudgetImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    protected $budgetId;

    public function __construct($budgetId)
    {
        $this->budgetId = $budgetId;
    }

    public function model(array $row)
    {
        $companyId = Auth::user()->company_id;

        if (
            empty($row['sub_budget_code']) ||
            empty($row['unit_cost']) ||
            empty($row['quantity']) ||
            empty($row['unit_meausre']) ||
            empty($row['sub_budget_description'])
        ) {
            return null;
        }

        $budget = DB::table('budgets')
            ->where('id', $this->budgetId)
            ->first();

        return new SubBudget([
            'budget_name'           => $budget->budget_name,
            'budget_code'            => $budget->budget_code,
            'sub_budget_code'    => $row['sub_budget_code'],
            'sub_budget_description'     => $row['sub_budget_description'],
            'unit_cost'  => $row['unit_cost'],
            'quantity'       => $row['quantity'],
            'unit_meausre' => $row['unit_meausre'],
            'cost_type' => $budget->cost_type,
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'input_encoding' => 'UTF-8',
        ];
    }
}
