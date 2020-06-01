/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))



Vue.component('profile-component', require('./components/ProfileComponent.vue').default);


/*
* Auth
*/
Vue.component('register-component', require('./components/Auth/RegisterComponent.vue').default);
Vue.component('login-component', require('./components/Auth/LoginComponent.vue').default);


/*
* Forms : Access
*/
Vue.component('form-enhance-sage-component', require('./components/Forms/Access/FormEnhanceSageComponent.vue').default);
Vue.component('form-vpn-wifi-component', require('./components/Forms/Access/FormVpnWifiComponent.vue').default);

/*
* Forms: Finance
*/
Vue.component('form-store-received-advice-component', require('./components/Forms/Finance/FormStoreReceivedAdviceComponent.vue').default);
Vue.component('form-stores-credit-note-component', require('./components/Forms/Finance/FormStoresCreditNoteComponent.vue').default);
Vue.component('form-stock-requisition-consignment-component', require('./components/Forms/Finance/FormStockRequisitionConsignmentComponent.vue').default);
Vue.component('form-stock-requisition-issue-component', require('./components/Forms/Finance/FormStockRequisitionIssueComponent.vue').default);


/*
* Approval Pages : Access
*/
Vue.component('approval-enhance-sage-component', require('./components/Approvals/Access/ApprovalEnhanceSageComponent.vue').default);
Vue.component('approval-vpn-wifi-component', require('./components/Approvals/Access/ApprovalVpnWifiComponent.vue').default);

/*
* Approval Pages: Finance
*/
Vue.component('approval-store-received-advice-component', require('./components/Approvals/Finance/ApprovalStoreReceivedAdviceComponent.vue').default);
Vue.component('approval-stores-credit-note-component', require('./components/Approvals/Finance/ApprovalStoresCreditNoteComponent.vue').default);
Vue.component('approval-stock-consignment-note-component', require('./components/Approvals/Finance/ApprovalStockConsignmentNoteComponent.vue').default);
Vue.component('approval-stock-issue-note-component', require('./components/Approvals/Finance/ApprovalStockIssueNoteComponent.vue').default);



/*
* Dashboard Pages
*/
//Vue.component('request-component', require('./ApprovalDashboard/components/ItDashboardComponent.vue').default);
Vue.component('requests-component', require('./components/Dashboards/RequestsComponent.vue').default);
Vue.component('settings-component', require('./components/Dashboards/SettingsComponent.vue').default);
Vue.component('root-component', require('./components/Dashboards/RootComponent.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
