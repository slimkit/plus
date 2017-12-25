import Vue from 'vue';
import { sync } from 'vuex-router-sync';

// Filters
import * as filters from './filters';
for (const k in filters) {
  Vue.filter(k, filters[k]);
}

// Injections

import './components/commons';
import router from './router';
import store from './store';

import VueAMap from 'vue-amap';
Vue.use(VueAMap);

import ImgCropper from './components/modules/imgCropper';
Vue.use(ImgCropper);

// 初始化vue-amap
VueAMap.initAMapApiLoader({
  // 高德的key
  key: 'abb777bcccf278aa589076944112267e',
  // 插件集合
  plugin: [
  			'Geocoder',
  			'AMap.Geolocation', 
  			'AMap.Autocomplete', 
  			'AMap.PlaceSearch', 
  			'AMap.Scale', 
  			'AMap.OverView', 
  			'AMap.ToolBar', 
  			'AMap.MapType', 
  			'AMap.PolyEditor', 
  			'AMap.CircleEditor'
  			],
});
// Root component
import App from './App';

new Vue({

  router,
  store,

  el: '#app',
  render: h => h(App)

});

