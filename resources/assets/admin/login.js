import 'assets/bootstrap';
import Vue from 'vue';
import LoginForm from 'admin/components/LoginForm';

/* eslint-disable no-new */
new Vue({
  el: '#app',
  template: '<LoginForm />',
  components: {
    LoginForm
  }
});
