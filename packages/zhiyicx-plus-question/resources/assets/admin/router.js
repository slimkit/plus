import Vue from 'vue';
import VueRouter from 'vue-router';

import Main from './components/pages/Main';
import NotFound from './components/pages/NotFound';
import Home from './components/pages/Home';
import Question from './components/pages/Question';
import Answer from './components/pages/Answer';
import Comment from './components/pages/Comment';
import Topic from './components/pages/Topic';
import Excellence from './components/pages/Excellence';

Vue.use(VueRouter);

const routes = [
  { path: "/", component: Main, children: [
    { path: '', component: Home },
    { path: 'questions', component: Question },
    { path: 'answers', component: Answer },
    { path: 'comments', component: Comment },
    { path: 'topics', component: Topic },
    { path: 'excellences', component: Excellence },
  ] },
  { path: '*', component: NotFound }
];

export default new VueRouter({
  routes,
  mode: 'hash',
});
