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


use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class RootController extends Controller
{


    /*
    * Renders Dashboard: Root dashboard
    */
    public function root_dashboard(){
        $data =  UtilityController::get_access(true);
        return view('Root.dashboard')->with($data);
    }

    /*
    * Add new form category
    */
    public function add_update_category(Request $request){

        if($request->is_new){
            $title = strtoupper($request->title);
            $cat = new FormCategory();
            $cat->title = $title;
            $cat->save();

            return "created";
        }
        else{
            $title = strtoupper($request->title);
            $cat = FormCategory::find($request->id);
            $cat->title = $title;
            $cat->save();

            return "updated";
        }

    }


    /*
    * Fetch categories
    */
    public function fetch_categories(){
        return FormCategory::orderBy('created_at', 'DESC')->get();
    }

    /*
    *  Delete a category
    */
    public function delete_category(Request $request){
        $cat = FormCategory::find($request->id);
        $cat->delete();

        return "deleted";
    }

    /*
    * Add new form
    */
    public function add_update_form(Request $request){

        $char = ['(', ')', '-', '__','/'];

        //return $request;
        $title = strtoupper($request->title);
        $owner = $request->owner;
        $level = $request->level;
        $category_id = $request->category_id;


        $title_parts = explode(" ", $title);
        $formatted_title  = strtolower(implode('_', $title_parts));

        $array = str_split($formatted_title);
        $arr = $array;

        //check if array contains certain characters ("(", ")", "-", "__") and replace with "_"
        foreach($char as $ch){
            if(in_array($ch, $array)){
                $index = array_search($ch, $array);
                $arr[$index] = "_";
            }
        }

        //if last item of resulting array is "_", remove it from the array
        if($arr[count($arr) - 1] == "_"){
            array_pop($arr);
        }

        //convert array to a string
        $ft = strtolower(implode('', $arr));

        //replace '__' with '_'
        $title_slug = str_replace('__', '_', $ft);

        if($request->is_new){
            $form = new Form();
            $status = "form created";
        }
        else{
            $form = Form::find($request->id);
            $status = "form updated";
        }

        $form->title = $title;
        $form->title_slug = $title_slug;
        $form->owner = $owner;
        $form->level = $level;
        $form->cat_id = $category_id;
        $form->save();

        return $status;
    }

    /*
    * Fetch form
    */
    public function fetch_forms(){
        //return Form::orderBy('created_at', 'DESC')->get();

        if(isset($_GET['is_root_user'])){
            return $req = DB::table('forms')
            ->join('form_categories', 'form_categories.id', '=', 'forms.cat_id')
            ->select('forms.*', 'form_categories.title as cat_title')
            ->get();
        }
        else{
            return $req = DB::table('forms')
            ->where('owner', Auth::user()->email)
            ->join('form_categories', 'form_categories.id', '=', 'forms.cat_id')
            ->select('forms.*', 'form_categories.title as cat_title')
            ->get();
        }

    }

    /*
    *  Delete a category
    */
    public function delete_form(Request $request){

        $cat = Form::find($request->id);
        $cat->delete();

        return "deleted";
    }




}
