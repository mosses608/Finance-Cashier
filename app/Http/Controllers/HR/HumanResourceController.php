<?php

namespace App\Http\Controllers\HR;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class HumanResourceController extends Controller
{
    //
    public function salaryAdvance()
    {
        $months = collect(range(1, 12))->mapWithKeys(function ($monthNumber) {
            return [$monthNumber => \Carbon\Carbon::create()->month($monthNumber)->format('F')];
        });

        $staffs = DB::table('emplyees')
            ->select([
                'first_name',
                'last_name',
                'id'
            ])
            ->where('soft_delete', 0)
            ->orderBy('first_name', 'ASC')
            ->get();

        $budgetData = DB::table('budgets')
            ->select('budget_year', 'project_name')
            ->where('soft_delete', 0)
            ->orderBy('budget_year', 'DESC')
            ->get();

        $salaryAdvances = DB::table('salary_advances AS SA')
            ->join('emplyees AS EM', 'SA.staff_id', '=', 'EM.id')
            ->select([
                'SA.date',
                'SA.amount',
                'EM.first_name',
                'EM.last_name',
                'SA.project',
                'SA.year',
                'SA.description',
                'SA.status',
                'SA.month',
            ])
            ->where('SA.status', 'pending')
            ->where('SA.soft_delete', 0)
            ->orderBy('SA.id', 'DESC')
            ->get();

        $approvedSalaryAdvances = DB::table('salary_advances AS SA')
            ->join('emplyees AS EM', 'SA.staff_id', '=', 'EM.id')
            ->select([
                'SA.date',
                'SA.amount',
                'EM.first_name',
                'EM.last_name',
                'SA.project',
                'SA.year',
                'SA.description',
                'SA.status',
                'SA.month',
            ])
            ->where('SA.status', 'approved')
            ->where('SA.soft_delete', 0)
            ->orderBy('SA.id', 'DESC')
            ->get();

        // dd($salaryAdvances);

        return view('templates.salary-advance', compact(
            'months',
            'staffs',
            'budgetData',
            'salaryAdvances',
            'approvedSalaryAdvances'
        ));
    }

    public function applySalaryAdvance(Request $request)
    {

        try {
            $request->validate([
                'date' => 'required|date',
                'amount' => 'required|decimal:0,2',
                'month' => 'required|integer',
                'staff_id' => 'required|integer',
                'year' => 'required|integer',
                'project' => 'required|string',
                'attachment' => 'nullable|file|mimes:pdf',
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            dd($e);
        }

        $filePath = null;

        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('attachments', 'public');
        }

        $staffAlreadyApplied = null;

        $staffAlreadyApplied = DB::table('salary_advances')
            ->where('staff_id', $request->staff_id)
            ->where('year', $request->year)
            ->where('soft_delete', 0)
            ->first();

        $staff = null;

        if ($staffAlreadyApplied) {
            $staff = DB::table('emplyees')
                ->where('id', $staffAlreadyApplied->staff_id)
                ->select([
                    'first_name',
                    'last_name',
                ])
                ->first();
        }

        if ($staffAlreadyApplied && $staffAlreadyApplied->paid === false) {
            return redirect()->back()->with('error_msg', 'Staff' . ' ' . $staff->first_name . ' ' . $staff->last_name . ' ' . ' has already applied for salary advance and not paid');
        }

        DB::table('salary_advances')->insert([
            'date' => $request->date,
            'amount' => $request->amount,
            'month' => $request->month,
            'staff_id' => $request->staff_id,
            'year' => $request->year,
            'project' => $request->project,
            'attachment' => $filePath,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_msg', 'Allowance application sent successfully. Wait for approval!');
        // dd($request->all());
    }

    public function leaveRegister()
    {
        $leaves = DB::table('leave_types AS LT')
            ->join('emplyees AS EM', 'LT.created_by', '=', 'EM.id')
            ->select('LT.*', 'EM.first_name', 'EM.last_name')
            ->where('LT.soft_delete', 0)
            ->orderBy('LT.name', 'ASC')
            ->get();

        return view('hr.leave-register', compact('leaves'));
    }

    public function storeLeaveTypes(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'days' => 'required|integer',
            'leave_priority' => 'required|string',
            'gender_specification' => 'required|string',
            'require_attachment' => 'required|integer',
            'is_balance_carry_over' => 'required|integer',
        ]);

        $leaveExists = DB::table('leave_types')
            ->where('name', $request->name)
            ->where('soft_delete', 0)
            ->exists();

        if ($leaveExists === true) {
            return redirect()->back()->with('error_msg', 'Leave name already exists!');
        }

        DB::table('leave_types')->insert([
            'name' => $request->name,
            'days' => $request->days,
            'leave_priority' => $request->leave_priority,
            'gender_specification' => $request->gender_specification,
            'require_attachment' => $request->require_attachment,
            'is_balance_carry_over' => $request->is_balance_carry_over,
            'created_by' => Auth::user()->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_msg', 'Leave has been created successfully!');
    }

    public function applyLeave()
    {
        $leaveTypes = DB::table('leave_types')
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->where('soft_delete', 0)
            ->get();

        $myLeaveApplications = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->select([
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.attachment AS attachment',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
            ])
            ->where('LA.user_id', Auth::user()->user_id)
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        return view('hr.leave-application', compact([
            'leaveTypes',
            'myLeaveApplications'
        ]));
    }

    public function leaveApplications(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'nullable|string',
            'attachment' => 'nullable|mimes:pdf|max:2048',
        ]);

        $leaveType = DB::table('leave_types')->where('id', $request->leave_type)->first();

        if (!$leaveType) {
            return redirect()->back()->with('error_msg', 'Selected leave type not found.');
        }

        $filePath = null;

        if ($leaveType->require_attachment != null) {
            if ($request->hasFile('attachment')) {
                $filePath = $request->file('attachment')->store('leave_attachments', 'public');
            } else {
                return redirect()->back()->with('error_msg', 'This leave type requires an attachment, please upload one!');
            }
        }

        if (Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) > $leaveType->days) {
            return redirect()->back()->with('error_msg', 'This leave type allows only ' . $leaveType->days . ' days.');
        }

        DB::table('leave_applications')->insert([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason ?? null,
            'user_id' => Auth::user()->user_id,
            'attachment' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success_msg', 'Leave application sent successfully. Please wait for approval!');
    }

    public function viewApplications()
    {
        $leaveApplications = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->join('emplyees AS EM', 'LA.user_id', 'EM.id')
            ->join('departments AS D', 'EM.department', '=', 'D.id')
            ->select([
                'LA.id AS leaveId',
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.attachment AS attachment',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
                'EM.first_name AS fName',
                'EM.last_name AS lName',
                'EM.phone_number AS phone',
                'D.name AS departmentName'
            ])
            ->where('LA.status', 'Pending')
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        $approvedApplications = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->join('emplyees AS EM', 'LA.user_id', 'EM.id')
            ->join('departments AS D', 'EM.department', '=', 'D.id')
            ->select([
                'LA.id AS leaveId',
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.attachment AS attachment',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
                'EM.first_name AS fName',
                'EM.last_name AS lName',
                'EM.phone_number AS phone',
                'D.name AS departmentName'
            ])
            ->where('LA.status', 'Approved')
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        return view('hr.view-leave-applications', compact([
            'leaveApplications',
            'approvedApplications'
        ]));
    }

    public function approveApplication(Request $request)
    {
        $request->validate([
            'application_id' => 'required|string',
            'approve' => 'nullable|string',
            'reject' => 'nullable|string',
        ]);

        try {
            $decryptedApplicationId = Crypt::decrypt($request->application_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $applicationExists = DB::table('leave_applications')
            ->where('id', $decryptedApplicationId)
            ->first();

        if (!$applicationExists) {
            return redirect()->back()->with('error_msg', 'This application request does not exists!');
        }

        if ($request->has('approve')) {
            DB::table('leave_applications')
                ->where('id', $decryptedApplicationId)
                ->update([
                    'status' => 'Approved',
                    'approved_by' => Auth::user()->user_id,
                    'approved_at' => Carbon::now(),
                ]);

            return redirect()->back()->with('success_msg', 'Application request approved successfully!');
        }

        if ($request->has('reject')) {
            DB::table('leave_applications')
                ->where('id', $decryptedApplicationId)
                ->update([
                    'status' => 'Rejected',
                    'approved_by' => Auth::user()->user_id,
                    'approved_at' => Carbon::now(),
                ]);

            return redirect()->back()->with('success_msg', 'Application request rejected successfully!');
        }

        return redirect()->back()->with('error_msg', 'Failed to update this leave application request!');
    }

    public function leaveAdjustments()
    {
        $myLeaveApplications = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->select([
                'LA.id AS leaveId',
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.attachment AS attachment',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
                'LA.is_adjusted AS is_adjusted',
                'LA.adjusted_days AS adjusted_days',
            ])
            ->where('LA.user_id', Auth::user()->user_id)
            ->whereNull('LA.adjusted_days')
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        $approvedAdjustments = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->select([
                'LA.id AS leaveId',
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.attachment AS attachment',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
                'LA.is_adjusted AS is_adjusted',
                'LA.adjusted_days AS adjusted_days',
            ])
            ->where('LA.user_id', Auth::user()->user_id)
            ->whereNotNull('LA.is_adjustment_approved')
            ->whereNotNull('LA.adjusted_days')
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        return view('hr.leave-adjustments', compact('myLeaveApplications', 'approvedAdjustments'));
    }

    public function applyForAdjustments(Request $request)
    {
        $request->validate([
            'leave_id' => 'required|string',
            'adjusted_days' => 'required|integer'
        ]);

        try {
            $decryptedApplicationId = Crypt::decrypt($request->leave_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $applicationExists = DB::table('leave_applications')
            ->where('id', $decryptedApplicationId)
            ->first();

        if (!$applicationExists) {
            return redirect()->back()->with('error_msg', 'This application request does not exists!');
        }

        DB::table('leave_applications')->where('id', $decryptedApplicationId)->update([
            'is_adjusted' => 1,
            'adjusted_days' => $request->adjusted_days,
        ]);

        return redirect()->back()->with('success_msg', $request->adjusted_days . ' ' . ' days' . ' ' .  'Adjustment application sent successfully!');
    }

    public function viewAdjustmentLists()
    {
        $adjustedLeaveApplications = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->join('emplyees AS EM', 'LA.user_id', 'EM.id')
            ->join('departments AS D', 'EM.department', '=', 'D.id')
            ->select([
                'LA.id AS leaveId',
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.attachment AS attachment',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
                'EM.first_name AS fName',
                'EM.last_name AS lName',
                'EM.phone_number AS phone',
                'D.name AS departmentName',
                'LA.adjusted_days AS adjusted_days',
                'LA.is_adjustment_approved as adjustmentStatus',
            ])
            ->whereNull('LA.is_adjustment_approved')
            ->whereNotNull('LA.is_adjusted')
            ->whereNotNull('LA.adjusted_days')
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        $approvedAdjustmentLists = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->join('emplyees AS EM', 'LA.user_id', 'EM.id')
            ->join('departments AS D', 'EM.department', '=', 'D.id')
            ->select([
                'LA.id AS leaveId',
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.attachment AS attachment',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
                'EM.first_name AS fName',
                'EM.last_name AS lName',
                'EM.phone_number AS phone',
                'D.name AS departmentName',
                'LA.adjusted_days AS adjusted_days',
                'LA.is_adjustment_approved as adjustmentStatus',
            ])
            ->whereNotNull('LA.is_adjustment_approved')
            ->whereNotNull('LA.is_adjusted')
            ->whereNotNull('LA.adjusted_days')
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        // dd($adjustedLeaveApplications);

        return view('hr.adjustment-lis', compact('adjustedLeaveApplications', 'approvedAdjustmentLists'));
    }

    public function approveAdjustmentApplication(Request $request)
    {
        $request->validate([
            'application_id' => 'required|string',
            'approve' => 'nullable|string',
            'reject' => 'nullable|string',
        ]);

        try {
            $decryptedApplicationId = Crypt::decrypt($request->application_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $applicationExists = DB::table('leave_applications')
            ->where('id', $decryptedApplicationId)
            ->first();

        if (!$applicationExists) {
            return redirect()->back()->with('error_msg', 'This application request does not exists!');
        }

        $adjustedDays = $applicationExists->adjusted_days;
        $originalEndDate = $applicationExists->end_date;

        $finalAdjustedDate = Carbon::parse($originalEndDate)->addDays($adjustedDays)->format('Y-m-d');

        // dd($finalAdjustedDate);

        if ($request->has('approve')) {
            DB::table('leave_applications')
                ->where('id', $decryptedApplicationId)
                ->update([
                    'is_adjustment_approved' => 1,
                    'end_date' => $finalAdjustedDate,
                ]);

            return redirect()->back()->with('success_msg', 'Leave adjustment for' . ' ' . $adjustedDays . ' ' . ' days ' . 'approved successfully!');
        }

        if ($request->has('reject')) {
            DB::table('leave_applications')
                ->where('id', $decryptedApplicationId)
                ->update([
                    'is_adjustment_approved' => null,
                    'is_adjusted' => null,
                    'adjusted_days' => null,
                ]);

            return redirect()->back()->with('success_msg', 'Leave adjustment for' . ' ' . $adjustedDays . ' ' . ' days ' . 'rejected!');
        }
    }

    public function leaveReports(Request $request)
    {
        $leaveApplicationReportData = DB::table('leave_applications AS LA')
            ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
            ->join('emplyees AS EM', 'LA.user_id', 'EM.id')
            ->join('departments AS D', 'EM.department', '=', 'D.id')
            ->select([
                'LA.id AS leaveId',
                'LT.name AS leaveName',
                'LA.start_date AS start_date',
                'LA.end_date AS end_date',
                'LT.days AS days',
                'LA.created_at AS dateApplied',
                'LA.status AS status',
                'EM.first_name AS fName',
                'EM.last_name AS lName',
                'EM.phone_number AS phone',
                'D.name AS departmentName',
            ])
            ->where('LA.status', 'Approved')
            ->where('LA.soft_delete', 0)
            ->where('LT.soft_delete', 0)
            ->orderBy('LA.id', 'DESC')
            ->get();

        $staffs = DB::table('emplyees')
            ->select('first_name', 'last_name', 'id')
            ->orderBy('first_name', 'ASC')
            ->get();

        if ($request->has('searchFrom') || $request->searchTo || $request->staff) {
            $fromDate = $request->searchFrom;
            $toDate = $request->searchTo;
            $staffId = $request->staff;

            $leaveApplicationReportData = DB::table('leave_applications AS LA')
                ->join('leave_types AS LT', 'LA.leave_type', '=', 'LT.id')
                ->join('emplyees AS EM', 'LA.user_id', 'EM.id')
                ->join('departments AS D', 'EM.department', '=', 'D.id')
                ->select([
                    'LA.id AS leaveId',
                    'LT.name AS leaveName',
                    'LA.start_date AS start_date',
                    'LA.end_date AS end_date',
                    'LT.days AS days',
                    'LA.created_at AS dateApplied',
                    'LA.status AS status',
                    'EM.first_name AS fName',
                    'EM.last_name AS lName',
                    'EM.phone_number AS phone',
                    'D.name AS departmentName',
                ])
                ->where('LA.user_id', $staffId)
                ->orWhere('LA.user_id', null)
                ->whereBetween('LA.created_at', [$fromDate, $toDate])
                ->where('LA.status', 'Approved')
                ->where('LA.soft_delete', 0)
                ->where('LT.soft_delete', 0)
                ->orderBy('LA.id', 'DESC')
                ->get();
        }

        return view('hr.leave-reports', compact('leaveApplicationReportData', 'staffs'));
    }
}
