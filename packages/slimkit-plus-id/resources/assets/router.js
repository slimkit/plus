import Vue from 'vue';
import VueRouter from 'vue-router';

import NotFound from './components/pages/NotFound';
import Main from './components/pages/Main';
import Client from './components/pages/Client';
import ClientCreate from './components/pages/ClientCreate';

Vue.use(VueRouter);

const routes = [
  { path: '/', component: Main, children: [
    { path: '/', component: Client },
    { path: '/client-add', component: ClientCreate },
  ] },
  { path: '*', component: NotFound },
];

export default new VueRouter({
  routes,
  mode: 'hash',
});
