import WaterSearch from './WaterSearch';
import WaterList from './WaterList';

import CashSearch from './CashSearch';
import CashList from './CashList';

import CurrencySearch from './CurrencySearch';
import CurrencyList from './CurrencyList';

export default {
  [WaterSearch.name]: WaterSearch,
  [WaterList.name]: WaterList,

  [CashSearch.name]: CashSearch,
  [CashList.name]: CashList,

  [CurrencySearch.name]: CurrencySearch,
  [CurrencyList.name]: CurrencyList,
  
};
