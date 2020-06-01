<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\ReqStoreRecievedAdvice;
use App\ReqStoreRecievedAdviceDet;
use App\ReqStoresCreditNote;
use App\ReqStoresCreditNoteDet;
use App\ReqStockRequisitionConsignment;
use App\ReqStockRequisitionConsignmentDet;
use App\ReqStockRequisitionIssue;
use App\ReqStockRequisitionIssueDet;
use App\Mail\Reminder;
use App\User;
use App\ReqDlEnhance;
use App\ReqSage;
use App\ReqVpnWifi;
use App\Role;
use App\Approval;
use App\Approvers;
use App\Form;
use PDF;
use DB;

class FinanceRequestController extends Controller
{
    //
    public function finance_form_request(Request $request){
        //return $request;

        if($request->form_type == 'store_received_advice'){
           return $this->submit_store_recieved_advised_form($request);
        }
        if($request->form_type == 'stores_credit_note'){
            return $this->submit_stores_credit_advised_form($request);
        }
        if($request->form_type == 'stock_requisition_and_consignment_note'){
            return $this->submit_stock_requisition_consignment_note($request);
        }
        if($request->form_type == 'stock_requisition_issue_note'){
            return $this->submit_stock_requisition_issue_note($request);
        }

    }

    public function submit_store_recieved_advised_form($request){

        $store = new ReqStoreRecievedAdvice;
        $store->account_id = Auth::user()->id;
        $store->form_id = $request->form_id;
        $store->staff_id = $request->staff_id;
        $store->name = $request->name;
        $store->cost_center = $request->cost_center;
        $store->ref_to_others = $request->ref_to_others;
        $store->local_purchase_no = $request->local_purchase_no;
        $store->indent_no = $request->indent_no;
        $store->consignment_note_no = $request->consignment_note_no;
        $store->item_no = $request->item_no;
        $store->supplier_name = $request->supplier_name;
        $store->supplier_invoice_no = $request->supplier_invoice_no;
        $store->case_markings = $request->case_markings;
        $store->date_recieved = $request->date_recieved;
        $store->invoice_value = $request->invoice_value;
        $store->inward_infreight = $request->inward_infreight;
        $store->custom_duty = $request->custom_duty;
        $store->vat = $request->vat;
        $store->total = $request->total;
        $store->status = 1;
        $store->remarks = $request->remarks;

        $store->save(); //remember to uncomment


        foreach($request->stock as $s){
            $stock = new ReqStoreRecievedAdviceDet;
            $stock->request_id = $store->id;
            $stock->vocab = $s['vocab'];
            $stock->desc = $s['desc'];
            $stock->qty = $s['qty'];
            $stock->price = $s['price'];
            $stock->value = $s['value'];
            $stock->save();
        }

        return $this->get_next_approval_link($request, $store);

    }

    public function submit_stores_credit_advised_form($request){
        $store = new ReqStoresCreditNote;
        $store->account_id = Auth::user()->id;
        $store->form_id = $request->form_id;
        $store->staff_id = $request->staff_id;
        $store->name = $request->name;

        $store->account_code = $request->account_code;
        $store->capital_exp_app_no = $request->capital_exp_app_no;
        $store->rechargeable_works_order_no = $request->rechargeable_works_order_no;
        $store->job_returned = $request->job_returned;
        $store->status = 1;
        $store->total = $request->total;
        $store->save();

        foreach($request->stock as $s){
            $stock = new ReqStoresCreditNoteDet;;
            $stock->request_id = $store->id;
            $stock->vocab = $s['vocab'];
            $stock->desc = $s['desc'];
            $stock->unit = $s['unit'];
            $stock->qty = $s['qty'];
            $stock->price = $s['price'];
            $stock->value = $s['value'];
            $stock->save();
        }

      //  return "yes";

       return $this->get_next_approval_link($request, $store);

    }

    public function submit_stock_requisition_consignment_note($request){

        //return $request->stock;
        //return $request->stock[0]['qty_issued']['figure'];

        $store = new ReqStockRequisitionConsignment;
        $store->account_id = Auth::user()->id;
        $store->form_id = $request->form_id;
        $store->staff_id = $request->staff_id;
        $store->name = $request->name;
        $store->req_store = $request->req_store;
        $store->issue_store = $request->issue_store;
        $store->location = $request->location;
        $store->driver_name = $request->driver_name;
        $store->lorry_no = $request->lorry_no;
        $store->status = 1;
        $store->total = $request->total;

        $store->save();


        foreach($request->stock as $s){
            $stock = new ReqStockRequisitionConsignmentDet;
            $stock->request_id = $store->id;
            $stock->stock_code = $s['stock_code'];
            $stock->desc = $s['desc'];
            $stock->unit_of_issue = $s['unit_of_issue'];
            $stock->qty_req_figure = $s['qty_req']['figure'];
            $stock->qty_req_words = $s['qty_req']['words'];
            $stock->qty_issued_figure = $s['qty_issued']['figure'];
            $stock->qty_issued_words = $s['qty_issued']['words'];
            $stock->unit_price = $s['unit_price'];
            $stock->value = $s['value'];
            $stock->save();
        }

        return $this->get_next_approval_link($request, $store);

    }

    public function submit_stock_requisition_issue_note($request){

        $store = new ReqStockRequisitionIssue;
        $store->account_id = Auth::user()->id;
        $store->form_id = $request->form_id;
        $store->staff_id = $request->staff_id;
        $store->name = $request->name;
        $store->requested_by = $request->requested_by;
        $store->cost_center = $request->cost_center;
        $store->destination = $request->destination;
        $store->project_type = $request->project_type;
        $store->department = $request->department;
        $store->budget_no = $request->budget_no;
        $store->requisition_purpose = $request->requisition_purpose;
        $store->status = 1;
        $store->total = $request->total;

        $store->save();

        foreach($request->stock as $s){
            $stock = new ReqStockRequisitionIssueDet;
            $stock->request_id = $store->id;
            $stock->code_no = $s['code_no'];
            $stock->desc = $s['desc'];
            $stock->qty_req = $s['qty_req'];
            $stock->qty_issued = $s['qty_issued'];
            $stock->unit_price = $s['price'];
            $stock->total_value = $s['value'];
            $stock->save();
        }

          return $this->get_next_approval_link($request, $store);

    }

    public function get_next_approval_link($request, $store){

          $form = Form::find($request->form_id);
          $approver = Approvers::where('form_id', $request->form_id)->where('level', $store->status)->first();

          $next_approval_link = config('app.url');
          $next_approval_link .= "/requests/".$form->title_slug."/";
          $next_approval_link .= $store->id."/".$request->staff_id;
          $next_approval_link .= "/?form_id=".$request->form_id."&role=".$approver->role;
          $next_approval_link .= "&approver_level=".$approver->level;

          //return $next_approval_link;

          $msg = new Reminder($form->title, $next_approval_link, $send_to="OTHERS");
          $msg->subject($form->title);
          Mail::to(trim($approver->email))->send($msg);

          return "success";
    }
}
