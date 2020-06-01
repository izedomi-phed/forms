<?php

namespace App\Http\Controllers;
use App\Http\Controllers\UtilityController;




//use UtilityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\AppraisalRequest;
use App\Mail\Reminder;
use App\ReqDlEnhance;
use App\ReqSage;
use App\ReqVpnWifi;
use App\User;
use App\OverallSummary;
use App\Ibc;
use App\Role;
use App\Form;
use App\FormCategory;
use App\Approvers;
use PDF;
use DB;




class SettingController extends Controller
{
    
    /*
    * Add/Update new form roles
    */
    public function add_update_role(Request $request){

        //return $request;
        $title = $request->title;
        $level = $request->level;
        $form_type = $request->form_type;
        $form_id = $request->form_id;

        if($request->is_new){
            $role = new Role();
            $status = "created";
        }
        else{
           
            $role = Role::find($request->id);
            $status = "updated";
        }

        $role->title = $title;
        $role->level = $level;
        $role->form_type = $form_type;
        $role->form_id = $form_id;
        $role->save();

        return $status;
        
    }


    /*
    * Fetch the list of available roles for forms currently logged in admin is the owner
    */
    public function fetch_roles(){
        //return "yes";
        //return Form::orderBy('created_at', 'DESC')->get();

        return $req = DB::table('forms')
        ->where('owner', Auth::user()->email)
        ->join('roles', 'roles.form_id', '=', 'forms.id')
        ->select('roles.*')
        ->get();
         
    }

    public function delete_roles(Request $request){
        $cat = Role::find($request->id);
        $cat->delete();

        return "deleted";
    }

    /*
    * Add/Update new form approvers
    */
    public function add_update_approver(Request $request){

        //return $request;
        $role = $request->role;
        $level = $request->level;
        $form_type = $request->form_type;
        $form_id = $request->form_id;
        $name = $request->name;
        $email = $request->email;

        if($request->is_new){
            $approver = new Approvers();
            $status = "created";
        }
        else{      
            $approver = Approvers::find($request->id);
            $status = "updated";
        }

        $approver->role = $role;
        $approver->level = $level;
        $approver->form_type = $form_type;
        $approver->form_id = $form_id;
        $approver->name = $name;
        $approver->email = $email;
        $approver->save();

        return $status;       
    }

    
    /*
    * Fetch the list of available roles for forms currently logged in admin is the owner
    */
    public function fetch_approvers(){
        //return "yes";
        //return Form::orderBy('created_at', 'DESC')->get();

        return $req = DB::table('forms')
        ->where('owner', Auth::user()->email)
        ->join('approvers', 'approvers.form_id', '=', 'forms.id')
        ->select('approvers.*')
        ->get();
         
    }

    public function delete_approver(Request $request){
         $cat = Approvers::find($request->id);
         $cat->delete();
 
         return "deleted";
     }

}
