import Main from '../component/captcha/Main';
import Home from '../component/captcha/Home';
import Gateway from '../component/captcha/Gateway';
const routers = {
    path: 'captcha',
    component: Main,
    children: [
        { path: '', component: Home },
        { path: 'gateway', component: Gateway },
    ],
};

export default routers;