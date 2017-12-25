import Vue from 'vue';
import VueRouter from 'vue-router';

// error pages.
import NotFound from './components/pages/NotFound';

// root 
import Main from './components/pages/Main';
// home
import Home from './components/pages/Home';
// category
import Category from './components/pages/Category';
// group
import Group from './components/pages/Group';
import GroupAdd from './components/pages/GroupAdd';
import GroupEdit from './components/pages/GroupEdit';
import GroupDetail from './components/pages/GroupDetail';
import GroupPost from './components/pages/GroupPost';
import GroupMember from './components/pages/GroupMember';

// comment
import PostComment from './components/pages/PostComment.vue';

// GroupRecommend
import GroupRecommend from './components/pages/GroupRecommend';

// Protocol
import GroupProtocol from './components/pages/GroupProtocol';

Vue.use(VueRouter);

const routes = [
  { path: "/", component: Main, children: [
    { path: '', component: Home },
    { path: 'categories', component: Category },
    { path: 'groups', component: Group },
    { path: 'protocol', component: GroupProtocol },
    { path: 'posts', component: GroupPost },
    { path: 'comments', component: PostComment },
    { path: 'recommends', component: GroupRecommend },
    { path: 'groups/add', component: GroupAdd },
    { path: 'groups/add', component: GroupAdd },
    { path: 'groups/:id', component: GroupDetail },
    { path: 'groups/:id/edit', component: GroupEdit },
    { path: 'groups/:id/members', component: GroupMember },
  ] },
  { path: '*', component: NotFound }
];

export default new VueRouter({
  routes,
  mode: 'hash',
});
