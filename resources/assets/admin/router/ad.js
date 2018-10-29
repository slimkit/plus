// ad router
import Main from '../component/ad/Main';
import Home from '../component/ad/Home';

export default {
  path: 'ad',
  component: Main,
    children: [
        { path: '', component: Home },
    ],
};
