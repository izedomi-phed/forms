<template>


<div class="container">
    <div class="row justify-content-center" v-show="!componentLoaded">
        <div class="col-md-6 text-center" style="margin-top:10%">
            <rotate-square5 :size="'120px'"></rotate-square5>

        </div>
    </div>
    <div class="row justify-content-center" v-show="componentLoaded">
        <div class="col-md-6">

            <form method="post" class="bg-white px-30 elevation-4 mb-20" id="dlenhanceform" @submit.prevent="verifyDetails">

                <div class="row px-3">
                    <div class="col-md-12 text-center mt-1">
                        <h5 class="text-primary mt-1 mb-0">

                            <img :src="'../../images/PHEDLogo.jpg'" alt="phed logo"  style="width:100px;height:70px"/><br/>

                        </h5>
                        <p id="add">
                            Information Technology Department<br/>
                            Port-Harcourt Electricity Distribution Company<br/>
                            118 Ordinance Junction, Trans Amadi Layout.<br/>
                            Email: it@phed.com.ng<br/>
                            Tel: 08139834029
                        </p>
                        <template v-if="valErrors.length > 0">
                            <ul class="mt-3 text-left">
                                <li class="text-danger" v-for="(err, index) in valErrors" :key=index>{{ err }}</li>
                            </ul>
                        </template>
                         <div class="alert alert-danger alert-dismissible fade show" role="alert" v-show="currentRequestStatus != ''">
                            <p class="bg-danger">
                                {{currentRequestStatus}}<br/><span class="">{{remarks}}</span>
                            </p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <p class="des">
                    <!--<strong>This account will be used to access {{type}} application at PHEDC.
                        Processing of this application form requires 1 or more working days.
                    </strong> -->
                </p>
                <hr class="divider">

                <div class="row px-3">
                    <div class="col-md-12 text-center">
                        <p> PHED SUPERVISING REP DETAILS </p>
                    </div>
                    <!-- First Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="firstName">First Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="firstName" v-model="details.staff_first_name" :readonly="details.staff_first_name != ''">
                        </div>
                    </div>
                    <!-- Last name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lastName">Last Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="lastName" v-model="details.staff_last_name" :readonly="details.staff_last_name != ''">
                        </div>
                    </div>

                    <!-- Other name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lastName">Other Names<span class="text-danger"></span></label>
                            <input type="text" class="form-control form-control-sm" id="otherName" v-model="details.staff_other_name">
                        </div>
                    </div>

                    <!-- job title -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="designation">Job Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="designation" v-model="details.staff_designation">
                        </div>
                    </div>

                </div>
                <div class="row px-3">
                    <div class="col-md-12 text-center">
                        <p> REQUESTER'S DETAILS </p>
                    </div>
                    <!-- First Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="firstName">Fullname<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="firstName" v-model="details.requester_first_name">
                        </div>
                    </div>
                    <!-- Last name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lastName">Last Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="lastName" v-model="details.requester_last_name">
                        </div>
                    </div>

                    <!-- Other name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lastName">Other Names<span class="text-danger"></span></label>
                            <input type="text" class="form-control form-control-sm" id="otherName" v-model="details.requester_other_name">
                        </div>
                    </div>

                    <!-- job title -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="designation">Company<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="designation" v-model="details.requester_company">
                        </div>
                    </div>


                    <!-- job title -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="designation">Date of Access<span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="designation" v-model="details.date_of_access">
                        </div>
                    </div>


                    <!-- Staff Type -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="officer_email">Duration of Access<span class="text-danger">*</span></label><br/>
                            <input type="text" class="form-control form-control-sm" id="designation" v-model="details.duration_of_access">
                        </div>
                    </div>

                </div>


                <div class="row justify-content-center">
                    <button name="submit" class="btn btn-round btn-primary mb-10"  v-show="currentRequestStatus == '' || isDeclined">
                        Submit FOR APPROVALS
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

</template>


<script>
    import {RotateSquare5} from 'vue-loading-spinner'
    import Swal from 'sweetalert2'

    export default {
        created(){
            this.details.official_email = this.email
            this.details.staff_id = this.staff_id
            this.details.staff_first_name = this.staff_first_name
            this.details.staff_last_name = this.staff_last_name
            this.details.staff_other_name = this.staff_other_name
            setTimeout(()=> {
                this.componentLoaded = true
            }, 2000)
            this.getCurrentRequest();
        },
        mounted(){

        },
        components: {
            RotateSquare5
        },
        props: {
            email: {
                type: String,
                required: true
            },
            staff_id: {
                type: String,
                required: true
            },
            staff_first_name: {
                type: String,
                required: true
            },
            staff_last_name: {
                type: String,
                required: true
            },
            staff_other_name: {
                type: String,
                required: true
            },
            form_type: {
                type: String,
                required: true
            },
            form_id: {
                type: String,
                required: true
            },

        },
        data() {
            return{
                details: {},
                valErrors: [],
                isLoading: false,
                componentLoaded: false,
                currentRequestStatus: '',
                remarks: '',
                modalState: '',
                title: '',
                type: "",
                isDeclined: false,
            }
        },
        methods: {
            openModal(state){
                this.modalState = state
                $('#terms').modal('show');
            },
            initiateSubmit(){
                //this.loadingAlert()
                $('#dlenhanceform').on('submit')
            },
            verifyDetails(){

                    let t = "<p>"
                    t +=  "On submission, it will be sent to the IT for approval<br/>"
                    t +=  "</p> "
                    Swal.fire({
                        title: 'Please verify details were entered correctly',
                        html: t,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Continue!'
                        }).then((result) => {
                        if (result.value) {
                            this.submit()
                        }
                    })
            },
            submit(){

                this.isLoading = true
                this.loadingAlert()
                this.details.form_type = this.form_type
                this.details.form_id = this.form_id;

                axios.post('../vpn_wifi_request', this.details).then((response)=> {
                    //
                   console.log(response.data)
                   if(response.status == 200 || response.status == 201){

                        if(response.data.hasOwnProperty('errors')){
                            this.valErrors = response.data.errors;
                            //this.isLoading = false;
                            this.status = "";
                            setTimeout(()=>{
                                this.errorAlert("It seems you didn't complete the fields correctly.", "Scroll to top of form for more details")
                            }, 2000)
                            //Swal.close();
                        }else{
                            setTimeout(()=>{
                                this.successAlert("Request submitted successfully", "Redirecting...please wait")
                            }, 1000)
                            setTimeout(()=>{
                                 //window.location.href = "home"
                                location.reload()
                            }, 3000)
                        }
                    }
                    else{
                        this.isLoading = false;
                        this.status = "";
                        this.errorAlert('Something went wrong! We couldn\'t submit your details','<a>please refresh or try again later</a>');
                    }
                }).catch((error)=> {
                    this.isLoading = false;
                    this.status = "";
                    this.errorAlert('Something went wrong! Error connecting to the server');
                })
            },
            /*
            * get the state of the most request
            */
            getCurrentRequest(){

                axios.get("../get_current_request?form_id="+this.form_id).then( response => {
                    console.log(response.data)
                    if(response.data != null){
                         console.log("not null");
                         let request = response.data;
                         console.log(request);

                         if(request.comment != null){
                             this.isDeclined = true
                             this.currentRequestStatus = "Your access request was declined \n"
                             this.remarks = "Reason: " +request.comment
                         }
                         else{
                            this.currentRequestStatus = "Your request is currently been processed!"
                         }
                    }


                }).catch(error => {
                    //this.infoAlert("Something went wrong", "couldn't retrieve current requests")
                })
            },
            loadingAlert(){
                Swal.fire({
                    html: '<i class="fa fa-circle-o-notch fa-spin text-primary fa-5x"></i>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    footer: 'processing request...please wait'
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
                    showConfirmButton: false,
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
            }
        }
    }
</script>

<style scoped>
    #add{
        font-size: 12px;
        line-height: 1.2;
    }
    .des{
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 1px;
    }
    hr{
        margin-top: 2px;
        margin-bottom: 2px;
    }
</style>
