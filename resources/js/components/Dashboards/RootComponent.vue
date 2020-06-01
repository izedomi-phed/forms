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
                         <h3><strong>FORM SETUP</strong> </h3>
                    </div>
                </div>
                <div class="row my-20">

                    <div class="col-12 col-md-8 mt-25">
                        <h6><strong>{{title}}</strong></h6>
                        <button href="#" class="mx-1 btn btn-sm btn-round" :class="{'bg-danger': isShowing == 'categories'}"
                        @click.prevent="categoriesSetting()">
                            Category
                        </button>
                        <button href="#" class="mx-1 btn btn-sm btn-round" :class="{'bg-danger': isShowing == 'forms'}"
                        @click.prevent="formsSetting()">
                            Forms
                        </button>

                    </div>
                    <div class="col-12 col-md-4 mt-25">
                        <form>
                            <section v-show="isShowing=='forms'">

                                <select class="form-control" @change="getRequests($event)" >
                                    <option value="ALL">ALL CATEGORIES</option>
                                    <option v-for="(category, index) in categories" :key="index"
                                    :value="category.title">
                                        {{category.title}}
                                    </option>
                                </select>
                            </section>
                        </form>
                    </div>

                </div>

                <section>


                    <div class="row text-center" v-show="sortedForms.length < 1">
                        <div class="col-md-10"><h3 class="text-center text-danger"> {{msg}}</h3></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <!-- forms table -->
                            <div class="table-responsive">

                                <!-- forms table -->
                                <table class="table table-bordered" id="user_table" width="70%" v-show="isShowing=='categories'">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><strong>S/N</strong></th>
                                            <th class="text-center"><strong>CATEGORY TITLE</strong> </th>
                                            <th class="text-center">
                                                <button class="btn btn-sm btn-success" @click.prevent="setCategory(null)" data-toggle="modal" data-target="#category">
                                                    New Category
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center" v-for="(category, index) in categories" :key="index" v-show="categories.length > 0">
                                            <td class="text-center">{{index + 1}}</td>
                                            <td class="text-center">{{category.title}}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" @click.prevent="setCategory(category)" data-toggle="modal" data-target="#category">
                                                    <i class="fa fa-pencil">
                                                    </i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" @click.prevent="deleteCategory(category.id)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="categories.length < 1" class="text-center">
                                            <td colspan="3" class="text-center"> No Categories have been created!</td>
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
                                <table class="table table-bordered" width="100%" v-show="isShowing =='forms'">

                                        <thead>
                                            <tr>
                                                <th class="text-center"><strong>S/N</strong></th>
                                                <th class="text-center"><strong>FORM TITLE</strong> </th>
                                                <th class="text-center"><strong>FORM OWNER</strong></th>
                                                <th class="text-center"><strong>FORM CATEGORY</strong></th>
                                                <th class="text-center"><strong>REQUIRED APPROVER LEVELS</strong></th>
                                                <th class="text-center">
                                                    <button class="btn btn-sm btn-success" @click.prevent="setForm(null)" data-toggle="modal" data-target="#form">
                                                        New
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                                <tr class="text-center" v-for="(form, index) in sortedForms" :key="index">
                                                    <td>{{index + 1}}</td>
                                                    <td>{{form.title}}</td>
                                                    <td>{{form.owner}}</td>
                                                    <td>{{form.cat_title}}</td>
                                                    <td>{{form.level}}</td>


                                                    <td>
                                                        <button class="btn btn-sm btn-primary"  @click.prevent="setForm(form)"  data-toggle="modal" data-target="#form">
                                                            <i class="fa fa-pencil">
                                                            </i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" @click.prevent="deleteForm(form.id)">
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

                            </div>

                        </div>

                    </div>
                </section>


                <div class="modal fade" id="form" ref="m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="exampleModalLabel">Create Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="submitForm">

                                <div class="modal-body">

                                   <label>Category</label>
                                   <select class="form-control mb-3" required v-model="formDetails.categoryId">
                                        <option v-for="(category, index) in this.categories" :key="index" :value="category.id">
                                            {{category.title}}
                                        </option>

                                    </select>
                                    <input type="text" class="form-control mb-3" placeholder="Form Title" v-model="formDetails.title" required/>
                                    <input type="email" class="form-control mb-3" placeholder="Form Owner" v-model="formDetails.owner"  required/>
                                    <input type="number" class="form-control mb-3" min="0" placeholder="Required Approval levels" v-model="formDetails.level" required/>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="category" ref="m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="exampleModalLabel">{{categoryAction}} Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="submitCategoryForm">

                                <div class="modal-body">
                                    <input type="text" class="form-control mb-3" placeholder="Category Name" v-model="categoryTitle" required/>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{categoryAction}}</button>
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

        this.fetchCategories();
        this.fetchForms();

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

            categories: [],
            categoryTitle: '',
            categoryId: '',
            categoryAction: 'Create',
            categoryFormData: {},
            forms: [],
            sortedForms: [],
            formDetails: {},
            formData: {},
            componentLoaded: false,
            isShowing: 'categories',


            roleList:[],
            title: "",
            activeRequests: [],
            msg: "",




        }
    },
    methods: {

        //create new appraisal
        getRequests(){

            this.loadingAlert()
            let query = event.target.value
            setTimeout(() => {
                if(query == 'ALL'){
                    this.sortedForms = this.forms;
                    Swal.close()
                }else{
                    console.log(query)
                    let v = this.forms.filter((item) => {
                        return item.cat_title == query
                    });

                    if(v.length > 0){
                        this.sortedForms = []
                        this.sortedForms = v
                        Swal.close()
                    }
                    else{
                        this.infoAlert("", "No current request for "+ query)
                        this.sortedForms = []
                        this.msg = "Nothing found"
                    }
                }

            }, 1000)

        },

        /*
        * submit_category
        */
        submitCategoryForm(){

            if(this.categoryId.toString().trim().length > 0){
                this.addUpdateCategory(false)
            }
            else{
                this.addUpdateCategory(true)
            }
        },
        submitForm(){
            if(this.formDetails.id.toString().trim().length > 0){
                this.addUpdateForm(false)
            }
            else{
                this.addUpdateForm(true)
            }
            //console.log(this.formDetails.categoryId)
        },

        /*
        * Adds a new category
        */
       addUpdateCategory(isNew = true){
            console.log(this.categoryTitle)
            if(this.categoryTitle.trim().length == 0){
                this.errorAlert("Please complete the title field", "")
                return
            }

            let text = ''
            if(isNew){
                this.categoryFormData = {
                    'title': this.categoryTitle,
                    'is_new': isNew
                }

                text = "New category added successfully"
                this.categoryAction = "Create"

            }
            else{
                 this.categoryFormData = {
                'title' : this.categoryTitle,
                'is_new': isNew,
                'id': this.categoryId
                }

                text = "Category updated successfully"
            }


            axios.post('root/add_update_category', this.categoryFormData).then( res => {
                console.log(res.data)
                this.successAlert("Success!", text);

                this.categoryTitle = '';
                setTimeout(() => {
                    this.fetchCategories()
                    this.fetchForms()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            });

       },
       fetchCategories(){
            axios.get('root/fetch_categories').then( res => {
               this.categories = res.data;
            }).catch( err => {
                console.log("something went wrong while fetching  form categories")
            });
       },
       editCategory(category){
            console.log(id)
            this.loadingAlert('completing action...please wait')

            axios.post('root/edit_category/'+this.categoryId, data).then( res => {
                console.log(res.data)
                this.successAlert("Updated!", "Category updated successfully");
                setTimeout(() => {
                    this.fetchCategories()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Couldn't complete action", "Error occurred")
            });
       },
       deleteCategory(id){
            console.log(id)
            this.loadingAlert('completing action...please wait')
            let data = {
                'id' : id
            }
            axios.post('root/delete_category', data).then( res => {
                console.log(res.data)
                this.successAlert("Deleted!", "Category deleted successfully");
                setTimeout(() => {
                    this.fetchCategories()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Couldn't complete action", "Error occurred")
            });
       },
      setCategory(category){
            if(category != null){
                this.categoryTitle = category.title
                this.categoryId = category.id
                this.categoryAction = "Update"
            }
            else{
                this.categoryAction = "Create"
                this.categoryId = ''
                this.categoryTitle = null
            }

        },

      addUpdateForm(isNew = true){
            let text = ''
            if(isNew){
                this.formData = {
                    'title': this.formDetails.title,
                    'owner': this.formDetails.owner,
                    'level': this.formDetails.level,
                    'category_id': this.formDetails.categoryId,
                    'is_new': isNew
                }

                text = "New form added successfully"
                this.formAction = "Create"

            }
            else{
                 this.formData = {
                    'title': this.formDetails.title,
                    'owner': this.formDetails.owner,
                    'level': this.formDetails.level,
                    'category_id': this.formDetails.categoryId,
                    'is_new': isNew,
                    'id': this.formDetails.id
                }

                text = "Form updated successfully"
            }

            axios.post('root/add_update_form', this.formData).then( res => {
                console.log(res.data)
                this.successAlert("Success!", text);

                this.formDetails = {};
                this.formDetails.id = ''
                setTimeout(() => {
                    this.fetchForms()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            });
        },
        fetchForms(){
            axios.get('root/fetch_forms?is_root_user').then( res => {
               this.forms = res.data;
               this.sortedForms = this.forms
            }).catch( err => {
                console.log("something went wrong while fetching  form categories")
            });
        },
        setForm(form){
            if(form != null){
                this.formDetails = form
                this.formAction = "Update"
            }
            else{
               this.formAction = "Create"
               this.formDetails = {}
               this.formDetails.id = ''
            }
            //console.log(this.formDetails)

        },
        deleteForm(id){
            console.log(id)
            this.loadingAlert('completing action...please wait')
            let data = {
                'id' : id
            }
            axios.post('root/delete_form', data).then( res => {
                console.log(res.data)
                this.successAlert("Deleted!", "form deleted successfully");
                setTimeout(() => {
                    this.fetchForms()
                }, 2000)
            }).catch( err => {
                this.errorAlert("Couldn't complete action", "Error occurred")
            });

        },

        /*
        *  Returns all forms to which user was assigned
        *  and all requests requiring user approval
        */
        categoriesSetting(){
            //get all roles available to staff
            this.isShowing = 'categories'
            //this.allRequests = []
            this.activeRequest = []
           /* axios.get("approvals/get_all_staff_approval_roles/"+this.admin_email).then(res => {
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
        formsSetting(){
            this.isShowing = 'forms'
            /*axios.get('all_roles?email='+this.admin_email).then(res => {
                this.assignedRoles = res.data.assigned_role
                this.ownedForms = res.data.owned_form
                if(this.ownedForms.length < 1){
                    this.isShowing = 'requests'
                }
                console.log(this.ownedForms);
            }).catch(err => {
                console.log(err)
            })
            */
        },
        resetRequest(id, formType){
           /* this.loadingAlert()
            let data = {
                'request_id' : id,
                'form_type': formType
            }
            axios.post('reset_request', data).then(err => {
                this.successAlert("Success", "Staff request reset successful")
                this.getAllFormReports()
            }).catch(err => {
                this.errorAlert("Something went wrong", "Action couldn't be completed")
            }) */
        },
        createRole(){
           /*this.loadingAlert()
           axios.post('create_new_role', this.newRole).then((res) => {
               console.log(res.data)
               this.successAlert("Success", "New role created successfully")
               //this.getAllAssignedRoles()
               location.reload()
           }).catch((err) => {
               this.errorAlert("Operation failed", "Please refresh or try again later")
           })
           */
        },
        showDetails(index){
            /*this.details = this.activeRequests[0];
            console.log(this.details.staff_id)
            $('#newAppraisal').modal('show')
            */
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
