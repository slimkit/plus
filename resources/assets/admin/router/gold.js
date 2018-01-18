// ad Gold
import Main from '../component/gold/Main';
import GoldType from '../component/gold/GoldType';
import AddGoldType from '../component/gold/AddGoldType';
import GoldRule from '../component/gold/GoldRule';
import AddGoldRule from '../component/gold/AddGoldRule';
import UpdateGoldRule from '../component/gold/UpdateGoldRule';
export default {
  path: 'gold',
  component: Main,
    children: [
        { path: '', component: GoldType },
        { path: 'types/add', component: AddGoldType },
        { path: 'rules', component: GoldRule },
        { path: 'rules/add', component: AddGoldRule },
        { path: 'rules/:id/update', component: UpdateGoldRule}
    ],
};
