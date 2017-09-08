/**
 * Created by anguer on 2017/4/11.
 * This is plugin entry.
 */
import VueEditor from './components/index';

const components = [
  VueEditor
];

const install = function (Vue, opts = {}) {
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

  // 添加组件
  components.map(component => {
    Vue.component(component.name, component);
  });
};

/* istanbul ignore if */
if (typeof window !== 'undefined' && window.Vue) {
  install(window.Vue);
}

export default {install, VueEditor}; // eslint-disable-line no-undef
