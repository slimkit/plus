import Main from '../component/captcha/Main';
import Home from '../component/captcha/Home';
import GatWay from '../component/captcha/GatWay';
const routers = {
    path: 'captcha',
    component: Main,
    children: [
        { path: '', component: Home },
        { path: 'gatWay', component: GatWay }
    ]
};

export default routers;