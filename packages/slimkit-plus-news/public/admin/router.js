import Vue from 'vue';
import VueRouter from 'vue-router';


// components.
import NewsList from './components/pages/NewsList';
import CatesList from './components/pages/CatesList';
import DeleteList from './components/pages/DeleteList';

import NewsSetting from './components/pages/NewsSetting';
import NewsPinneds from './components/pages/NewsPinneds';

import ManageNews from './components/pages/ManageNews';

// import RecommendList from './components/pages/RecommendList';
// import AddRecommend from './components/pages/AddRecommend';

Vue.use(VueRouter);

const routes = [
    { path: '/', redirect: 'setting' },
    { path: '/newslist', component: NewsList },
    { path: '/deletelist', component: DeleteList },
    { path: '/cates', component: CatesList },
    { path: '/manage', component: ManageNews },
    { path: '/manage/:newsID', component: ManageNews },
    // { path: '/bin', component: BinList },
    // { path: '/rec', component: AddRecommend },
    // { path: '/rec/:rid', component: AddRecommend },
    // { path: '/recommend', component: RecommendList },

    { path: '/setting', component: NewsSetting },
    { path: '/pinneds', component: NewsPinneds }
];
export default new VueRouter({
    routes,
    mode: 'hash',
});