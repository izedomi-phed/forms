<template>

    <div>

        <div class="row justify-content-center" v-show="!componentLoaded">
            <div class="col-md-6 text-center" style="margin-top:10%">
                <rotate-square5 :size="'120px'"></rotate-square5>
            </div>
        </div>
        <section v-show="componentLoaded">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 text-center mt-10">
                         <h3><strong>DASHBOARD</strong> </h3>
                    </div>
                </div>
                <div class="row my-20">

                    <div class="col-12 col-md-8 mt-25">
                        <h6><strong>{{title}}</strong></h6>

                        <button href="#" class="mx-1 btn btn-sm btn-round" :class="{'bg-danger': isShowing == 'report'}"
                        @click.prevent="getAllFormReports()" v-show="ownedForms.length > 0">
                            Report
                        </button>
                        <button href="#" class="mx-1 btn btn-sm btn-round" :class="{'bg-danger': isShowing == 'requests'}"
                        @click.prevent="getStaffApprovalRoles()" v-show="roleList.length > 0">
                            Requests
                        </button>
                    </div>
                    <div class="col-12 col-md-4 mt-25">
                        <form>
                            <section v-show="isShowing == 'requests'">
                                <select class="form-control" @change="getRequests($event)" >
                                    <option value="ALL">ALL REQUESTS</option>
                                    <option v-for="(form, index) in roleList" :key="index"
                                    :value="form.form_type">
                                        {{form.form_type}}
                                    </option>
                                </select>
                            </section>
                            <section v-show="isShowing == 'report' && ownedForms.length > 0">
                                <select class="form-control" @change="getRequests($event)" >
                                    <option value="ALL">ALL REPORTS</option>
                                    <option v-for="(form, index) in ownedForms" :key="index"
                                    :value="form.title">
                                        {{form.title}}
                                    </option>
                                </select>
                            </section>

                        </form>
                    </div>

                </div>

                <section v-show="isShowing=='requests' || isShowing=='report'">
                    <div class="row mb-1 justify-content-end elevation-3">

                        <div class="col-md-6">
                        {{searchQuery}}
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-sm btn-primary" title="Accept Request" @click.prevent="bulkSubmission" v-show="bulkApprovalId.length > 0">
                                <i class="fa fa-check"></i> Approve Selected
                            </a>
                        </div>
                    </div>

                    <div class="row text-center" v-show="activeRequests.length < 1">
                        <div class="col-md-10"><h3 class="text-center text-danger"> {{msg}}</h3></div>
                    </div>
                    <div class="row" v-show="activeRequests.length > 0">
                        <div class="col-md-12">

                                <div class="table-responsive">

                                    <table class="table table-bordered" id="user_table" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center"><strong>S/N</strong></th>
                                                <th class="text-center" v-show="isShowing=='requests'"></th>
                                                <th class="text-center"><strong>STAFF NAME</strong> </th>
                                                <th class="text-center"><strong>STAFF ID</strong></th>
                                                <th class="text-center"><strong>DESIGNATION</strong></th>
                                                <th class="text-center"><strong>FORM TYPE</strong></th>
                                                <th class="text-center" v-show="isShowing=='requests'"><strong>APPROVAL LEVEL</strong></th>
                                                <th class="text-center" v-show="isShowing=='report'"><strong>REQUEST STATUS</strong></th>
                                                 <th class="text-center" v-show="isShowing=='report'"><strong>REQUIRED APPROVER</strong></th>

                                                <th class="text-center" v-show="isShowing=='report'"><strong></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                                <tr v-for="(request, index) in activeRequests" :key="index" class="text-center" v-show="request.comment == null">

                                                    <td>
                                                        {{index = index + 1}}
                                                    </td>
                                                    <td v-show="isShowing=='requests'">
                                                       <!-- note: id and form id forms a unique indentifier. the id does not -->
                                                        <input type="checkbox" :value="request.id+'|'+request.form_id" v-model="bulkApprovalId" @change="addSelected($event)">
                                                    </td>
                                                    <td>
                                                        <a
                                                        :href="'../requests/'+request.title_slug+'/'+request.id+'/'+request.staff_id+'/?form_id='+request.form_id+'&role='+request.approval_level+'&approver_level='+request.status">
                                                            {{request.name}}
                                                        </a>
                                                    </td>
                                                    <td> {{request.staff_id}}</td>
                                                    <td>
                                                          <p v-show="isShowing=='requests'">
                                                              {{request.designation}}
                                                          </p>

                                                          <p v-show="isShowing == 'report' && (request.title_slug == 'vpn_non_staff' || request.title_slug == 'wifi_non_staff')">
                                                              {{request.staff_designation}}
                                                          </p>
                                                          <p v-show="isShowing == 'report' && (request.title_slug == 'dl_enhance' || request.title_slug == 'sage')">
                                                              {{request.designation}}
                                                          </p>
                                                    </td>

                                                    <td class="text-center">
                                                        <p> {{request.title}} </p>
                                                    </td>
                                                    <td class="text-center" v-show="isShowing=='requests'">
                                                        <p>{{request.approval_level}}</p>
                                                    </td>
                                                    <td class="text-center" v-show="isShowing=='report'">

                                                        <p v-if="request.approval_comment != null">DECLINED</p>
                                                        <p v-else-if="request.status > 0 && request.status <= request.required_approver_levels">{{request.approval_level}} approval required</p>
                                                        <p v-else>COMPLETED</p>
                                                    </td>
                                                    <td class="text-center" v-show="isShowing=='report'">
                                                        <p v-if="request.approval_comment != null">DECLINED</p>
                                                        <p v-else-if="request.status <= request.required_approver_levels">{{request.approval_email}}</p>
                                                        <p v-else>COMPLETED</p>
                                                    </td>

                                                    <td class="text-center" v-show="isShowing=='report'">
                                                        <p class="d-flex">
                                                          <button
                                                            class="btn btn-sm btn-primary"
                                                            @click.prevent="confirmAlert(request.id, request.title_slug, 'reminder')"
                                                            title="Send Reminder"
                                                            v-show="request.status <= request.required_approver_levels">
                                                              <i class="fa fa-bell"></i>
                                                          </button>
                                                          <button
                                                              class="btn btn-sm btn-danger"
                                                              @click.prevent="confirmAlert(request.id, request.title_slug, 'cancel')"
                                                              title="cancel request"
                                                              v-show="request.status <= request.required_approver_levels">
                                                              <i class="fa fa-times"></i>
                                                          </button>
                                                        </p>
                                                    </td>
                                                </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <td colspan='8'>
                                                PHED STAFF FORMS
                                                </td>

                                            </tr>
                                        </tfoot>

                                    </table>

                                </div>
                            </div>

                    </div>
                </section>


            </div>
        </section>
    </div>
</template>


<script>

import {RotateSquare5} from 'vue-loading-spinner'
import Swal from 'sweetalert2'

export default {
    created(){
        setTimeout(() => {
            this.componentLoaded = true
        }, 2000)
    },
    mounted(){

        //this.getAllRequests()
        this.getStaffApprovalRoles();
        this.getAllAssignedRoles()
    },
    props:{
        admin_email: {
            type: String,
            required: true
        }
    },
    components: {
        RotateSquare5
    },
    data(){
        return {
            componentLoaded: false,
            activeRequests: [],
            allRequests: [],
            roleList:[],
            newRole: {},
            bulkApprovalId: [],
            bulkApprovalList: [],
            assignedRoles: [],
            ownedForms: [],
            msg: "",
            details: {},
            title: "",
            searchQuery: "",
            formSlug: "",
            formId: "",
            report: "",
            approvalRole: '',
            isShowing: 'requests'
        }
    },
    methods: {
        addSelected(){

          //console.log(event.target.value)
          // console.log(this.bulkApprovalId)

          //loop through the list holding the selected Id
          //foreach Id, loop through the array of current requests
          //using the pattern id|form_id, match requests selected
          //and setup the post data
          //note id|form_id is used for the matching because id alone does not provide a unique identifier

           if(this.bulkApprovalId.length > 0){
                this.bulkApprovalList = []
                this.bulkApprovalId.forEach((id) => {
                    this.activeRequests.forEach((item) => {
                        if(item.id+'|'+item.form_id == id){
                            let data = {
                                'request_type' : item.title_slug,
                                'approval_status': "ACCEPTED",
                                'id': item.id
                            }
                            this.bulkApprovalList.push(data)
                        }
                    })
                })
            }
            else{
                this.bulkApprovalList = []
            }

            console.log(this.bulkApprovalList)

        },
        bulkSubmission(){
              this.loadingAlert()
              axios.post('../requests/bulk_approval', this.bulkApprovalList).then((res) => {
                  this.successAlert("Requests approved successfully", '');
                 // location.reload();
              }).catch((err) => {
                  console.log(err)
                  this.errorAlert("Something went wrong", "Couldn't connect to the server. Please refresh or try again later")
              });
         },
        //create new appraisal
        getRequests(){

            this.loadingAlert()
             let query = event.target.value
            setTimeout(() => {
                if(query == 'ALL'){
                     this.activeRequests = this.allRequests;
                     Swal.close()
                }else{
                    console.log(query)
                    let v = this.allRequests.filter((item) => {
                        return item.title == query
                    });

                    if(v.length > 0){
                        this.activeRequests = []
                        this.activeRequests = v
                        Swal.close()
                    }
                    else{
                        this.infoAlert("", "No current request for "+ query)
                        this.activeRequests = []
                        this.msg = "Nothing found"
                    }
                }

            }, 1000)

        },

        /*
        *  Returns all forms to which user was assigned
        *  and all requests requiring user approval
        */
        getStaffApprovalRoles(){
            //get all roles available to staff
            this.activeRequest = []
            this.isShowing = 'requests'
            //this.allRequests = []

            axios.get("approvals/get_all_staff_approval_roles/"+this.admin_email).then(res => {
                if(res.status == 200 || res.status == 201){
                    console.log(res.data.assigned_requests);
                    this.allRequests = res.data.assigned_requests;
                    this.activeRequests = res.data.assigned_requests;
                    this.roleList = res.data.assigned_forms;
                }
            })
            .catch(err => {
                console.log(err)
                 //this.errorAlert("Something went wrong", "No request found")
            })
        },
        getAllAssignedRoles(){
            axios.get('all_roles?email='+this.admin_email).then(res => {
                this.assignedRoles = res.data.assigned_role
                this.ownedForms = res.data.owned_form
                if(this.ownedForms.length < 1){
                    this.isShowing = 'requests'
                }
                console.log(this.ownedForms);
            }).catch(err => {
                console.log(err)
            })
        },
        getAllFormReports(){
            this.isShowing = 'report'
            this.activeRequests = []
            axios.get("get_all_requests_for_owned_forms?email="+this.admin_email).then(res => {
                if(res.status == 200 || res.status == 201){
                   // this.roleList = res.data;
                    console.log(res.data.assigned_requests);
                    this.allRequests = res.data.assigned_requests;
                    this.activeRequests = res.data.assigned_requests;
                    //this.roleList = res.data.assigned_forms;
                  /*   res.data.forEach((item) => {
                        console.log(item.Tables_in_phed_new)
                    })
                  */
                }
            })
            .catch(err => {
                console.log(err)
                 //this.errorAlert("Something went wrong", "No request found")
            })
        },
        resetRequest(id, formSlug){
            this.loadingAlert()
            let data = {
                'request_id' : id,
                'form_slug': formSlug
            }
            axios.post('reset_request', data).then(err => {
                this.successAlert("Success", "Staff request reset successful")
                this.getAllFormReports()
            }).catch(err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            })
        },
        sendReminder(id, formSlug){
            this.loadingAlert()
            let data = {
                'request_id' : id,
                'form_slug': formSlug
            }
            axios.post('send_reminder', data).then(err => {
                this.successAlert("Success", "Staff request reset successful")
                this.getAllFormReports()
            }).catch(err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            })
        },

        showDetails(index){
            this.details = this.activeRequests[0];
            console.log(this.details.staff_id)
            $('#newAppraisal').modal('show')
        },
        loadingAlert(footer = 'retrieving requests...please wait' ){
            Swal.fire({
                html: '<i class="fa fa-circle-o-notch fa-spin text-primary fa-3x"></i>',
                allowOutsideClick: false,
                showConfirmButton: false,
                footer: footer
            })
        },
        errorAlert(text, footer){
            Swal.fire({
                icon: 'error',
                position: 'center',
                title: 'Oops...',
                text: text,
                showConfirmButton: true,
                footer: footer
            })
        },
        successAlert(text, footer){
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: "Congrats...",
                text: text,
                showConfirmButton: true,
                allowOutsideClick: false,
                footer: footer
            })
        },
        infoAlert(text, footer){
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: "Ooops....",
                text: text,
                showConfirmButton: true,
                allowOutsideClick: true,
                footer: footer
            })
        },
        confirmAlert(id, slug, action){

            Swal.fire({
                title: '<small><strong>Are you sure?</strong></small>',
                html: '<strong>You won\'t be able to revert this!</strong>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continue',
                width: '350px',
                height: '250px'
                }).then((result) => {
                if (result.value) {
                    if(action == 'cancel'){
                        this.resetRequest(id, slug)
                    }else{
                        this.sendReminder(id, slug)
                    }
                    console.log(action)

                }
            })
        }
    },
    filters: {
    }
}


</script>
