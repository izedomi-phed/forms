<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppraisalRequest;
use App\Mail\Reminder;
use App\Employee;
use App\Supervisor;
use App\FinancialPerformance;
use App\CustomerPerformance;
use App\ProcessOperationPerformance;
use App\PerformanceCompletionForm;
use App\BehaviouralAttribute;
use App\Training;
use App\User;
use App\Recommendation;
use App\OverallSummary;
use App\Proof;
use App\Ibc;
use App\ReqDlEnhance;
use App\ReqSage;
use App\Role;
use App\Form;
use PDF;

class AccessRequestController extends Controller
{
    //

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware(['auth','verified']);    
    }

   
    public function dl_enhance_request(Request $request){
        //return $request;

        $errors = array();
        if($request->hod_email != "" || $request->hod_email != null){
            $email = explode("@", trim($request->hod_email));
            if($email[1] != 'phed.com.ng'){
                array_push($errors, "Unauthorized user. Invalid HOD Email");
            
                return response()->json([
                    'errors' => $errors,
                    'validaton' => true,
                ], 200);
            }
        }
        
        $val = $this->val($request->all());
        if($val->fails()){
            //return $val->errors();
            $errors = array();
            foreach($val->errors()->all() as $message) {
               array_push($errors, $message);
            }       

            return response()->json([
                'errors' => $errors,
                'validaton' => true,
            ], 200);
        }
       
        $access_level = implode(",", $request->access_level);

        if($request->request_type == 'dl_enhance'){
            $role = null;
            if(count($request->role) > 0){
                $role = implode(",", $request->role);
            }

            $existingR = ReqDlEnhance::where('account_id', Auth::user()->id)->first();
            if($existingR != null){
                $new_request = $existingR;
            }
            else{
                $new_request = new ReqDlEnhance;
            }
            
            
            $new_request->role = $role;
        }
        if($request->request_type == 'sage'){

            $hr_module = null;
            $finance_module = null;
            if(count($request->hr_module) > 0){
                $hr_module = implode(",", $request->hr_module);
            }
            if(count($request->finance_module) > 0){
                $finance_module = implode(",", $request->finance_module);
            }
            
            //return $request;
            $existingR = ReqSage::where('account_id', Auth::user()->id)->first();
            if($existingR != null){
                $new_request = $existingR;
            }
            else{
                $new_request = new ReqSage;
            }
            
            $new_request->hr_module = $hr_module;
            $new_request->finance_module = $finance_module;
        
        }

        $account_id = Auth::user()->id;
        $user = User::find($account_id);
        $user->name = $request->first_name . " ". $request->last_name;


        $new_request->account_id = $account_id;
        $new_request->staff_id = $request->staff_id;
        $new_request->name = $request->first_name . " ". $request->last_name;
        $new_request->staff_type = $request->staff_type;
        $new_request->job_desc = $request->job_desc;
        $new_request->designation = $request->designation;
        $new_request->access_level = $access_level;
        $new_request->location = $request->location;
        $new_request->department = $request->department;
        $new_request->termination_date = $request->termination_date;
        $new_request->mobile_no = $request->mobile_no;
        $new_request->work_no = $request->work_no;
        $new_request->hod_name = $request->hod_name;
        $new_request->hod_email = $request->hod_email;
        $new_request->status = "TO_HOD";
        $new_request->save();
        $user->save();

        if($request->request_type == "dl_enhance"){
            $subject = "PHEDC DLEnhance Access Request";
        }
        else{
            $subject = "PHEDC Sage X3 ERP Access Request";
        }
    
        $msg = new Reminder($request->request_type, $new_request->id, $request->staff_id, "TO_HOD");
        $msg->subject($subject);
        Mail::to(trim($request->hod_email))->send($msg);

    }

   

    public function val($data){
        
        return Validator::make($data, [
            'staff_id' => ['required', 'string', 'max:255',],
            'official_email' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'designation' => ['required', 'string'],
            'staff_type' => ['required', 'string'],
            'job_desc' => ['required', 'string'],
            'location' => ['required', 'string'],
            'department' => ['required', 'string'],
            'mobile_no' => ['required'],
            'work_no' => ['required'],
            'hod_name' => ['required', 'string'],
            'hod_email' => ['required', 'string'],
        ]);
    }
        
}

