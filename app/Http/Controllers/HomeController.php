<?php

namespace App\Http\Controllers;


use App\Http\Controllers\UtilityController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\AppraisalRequest;
use App\Mail\Reminder;
use App\Approvers;
use App\Approval;
use App\ReqDlEnhance;
use App\ReqSage;
use App\ReqVpnWifi;
use App\User;
use App\OverallSummary;
use App\Ibc;
use App\Role;
use App\Form;
use PDF;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    /*
     * Show the application dashboard.
     * After successful, login system checks if the current user is a staff with approval rights
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        //return 'yes';
        //$role = Role::where('email', Auth::user()->email)->first();
        $owner = Form::where('owner', Auth::user()->email)->first();

        //return $role;
        return $this->staff_dashboard();

        if($owner != null){
            return redirect()->route('approval-dashboard');
        }
        else{
           return $this->staff_dashboard();
        }
        //return redirect('admin/it'); // redirect to admin dashboard
    }

    /*
    *  Renders Dashboard: Staff with approval access
    */
    public function approval_dashboard(){
        $data = UtilityController::get_access(true);
        //return $data;
        return view('Dashboard.requests')->with($data);
    }

    /*
    * Renders Dashboard: Staff that have access 'owner' can make changes to the form settings
    * settings like: set roles, set levels
    */
    public function settings_dashboard(){
        $data = UtilityController::get_access(true);
        return view('Dashboard.settings')->with($data);
    }

    /*
    *  Renders Dashboard: Staff without approval access
    */
    public function staff_dashboard(){

        $data =  UtilityController::get_access(false);
        return view('Staff.dashboard', $data); //redirect to  staff dashboard
    }

    /*
    *   Renders selected form
    */
    public function show_form($form_id, $form_type){

          $data =  UtilityController::get_access(false, $form_id, $form_type);

          if($form_type == 'dl_enhance' || $form_type == 'sage' || $form_type == 'dlenhance'){
              return view('Staff.Forms.Access.form_enhance_sage', $data);
          }
          if($form_type == 'vpn_non_staff' || $form_type == 'wifi_non_staff'){
              return view('Staff.Forms.Access.form_vpn_wifi', $data);
          }
          if($form_type == 'store_received_advice'){
              return view('Staff.Forms.Finance.form_store_received_advice', $data);
          }
          if($form_type == 'stores_credit_note'){
              return view('Staff.Forms.Finance.form_stores_credit_note', $data);
          }
          if($form_type == 'stock_requisition_and_consignment_note'){
              return view('Staff.Forms.Finance.form_stock_requisition_consignment', $data);
          }
          if($form_type == 'stock_requisition_issue_note'){
             return view('Staff.Forms.Finance.form_stock_requisition_issue', $data);
          }
          else{
            //Todo: create a view to display when no valid form was selected
              return "No valid form selected";
          }

    }

    /*
    *   Get ongoing request status
    */
    public function current_request(){

        $request = array();

        if(isset($_GET['form_type'])){
            $form_type = $_GET['form_type'];

            if($form_type == 'sage'){
                $request = ReqSage::where("account_id", Auth::user()->id)->first();
            }
            else{
                $request = ReqDlEnhance::where("account_id", Auth::user()->id)->first();
            }
        }
        if(isset($_GET['form_id'])){
           $form_id = $_GET['form_id'];
           $request = ReqVpnWifi::where("account_id", Auth::user()->id)->where('form_id', $form_id)->first();
        }

        if($request != null){
            $approver_comment = Approval::select('comment')->where('level', $request->status)->where('form_id', $request->form_id)->where('form_request_id', $request->id)->first();
            if($approver_comment != null){
              $request->comment = $approver_comment->comment;
            }
            else{
               $request->comment = null;
            }

        }

        return $request;
    }


    /*
    * Get the form setup status;
    * check if the assigned number of approvers available is equal to required approver levels
    * for the form
    * Disbale form submission if assigned approvers is not equal to the required approver level
    */
    public function get_form_setup_status(){
        if(isset($_GET['form_id'])){
            $form_id = $_GET['form_id'];
            $form = Form::find($form_id);
            $available_approvers = Approvers::where('form_id', $form_id)->get();

            //return $form_id;
            //return count($available_approvers);
            //return $form->level;
            if(count($available_approvers) < $form->level){
               return "setup_incomplete";
            }
            return "setup_complete";
        }

        return "setup_incomplete";
    }


    /*
    * Handles form submission for vpn and wifi
    */
    public function submit_vpn_wifi(Request $request){


      //return $request;
      //  return $request;

        /*
        * validate input fields
        */
        $subject = '';
        $val = $this->val_vpn_wifi($request->all());
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

        /*
        * get existing request if it exists, create new reqiest if not
        */
        $existingR = ReqVpnWifi::where('account_id', Auth::user()->id)->where('form_id', $request->form_id)->first();
        if($existingR != null){$new_request = $existingR;}
        else{$new_request = new ReqVpnWifi;}


        $staff_fullname = $request->staff_first_name . " ". $request->staff_last_name . " ". $request->staff_other_name;
        $requester_fullname = $request->requester_first_name . " ". $request->requester_last_name . " ". $request->requester_other_name;

        $account_id = Auth::user()->id;
        $user = User::find($account_id);
        $user->name = $staff_fullname;

        $new_request->account_id = $account_id;
        $new_request->form_id = $request->form_id;
        $new_request->form_type = $request->form_type;
        $new_request->staff_id = $request->staff_id;
        $new_request->name = $staff_fullname;
        $new_request->staff_designation = $request->staff_designation;
        $new_request->requester_fullname = $requester_fullname;
        $new_request->requester_company = $request->requester_company;
        $new_request->date_of_request = date("D, j M Y - h:ia");
        $new_request->date_of_access = $request->date_of_access;
        $new_request->duration_of_access = $request->duration_of_access;
        $new_request->status = 1;

        //return $new_request;
        //return $new_request->id;
        //get the total number of approvers for the current form
        $form = Form::find($request->form_id);
        $form_approver_count = $form->level;

        $approver = Approvers::where('form_id', $request->form_id)->where('level', 1)->first();

        $next_approval_link = config('app.url');
        $next_approval_link .= "/requests/".$form->title_slug."/";
        $next_approval_link .= $new_request->id."/".$request->staff_id;
        $next_approval_link .= "/?form_id=".$request->form_id."&role=".$approver->role;
        $next_approval_link .= "&approver_level=".$approver->level;


       //return $next_approval_link;

        $new_request->save();
        $user->save();

        $next_approval_link = config('app.url');
        $next_approval_link .= "/requests/".$form->title_slug."/";
        $next_approval_link .= $new_request->id."/".$request->staff_id;
        $next_approval_link .= "/?form_id=".$request->form_id."&role=".$approver->role;
        $next_approval_link .= "&approver_level=".$approver->level;

        //return $new_request;
        //return $next_approval_link;

        $msg = new Reminder($form->title, $next_approval_link, $send_to="OTHERS");
        $msg->subject($form->title . ' ACCESS REQUEST');
        Mail::to(trim($approver->email))->send($msg);

    }

    /*
    * Handles form submission for sage and dl enhance
    */
    public function submit_form_request(Request $request){

        //return $request;
        $errors = array();


        /*
        *  Validate email belongs to phed
        */
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

        /*
        * validate input fields
        */
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

        if($request->form_type == 'dl_enhance'){
            $role = null;
            if(count($request->role) > 0){
                $role = implode(",", $request->role);
            }

            /*
            * get existing request if it exists, create new reqiest if not
            */
            $existingR = ReqDlEnhance::where('account_id', Auth::user()->id)->first();
            if($existingR != null){$new_request = $existingR;}
            else{$new_request = new ReqDlEnhance;}

            $new_request->role = $role;
        }
        if($request->form_type == 'sage'){

            $hr_module = null;
            $finance_module = null;
            if(count($request->hr_module) > 0){
                $hr_module = implode(",", $request->hr_module);
            }
            if(count($request->finance_module) > 0){
                $finance_module = implode(",", $request->finance_module);
            }

            /*
            * get existing request if it exists, create new reqiest if not
            */
            $existingR = ReqSage::where('account_id', Auth::user()->id)->first();
            if($existingR != null){$new_request = $existingR;}
            else{$new_request = new ReqSage;}

            $new_request->hr_module = $hr_module;
            $new_request->finance_module = $finance_module;

        }

        $fullname = $request->first_name . " ". $request->last_name . " ". $request->other_name;

        $account_id = Auth::user()->id;
        $user = User::find($account_id);
        $user->name = $fullname;

        $new_request->account_id = $account_id;
        $new_request->form_id = $request->form_id;
        $new_request->staff_id = $request->staff_id;
        $new_request->name = $fullname;
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
        $new_request->status = 0;


        $form = Form::find($request->form_id);


        $new_request->save();
        $user->save();


        $next_approval_link = config('app.url');
        $next_approval_link .= "/requests/".$form->title_slug."/";
        $next_approval_link .= $new_request->id."/".$request->staff_id;
        $next_approval_link .= "/?form_id=".$request->form_id."&role=HOD";
        $next_approval_link .= "&approver_level=0";

        $msg = new Reminder($form->title, $next_approval_link, $send_to="TO_HOD");
        $msg->subject($form->title . ' ACCESS REQUEST');
        Mail::to(trim($request->hod_email))->send($msg);

    }


    /*
    * Validate form fields
    */
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

    /*
    * Validate vpn and wifi fields
    */

    public function val_vpn_wifi($data){

      return Validator::make($data, [
          'staff_id' => ['required', 'string', 'max:255',],
          'official_email' => ['required', 'string', 'max:255'],
          'staff_first_name' => ['required', 'string'],
          'staff_last_name' => ['required', 'string'],
          'staff_designation' => ['required', 'string'],
          'requester_first_name' => ['required', 'string'],
          'requester_last_name' => ['required', 'string'],
          'requester_company' => ['required', 'string'],
          'date_of_access' => ['required', 'string'],
          'duration_of_access' => ['required', 'string'],
      ]);

    }


    /*
    *   Get requests to be approved
    */
    public function get_all_requests($request_type, $query){

        if($query != "ALL"){

            if($request_type == 'dl_enhance'){
                return ReqDlEnhance::where('status', $query)->orderBy('created_at', 'DESC')->get();
            }
            else{
                return ReqSage::where('status', $query)->orderBy('created_at', 'DESC')->get();
            }
        }

        if($query == "ALL"){
            if($request_type == 'dl_enhance'){
                return ReqDlEnhance::orderBy('created_at', 'DESC')->get();
            }
            else{
                return ReqSage::orderBy('created_at', 'DESC')->get();
            }
        }

    }


    /*
    *
    */
    public function test_request(Request $request){
        return $request;
    }



}
