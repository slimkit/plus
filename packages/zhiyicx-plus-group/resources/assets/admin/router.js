import Vue from 'vue';
import VueRouter from 'vue-router';

// root
import WrapComponent from './components/pages/wrap-component';

Vue.use(VueRouter);

const routes = [
  { path: "", component: WrapComponent},
];

export default new VueRouter({
  routes,
  mode: 'hash',
});
