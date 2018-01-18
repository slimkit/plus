import Vue from 'vue';
import VueRouter from 'vue-router';


// components.
import NewsList from './component/news/NewsList.vue';
import BinList from './component/news/BinList.vue';
import CategoriesList from './component/news/CategoriesList.vue';

import NewsSetting from './component/news/NewsSetting.vue';
import NewsPinneds from './component/news/NewsPinneds.vue';

import ManageNews from './component/news/ManageNews.vue';

import RecommendList from './component/news/RecommendList.vue';
import AddRecommend from './component/news/AddRecommend.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'hash',
    routes: [
        // root.
        {
            path: '/',
            redirect: 'setting'
        },
        // Setting router.
        {
            path: '/newslist',
            component: NewsList
        }, {
            path: '/categories',
            component: CategoriesList
        }, {
            path: '/recommend',
            component: RecommendList
        }, {
            path: '/manage/:newsId',
            component: ManageNews
        }, {
            path: '/manage',
            component: ManageNews
        }, {
            path: '/rec',
            component: AddRecommend
        }, {
            path: '/rec/:rid',
            component: AddRecommend
        }, {
            path: '/bin',
            component: BinList
        }, {
            path: '/setting',
            component: NewsSetting
        }, {
            path: '/pinneds',
            component: NewsPinneds
        }
    ]
});

export default router;