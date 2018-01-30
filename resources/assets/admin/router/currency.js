import Main from '../components/modules/Currency/Main';
import Currency from '../components/pages/currency/Currency';
import Statistics from '../components/pages/currency/Statistics';
import Water from '../components/pages/currency/Water';
import Cash from '../components/pages/Currency/Cash';

export default {
    path: 'currency',
    component: Main,
    children: [
        {path: '', component: Currency},
        {path: 'statistics', component: Statistics},
        {path: 'waters', component: Water},
        {path: 'cashes', component: Cash}
    ]
}