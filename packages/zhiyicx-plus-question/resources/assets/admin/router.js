import Vue from 'vue';
import VueRouter from 'vue-router';

import Main from './components/pages/Main';
import NotFound from './components/pages/NotFound';
import Home from './components/pages/Home';
import Question from './components/pages/Question';
import QuestionEdit from './components/pages/QuestionEdit';
import Answer from './components/pages/Answer';
import Comment from './components/pages/Comment';
import Topic from './components/pages/Topic';
import Excellence from './components/pages/Excellence';
import TopicAdd from './components/pages/TopicAdd';
import TopicApplication from './components/pages/TopicApplication';
import TopicEdit from './components/pages/TopicEdit';
import TopicBase from './components/pages/TopicBase';
import TopicFollower from './components/pages/TopicFollower';
import TopicExpert from './components/pages/TopicExpert';

Vue.use(VueRouter);

const routes = [
  { path: "/", component: Main, children: [
    { path: '', component: Home },
    { path: 'questions', component: Question },
    { path: 'questions/:id(\\d+)', component: QuestionEdit },
    { path: 'answers', component: Answer },
    { path: 'comments', component: Comment },
    { path: 'topics', component: Topic },
    { path: 'excellences', component: Excellence },
    { path: 'topic/add', component: TopicAdd },
    { path: 'topics/applications', component: TopicApplication },
    { path: 'topics/:id(\\d+)/edit', component: TopicEdit },
    { path: 'topics/:id(\\d+)/followers', component: TopicFollower },
    { path: 'topics/:id(\\d+)/experts', component: TopicExpert },
    { path: 'topics/:id(\\d+)', component: TopicBase },
  ] },
  { path: '*', component: NotFound }
];

export default new VueRouter({
  routes,
  mode: 'hash',
});
