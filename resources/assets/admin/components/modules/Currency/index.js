import WaterSearch from './WaterSearch';
import WaterList from './WaterList';

import CashSearch from './CashSearch';
import CashList from './CashList.vue';

export default {
  [WaterSearch.name]: WaterSearch,
  [WaterList.name]: WaterList,

  [CashSearch.name]: CashSearch,
  [CashList.name]: CashList,
};
