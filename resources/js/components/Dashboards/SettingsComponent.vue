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
                         <h3><strong>FORM SETTINGS</strong> </h3>
                    </div>
                </div>
                <div class="row my-20">

                    <div class="col-12 col-md-8 mt-25">
                        <h6><strong>{{title}}</strong></h6>
                        <button href="#" class="mx-1 btn btn-sm btn-round" :class="{'bg-danger': isShowing == 'forms'}"
                        @click.prevent="formSetting()">
                            Forms
                        </button>
                        <button href="#" class="mx-1 btn btn-sm btn-round" :class="{'bg-danger': isShowing == 'approvers'}"
                        @click.prevent="approverSetting()">
                            APPROVERS
                        </button>
                        <button href="#" class="mx-1 btn btn-sm btn-round" :class="{'bg-danger': isShowing == 'roles'}"
                        @click.prevent="roleSetting()">
                            ROLES
                        </button>
                    </div>
                    <div class="col-12 col-md-4 mt-25">
                        <form>
                            <section v-show="isShowing=='forms'">

                                <select class="form-control" @change="sort($event)" >
                                    <option value="ALL">ALL FORM CATEGORIES</option>
                                    <option v-for="(form, index) in categories" :key="index"
                                    :value="form.title">
                                        {{form.title}}
                                    </option>
                                </select>
                            </section>
                            <section v-show="isShowing=='roles' || isShowing=='approvers' ">
                                <select class="form-control" @change="sort($event)" >
                                    <option value="ALL">ALL FORMS</option>
                                    <option v-for="(form, index) in forms" :key="index"
                                    :value="form.title">
                                        {{form.title}}
                                    </option>
                                </select>
                            </section>


                        </form>
                    </div>

                </div>

                <section>


                    <div class="row text-center" v-show="activeRequests.length < 1">
                        <div class="col-md-10"><h3 class="text-center text-danger"> {{msg}}</h3></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                                <!-- forms table -->
                                <div class="table-responsive">

                                    <!-- forms table -->
                                    <table class="table table-bordered" id="user_table" width="70%" v-show="isShowing=='forms'">
                                        <thead>
                                            <tr>
                                                <th class="text-center"><strong>S/N</strong></th>
                                                <th class="text-center"><strong>FORM TITLE</strong> </th>
                                                <th class="text-center"><strong>REQUIRED APPROVERS</strong></th>
                                                <th class="text-center"><strong>CATEGORY</strong></th>
                                                <th class="text-center"><strong></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(form, index) in sortedForms" :key="index">
                                                <td class="text-center">{{index + 1 }}</td>
                                                <td class="text-center">{{ form.title}}</td>
                                                <td class="text-center">{{form.level}}</td>
                                                <td class="text-center">{{form.cat_title}}</td>
                                                <td class="text-center">
                                                    <strong>

                                                        <a class="btn btn-danger" data-toggle="modal" href="#form" @click.prevent="setForm(form)">
                                                            <i class="fa fa-pencil text-white"></i>
                                                        </a>
                                                    </strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <td colspan='8<br/>'>
                                                PHED STAFF FORMS
                                                </td>

                                            </tr>
                                        </tfoot>

                                    </table>
                                    <!-- fors table: ends -->

                                    <!-- roles table -->
                                    <table class="table table-bordered" width="100%" v-show="isShowing =='approvers'">

                                            <thead>
                                                <tr>
                                                    <th class="text-center"><strong>S/N</strong></th>
                                                    <th class="text-center"><strong>STAFF NAME</strong> </th>
                                                    <th class="text-center"><strong>STAFF EMAIL</strong></th>
                                                    <th class="text-center"><strong>FORM TYPE</strong></th>
                                                    <th class="text-center"><strong>ROLE</strong></th>
                                                    <th class="text-center">
                                                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#ab" @click.prevent="setApprover(null)">
                                                            New
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                    <tr class="text-center" v-for="(approver, index) in sortedApprovers" :key='index'>
                                                        <td>
                                                         {{index + 1}}
                                                        </td>
                                                          {{approver.name}}
                                                        <td>
                                                            {{approver.email}}
                                                        </td>
                                                        <td>
                                                            {{approver.form_type}}
                                                        </td>
                                                        <td>
                                                            {{approver.role}}
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#ab" @click.prevent="setApprover(approver)">
                                                                <i class="fa fa-pencil">
                                                                </i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" @click.prevent="deleteApprover(approver.id)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="text-center">
                                                    <td colspan='8<br/>'>
                                                    PHED STAFF FORMS
                                                    </td>

                                                </tr>
                                            </tfoot>

                                    </table>
                                    <!-- roles table -->


                                    <!-- form approver levels table -->
                                    <table class="table table-bordered" width="70%" v-show="isShowing=='roles'">
                                        <thead>
                                            <tr>
                                                <th class="text-center"><strong>S/N</strong></th>
                                                <th class="text-center"><strong>ROLE LEVEL</strong> </th>
                                                <th class="text-center"><strong>ROLE TITLE</strong></th>
                                                <th class="text-center"><strong>FORM TYPE</strong></th>
                                                <th class="text-center">
                                                        <button class="btn btn-sm btn-success" @click.prevent="setRole(null)" data-toggle="modal" data-target="#roles">
                                                            New
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(role, index) in sortedRoles" :key="index">
                                                <td class="text-center">{{index + 1 }}</td>
                                                <td class="text-center">{{ role.level }}</td>
                                                <td class="text-center">{{ role.title }}</td>
                                                <td class="text-center">{{ role.form_type }}</td>
                                                 <td>
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#roles" @click.prevent='setRole(role)'>
                                                        <i class="fa fa-pencil">
                                                        </i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" @click.prevent="deleteRole(role.id)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <td colspan='8<br/>'>
                                                PHED STAFF FORMS
                                                </td>

                                            </tr>
                                        </tfoot>

                                    </table>
                                    <!-- form approver levels table : ends -->

                                </div>


                        </div>

                    </div>
                </section>



                <div class="modal fade" id="ab" ref="m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="exampleModalLabel">{{approverStatus}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="submitApprover">

                                <div class="modal-body">
                                    <input type="text" class="form-control mb-3" placeholder="Name" required v-model="approverDetails.name">
                                    <input type="email" class="form-control mb-3" placeholder="Email" required v-model="approverDetails.email"/>
                                    <label>Form type</label>
                                    <select class="form-control" required @change="getFormRoleTitles($event)" v-model="approverDetails.form">
                                        <option v-for="(form, index) in forms" :key="index" :value="form.title+'|'+form.id">{{form.title}}</option>
                                    </select>
                                    <label>Role Title</label>
                                    <select class="form-control" required v-model="approverDetails.role">
                                        <option v-for="(role, index) in formRoleTitles" :key='index' :value="role.title+'|'+role.level">{{role.title}}</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{approverStatus}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="form" ref="m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="exampleModalLabel">Create Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="addUpdateForm">

                                <div class="modal-body">

                                   <label>Category</label>
                                   <select class="form-control mb-3" required v-model="formDetails.categoryId" readonly >
                                        <option v-for="(category, index) in this.categories" :key="index" :value="category.id">
                                            {{category.title}}
                                        </option>
                                    </select>
                                    <input type="text" class="form-control mb-3" placeholder="Form Title" v-model="formDetails.title" required readonly/>
                                    <input type="email" class="form-control mb-3" placeholder="Form Owner" v-model="formDetails.owner"  required readonly/>
                                    <input type="number" class="form-control mb-3" min="0" placeholder="Required Approval levels" v-model="formDetails.level" required/>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="roles" ref="m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="exampleModalLabel">{{roleStatus}} Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="submitRole">

                                <div class="modal-body">

                                   <label>Form Type</label>
                                   <select class="form-control mb-3" v-model="roleDetails.form_type" required @change="getLevel($event)" >
                                        <option v-for="(form, index) in forms" :key="index" :value="form.title+'|'+form.level+'|'+form.id">{{form.title}}</option>
                                    </select>

                                    <label>Clearance Level</label>
                                    <select class="form-control mb-3" v-model="roleDetails.level" required>
                                        <option  v-for="(n, index) in Number(formLevel)" :key="index">{{n}}</option>
                                    </select>

                                    <label>Role Title</label>
                                    <input type="text" class="form-control mb-3" placeholder="Role Title"  v-model="roleDetails.title" required/>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{roleStatus}}</button>
                                </div>
                            </form>
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
    created(){
        setTimeout(() => {
            this.componentLoaded = true
        }, 2000)
    },
    mounted(){

        //this.getAllRequests()
        //this.formSetting();
        this.fetchCategories()
        this.fetchForms()
        this.fetchRoles()
        this.fetchApprovers()

        //this.getAllAssignedRoles()
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

            activeRequests: [],
            roleList:[],

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
            isShowing: 'forms',

            x: 'baz',
            componentLoaded: false,
            formRoleTitles: [],
            categories: [],
            forms: [],
            sortedForms: [],
            formDetails: {},
            formLevel: 0,
            formData: {},
            roles: [],
            sortedRoles: [],
            roleDetails: {},
            roleData: {},
            roleStatus: 'Create',
            approverDetails: {},
            approverStatus: 'Create',
            approvers: [],
            sortedApprovers: []


        }
    },
    methods: {

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
        getLevel(){

            this.formLevel = event.target.value.split("|")[1]
        },
        getFormRoleTitles(){
            let query = event.target.value.split("|")[0]
            this.formRoleTitles = this.roles.filter((item) => {
                    return item.form_type == query
                })
        },

        /*
        * Forms methods
        */
        setForm(form){
            this.formDetails = form
            this.formDetails.categoryId = form.cat_id
            this.formAction = "Update"
        },
        fetchForms(){
            axios.get('../root/fetch_forms').then( res => {
               this.forms = res.data;
               this.sortedForms = this.forms
            }).catch( err => {
                console.log("something went wrong while fetching  form categories")
            });
        },
        addUpdateForm(){

            this.formData = {
                'title': this.formDetails.title,
                'owner': this.formDetails.owner,
                'level': this.formDetails.level,
                'category_id': this.formDetails.categoryId,
                'is_new': false,
                'id': this.formDetails.id
            }

            let text = "Form updated successfully"

            axios.post('../root/add_update_form', this.formData).then( res => {
                console.log(res.data)
                this.successAlert("Success!", text);

                this.formDetails = {};
                setTimeout(() => {
                    this.fetchForms()
                    location.reload()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            });
        },
        sort(){

            if(this.isShowing == 'forms'){
                if(event.target.value == 'ALL'){
                    this.sortedForms = this.forms
                }
                else{
                    this.sortedForms = this.forms.filter((item) =>{
                        return item.cat_title == event.target.value
                    });
                }
            }
            if(this.isShowing == 'roles'){
                  if(event.target.value == 'ALL'){
                    this.sortedRoles = this.roles
                }
                else{
                    this.sortedRoles = this.roles.filter((item) =>{
                        return item.form_type == event.target.value
                    });
                }
            }
            if(this.isShowing == 'approvers'){
                if(event.target.value == 'ALL'){
                    this.sortedApprovers = this.approvers
                }
                else{
                    this.sortedApprovers = this.approvers.filter((item) =>{
                        return item.form_type == event.target.value
                    });
                }
            }


        },

        /*
        * Roles methods
        */
        submitRole(){
            console.log("submit")
            if(this.roleDetails.id.toString().trim().length > 0){
                this.addUpdateRole(false)
            }
            else{
                this.addUpdateRole(true)
            }
        },
        fetchRoles(){
            axios.get('fetch_roles').then( res => {
                this.roles = res.data;
                this.sortedRoles = this.roles
            }).catch( err => {
                console.log("something went wrong while fetching form categories")
            });
        },
        addUpdateRole(isNew = true){
            let text = ''

            let d = this.roleDetails.form_type.split("|")

            
            this.roleDetails.form_type = d[0]
            this.roleDetails.form_id = d[2]
            console.log(this.roleDetails.form_type)
            console.log(this.roleDetails.form_id)


            if(isNew){
                this.roleData = {
                    'form_type': this.roleDetails.form_type,
                    'form_id': this.roleDetails.form_id,
                    'level': this.roleDetails.level,
                    'title': this.roleDetails.title,
                    'is_new': isNew
                }

                text = "New form added successfully"
                this.formAction = "Create"

            }
            else{
                 this.roleData = {
                    'form_type': this.roleDetails.form_type,
                    'form_id': this.roleDetails.form_id,
                    'level': this.roleDetails.level,
                    'title': this.roleDetails.title,
                    'is_new': isNew,
                    'id': this.roleDetails.id
                }

                text = "Form updated successfully"
            }

            console.log("sfsdfs")

            axios.post('add_update_role', this.roleData).then( res => {
                console.log(res.data)
                this.successAlert("Success!", text);

                this.roleDetails = {};
                setTimeout(() => {
                    this.fetchRoles()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            });
        },
        setRole(role){
            if(role != null){this.roleDetails = role; this.roleStatus = "Update"}
            else{
                this.roleDetails = {}
                this.roleDetails.id  = ''
                this.roleStatus = "Create"
            }
        },
        deleteRole(id){
            console.log("delete "+ id)
            console.log(id)
            this.loadingAlert('completing action...please wait')
            let data = {
                'id' : id
            }
            axios.post('delete_role', data).then( res => {
                console.log(res.data)
                this.successAlert("Deleted!", "form deleted successfully");
                setTimeout(() => {
                    this.fetchRoles()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Couldn't complete action", "Error occurred")
            });
        },

        fetchCategories(){
            axios.get('../root/fetch_categories').then( res => {
               this.categories = res.data;
            }).catch( err => {
                console.log("something went wrong while fetching  form categories")
            });
        },

        /*
        * Approver methods
        */
        submitApprover(){
            console.log("submit")
            if(this.approverDetails.id.toString().trim().length > 0){
                this.addUpdateApprover(false)
            }
            else{
                this.addUpdateApprover(true)
            }
        },
        addUpdateApprover(isNew = true){

            let text = ''

            let d = this.approverDetails.form.split("|")
            let r = this.approverDetails.role.split("|");

            this.approverDetails.form_type = d[0]
            this.approverDetails.form_id = d[1]
            this.approverDetails.role_level = r[1]
            this.approverDetails.role_title = r[0]
            console.log(this.approverDetails.form_type)
            console.log(this.approverDetails.form_id)
            console.log(this.approverDetails.role_level)
            console.log(this.approverDetails.role_title)

            if(isNew){
                this.approverData = {
                    'form_type': this.approverDetails.form_type,
                    'form_id': this.approverDetails.form_id,
                    'level': this.approverDetails.role_level,
                    'role': this.approverDetails.role_title,
                    'name': this.approverDetails.name,
                    'email': this.approverDetails.email,
                    'is_new': isNew
                }

                text = "New form added successfully"
                this.formAction = "Create"

            }
            else{
                 this.approverData = {
                    'form_type': this.approverDetails.form_type,
                    'form_id': this.approverDetails.form_id,
                    'level': this.approverDetails.role_level,
                    'role': this.approverDetails.role_title,
                    'name': this.approverDetails.name,
                    'email': this.approverDetails.email,
                    'id': this.approverDetails.id
                }

                text = "Form updated successfully"
            }

            console.log("sfsdfs")

            axios.post('add_update_approver', this.approverData).then( res => {
                console.log(res.data)
                this.successAlert("Success!", text);

                this.approverDetails = {};
                setTimeout(() => {
                    this.fetchApprovers()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            });
        },
        setApprover(approver){
            if(approver != null){this.approverDetails = approver; this.approverStatus = "Update"}
            else{
                this.approverDetails = {}
                this.approverDetails.id  = ''
                this.approverStatus = "Create"
            }
        },
        fetchApprovers(){
            axios.get('fetch_approvers').then( res => {
                this.approvers = res.data;
                this.sortedApprovers = this.approvers
            }).catch( err => {
                console.log("something went wrong while fetching form categories")
            });
        },
        deleteApprover(id){
            console.log("delete "+ id)
            console.log(id)
            this.loadingAlert('completing action...please wait')
            let data = {
                'id' : id
            }
            axios.post('delete_approver', data).then( res => {
                console.log(res.data)
                this.successAlert("Deleted!", "Deleted successfully");
                setTimeout(() => {
                    this.fetchApprovers()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Couldn't complete action", "Error occurred")
            });
        },


        /*
        *  Returns all forms to which user was assigned
        *  and all requests requiring user approval
        */
        formSetting(){
            //get all roles available to staff
            this.isShowing = 'forms'
            //this.allRequests = []
           /* this.activeRequest = []
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
            */
        },
        approverSetting(){
            this.isShowing = 'approvers'
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
        roleSetting(){
            this.isShowing = 'roles'
            /*axios.get("get_all_requests_for_owned_forms?email="+this.admin_email).then(res => {
                if(res.status == 200 || res.status == 201){
                   // this.roleList = res.data;
                    console.log(res.data.assigned_requests);
                    this.allRequests = res.data.assigned_requests;
                    this.activeRequests = res.data.assigned_requests;
                    //this.roleList = res.data.assigned_forms;
                  /*   res.data.forEach((item) => {
                        console.log(item.Tables_in_phed_new)
                    })

                }
            })
            .catch(err => {
                console.log(err)
                 //this.errorAlert("Something went wrong", "No request found")
            })
            */
        },
        resetRequest(id, formType){
            this.loadingAlert()
            let data = {
                'request_id' : id,
                'form_type': formType
            }
            axios.post('reset_request', data).then(err => {
                this.successAlert("Success", "Staff request reset successful")
                this.getAllFormReports()
            }).catch(err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            })
        },
        deleteRole(id){
            this.loadingAlert('completing action...please wait')
            let data = {
                'id' : id
            }
            axios.post('delete_role', data).then( res => {
                console.log(res.data)
                this.successAlert("Deleted!", "Role deleted successfully");
                setTimeout(() => {
                    this.fetchRoles()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Couldn't complete action", "Error occurred")
            });
        },

        createRole(){
           this.loadingAlert()
           axios.post('create_new_role', this.role).then((res) => {
               console.log(res.data)
               this.successAlert("Success", "New role created successfully")
               this.fetchRoles()
               location.reload()
           }).catch((err) => {
               this.errorAlert("Operation failed", "Please refresh or try again later")
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
        confirmAlert(id, title = null){
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
                    if(title == null){
                        this.resetRequest(id, title)
                    }
                    else{
                        this.deleteRole(id)
                    }

                }
            })
        }
    },
    filters: {
        toUpper(str){

            if(str != null || str != ''){
                return str.toUpperCase()
            }

        }
    }
}


</script>
