// ad router
import Main from '../component/report/Main';
import Home from '../component/report/Home';

export default {
  path: 'reports',
  component: Main,
    children: [
        { path: '', component: Home },
    ],
};
