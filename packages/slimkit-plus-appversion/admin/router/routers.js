import Home from '../component/Home.vue';
import Add from '../component/Add.vue';

const routers = [
  {
    path: '/',
    redirect: '/home'
  },
	{
    path: '/home',
    component: Home,
    name: 'home'
	},
  {
    path: '/add',
    component: Add,
    name: 'add'
  }
];

export default routers;