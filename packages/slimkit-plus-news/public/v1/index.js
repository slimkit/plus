import $ from "jquery";

import 'bootstrap-sass';
import Vue from 'vue';
import router from './router';
import App from './app.vue';

import ModelTips from './component/ModelTips/ModelTips'; // 右下角提示插件

import VueEditor from './component/edit.md/components/index'; // markdown编辑器

Vue.use(router);

const components = [
    VueEditor,
    ModelTips
];

// 添加组件
components.map(component => {
    Vue.component(component.name, component);
});

// 配置键盘快捷键
Vue.config.keyCodes.a = 65;
Vue.config.keyCodes.b = 66;
Vue.config.keyCodes.c = 67;
Vue.config.keyCodes.d = 68;
Vue.config.keyCodes.h = 72;
Vue.config.keyCodes.i = 73;
Vue.config.keyCodes.l = 76;
Vue.config.keyCodes.o = 79;
Vue.config.keyCodes.p = 80;
Vue.config.keyCodes.q = 81;
Vue.config.keyCodes.t = 84;
Vue.config.keyCodes.u = 85;

const app = new Vue({
    router,
    el: '#app',
    render: h => h(App)
});