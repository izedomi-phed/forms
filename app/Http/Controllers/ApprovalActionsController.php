<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppraisalRequest;
use App\Mail\Reminder;
use App\Mail\Declined;
use App\Mail\Finance;;
use App\Employee;
use App\User;
use App\Approval;
use App\Approvers;
use App\ReqDlEnhance;
use App\ReqSage;
use App\ReqVpnWifi;
use App\Role;
use App\Form;
use App\Email;
use App\ReqStoreRecievedAdvice;
use App\ReqStoreRecievedAdviceDet;
use App\ReqStoresCreditNote;
use App\ReqStoresCreditNoteDet;
use App\ReqStockRequisitionConsignment;
use App\ReqStockRequisitionConsignmentDet;
use App\ReqStockRequisitionIssue;
use App\ReqStockRequisitionIssueDet;
use PDF;
use DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


/*
* Handles all backend calls initiated on the staff approval dashboard
*/
class ApprovalActionsController extends Controller
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


    /*
    *  Return all the requests for forms for which the user is the owner
    *  To enabel user see status of all requests for forms user owns
    *  To do: delete this method
    */

    public function get_all_requests_for_owned_forms(){
        if(isset($_GET['email'])){

            $email = trim($_GET['email']);
            $owner = Form::where('owner', $email)->get();

            $requests = array();
            $req = array();

            foreach($owner as $form){
                $req = [];
                if($form->title_slug == "sage"){
                     $req = ReqSage::all();
                }
                if($form->title_slug == "dl_enhance"){
                    $req = ReqDlEnhance::all();
                }
                if($form->title_slug == "vpn_non_staff" || $form->title_slug == 'wifi_non_staff'){
                    $req = ReqVpnWifi::where('form_id', $form->id)->get();
                  //  return $req;
                }
                if($form->title_slug == "store_received_advice"){
                    $req = ReqStoreRecievedAdvice::all();
                }
                if($form->title_slug == "stores_credit_note"){
                    $req = ReqStoresCreditNote::all();
                }
                if($form->title_slug == "stock_requisition_issue_note"){
                    $req = ReqStockRequisitionIssue::all();
                }
                if($form->title_slug == "stock_requisition_and_consignment_note"){
                    $req = ReqStockRequisitionConsignment::all();
                }

                //array_push($requests, $req);

                if(count($req) > 0){
                // array_push($requests, $req);
                  //return $req;
                    foreach($req as $r){
                        // array_push($requests, $r);
                        //the r->status would be 0, when the current request is with the HOD and the HOD is not
                        //on the role table
                        //the r->status would be greater than the form->level when the form has been completed
                        if($r->status != 0){

                             $current_status = $r->status;
                             if($r->status > $form->level){$current_status = $form->level;}

                             $role = Role::where('level', $current_status)->where('form_id', $form->id)->first();  //get the role for the current level
                             $approver = Approvers::where('level', $current_status)->where('form_id', $form->id)->first(); //get the approver for the current approver

                             // if return value is not null, then request is declined
                             $approver_comment = Approval::select('comment')->where('level', $current_status)->where('form_id', $form->id)->where('form_request_id', $r->id)->first();
                             $approver_comment != null ? $r->approval_comment = $approver_comment->comment : $r->approval_comment = null;


                             $r->approval_level = $role->title;
                             $r->approval_name = $approver->name;
                             $r->approval_email = $approver->email;
                             $r->title = $form->title;
                             $r->title_slug = $form->title_slug;
                             $r->required_approver_levels = $form->level;
                             if(property_exists($r, "staff_designation")){
                                 $r->designation = $r->staff_designation;
                             }
                             array_push($requests, $r);
                       }


                    }
                }
            }

            //return $requests;

            return $arr = array(
                "assigned_requests" => $requests,
            );
        }

    }


    /*
    *  Returns all forms to which user was assigned
    *  and all requests requiring user approval
    */
    public function get_all_roles_by_staff($approver_email){


          /*
          * Select all the roles for which the user has assigned roles
          * Then for each role the user is assigned to, get the corresponding form details
          * Note: a user can have more than one role to a particular form
          * e.g For form "DL ENHANCE" a user can have HOD role and AUDIT role
          */

          $roles = DB::table('approvers')
          ->where('email', $approver_email)
          ->join('forms', 'forms.id', '=', 'approvers.form_id')
          ->select('approvers.*', 'forms.level as forms.form_level')
          ->get();

          //return $roles;


          /*
          * Because a user can have dual roles for a form, get distinct form
          * from the 'role' table using 'form_type' column
          */
          $assigned_forms = Approvers::where('email', $approver_email)->select('form_type')->distinct()->get();

          $arr = array(); //wrapper array
          $req = array();  //holds requests per form
          $assigned_requests = array(); //holds all requests


          /*
          * if a user is assigned an approver role to a form, check if
          * user has any pending request(s) on the form
          * if yes, get all the requests from that form requiring his approval.
          * Then push approvals into wrapper array($arr)
          */

          foreach($roles as $role){

                  $req = [];
                  if($role->form_type == "SAGE"){
                      $req = DB::table('req_sages')
                      ->where('status', $role->level)
                      ->join('forms', 'forms.id', '=', 'req_sages.form_id')
                      ->select('req_sages.*', 'forms.title', 'forms.title_slug')
                      ->get();
                  }
                  elseif($role->form_type == "DL ENHANCE"){
                      $req = DB::table('req_dl_enhances')
                      ->where('status', $role->level)
                      ->join('forms', 'forms.id', '=', 'req_dl_enhances.form_id')
                      ->select('req_dl_enhances.*', 'forms.title', 'forms.title_slug')
                      ->get();
                  }
                  elseif($role->form_type == "VPN (NON-STAFF)" || $role->form_type == "WIFI(NON-STAFF)"){
                      $req = DB::table('req_vpn_wifis')
                      ->where('status', $role->level)->where('form_id', $role->form_id)
                      ->join('forms', 'forms.id', '=', 'req_vpn_wifis.form_id')
                      ->select('req_vpn_wifis.*', 'forms.title', 'forms.title_slug')
                      ->get();
                  }
                  elseif($role->form_type == 'STORE RECEIVED ADVICE'){
                      $req = DB::table('req_store_recieved_advices')
                      ->where('status', $role->level)
                      ->join('forms', 'forms.id', '=', 'req_store_recieved_advices.form_id')
                      ->select('req_store_recieved_advices.*', 'forms.title', 'forms.title_slug')
                      ->get();
                  }
                  elseif($role->form_type == 'STORES CREDIT NOTE'){
                      $req = DB::table('req_stores_credit_notes')
                      ->where('status', $role->level)
                      ->join('forms', 'forms.id', '=', 'req_stores_credit_notes.form_id')
                      ->select('req_stores_credit_notes.*', 'forms.title', 'forms.title_slug')
                      ->get();
                  }
                  elseif($role->form_type == 'STOCK REQUISITION AND CONSIGNMENT NOTE'){
                      $req = DB::table('req_stock_requisition_consignments')
                      ->where('status', $role->level)
                      ->join('forms', 'forms.id', '=', 'req_stock_requisition_consignments.form_id')
                      ->select('req_stock_requisition_consignments.*', 'forms.title', 'forms.title_slug')
                      ->get();
                  }
                  elseif($role->form_type == 'STOCK REQUISITION ISSUE NOTE'){
                      $req = DB::table('req_stock_requisition_issues')
                      ->where('status', $role->level)
                      ->join('forms', 'forms.id', '=', 'req_stock_requisition_issues.form_id')
                      ->select('req_stock_requisition_issues.*', 'forms.title', 'forms.title_slug')
                      ->get();
                  }
                  if(count($req) > 0){
                        foreach($req as $r){
                             //check if approval action has occurred for request
                             //add to wrapper array if no approval action is found
                              $hasBeenApproved = Approval::select('comment')->where('level', $r->status)->where('form_id', $role->form_id)->where('form_request_id', $r->id)->first();
                              if($hasBeenApproved == null){ // no approval action has occurred
                                  $approval = $role->role;

                                  if(property_exists($r, 'staff_designation')){
                                        $r->designation = $r->staff_designation;
                                  }
                                  $r->approval_level = $approval;
                                  array_push($assigned_requests, $r);
                              }
                        }
                  }
              }

          /*
          * Check if a user has a pending request as HOD role
          * If yes, get the request
          * Note: this check is only done on the sage and dl enhance form Because
          * they require the email of the HOD to be entered manually
          */

          //sage HOd pending request check;
          $req = [];
          $req = ReqSage::where('status', 0)
          ->where('hod_email', Auth::user()->email)
          ->get();
          if(count($req) > 0){
              foreach($req as $r){
                  //check if approval action has occurred for request
                  //add to wrapper array if no approval action is found
                   $hasBeenApproved = Approval::select('comment')->where('level', $r->status)->where('form_id', $role->form_id)->where('form_request_id', $r->id)->first();
                   if($hasBeenApproved == null){ // no approval action has occurred
                       $r->approval_level = "HOD";
                       array_push($assigned_requests, $r);
                   }

              }
          }
          //dl enhance HOD pending request check
          $req = [];
          $req = ReqDlEnhance::where('status', 0)
          ->where('hod_email', Auth::user()->email)
          ->get();
          if(count($req) > 0){
              foreach($req as $r){
                //check if approval action has occurred for request
                //add to wrapper array if no approval action is found
                 $hasBeenApproved = Approval::select('comment')->where('level', $r->status)->where('form_id', $role->form_id)->where('form_request_id', $r->id)->first();
                 if($hasBeenApproved == null){ // no approval action has occurred
                     $r->approval_level = "HOD";
                     array_push($assigned_requests, $r);
                 }
              }
          }

          //return $req;
          return $arr = array(
              "assigned_requests" => $assigned_requests,
              "assigned_forms" => $assigned_forms
          );
    }

    /*
    *   Returns all requests available to a staff
    */
    public function get_all_requests_to_staff($form_type, $request_status){

        if($form_type == "dl_enhance"){
            return ReqDlEnhance::where('status', $request_status)->get();
        }
        if($form_type == "sage"){
            return ReqSage::where('status', $request_status)->get();
        }
        if($form_type == 'vpn' || $form_type == 'wifi'){
            return ReqVpnWifi::where('status', $request_status)->get();
        }

    }


    /*
    *   Renders all Roles
    */
    public function assigned_form_roles(){
        //$email = trim($_GET['email']);
        //return Form::where('owner', $email)->get();

        $assigned_role = array();
        $owned_form = array();

        /*
        * For each form owned by a user, get all roles assigned to that form
        */
        if(isset($_GET['email'])){
            $email = trim($_GET['email']);
            $owner = Form::where('owner', $email)->get();
            if(count($owner) > 0){
                foreach($owner as $own){
                    $form_roles = Role::where('form_id', $own->id)->get();
                    foreach($form_roles as $role){
                        array_push($assigned_role, $role);
                    }

                    array_push($owned_form, $own);
                }
            }
        }

        $data = array(
            'assigned_role' => $assigned_role,
            'owned_form' => $owned_form
        );
        return $data;
    }


    /*
    *   Delect an approval role
    */
    public function create_role(Request $request){
        //return $request;
        if($request->id != null){
            $role = Role::find($request->id);
            $role->form_id = $request->form_id;
        }
        else{
            $role = new Role;

            //get form id from form type
            $form = Form::where('title', $request->form_type)->first();
            $role->form_id = $form->id;
        }

        $role->name = $request->name;
        $role->email = $request->email;
        $role->role = strtolower($request->role);
        $role->form_type = $request->form_type;

        $role->status_query = "TO_".$request->role;

        //return $role;
        $role->save();
    }


    /*
    *   Delect an approval role
    */
    public function delete_role(Request $request){
        $id = $request->id;
        $role = Role::find($id);
        $role->delete();
    }

    /*
    *   Cancel Staff Request
    */
    public function reset_request(Request $request){

          $req = $this->get_user_request($request->request_id, $request->form_slug);
          $allApprovals = Approval::where('form_request_id', $req->id)->where('form_id', $req->form_id)->get(); //this criterion is not enough to find approval entry
          $req->delete();
          if(count($allApprovals) > 0){
              foreach($allApprovals as $approval){
                 $approval->delete();
              }

          }
          return "deleted successfully";

    }

    public function send_reminder(Request $request){

        //return $request;

        $request_id = $request->request_id;
        $form_title_slug = $request->form_slug;

        //determine and get the request details
        $req = $this->get_user_request($request_id, $form_title_slug);

        //get the details of the approver whose approver is required
        $required_approver = Approvers::where('form_id', $req->form_id)->where('level', $req->status)->first();

        //send a link to the approver via mail
        $next_approval_link = config('app.url');
        $next_approval_link .= "/requests/".$form_title_slug."/";
        $next_approval_link .= $request_id."/".$req->staff_id;
        $next_approval_link .= "/?form_id=".$req->form_id."&role=".$required_approver->role;
        $next_approval_link .= "&approver_level=".$required_approver->level;

       // return $next_approval_link;

        $msg = new Reminder($required_approver->form_type, $next_approval_link, $send_to="OTHERS");
        $msg->subject($required_approver->form_type . ' ACCESS REQUEST');
        Mail::to(trim($required_approver->email))->send($msg);

        return "Reminder sent successfully";

    }

    public function get_user_request($request_id, $form_slug){
        if($form_slug == "sage"){
            return ReqSage::find($request_id);
        }
        if($form_slug == "dl_enhance"){
            return ReqDlEnhance::find($request_id);
        }
        if($form_slug == "vpn_non_staff" || $form_slug == "wifi_non_staff"){
            return ReqVpnWifi::find($request_id);
        }
        if($form_slug == 'store_received_advice'){
            $request = ReqStoreRecievedAdvice::find($request_id);
        }
        if($form_slug == 'stores_credit_note'){
           $request = ReqStoresCreditNote::find($request_id);
        }
        if($form_slug == 'stock_requisition_and_consignment_note'){
          $request = ReqStockRequisitionConsignment::find($request_id);
        }
        if($form_slug == 'stock_requisition_issue_note'){
            $request = ReqStockRequisitionIssue::find($request_id);
        }

        return $request;
    }


}
