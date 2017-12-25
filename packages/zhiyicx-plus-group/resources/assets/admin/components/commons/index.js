import Vue from 'vue';

import NavBar from './NavBar';
import Panle from './Panle';
import Table from './Table';
import OffsetPaginator from './OffsetPaginator';
import Modal from './Modal';

Vue.component('ui-panle', Panle);
Vue.component('ui-nav-bar', NavBar);
Vue.component('ui-table', Table);
Vue.component('ui-paginator', OffsetPaginator);
Vue.component('ui-modal', Modal);

