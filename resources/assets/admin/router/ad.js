// ad router
import Main from '../component/ad/Main';
import Home from '../component/ad/Home';
import AddAd from '../component/ad/AddAd';

export default {
  path: 'ad',
  component: Main,
    children: [
        { path: '', component: Home },
        { path: 'add', component: AddAd },
    ],
};
