import Main from '../component/captcha/Main';
import Home from '../component/captcha/Home';
import Gateway from '../component/captcha/Gateway';
import Template from '../component/captcha/Template';

const routers = {
    path: 'captcha',
    component: Main,
    children: [
        { path: '', component: Home },
        { path: 'gateway', component: Gateway },
        { path: 'template', component: Template }
    ],
};

export default routers;