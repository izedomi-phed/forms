<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppraisalRequest;
use App\Mail\Reminder;
use App\Mail\Declined;
use App\Mail\Finance;
use App\Employee;
use App\User;
use App\Approval;
use App\Approvers;
use App\ReqDlEnhance;
use App\ReqSage;
use App\ReqVpnWifi;
use App\Role;
use App\Form;
use App\FormCategory;
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


class AccessApprovalController extends Controller
{
    /*ReqStoreRecievedAdvice
    *  This methods
    */
    public function dl_enhance_approval($request_type, $request_id, $staff_id){
        //return $request_id;
        //return $request_type;
        if(isset($_GET['form_id'])){

            $role = trim($_GET['role']);
            $form_id = trim($_GET['form_id']);
            $current_approver_level = trim($_GET['approver_level']);
            $isFinalApprover = 'false';
            $currentApproverHasCompletedAction = 'false';

            $request = $this->get_current_request($request_type, $request_id, "view");

            $name = explode(" ", $request->name);

            //get details of user that initiated request
            $user = User::find($request->account_id);

            $approver = Approval::where('level', $current_approver_level)
            ->where('form_id', $form_id)
            ->where('form_request_id', $request->id)
            ->first();

            if($approver != null){
               $currentApproverHasCompletedAction = 'true';
            }

            $firstname = $name[0];
            $lastname = "";
            $otherNames = null;
            $staffId = $user->staff_id;
            $email = $user->email;
            if(count($name) > 1){
                $lastname = $name[1];
            }
            if(count($name) > 2){
                $otherNames = $name[2];
            }

            //return $firstname;

            $form = Form::find($form_id);
            $form_category = FormCategory::find($form->cat_id);

            if($request->status == $form->level){
               $isFinalApprover = 'true';
            }

            $request->email = $email;
            $request->firstname = $firstname;
            $request->lastname = $lastname;
            $request->otherNames = $otherNames;
            $request->form_category = $form_category->title;

            $request->form_id = trim($_GET['form_id']);
            $request->request_type = $request_type;

            //return $request;

            $data = array(
                'request' => $request,
                'role' => $role,
                'isFinalApprover' => $isFinalApprover,
                'actionCompleted' => $currentApproverHasCompletedAction
            );



            if($request_type == 'dl_enhance' || $request_type == 'sage'){
              return view('ApprovalPageAccess.enhance_sage')->with($data);
            }
            if($request_type == 'vpn_non_staff' || $request_type == 'wifi_non_staff'){
              return view('ApprovalPageAccess.vpn_wifi')->with($data);
            }
            if($request_type == 'store_received_advice'){
              return view('ApprovalPageFinance.store_received_advice')->with($data);
            }
            if($request_type == 'stores_credit_note'){
              return view('ApprovalPageFinance.stores_credit_note')->with($data);
            }
            if($request_type == 'stock_requisition_and_consignment_note'){
              return view('ApprovalPageFinance.stock_consignment')->with($data);
            }
            if($request_type == 'stock_requisition_issue_note'){
              return view('ApprovalPageFinance.stock_issue')->with($data);
            }



        }

        return view("error");

    }

    public function bulk_approval(Request $request){
        //return $request[0]['id'];

        //return $request->all();
        $comment = null;
        foreach($request->all() as $req) {
            $this->execute_approval($req['id'], $req['request_type'], $req['approval_status'], $comment );
        }

    }

    public function requests_approval_action(Request $request){
       // "stock_requisition_and_consignment_note";

        $request_id = $request->request_id;
        $request_type = $request->request_type;
        $approval_status = $request->approval_status;
        $comment = $request->decline_comment;

        //return $approval_status;
        //$emails = Email::find(1);

        return $this->execute_approval($request_id, $request_type, $approval_status, $comment);
        //return $emailArr;
    }

    public function execute_approval($request_id, $request_type, $approval_status, $comment){

          $request = $this->get_current_request($request_type, $request_id, "process");

          $form_id = $request->form_id;  //set the form id
          $current_approver_name_role = '';



          $current_request_status = $request->status; //current request status as at the time the  approver action was taken
          $next_request_status = $request->status + 1; //new request status after approver action is completed processed

          //return $request->status;

           //Get staff that who initiated form request
          $user = User::find($request->account_id);

          // Get all previous approvals and extract their emails into an array
          // Used for email purposes during request decline and successful creation
          $emailArr = [$user->email];

          $approvals = $this->get_current_approvals($request_id, $form_id, $request_type);

          if(count($approvals) > 0){
              foreach ($approvals as $approval) {
                  array_push($emailArr, $approval->email);
              }
          }

          //get the total number of approvers for the current form and form category

          $form = DB::table('forms')
          ->where('forms.id', $request->form_id)
          ->join('form_categories', 'form_categories.id', '=', 'forms.cat_id')
          ->select('forms.*', 'form_categories.title as cat_title')
          ->first();

           $form_approver_count = $form->level;
           $form_category = $form->cat_title;

          // check if current approver already exists
          $exists = Approval::where('form_id', $form_id)
          ->where('form_request_id', $request_id)
          ->where('level', $current_request_status)
          ->first();

          if($exists == null || $exists == ''){$app = new Approval;}
          else{ $app = $exists; }


          if($request->status == 0){
                $app->form_request_id = $request_id;
                $app->form_id = $form_id;
                $app->name = $request->hod_name;
                $app->email =  $request->hod_email;
                $app->level =  0;
                $app->action_date = date("D, j M Y - h:ia");
                $app->comment = $comment;
                $current_approver_name_role = "HOD " . " (". strtoupper($request->hod_name) .")";
          }
          if($request->status > 0 && $request->status <= $form_approver_count){
                $current_approver = Approvers::where('form_id', $form_id)->where('level', $current_request_status)->first();
                $app->form_request_id = $request_id;
                $app->form_id = $form_id;
                $app->name = $current_approver->name;
                $app->email =  $current_approver->email;
                $app->level =  $request->status;
                $app->action_date = date("D, j M Y - h:ia");
                $app->comment = $comment;
                $current_approver_name_role = strtoupper($current_approver->role ." (". $current_approver->name .")");
          }

          if($request->status < $form_approver_count){
              $request->status = $next_request_status;
          }

        //return $request;
        //return $request->status;

        //return $app;

        $app->save(); //save to database approver details

        if($form_category == "FINANCE"){

            $isFinal = false;

            if($approval_status == 'ACCEPTED'){
                //return "yes";
                 //saves request only when approver action is "ACCEPTED"

                $next_approval_link = null;
                if($current_request_status < $form_approver_count){
                   // get next person in line for approver
                   // next approver is only available if more form approvers exitst
                   $next_approver = Approvers::where('form_id', $form_id)->where('level', $next_request_status)->first();
                   $next_approval_link = config('app.url');
                   $next_approval_link .= "/requests/".$form->title_slug."/";
                   $next_approval_link .= $request->id."/".$request->staff_id;
                   $next_approval_link .= "/?form_id=".$form_id."&role=".$next_approver->role;
                   $next_approval_link .= "&approver_level=".$next_approver->level;
                }
                if($current_request_status == $form_approver_count){
                   $request->status = $current_request_status + 1;
                }

                $request->save();


                //return $next_approval_link;
                if($current_request_status == $form_approver_count){
                    $isFinal = true;
                    foreach ($emailArr as $e) {
                        $msg = new Finance(
                            $form->title,
                            $approval_status,
                            $comment,
                            $request->staff_id,
                            $request->name,
                            $current_approver_name_role,
                            $next_approval_link,
                            $isFinal
                        );
                        $msg->subject($form->title);
                        Mail::to($e)->send($msg);
                    }
                }
                else{
                    $msg = new Finance(
                        $form->title,
                        $approval_status,
                        $comment,
                        $request->staff_id,
                        $request->name,
                        $current_approver_name_role,
                        $next_approval_link,
                        $isFinal
                    );
                    $msg->subject($form->title);
                    Mail::to($next_approver->email)->send($msg);
                }

            }

            if($approval_status == "DECLINED"){
                foreach($emailArr as $e){
                    $msg = new Finance(
                        $form->title,
                        $approval_status,
                        $comment,
                        $request->staff_id,
                        $request->name,
                        $current_approver_name_role,
                        $next_approval_link,
                        $isFinal
                    );
                    $msg->subject($form->title);
                    Mail::to($e)->send($msg);
                }
            }
        }

        if($form_category == "ACCESS"){

              $subject = "Access Request";
              if($approval_status == "ACCEPTED"){

                    //save new form request status

                      // the last persons
                      // send email to all approvers
                      if($current_request_status == $form_approver_count){
                         $request->status = $current_request_status + 1;
                         $request->save();
                         foreach($emailArr as $e){
                               $msg = new Declined($form->title, $approval_status, $comment, $request->staff_id, $request->name, $current_approver_name_role);
                               $msg->subject($form->title . " " . $subject);
                               Mail::to($e)->send($msg);
                          }

                      }

                      if($current_request_status < $form_approver_count){
                              $request->save();
                            //note: this will work for forms in the "ACCESS FORMS"
                            $send_to = null;
                            if($form_approver_count - $current_request_status == 1){
                              $send_to = "TO_CREATE";
                            }
                            else{
                               $send_to = "OTHERS";
                            }

                            $next_approver = Approvers::where('form_id', $form_id)->where('level', $next_request_status)->first(); // get next person in line for approver
                            $next_approval_link = config('app.url');
                            $next_approval_link .= "/requests/".$form->title_slug."/";
                            $next_approval_link .= $request->id."/".$request->staff_id;
                            $next_approval_link .= "/?form_id=".$form_id."&role=".$next_approver->role;
                            $next_approval_link .= "&approver_level=".$next_approver->level;

                            //return $next_approval_link;

                            $msg = new Reminder($form->title, $next_approval_link, $send_to);
                            $msg->subject($form->title . ' ACCESS REQUEST');
                            Mail::to($next_approver->email)->send($msg);
                      }

              }

              //if request action is DECLINED, get all the previous approvers DETAILS
              //send email to all of them
              if($approval_status == "DECLINED"){

                    if($request_type != 'vpn_non_staff' && $request_type != 'wifi_non_staff'){
                      array_push($emailArr, $request->hod_email);
                    }
                    foreach($emailArr as $e){
                        $msg = new Declined($form->title, $approval_status, $comment, $request->staff_id, $request->name, $current_approver_name_role);
                        $msg->subject($subject);
                        Mail::to($e)->send($msg);
                    }
              }
        }

        return "success with approval";

        // the part I cut out should be placed here!

    }

    public function get_current_request($request_type, $request_id, $function_call){
        $request = null;
        //dl enhance
        if($request_type == 'dl_enhance'){
            $request = ReqDlEnhance::find($request_id);
        }
        //vpn and wifi
        if($request_type == 'vpn_non_staff' || $request_type == 'wifi_non_staff'){
            $request = ReqVpnWifi::find($request_id);
        }
        //sage
        if($request_type == 'sage'){
            $request = ReqSage::find($request_id);
        }
        //store received advice
        if($request_type == 'store_received_advice'){
            $request = ReqStoreRecievedAdvice::find($request_id);
            $stock = ReqStoreRecievedAdviceDet::where('request_id', $request_id)->get();
            if($function_call == "view"){$request->stock = $stock;}
        }

        //stores credit note
        if($request_type == 'stores_credit_note'){
            $request = ReqStoresCreditNote::find($request_id);
            $stock = ReqStoresCreditNoteDet::where('request_id', $request_id)->get();
            if($function_call == "view"){$request->stock = $stock;}
        }

        //stock requisition and consignment note
        if($request_type == 'stock_requisition_and_consignment_note'){
            $request = ReqStockRequisitionConsignment::find($request_id);
            $stock = ReqStockRequisitionConsignmentDet::where('request_id', $request_id)->get();
            if($function_call == "view"){$request->stock = $stock;}
        }

        //stock requisition issue
        if($request_type == 'stock_requisition_issue_note'){
            $request = ReqStockRequisitionIssue::find($request_id);
            $stock = ReqStockRequisitionIssueDet::where('request_id', $request_id)->get();
            if($function_call == "view"){$request->stock = $stock;}
        }

        return $request;

    }

    public function get_approval_detail($status, $form_id, $arg){
        $role = Role::where("status_query", $status)->where("form_id", $form_id)->first();
        if($role != null){
            if($arg == 'email'){
                return $role->email;
            }
            if($arg == 'name'){
                return $role->name;
            }
            if($arg == 'role'){
                return $role->role;
            }
        }
        else{
            return null;
        }
    }

    public function get_current_approvals($request_id, $form_id, $request_type = null){

        return Approval::where("form_request_id", $request_id)->where("form_id", $form_id)->get();

        /*$req = DB::table('approvals')
        ->where("form_request_id", $request_id)->where("form_id", $form_id)
        ->join('roles', 'forms.id', '=', 'req_vpn_wifis.form_id')
        ->select('req_vpn_wifis.*', 'forms.title', 'forms.slug')
        ->get();
        */
    }


}
