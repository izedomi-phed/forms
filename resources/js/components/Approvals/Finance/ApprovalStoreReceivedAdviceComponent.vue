<template>

<div>

    <div class="row justify-content-center" v-show="!componentLoaded">
        <div class="col-md-6 text-center" style="margin-top:10%">
            <rotate-square-5 :size="'120px'"></rotate-square-5>
        </div>
    </div>

    <section class="mh-fullscreen bg-img p-20 bg-primary" v-show="componentLoaded">
        <canvas class="constellation"></canvas>

            <div class="row justify-content-center" v-if="request">
                <div class="col-md-8 px-8">
                    <div class="card card-shadowed p-10 mb-0 elevation-4" >
                    <div class="text-center">
                        <a href='.'><img :src="'../../../images/PHEDLogo.jpg'" alt="phed logo"  style="width:100px;height:100px"/></a>
                    </div>

                    <h5 class="text-uppercase text-center">{{returnTitle()}}</h5>

                    <!-- form label -->
                    <h6 class="text-center text-danger" v-if="request.status == 0">HOD APPROVAL FORM</h6>
                    <h6 class="text-center text-danger" v-else>{{role.toUpperCase()}} APPROVAL FORM</h6>


                    <template v-if="valErrors.length > 0">
                        <ul>
                            <li class="text-danger" v-for="(err, index) in valErrors" :key='index'><strong>{{ err }}</strong></li>
                        </ul>
                    </template>

                    <div class="row text-dark justify-content-center px-20 mb-2">
                        <div class="col-md-12"><h3 class="text-primary text-center"> STAFF DETAILS</h3> </div>
                        <div class="col-md-4">
                            <small>FIRST NAME</small><br/><strong>{{request.firstname}}</strong>
                        </div>
                        <div class="col-md-4">
                           <small>LAST NAME</small><br/><strong>{{request.lastname}}</strong>
                        </div>
                          <div class="col-md-4">
                           <small>OTHER NAMES</small><br/><strong>{{request.otherNames}}</strong>
                        </div>

                    </div>
                     <div class="row text-dark justify-content-center px-20 mb-2">
                        <div class="col-md-4">
                            <small>STAFF EMAIL</small><br/><strong>{{request.email}}</strong>
                        </div>
                        <div class="col-md-4">
                           <small>STAFF ID</small><br/><strong>{{request.staff_id}}</strong>
                        </div>
                        <div class="col-md-4">
                           <small>CREATED</small><br/><strong>{{request.created_at}}</strong>
                        </div>

                    </div>
                    <div class="row text-dark justify-content-center px-20 mb-2">
                        <div class="col-md-12"><h3 class="text-primary text-center"> FORM DETAILS</h3> </div>
                        <div class="col-md-6">
                            <small>Cost Center</small><br/><strong>{{request.cost_center}}</strong>
                        </div>
                        <div class="col-md-6">
                           <small>Reference to other S.R AS</small><br/> <strong>{{request.ref_to_others}}</strong>
                        </div>

                    </div>
                    <div class="row text-dark px-20 mb-2">
                          <div class="col-md-3">
                            <small>Local Purchase Order No </small><br/><strong>{{request.local_purchase_no}}</strong>
                         </div>
                         <div class="col-md-3">
                          <small>Indent No </small><br/><strong>{{request.indent_no}}</strong>
                          </div>
                          <div class="col-md-3">
                             <small>Consignment Note No</small> <br/><strong>{{request.consignment_note_no}}</strong>
                          </div>
                           <div class="col-md-3">
                             <small>Item No</small> <br/><strong>{{request.item_no}}</strong>
                          </div>
                    </div>
                    <div class="row text-dark px-20 mb-2">
                          <div class="col-md-3">
                            <small>Supplier's Name</small><br/><strong>{{request.supplier_name}}</strong>
                         </div>
                         <div class="col-md-3">
                          <small>Supplier Invoice No</small><br/><strong>{{request.supplier_invoice_no}}</strong>
                          </div>
                          <div class="col-md-3">
                             <small>Case Markings</small> <br/><strong>{{request.case_markings}}</strong>
                          </div>
                           <div class="col-md-3">
                             <small>Date Recieved</small> <br/><strong>{{request.date_recieved}}</strong>
                          </div>
                    </div>
                    <div class="row text-dark px-20 my-3">
                      <div class="col-md-12">
                          <!-- dynamic portion -->
                              <div class="table-responsive">
                                      <table class="table table-bordered" width="100%">
                                          <thead>
                                              <tr class="text-dark">
                                                  <th width="4%">S/N</th>
                                                  <th width="10%"> vocab</th>
                                                  <th width="40%">Description</th>
                                                  <th width="14%">Qty</th>
                                                  <th width="16%">Price</th>
                                                  <th width="16%">Value</th>

                                              </tr>
                                          </thead>

                                          <tbody>
                                              <tr v-for="(input,k) in request.stock" :key="k">
                                                  <td>{{ k + 1}}</td>
                                                  <td>{{input.vocab}}</td>
                                                  <td>{{input.desc}}</td>
                                                  <td>{{input.qty}}</td>
                                                  <td>{{input.price}}</td>
                                                  <td>{{input.value}}</td>

                                              </tr>

                                          </tbody>
                                          <tfoot>

                                              <!-- <tr>
                                                  <td colspan='7'>
                                                      <button class="btn btn-sm btn-danger" @click.prevent="add(k)" >
                                                          <i class="fa fa-plus"></i>
                                                          Add Set Objective
                                                      </button>
                                                  </td>

                                              </tr> -->
                                          </tfoot>
                                      </table>
                                  </div>
                          <!-- dynamic portion -->
                      </div>
                    </div>

                    <div class="row text-dark px-20 mb-2" >
                         <div class="col-md-3 justify-content-center">
                            <small>Invoice Value</small><br/><strong>{{request.invoice_value}}</strong>
                         </div>
                         <div class="col-md-3">
                          <small>Inward Freight</small><br/><strong>{{request.inward_infreight}}</strong>
                          </div>
                          <div class="col-md-3">
                             <small>Custom Duty</small> <br/><strong>{{request.custom_duty}}</strong>
                          </div>
                          <div class="col-md-3">
                             <small>VAT(5%)</small> <br/><strong>{{request.vat}}</strong>
                          </div>
                          <div class="col-md-6 text-danger my-2">
                             <small class="text-danger">Total</small> <br/><strong>{{request.total}}</strong>
                          </div>
                    </div>
                    <div class="row text-dark px-20 mb-2">
                          <div class="col-md-12">
                            <small>Remarks</small><br/><strong>{{request.remarks}}</strong>
                         </div>
                    </div>



                    <div class="row text-dark d-block justify-content-center px-20 mb-2" v-show="approvals.length > 0">
                        <div class="col-md-12" v-for="(app, index) in approvals" :key="'a-'+index" v-show="app.comment != null">
                             <small>DECLINE COMMENT</small> <br/><strong>{{app.comment}}</strong>
                        </div>
                    </div>

                    <div class="row text-dark d-block justify-content-center px-20 mb-2" v-show="approvals.length > 0">
                        <div class="col-md-12" v-for="(app, index) in approvals" :key="'a-'+index">
                            <p><small>COMPLETED BY {{app.name.toUpperCase()}} : <strong class="text-dark">{{app.action_date}}</strong></small></p>
                        </div>
                    </div>

                    <div class="row text-dark justify-content-center px-20 mb-2" v-show="completed == 'false'">
                       <template >
                            <div class="col-md-6">
                              <a @click.prevent="commentAlert()" href="#" class="btn btn-danger" :disabled="isLoading">Decline Request</a>
                            </div>
                            <div class="col-md-6">
                              <a @click.prevent="acceptRequest()" href="#" class="btn btn-primary" :disabled="isLoading">Accept Request</a>
                            </div>
                       </template>
                    </div>
                    <div class="row text-dark justify-content-center px-20 mb-2" v-show="completed == 'true'">

                        <p class="btn btn-danger"> STATUS: COMPLETED</p>

                    </div>

                    </div>
                </div>

        </div>

    </section>

</div>


</template>


<script>

    import {RotateSquare5} from 'vue-loading-spinner'
    import Swal from 'sweetalert2'

    export default {
        props: {
            request: {
                type: Object,
                required: true,
            },
            role: {
              type: String,
              required: true
            },
            final: {
              type: String,
              required: true
            },
            completed: {
              type: String,
              required: true
            }
        },
        mounted() {
            console.log('Component mounted.')
            //this.getCurrentRequest()
            this.getCurrentApprovals();
        },
        created(){
            setTimeout(() => {
                this.componentLoaded = true
                //this.commentAlert()
            }, 2000)
        },
        components: {
            RotateSquare5
        },
        data(){
            return {
                email: "",
                password: "",
                remember: true,
                valErrors: [],
                approvals: [],
                componentLoaded: false,
                status: "",
                isLoading: false,
                showActionButton: false,
                title: '',
                formLabel: '',
                displayActionButton : true
            }
        },
        methods: {

            rejectRequest(){
                this.isLoading = true
                this.loadingAlert("Processing...please wait")
                this.processAction("DECLINED")
            },

            acceptRequest(){
                this.isLoading = true
                this.request.comment = null;
                this.loadingAlert("Processing...please wait")
                this.processAction("ACCEPTED")
            },
            processAction(approvalStatus){
                let data = {
                    'decline_comment' : this.request.comment,
                    'request_id' : this.request.id,
                    'request_type': this.request.request_type,
                    'approval_status': approvalStatus
                }

              axios.post("../../../requests/approval", data).then((response) => {
                     this.isLoading = false
                     console.log(response.data);
                     if(response.status == 200 || response.status == 201){

                          if(response.data.hasOwnProperty('errors')){
                              setTimeout(()=>{
                                  this.errorAlert("Failed", this.valErrors[0])
                                   this.valErrors = response.data.errors;
                              }, 2000)
                          }else{
                              console.log(response)
                              setTimeout(()=>{
                                  this.successAlert(approvalStatus, "staff request " + approvalStatus + " successfully", false)

                              }, 1000)
                               setTimeout(()=>{
                                  location.reload()
                              }, 5000)
                              //this.details = response.data
                              console.log(response.data)

                          }
                     }
                     else{
                          setTimeout(()=>{
                              this.errorAlert("An error occured", " Please refresh or try again later")
                          }, 2000)

                     }

              }).catch((error) => {
                    this.isLoading = false
                    setTimeout(()=>{
                        this.errorAlert("Something went wrong", "Error connecting to the server",)
                    }, 2000)
              });

            },

            returnTitle(){
                if(this.request.request_type == "dl_enhance"){
                    this.title = "DLEnhance Access Request"
                }
                if(this.request.request_type == "sage"){
                    this.title = "Sage X3 ERP Access Request"
                }

                return this.title;
            },
            getCurrentApprovals(){
                axios.get('../../get_current_approvals/'+this.request.id+'/'+this.request.form_id+"/"+this.request.request_type).then((res) => {
                    this.approvals = res.data;
                }).catch((err) => {
                    console.log(err)
                });
            },

            loadingAlert(footer){
                Swal.fire({
                    html: '<i class="fa fa-circle-o-notch fa-spin text-primary fa-3x"></i>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    footer: footer
                })
            },
            errorAlert(title, text){
                Swal.fire({
                    icon: 'error',
                    position: 'center',
                    title: 'Oops..'+title,
                    text: text,
                    showConfirmButton: true,
                })
            },
            successAlert(text, footer, toast){
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    text: text,
                    toast: toast,
                    showConfirmButton: true,
                    allowOutsideClick: false,
                    footer: footer
                })
            },
            infoAlert(text, footer){
                 Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: "Ooop..",
                    text: text,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    footer: footer
                })
            },
            commentAlert(){
                Swal.fire({
                title: '<html><small class="text-dark">Please state reason for decline</small></html>',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: false,
                confirmButtonText: 'submit',
                showLoaderOnConfirm: false,
                allowOutsideClick: true
                }).then((result) => {
                if (result.value) {
                  console.log(result.value)
                    if(result.value.trim().length == 0){
                        Swal.fire({
                        title: `${result.value}`,

                        })
                    }
                    else{
                        this.request.comment = result.value
                        this.rejectRequest(this.request.approval)
                    }

                }
                })
             }

        },
        filters: {
          upperCase(p){
             return p.toString().toUpperCase();
          }
        }
    }
</script>
