<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    //
    protected $table = 'emplyees';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        // PERSONAL INFO
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'date_of_birth',
        'national_id_number',

        // CONTACT INFO
        'email','phone_number','emergency_contact_name','emergency_contact_phone',
        
        // EMPLOYMENT DETAILS
        'role','department',
        'job_title','date_hired','contract_end_date','employment_type','reporting_manager',

        // ADDRESS INFO
        'address','city','region','country','postal_code',

        // BANK INFO
        'bank_name','bank_account_number','tax_identification_number','social_security_name','social_security_number',

        // HR 
        'company_id',
        'status','termination_date','salary_amount',
        'termination_reason','created_by','soft_delete',
    ];
}
