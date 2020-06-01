<?php

namespace App\Http\Controllers;


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
use PDF;
use DB;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UtilityController extends Controller
{
    //

    
    /*
    * Helper method to get user access level and status
    */
    public static function get_access($has_approval_rights, $form_id = null, $form_type = null){

        $name = explode(" ", trim(Auth::user()->name));
        $firstname = $name[0];

        $lastname = "";
        $otherNames = null;
        if(count($name) > 1){$lastname = $name[1];}
        if(count($name) > 2){$otherNames = $name[2];}

        $isAdmin = false;
        $role = null;

        //$role = Role::where('email', Auth::user()->email)->first();
        $owner = Form::where('owner', Auth::user()->email)->first();
        $forms = Form::all();


        //$form_cat = FormCategory::all();
        $forms = DB::table('forms')
        ->join('form_categories', 'forms.cat_id', '=', 'form_categories.id')
        ->select('forms.*', 'form_categories.title as cat_title')
        ->get();


        if($role != null || $owner != null){
            $isAdmin = true;
        }

        return array(
            'email' => Auth::user()->email,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'othername' => $otherNames,
            'staffId' => Auth::user()->staff_id,
            'requestType' => $form_type,
            'formId' => $form_id,
            'forms' => $forms,
            'isAdmin' => $isAdmin
        );


        /*if($has_approval_rights){
            return array(
                "admin_name" => Auth::user()->name,
                "admin_email" => Auth::user()->email,
                "forms" => $forms,
            );
        }else{
            return array(
                'email' => Auth::user()->email,
                'lastname' => $lastname,
                'firstname' => $firstname,
                'othername' => $otherNames,
                'staffId' => Auth::user()->staff_id,
                'requestType' => $form_type,
                'formId' => $form_id,
                'forms' => $forms
            );
        }
        */


    }

}
