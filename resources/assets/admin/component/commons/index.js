import Vue from 'vue';
/**
 * table loading component.
 */
import TableLoading from './TableLoading';
/**
 * loding component
 */
import Loading from './Loading';
/**
 * paginator loading component.
 */
import OffsetPaginator from './OffsetPaginator';

Vue.component('loading', Loading);
Vue.component('table-loading', TableLoading);
Vue.component('offset-paginator', OffsetPaginator);