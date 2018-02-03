import Main from '../components/modules/currency/Main';
import Config from '../components/pages/currency/Config';
import Statistics from '../components/pages/currency/Statistics';
import Water from '../components/pages/currency/Water';
import Cash from '../components/pages/currency/Cash';
import Currency from '../components/pages/currency/Currency';

export default {
    path: 'currency',
    component: Main,
    children: [
    	{path: '', component: Currency},
        {path: 'config', component: Config},
        {path: 'statistics', component: Statistics},
        {path: 'waters', component: Water},
        {path: 'cashes', component: Cash}
    ]
}