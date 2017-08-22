webpackJsonp([0],[
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.createAPI = exports.createRequestURI = undefined;

var _axios = __webpack_require__(49);

var _axios2 = _interopRequireDefault(_axios);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var _window$TS = window.TS,
    baseURL = _window$TS.baseURL,
    api = _window$TS.api; // This "TS" variable is set from the global variables in the template.

// Export a method to create the requested address.

var createRequestURI = exports.createRequestURI = function createRequestURI(PATH) {
    return baseURL + '/' + PATH;
};

// Created the request address of API.
var createAPI = exports.createAPI = function createAPI(PATH) {
    return api + '/' + PATH;
};

_axios2.default.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

var token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    _axios2.default.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

exports.default = _axios2.default;

// ...
// Read the https://github.com/mzabriskie/axios

/***/ }),
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
// The file is defined type.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
// ---------------------------------------

// The const is defined update user data type.
var USER_UPDATE = exports.USER_UPDATE = 'user/update';

// The const is defined delete user data type.
var USER_DELETE = exports.USER_DELETE = 'user/delete';

// Defined [settings/site].
var SETTINGS_SITE_UPDATE = exports.SETTINGS_SITE_UPDATE = 'settings/site/UPDATE';

var SETTINGS_SYSTEM_UPDATE = exports.SETTINGS_SYSTEM_UPDATE = 'settings/system/UPDATE';
// export const SETTINGS_SITE_TITLE = 'settings/site/title';
// export const SETTINGS_SITE_KEYWORDS = 'settings/site/keywords';
// export const SETTINGS_SITE_DESCRIPTION = 'settings/site/description';
// export const SETTINGS_SITE_ICP = 'settings/site/icp';

// Defined [settings/area]
var SETTINGS_AREA_APPEND = exports.SETTINGS_AREA_APPEND = 'settings/area/APPEND';
var SETTINGS_AREA_CHANGEITEM = exports.SETTINGS_AREA_CHANGEITEM = 'settings/area/CHANGE-ITEM';
var SETTINGS_AREA_CHANGE = exports.SETTINGS_AREA_CHANGE = 'settings/area/CHANGE';
var SETTINGS_AREA_DELETE = exports.SETTINGS_AREA_DELETE = 'settings/area/DELETE';

// Defined [users/]
var USERS_CHANGE = exports.USERS_CHANGE = 'users/CHANGE';

// Defined [manages/SET]
var MANAGES_SET = exports.MANAGES_SET = 'manages/SET';

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
// The file is defined getter types.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
// ---------------------------------------

// The const is defined [user/logged] type.
var USER_LOGGED = exports.USER_LOGGED = 'user/logged';

// The const is defined [user/user] type.
var USER = exports.USER = 'user/user';

// the const is defined [user/datas] type.
var USER_DATA = exports.USER_DATA = 'user/datas';

// The const is defined [settings/site]
var SETTINGS_SITE = exports.SETTINGS_SITE = 'settings/site';

var SETTINGS_SYSTEM = exports.SETTINGS_SYSTEM = 'settings/system';
// export const SITE_TITLE = 'settings/site/title';
// export const SITE_KEYWORDS = 'settings/site/keywords';
// export const SITE_DESCRIPTION = 'settings/site/description';
// export const SITE_ICP = 'settings/site/icp';

// The conts is defined [setting/area]
var SETTINGS_AREA = exports.SETTINGS_AREA = 'settings/area';

// Users.
var USERS = exports.USERS = 'user/GET-LIST';

// Defined [manages/GET]
var MANAGES_GET = exports.MANAGES_GET = 'manages/GET';

/***/ }),
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _vue = __webpack_require__(9);

var _vue2 = _interopRequireDefault(_vue);

var _vuex = __webpack_require__(8);

var _vuex2 = _interopRequireDefault(_vuex);

var _user = __webpack_require__(42);

var _user2 = _interopRequireDefault(_user);

var _site = __webpack_require__(43);

var _site2 = _interopRequireDefault(_site);

var _area = __webpack_require__(44);

var _area2 = _interopRequireDefault(_area);

var _manages = __webpack_require__(45);

var _manages2 = _interopRequireDefault(_manages);

var _system = __webpack_require__(46);

var _system2 = _interopRequireDefault(_system);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_vue2.default.use(_vuex2.default);

// modules.
// The file is store.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
// ---------------------------------------

var modules = {
  user: _user2.default,
  site: _site2.default,
  area: _area2.default,
  manages: _manages2.default,
  system: _system2.default
};

var store = new _vuex2.default.Store({
  modules: modules
});

exports.default = store;

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.requireAuth = requireAuth;
exports.loggedAuth = loggedAuth;

var _store = __webpack_require__(12);

var _store2 = _interopRequireDefault(_store);

var _types = __webpack_require__(5);

var _getterTypes = __webpack_require__(6);

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var login = function login(access, password) {
  return _request2.default.post((0, _request.createRequestURI)('login'), { account: access, password: password }, { validateStatus: function validateStatus(status) {
      return status === 201;
    } });
};

/**
 * 返回用户是否已经登陆
 *
 * @return {[type]} [description]
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
var logged = function logged() {
  return _store2.default.getters[_getterTypes.USER_LOGGED];
};

/**
 * 退出登录方法
 *
 * @param {Function} cb 回掉方法
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
var logout = function logout(cb) {
  _store2.default.dispatch(_types.USER_DELETE, cb);
};

/**
 * auth验证器.
 *
 * @param {object} to 要跳转的对象地址
 * @param {object} from 上一层的对象地址
 * @param {Function} next 下一步执行回掉
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function requireAuth(to, from, next) {
  if (!logged()) {
    next({
      path: '/login',
      query: {
        redirect: to.fullPath
      }
    });
  } else {
    next();
  }
};

/**
 * 登录情况下不允许访问的路由前置验证
 *
 * @param {object} to 要跳转的对象地址
 * @param {object} from 上一层的对象地址
 * @param {Function} next 继续执行的异步回调
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function loggedAuth(to, from, next) {
  if (logged()) {
    next({
      path: from.fullPath
    });
  } else {
    next();
  }
};

exports.default = {
  login: login, logged: logged, logout: logout
};

/***/ }),
/* 14 */,
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */,
/* 19 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_1KZThGFAzqMZHWZXSnyyqW_0","containerAround":"_3mmv8-PuOxpsgKq-h6Hil__0"};

/***/ }),
/* 20 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_1z4RhTrWlanBvCuyqi6fBV_0","loadding":"_1mZzobTI0IkBqSC5lgNrlj_0","loaddingIcon":"_4_lrzbUDzh31qV7YzKgKI_0","breadcrumbNotActvie":"_179TtcPFaYQhDG3qbBUqaq_0","areaTab":"_3yNydw7ENv-B_wPVTMdCNN_0"};

/***/ }),
/* 21 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_3a3ApJ5vBfeQkOR5SQTVKy_0","loadding":"_1DqvaIgu-DhLYayOrTp0V2_0","loaddingIcon":"_123nZVdWRQopOtyatixg0B_0","areaTab":"_1qu9NINeJ6UFglSbZDvW0Z_0"};

/***/ }),
/* 22 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"loadding":"_1SBfTxGdVlwvRXPZ290q0F_0","loaddingIcon":"_3WyhphwJSN8v6fmcgrI3ak_0","link":"yTd8NJakd34911aqfuYTj_0"};

/***/ }),
/* 23 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"loadding":"_20-JAiaoX4MD44_ysQWja2_0","loaddingIcon":"_3tzTsLCE6bNIw9sUQV3isi_0","link":"_2TF2_fep6E9dEpV2_z7KD_0"};

/***/ }),
/* 24 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"nav":"_3aSlbEE3z6zx2l_uo3HI0c_0"};

/***/ }),
/* 25 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"roleItem":"_31HzegN0N_MKNx8ek-ewdS_0"};

/***/ }),
/* 26 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_3dmnCvozpw3m8fFish2esW_0","loadding":"_2r5QYXEH13EfeIfzZcabFJ_0","loaddingIcon":"_1z3mStIIgVBALloTE-yh_I_0"};

/***/ }),
/* 27 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_1rfKjF_Jbsrt-NPuVUF68J_0","loadding":"_12izD6_COs4Sv6Mf_3NeY4_0","loaddingIcon":"_2QJw2UnKca7-Q7P6_KVdU0_0"};

/***/ }),
/* 28 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"lWxMHASIe22tHRzHw5Jrt_0","loadding":"_24gnTDmaU_22fnRx6b_rdh_0","loaddingIcon":"_1XxUsqzTSJmFcRgogAR05X_0"};

/***/ }),
/* 29 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"alert":"_18zkCHcsD6BvYfk_RfuVLu_0","modal":"gHPYWsfLJKHf2gOJRSGds_0","modalContent":"_1O4OTL2FvYuh6CpTMSyGwW_0","modalIcon":"_2mwThkOL24of1sO1PKbvpu_0"};

/***/ }),
/* 30 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"alert":"kU2xO7mSoPYQiLDZxw6mY_0"};

/***/ }),
/* 31 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"input":"_1gk48jgPMkr-DqCx-15Qy5_0","labelBox":"_2xxNAnwVXAMC2m7Y3v7VB3_0","label":"_3sxbqz7ysithx58bvO8I0f_0","add":"_2-_SJqKm_4d5oVmwj3r0oY_0","labelDelete":"_17dl3QPDXrgRtLOluH_w6x_0","addLabel":"_3X6sAnYNcDwYl7F14RnCP7_0","alert":"_3il_emhs4MbYGIIyQi9Cfl_0"};

/***/ }),
/* 32 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"alert":"_1-jHVXNOObbINCoPz14_FI_0"};

/***/ }),
/* 33 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"alert":"_2GDhN1P5bM-BD821OsSYyg_0"};

/***/ }),
/* 34 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"appIframe":"_35pOO3wniuJQ-kNkV4ib0k_0"};

/***/ }),
/* 35 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"appIframe":"_3ktd3GaQ51QNxBbNqAhp9r_0"};

/***/ }),
/* 36 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.store = exports.router = exports.app = undefined;

var _vue = __webpack_require__(9);

var _vue2 = _interopRequireDefault(_vue);

var _vuexRouterSync = __webpack_require__(37);

var _App = __webpack_require__(38);

var _App2 = _interopRequireDefault(_App);

var _store = __webpack_require__(12);

var _store2 = _interopRequireDefault(_store);

var _router = __webpack_require__(47);

var _router2 = _interopRequireDefault(_router);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// jQuery and Bootstrap-SASS
// -------------------------
// Questions: Why use CommonJS require?
// Answer: Because es6 module export lead to jquery plug-in can not run.
// -------------------------
window.$ = window.jQuery = __webpack_require__(190);
__webpack_require__(191);

// sync the router with the vuex store.
// this registers `store.state.route`
(0, _vuexRouterSync.sync)(_store2.default, _router2.default);

// create the app instance.
// here we inject the router and store to all child components,
// making them available everywhere as `this.$router` and `this.$store`.
var app = new _vue2.default({
  router: _router2.default,
  store: _store2.default,
  el: '#app',
  render: function render(h) {
    return h(_App2.default);
  }
});

exports.app = app;
exports.router = _router2.default;
exports.store = _store2.default;

/***/ }),
/* 37 */,
/* 38 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_577de07b_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_App_vue__ = __webpack_require__(41);
var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(39)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_577de07b_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_App_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/App.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] App.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-577de07b", Component.options)
  } else {
    hotAPI.reload("data-v-577de07b", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 39 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 40 */,
/* 41 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    attrs: {
      "id": "app"
    }
  }, [_c('router-view')], 1)
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-577de07b", esExports)
  }
}

/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _mutations, _actions, _getters;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; // The module of The auth user.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
// --------------------------

var _types = __webpack_require__(5);

var _getterTypes = __webpack_require__(6);

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

// Use the server in data.
var logged = window.TS.logged;
var user = window.TS.user;

// sync window data.
var syncWindow = function syncWindow(user) {
  window.TS.logged = !!user;
  window.TS.user = user;
};

// create state.
var state = {
  logged: logged,
  user: user
};

// create mutations.
var mutations = (_mutations = {}, _defineProperty(_mutations, _types.USER_UPDATE, function (state, user) {
  state.logged = !!user;
  state.user = _extends({}, state.user, user);
  syncWindow(state.user);
}), _defineProperty(_mutations, _types.USER_DELETE, function (state) {
  state.logged = false;
  state.user = null;
  syncWindow(state.user);
}), _mutations);

// Created actions.
var actions = (_actions = {}, _defineProperty(_actions, _types.USER_UPDATE, function (context, cb) {
  return cb(function (user) {
    return context.commit(_types.USER_UPDATE, user);
  }, context);
}), _defineProperty(_actions, _types.USER_DELETE, function (context, cb) {
  return cb(function () {
    return context.commit(_types.USER_DELETE);
  }, context);
}), _actions);

// Created getters.
var getters = (_getters = {}, _defineProperty(_getters, _getterTypes.USER_LOGGED, function (_ref) {
  var logged = _ref.logged;
  return logged;
}), _defineProperty(_getters, _getterTypes.USER, function (_ref2) {
  var user = _ref2.user;
  return user;
}), _defineProperty(_getters, _getterTypes.USER_DATA, function (_ref3) {
  var _ref3$user = _ref3.user,
      user = _ref3$user === undefined ? {} : _ref3$user;
  var _user$datas = user.datas,
      datas = _user$datas === undefined ? [] : _user$datas;

  var newData = {};
  datas.forEach(function (data) {
    newData[data.profile] = {
      display: data.profile_name,
      value: data.pivot.user_profile_setting_data,
      type: data.type,
      options: data.default_options,
      updated_at: data.updated_at
    };
  });

  return newData;
}), _getters);

// create store.
var userStore = {
  state: state,
  mutations: mutations,
  getters: getters,
  actions: actions
};

exports.default = userStore;

/***/ }),
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _types = __webpack_require__(5);

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; } /**
                                                                                                                                                                                                                   * The store of settings site info.
                                                                                                                                                                                                                   *
                                                                                                                                                                                                                   * @author Seven Du <shiweidu@outlook.com>
                                                                                                                                                                                                                   */

// import { SETTINGS_SITE } from '../getter-types';

var state = {
  name: '',
  keywords: '',
  description: '',
  icp: ''
};

var mutations = _defineProperty({}, _types.SETTINGS_SITE_UPDATE, function (state, site) {
  Object.keys(site).forEach(function (key) {
    state[key] = site[key];
  });
});

var siteStore = {
  state: state,
  mutations: mutations
};

exports.default = siteStore;

/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _mutations, _actions;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _types = __webpack_require__(5);

var _getterTypes = __webpack_require__(6);

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } } /**
                                                                                                                                                                                                     * The store of settings area margin.
                                                                                                                                                                                                     *
                                                                                                                                                                                                     * @author Seven Du <shiweidu@outlook.com>
                                                                                                                                                                                                     */

var state = {
  list: []
};

var mutations = (_mutations = {}, _defineProperty(_mutations, _types.SETTINGS_AREA_APPEND, function (state, item) {
  state.list = [].concat(_toConsumableArray(state.list), [item]);
}), _defineProperty(_mutations, _types.SETTINGS_AREA_CHANGEITEM, function (state, item) {
  var list = state.list;
  state.list = list.map(function (_item) {
    if (_item.id === item.id) {
      return _extends({}, _item, item);
    }

    return _item;
  });
}), _defineProperty(_mutations, _types.SETTINGS_AREA_CHANGE, function (state, list) {
  state.list = [].concat(_toConsumableArray(list));
}), _defineProperty(_mutations, _types.SETTINGS_AREA_DELETE, function (state, id) {
  var list = [];
  state.list.forEach(function (area) {
    if (area.id !== id) {
      list.push(area);
    }
  });

  state.list = list;
}), _mutations);

var getters = _defineProperty({}, _getterTypes.SETTINGS_AREA, function (state) {
  return state.list;
});

var actions = (_actions = {}, _defineProperty(_actions, _types.SETTINGS_AREA_APPEND, function (context, cb) {
  return cb(function (item) {
    return context.commit(_types.SETTINGS_AREA_APPEND, item);
  }, context);
}), _defineProperty(_actions, _types.SETTINGS_AREA_CHANGEITEM, function (context, cb) {
  return cb(function (item) {
    return context.commit(_types.SETTINGS_AREA_CHANGEITEM, item);
  }, context);
}), _defineProperty(_actions, _types.SETTINGS_AREA_CHANGE, function (context, cb) {
  return cb(function (list) {
    return context.commit(_types.SETTINGS_AREA_CHANGE, list);
  }, context);
}), _defineProperty(_actions, _types.SETTINGS_AREA_DELETE, function (context, cb) {
  return cb(function (id) {
    return context.commit(_types.SETTINGS_AREA_DELETE, id);
  }, context);
}), _actions);

var areaStore = {
  state: state,
  mutations: mutations,
  getters: getters,
  actions: actions
};

exports.default = areaStore;

/***/ }),
/* 45 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _types = __webpack_require__(5);

var _getterTypes = __webpack_require__(6);

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var state = {
  manages: []
};

var mutations = _defineProperty({}, _types.MANAGES_SET, function (state, manages) {
  state.manages = manages;
});

var getters = _defineProperty({}, _getterTypes.MANAGES_GET, function (state) {
  return state.manages;
});

var actions = _defineProperty({}, _types.MANAGES_SET, function (context, cb) {
  return cb(function (manages) {
    return context.commit(_types.MANAGES_SET, manages);
  }, context);
});

exports.default = {
  state: state,
  mutations: mutations,
  getters: getters,
  actions: actions
};

/***/ }),
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _types = __webpack_require__(5);

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var state = {
  php_version: '',
  os: '',
  server: '',
  db: '',
  port: '',
  root: '',
  agent: '',
  protocol: '',
  method: '',
  laravel_version: '',
  max_upload_size: '',
  execute_time: '',
  server_date: '',
  local_date: '',
  domain_ip: '',
  user_ip: '',
  disk: ''
};

var mutations = _defineProperty({}, _types.SETTINGS_SYSTEM_UPDATE, function (state, site) {
  Object.keys(site).forEach(function (key) {
    state[key] = site[key];
  });
});

var siteSystem = {
  state: state,
  mutations: mutations
};

exports.default = siteSystem;

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _vue = __webpack_require__(9);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(48);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _auth = __webpack_require__(13);

var _setting = __webpack_require__(69);

var _setting2 = _interopRequireDefault(_setting);

var _user = __webpack_require__(99);

var _user2 = _interopRequireDefault(_user);

var _sms = __webpack_require__(123);

var _sms2 = _interopRequireDefault(_sms);

var _wallet = __webpack_require__(132);

var _wallet2 = _interopRequireDefault(_wallet);

var _ad = __webpack_require__(161);

var _ad2 = _interopRequireDefault(_ad);

var _paid = __webpack_require__(164);

var _paid2 = _interopRequireDefault(_paid);

var _Login = __webpack_require__(170);

var _Login2 = _interopRequireDefault(_Login);

var _Home = __webpack_require__(174);

var _Home2 = _interopRequireDefault(_Home);

var _Package = __webpack_require__(184);

var _Package2 = _interopRequireDefault(_Package);

var _Component = __webpack_require__(187);

var _Component2 = _interopRequireDefault(_Component);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// components.


// routes.
_vue2.default.use(_vueRouter2.default);

var baseRoutes = [{ path: '', redirect: '/setting' }, { path: 'package/:key', component: _Package2.default }, { path: 'component/:component(.*)', component: _Component2.default }];

var childrenRoutes = [_setting2.default, _user2.default, _sms2.default, _wallet2.default, _ad2.default, _paid2.default];

var router = new _vueRouter2.default({
  mode: 'hash',
  // base: '/admin/',
  routes: [{
    path: '/',
    component: _Home2.default,
    beforeEnter: _auth.requireAuth,
    children: [].concat(baseRoutes, childrenRoutes)
  }, { path: '/login', component: _Login2.default, beforeEnter: _auth.loggedAuth }]
});

exports.default = router;

/***/ }),
/* 48 */,
/* 49 */,
/* 50 */,
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */,
/* 56 */,
/* 57 */,
/* 58 */,
/* 59 */,
/* 60 */,
/* 61 */,
/* 62 */,
/* 63 */,
/* 64 */,
/* 65 */,
/* 66 */,
/* 67 */,
/* 68 */,
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Setting = __webpack_require__(70);

var _Setting2 = _interopRequireDefault(_Setting);

var _Base = __webpack_require__(73);

var _Base2 = _interopRequireDefault(_Base);

var _Area = __webpack_require__(76);

var _Area2 = _interopRequireDefault(_Area);

var _Hots = __webpack_require__(80);

var _Hots2 = _interopRequireDefault(_Hots);

var _Mail = __webpack_require__(83);

var _Mail2 = _interopRequireDefault(_Mail);

var _SendMail = __webpack_require__(86);

var _SendMail2 = _interopRequireDefault(_SendMail);

var _Server = __webpack_require__(89);

var _Server2 = _interopRequireDefault(_Server);

var _Tags = __webpack_require__(93);

var _Tags2 = _interopRequireDefault(_Tags);

var _TagCategories = __webpack_require__(96);

var _TagCategories2 = _interopRequireDefault(_TagCategories);

var _AddTag = __webpack_require__(225);

var _AddTag2 = _interopRequireDefault(_AddTag);

var _UpdateTag = __webpack_require__(241);

var _UpdateTag2 = _interopRequireDefault(_UpdateTag);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var settingRouter = {
  path: 'setting',
  component: _Setting2.default,
  children: [{
    path: '',
    component: _Base2.default
  }, {
    path: 'area',
    component: _Area2.default
  }, {
    path: 'hots',
    component: _Hots2.default
  }, {
    path: 'mail',
    component: _Mail2.default
  }, {
    path: 'sendmail',
    component: _SendMail2.default
  }, {
    path: 'tags',
    component: _Tags2.default
  }, {
    path: 'tag-categories',
    component: _TagCategories2.default
  }, {
    path: 'addtag',
    component: _AddTag2.default
  }, {
    path: 'updatetag/:tag_id',
    component: _UpdateTag2.default
  }, {
    path: 'server',
    component: _Server2.default
  }]
}; //
// The file is defined "/setting" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//
exports.default = settingRouter;

/***/ }),
/* 70 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_9bb730d0_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Setting_vue__ = __webpack_require__(72);
var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(71)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_9bb730d0_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Setting_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/Setting.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Setting.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-9bb730d0", Component.options)
  } else {
    hotAPI.reload("data-v-9bb730d0", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 71 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 72 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "setting-container"
  }, [_c('ul', {
    staticClass: "nav nav-tabs setting-nav"
  }, [_c('router-link', {
    attrs: {
      "to": "/setting",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("基本信息")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/setting/area",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("地区库")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/setting/mail",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("邮件")])]), _vm._v(" "), _c('li', {
    staticClass: "dropdown",
    attrs: {
      "role": "presentation"
    }
  }, [_vm._m(0), _vm._v(" "), _c('ul', {
    staticClass: "dropdown-menu"
  }, [_c('router-link', {
    attrs: {
      "tag": "li",
      "active-class": "active",
      "to": "/setting/tags"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("标签列表")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "tag": "li",
      "active-class": "active",
      "to": "/setting/tag-categories"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("标签分类")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "tag": "li",
      "active-class": "active",
      "to": "/setting/addtag"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("添加标签")])])], 1)]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/setting/server",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("服务器信息")])])], 1), _vm._v(" "), _c('router-view')], 1)
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('a', {
    staticClass: "dropdown-toggle",
    attrs: {
      "data-toggle": "dropdown",
      "href": "#",
      "role": "button",
      "aria-haspopup": "true",
      "aria-expanded": "false"
    }
  }, [_vm._v("\n        标签设置 "), _c('span', {
    staticClass: "caret"
  })])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-9bb730d0", esExports)
  }
}

/***/ }),
/* 73 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Base_vue__ = __webpack_require__(74);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Base_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Base_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_7334d518_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Base_vue__ = __webpack_require__(75);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-7334d518\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Base.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(19)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-7334d518")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(19)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Base_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_7334d518_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Base_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/Base.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Base.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7334d518", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-7334d518", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 74 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _types = __webpack_require__(5);

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var settingBase = {
  data: function data() {
    return {
      loadding: true,
      error: false,
      error_message: '重新加载',
      message: '提交'
    };
  },
  computed: {
    name: {
      get: function get() {
        return this.$store.state.site.name;
      },
      set: function set(name) {
        this.$store.commit(_types.SETTINGS_SITE_UPDATE, { name: name });
      }
    },
    keywords: {
      get: function get() {
        return this.$store.state.site.keywords;
      },
      set: function set(keywords) {
        this.$store.commit(_types.SETTINGS_SITE_UPDATE, { keywords: keywords });
      }
    },
    description: {
      get: function get() {
        return this.$store.state.site.description;
      },
      set: function set(description) {
        this.$store.commit(_types.SETTINGS_SITE_UPDATE, { description: description });
      }
    },
    icp: {
      get: function get() {
        return this.$store.state.site.icp;
      },
      set: function set(icp) {
        this.$store.commit(_types.SETTINGS_SITE_UPDATE, { icp: icp });
      }
    }
  },
  methods: {
    requestSiteInfo: function requestSiteInfo() {
      var _this = this;

      _request2.default.get((0, _request.createRequestURI)('site/baseinfo'), {
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;

        _this.$store.commit(_types.SETTINGS_SITE_UPDATE, _extends({}, data));
        _this.loadding = false;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message,
            message = _ref2$response$data$m === undefined ? '加载失败' : _ref2$response$data$m;

        _this.loadding = false;
        _this.error = true;
        window.alert(message);
        // this.error_message
      });
    },
    submit: function submit() {
      var _this2 = this;

      var name = this.name,
          keywords = this.keywords,
          description = this.description,
          icp = this.icp;

      this.loadding = true;
      _request2.default.patch((0, _request.createRequestURI)('site/baseinfo'), { name: name, keywords: keywords, description: description, icp: icp }, {
        validateStatus: function validateStatus(status) {
          return status === 201;
        }
      }).then(function () {
        _this2.message = '执行成功';
        _this2.loadding = false;
        setTimeout(function () {
          _this2.message = '提交';
        }, 1000);
      }).catch(function (_ref3) {
        var _ref3$response = _ref3.response;
        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data;
        _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
        var _ref3$response$data$m = _ref3$response$data.message,
            message = _ref3$response$data$m === undefined ? '加载失败' : _ref3$response$data$m;

        _this2.loadding = false;
        _this2.error = true;
        _this2.error_message = message;
        setTimeout(function () {
          _this2.error = false;
          _this2.error_message = '重新加载';
        }, 1000);
      });
    }
  },
  created: function created() {
    this.requestSiteInfo();
  }
};

exports.default = settingBase;

/***/ }),
/* 75 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('form', {
    staticClass: "form-horizontal",
    class: _vm.$style.container,
    on: {
      "submit": function($event) {
        $event.preventDefault();
        _vm.submit($event)
      }
    }
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "site-name"
    }
  }, [_vm._v("应用名称")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.name),
      expression: "name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "site-name",
      "aria-describedby": "site-name-help-block",
      "placeholder": "输入网站标题"
    },
    domProps: {
      "value": (_vm.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "site-name-help-block"
    }
  }, [_vm._v("\n      应用名称，将在网页中显示在title的基本信息。也是搜索引擎为搜录做筛选标题的重要信息。\n    ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "site-keywords"
    }
  }, [_vm._v("关键词")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.keywords),
      expression: "keywords"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "site-keywords",
      "aria-describedby": "site-keywords-help-block",
      "placeholder": "网站关键词"
    },
    domProps: {
      "value": (_vm.keywords)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.keywords = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(0)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "site-description"
    }
  }, [_vm._v("描述")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.description),
      expression: "description"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "site-description",
      "aria-describedby": "site-description-help-block",
      "placeholder": "网站描述"
    },
    domProps: {
      "value": (_vm.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.description = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "site-description-help-block"
    }
  }, [_vm._v("\n      描述用于简单的介绍站点，在搜索引擎中用于搜索结果的概述。\n    ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "site-icp"
    }
  }, [_vm._v("ICP 备案信息")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.icp),
      expression: "icp"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "site-icp",
      "aria-describedby": "site-icp-help-block",
      "placeholder": "网站描述"
    },
    domProps: {
      "value": (_vm.icp)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.icp = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "site-icp-help-block"
    }
  }, [_vm._v("\n      填写 ICP 备案的信息，例如: 浙ICP备xxxxxxxx号\n    ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-10"
  }, [(_vm.loadding) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.containerAround
  })]) : (_vm.error) ? _c('button', {
    staticClass: "btn btn-danger",
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.requestSiteInfo($event)
      }
    }
  }, [_vm._v(_vm._s(_vm.error_message))]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit"
    }
  }, [_vm._v(_vm._s(_vm.message))])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "site-keywords-help-block"
    }
  }, [_vm._v("\n      网站关键词，是通过搜索引擎检索网站的重要信息，多个关键词使用英文半角符号“"), _c('strong', [_vm._v(",")]), _vm._v("”分割。\n    ")])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7334d518", esExports)
  }
}

/***/ }),
/* 76 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Area_vue__ = __webpack_require__(77);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Area_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Area_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_768160d4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Area_vue__ = __webpack_require__(79);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-768160d4\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Area.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(20)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-768160d4")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(20)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Area_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_768160d4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Area_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/Area.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Area.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-768160d4", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-768160d4", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 77 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _vuex = __webpack_require__(8);

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

var _getterTypes = __webpack_require__(6);

var _types = __webpack_require__(5);

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var AreaComponent = {
  /**
   * 当前状态数据.
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  data: function data() {
    return {
      current: 0,
      loadding: true,
      add: {
        name: '',
        extends: '',
        loadding: false,
        error: false,
        error_message: {}
      },
      deleteIds: {}
    };
  },
  /**
   * 定义需要初始化时候计算的数据对象.
   *
   * @type {Object}
   */
  computed: _extends({}, (0, _vuex.mapGetters)({
    areas: _getterTypes.SETTINGS_AREA
  }), {
    /**
     * 计算路径导航对象.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    tree: function tree() {
      var current = this.current;
      if (current === 0) {
        return false;
      }

      return this.getTrees(current);
    },

    /**
     * 获取当前选中子列表
     *
     * @return {Object} [description]
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    list: function list() {
      var current = this.current;
      var trees = [];
      this.areas.forEach(function (area) {
        if (area.pid === current) {
          trees.push(area);
        }
      });

      return trees;
    }
  }),
  /**
   * 方法对象, 用于设置各个处理方法.
   *
   * @type {Object}
   */
  methods: {
    /**
     * 获取路径导航树.
     *
     * @param {Number} pid
     * @return {Array}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    getTrees: function getTrees(pid) {
      var _this = this;

      var trees = [];
      this.areas.forEach(function (area) {
        if (area.id === pid) {
          trees = [].concat(_toConsumableArray(_this.getTrees(area.pid)), [area]);
        }
      });

      return trees;
    },

    /**
     * 设置选中id.
     *
     * @param {Number} id
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    selectCurrent: function selectCurrent(id) {
      if (this.add.loadding) {
        alert('正在添加新地区，请等待！！！');
        return;
      }

      this.current = id;
      this.add = {
        name: '',
        extends: '',
        loadding: false
      };
      // 用于回到顶部
      document.documentElement.scrollTop = document.body.scrollTop = 0;
    },

    /**
     * 添加新地区.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    addArea: function addArea() {
      var _this2 = this;

      this.add.loadding = true;
      this.add.error = false;

      var data = {
        name: this.add.name,
        extends: this.add.extends,
        pid: this.current
      };

      this.$store.dispatch(_types.SETTINGS_AREA_APPEND, function (cb) {
        return _request2.default.post((0, _request.createRequestURI)('site/areas'), data, { validateStatus: function validateStatus(status) {
            return status === 201;
          } }).then(function (_ref) {
          var data = _ref.data;

          _this2.add = {
            name: '',
            extends: '',
            loadding: false
          };
          cb(data);
        }).catch(function (_ref2) {
          var _ref2$response = _ref2.response;
          _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
          var _ref2$response$data = _ref2$response.data,
              data = _ref2$response$data === undefined ? {} : _ref2$response$data;
          var _data$error = data.error,
              error = _data$error === undefined ? ['添加失败!!!'] : _data$error;

          _this2.add.loadding = false;
          _this2.add.error = true;
          _this2.add.error_message = error;
        });
      });
    },

    /**
     * 关闭添加错误消息.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    dismisAddAreaError: function dismisAddAreaError() {
      this.add.error = false;
    },

    /**
     * 删除地区.
     *
     * @param {Number} id
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    deleteArea: function deleteArea(id) {
      var _this3 = this;

      if (window.confirm('确认删除?')) {
        this.deleteIds = _extends({}, this.deleteIds, _defineProperty({}, id, id));

        var deleteId = function deleteId(id) {
          var ids = {};
          for (var _id in _this3.deleteIds) {
            if (parseInt(_id) !== parseInt(id)) {
              ids = _extends({}, ids, _defineProperty({}, _id, _id));
            }
          }
          _this3.deleteIds = ids;
        };

        this.$store.dispatch(_types.SETTINGS_AREA_DELETE, function (cb) {
          return _request2.default.delete((0, _request.createRequestURI)('site/areas/' + id), { validateStatus: function validateStatus(status) {
              return status === 204;
            } }).then(function () {
            cb(id);
            deleteId(id);
          }).catch(function (_ref3) {
            var _ref3$response = _ref3.response;
            _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
            var _ref3$response$data = _ref3$response.data,
                data = _ref3$response$data === undefined ? {} : _ref3$response$data;

            deleteId(id);
            var _data$error2 = data.error,
                error = _data$error2 === undefined ? '删除失败' : _data$error2;

            window.alert(error);
          });
        });
      }
    },

    /**
     * 更新地区数据
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    patchArea: function patchArea(id, key, value) {
      this.$store.dispatch(_types.SETTINGS_AREA_CHANGEITEM, function (cb) {
        return _request2.default.patch((0, _request.createRequestURI)('site/areas/' + id), { key: key, value: value }, { validateStatus: function validateStatus(status) {
            return status === 201;
          } }).then(function () {
          cb(_defineProperty({
            id: id
          }, key, value));
        }).catch(function (_ref4) {
          var _ref4$response = _ref4.response;
          _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
          var _ref4$response$data = _ref4$response.data,
              data = _ref4$response$data === undefined ? {} : _ref4$response$data;
          var _data$error3 = data.error,
              error = _data$error3 === undefined ? ['更新失败'] : _data$error3;

          var errorMessage = _lodash2.default.values(error).pop();
          window.alert(errorMessage);
        });
      });
    }
  },
  /**
   * 组件初始化完成后执行.
   *
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  created: function created() {
    var _this4 = this;

    this.$store.dispatch(_types.SETTINGS_AREA_CHANGE, function (cb) {
      return _request2.default.get((0, _request.createRequestURI)('site/areas'),
      // 判断状态是否是正确获取的状态, 正确进入 then.
      { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref5) {
        var _ref5$data = _ref5.data,
            data = _ref5$data === undefined ? [] : _ref5$data;

        cb(data);
        _this4.loadding = false;
      }).catch(function (_ref6) {
        var _ref6$response = _ref6.response;
        _ref6$response = _ref6$response === undefined ? {} : _ref6$response;
        var _ref6$response$data = _ref6$response.data;
        _ref6$response$data = _ref6$response$data === undefined ? {} : _ref6$response$data;
        var _ref6$response$data$m = _ref6$response$data.message,
            message = _ref6$response$data$m === undefined ? '获取地区失败' : _ref6$response$data$m;

        _this4.loadding = false;
        window.alert(message);
      });
    });
  }
};

exports.default = AreaComponent;

/***/ }),
/* 78 */,
/* 79 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    class: _vm.$style.container
  }, [_c('ul', {
    staticClass: "nav nav-tabs",
    class: _vm.$style.areaTab
  }, [_c('router-link', {
    attrs: {
      "to": "/setting/area",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("地区管理")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/setting/hots",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("热门城市")])])], 1), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }],
    class: _vm.$style.loadding
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.loadding),
      expression: "!loadding"
    }]
  }, [(_vm.tree) ? _c('ol', {
    staticClass: "breadcrumb"
  }, [_c('li', {
    class: _vm.$style.breadcrumbNotActvie,
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.selectCurrent(0)
      }
    }
  }, [_vm._v("全部")]), _vm._v(" "), _vm._l((_vm.tree), function(area) {
    return _c('li', {
      class: area.id === _vm.current ? 'active' : _vm.$style.breadcrumbNotActvie,
      on: {
        "click": function($event) {
          $event.preventDefault();
          _vm.selectCurrent(area.id)
        }
      }
    }, [_vm._v("\n        " + _vm._s(area.name) + "\n      ")])
  })], 2) : _vm._e(), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.current),
      expression: "!current"
    }],
    staticClass: "alert alert-success",
    attrs: {
      "role": "alert"
    }
  }, [_c('p', [_vm._v("1. 提交：编辑地区信息的时候，直接修改输入框内容，失去焦点后程序会自动提交")]), _vm._v(" "), _vm._m(0)]), _vm._v(" "), _c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(1), _vm._v(" "), _c('tbody', [_vm._l((_vm.list), function(area) {
    return _c('tr', [_c('td', [_c('div', {
      staticClass: "input-group"
    }, [_c('input', {
      staticClass: "form-control",
      attrs: {
        "type": "text",
        "placeholder": "输入名称"
      },
      domProps: {
        "value": area.name
      },
      on: {
        "change": function($event) {
          if (!('button' in $event) && _vm._k($event.keyCode, "lazy")) { return null; }
          _vm.patchArea(area.id, 'name', $event.target.value)
        }
      }
    })])]), _vm._v(" "), _c('td', [_c('div', {
      staticClass: "input-group"
    }, [_c('input', {
      staticClass: "form-control",
      attrs: {
        "type": "text",
        "placeholder": "输入拓展信息"
      },
      domProps: {
        "value": area.extends
      },
      on: {
        "change": function($event) {
          if (!('button' in $event) && _vm._k($event.keyCode, "lazy")) { return null; }
          _vm.patchArea(area.id, 'extends', $event.target.value)
        }
      }
    })])]), _vm._v(" "), _c('td', [_c('button', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          $event.preventDefault();
          _vm.selectCurrent(area.id)
        }
      }
    }, [_vm._v("下级管理")]), _vm._v(" "), (_vm.deleteIds.hasOwnProperty(area.id)) ? _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_c('span', {
      staticClass: "glyphicon glyphicon-refresh",
      class: _vm.$style.loaddingIcon
    })]) : _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          $event.preventDefault();
          _vm.deleteArea(area.id)
        }
      }
    }, [_vm._v("删除")])])])
  }), _vm._v(" "), _c('tr', [_c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.name),
      expression: "add.name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入名称"
    },
    domProps: {
      "value": (_vm.add.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.name = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.extends),
      expression: "add.extends"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入拓展信息"
    },
    domProps: {
      "value": (_vm.add.extends)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.extends = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td', [(!_vm.add.loadding) ? _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.addArea($event)
      }
    }
  }, [_vm._v("添加")]) : _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })])])])], 2)]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.add.error),
      expression: "add.error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisAddAreaError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v(" "), _c('strong', [_vm._v("Error:")]), _vm._v(" "), _vm._l((_vm.add.error_message), function(error) {
    return _c('p', [_vm._v(_vm._s(error))])
  })], 2)])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('p', [_vm._v("2. 拓展信息：拓展信息赋予单条信息而外的数据，例如国家设置，"), _c('strong', [_vm._v("中国")]), _vm._v("的拓展信息设置的"), _c('strong', [_vm._v("3")]), _vm._v(",用于在app开发中UI层展示几级选择菜单，所以，只有在业务需求下，设置拓展信息才是有用的。其他情况下留空即可。")])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("名称")]), _vm._v(" "), _c('th', [_vm._v("拓展(无需设置)")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-768160d4", esExports)
  }
}

/***/ }),
/* 80 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Hots_vue__ = __webpack_require__(81);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Hots_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Hots_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_6ce57ce6_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Hots_vue__ = __webpack_require__(82);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-6ce57ce6\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Hots.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(21)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-6ce57ce6")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(21)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Hots_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_6ce57ce6_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Hots_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/Hots.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Hots.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-6ce57ce6", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-6ce57ce6", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 81 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request2 = __webpack_require__(1);

var _request3 = _interopRequireDefault(_request2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var HotsComponent = {

  data: function data() {
    return {
      loadding: false,
      message: '',
      add: {
        content: '',
        message: '',
        update: 0,
        loadding: false,
        type: 'muted'
      },
      list: {}
    };
  },
  methods: {
    doHotsArea: function doHotsArea() {
      var _this = this;

      this.add.loadding = true;
      var data = {
        content: this.add.content,
        update: this.add.update
      };
      _request3.default.post((0, _request2.createRequestURI)('site/areas/hots'), data, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref) {
        var _ref$data = _ref.data,
            _ref$data$message = _ref$data.message,
            message = _ref$data$message === undefined ? '提交成功' : _ref$data$message,
            _ref$data$status = _ref$data.status,
            status = _ref$data$status === undefined ? '' : _ref$data$status;

        var index = _this.list.indexOf(_this.add.content);
        _this.add.loadding = false;
        _this.add.type = 'success';
        if (status == 1 && index < 0) {
          _this.list.push(_this.add.content);
          _this.add.message = message;
        } else {
          _this.add.message = '已存在此地区';
        }
        if (status == 2) {
          if (index > -1) {
            _this.list.splice(index, 1);
          }
          _this.add.message = '删除成功';
        }
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$e = _ref2$response$data.error;
        _ref2$response$data$e = _ref2$response$data$e === undefined ? [] : _ref2$response$data$e;

        var _ref2$response$data$e2 = _slicedToArray(_ref2$response$data$e, 1),
            _ref2$response$data$e3 = _ref2$response$data$e2[0],
            error = _ref2$response$data$e3 === undefined ? '提交失败' : _ref2$response$data$e3;

        _this.add.loadding = false;
        _this.add.type = 'danger';
        _this.add.message = error;
      });
    },
    deleteArea: function deleteArea(area) {
      if (window.confirm('确认删除?')) {
        this.add.content = area;
        this.add.update = 1;
        this.doHotsArea();
      }
    },
    request: function request() {
      var _this2 = this;

      this.loadding = true;
      _request3.default.get((0, _request2.createRequestURI)('site/areas/hots'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref3) {
        var _ref3$data$data = _ref3.data.data,
            data = _ref3$data$data === undefined ? {} : _ref3$data$data;

        _this2.loadding = false;
        _this2.list = data;
      }).catch(function (_ref4) {
        var _ref4$response = _ref4.response;
        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$m = _ref4$response$data.message;
        _ref4$response$data$m = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m;

        var _ref4$response$data$m2 = _slicedToArray(_ref4$response$data$m, 1),
            _ref4$response$data$m3 = _ref4$response$data$m2[0],
            message = _ref4$response$data$m3 === undefined ? '加载失败' : _ref4$response$data$m3;

        _this2.loadding = false;
        _this2.message = message;
      });
    }
  },
  created: function created() {
    var _this3 = this;

    window.setTimeout(function () {
      return _this3.request();
    }, 500);
  }
};

exports.default = HotsComponent;

/***/ }),
/* 82 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    class: _vm.$style.container
  }, [_c('ul', {
    staticClass: "nav nav-tabs",
    class: _vm.$style.areaTab
  }, [_c('router-link', {
    attrs: {
      "to": "/setting/area",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("地区管理")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/setting/hots",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("热门城市")])])], 1), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }],
    class: _vm.$style.loadding
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })]), _vm._v(" "), (!_vm.message) ? _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.loadding),
      expression: "!loadding"
    }]
  }, [_vm._m(0), _vm._v(" "), _c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(1), _vm._v(" "), _c('tbody', [_vm._l((_vm.list), function(item) {
    return _c('tr', [_c('td', [_c('div', {
      staticClass: "input-group"
    }, [_vm._v(_vm._s(item))])]), _vm._v(" "), _c('td', [_c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          $event.preventDefault();
          _vm.deleteArea(item)
        }
      }
    }, [_vm._v("删除")])])])
  }), _vm._v(" "), _c('tr', [_c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.content),
      expression: "add.content"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入名称"
    },
    domProps: {
      "value": (_vm.add.content)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.content = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td', [(!_vm.add.loadding) ? _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.doHotsArea($event)
      }
    }
  }, [_vm._v("添加")]) : _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })]), _vm._v(" "), _c('span', {
    class: ("text-" + (_vm.add.type))
  }, [_vm._v(_vm._s(_vm.add.message))])])])], 2)])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.request($event)
      }
    }
  }, [_vm._v("刷新")])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "alert alert-success",
    attrs: {
      "role": "alert"
    }
  }, [_c('p', [_vm._v("添加：直接输入地区名以空格分开， 例如：中国 四川省 成都市")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("名称")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-6ce57ce6", esExports)
  }
}

/***/ }),
/* 83 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Mail_vue__ = __webpack_require__(84);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Mail_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Mail_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_4d981e04_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Mail_vue__ = __webpack_require__(85);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Mail_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_4d981e04_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Mail_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/Mail.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Mail.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4d981e04", Component.options)
  } else {
    hotAPI.reload("data-v-4d981e04", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 84 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request2 = __webpack_require__(1);

var _request3 = _interopRequireDefault(_request2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var MailComponent = {
  data: function data() {
    return {
      loadding: {
        state: 0,
        message: ''
      },
      submit: {
        state: false,
        type: 'muted',
        message: ''
      },
      options: {}
    };
  },
  methods: {
    request: function request() {
      var _this = this;

      this.loadding.state = 0;
      _request3.default.get((0, _request2.createRequestURI)('site/mail'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;

        _this.loadding.state = 1;
        _this.options = data;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            _ref2$response$data$m3 = _ref2$response$data$m2[0],
            message = _ref2$response$data$m3 === undefined ? '加载失败' : _ref2$response$data$m3;

        _this.loadding.state = 2;
        _this.loadding.message = message;
      });
    },
    submitHandle: function submitHandle() {
      var _this2 = this;

      this.submit.state = true;
      _request3.default.patch((0, _request2.createRequestURI)('site/mail'), this.options, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref3) {
        var _ref3$data$message = _ref3.data.message,
            message = _ref3$data$message === undefined ? '提交成功' : _ref3$data$message;

        _this2.submit.state = false;
        _this2.submit.type = 'success';
        _this2.submit.message = message;
      }).catch(function (_ref4) {
        var _ref4$response = _ref4.response;
        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$m = _ref4$response$data.message;
        _ref4$response$data$m = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m;

        var _ref4$response$data$m2 = _slicedToArray(_ref4$response$data$m, 1),
            _ref4$response$data$m3 = _ref4$response$data$m2[0],
            message = _ref4$response$data$m3 === undefined ? '提交失败' : _ref4$response$data$m3;

        _this2.submit.state = false;
        _this2.submit.type = 'danger';
        _this2.submit.message = message;
      });
    }
  },
  created: function created() {
    var _this3 = this;

    window.setTimeout(function () {
      return _this3.request();
    }, 500);
  }
};

exports.default = MailComponent;

/***/ }),
/* 85 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("邮件配置 \n\t\t"), _c('router-link', {
    attrs: {
      "to": "/setting/sendmail"
    }
  }, [_c('button', {
    staticClass: "btn btn-primary btn-xs pull-right",
    attrs: {
      "type": "button"
    }
  }, [_vm._v("测试发送")])])], 1), _vm._v(" "), (_vm.loadding.state === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n        加载中...\n      ")]) : (_vm.loadding.state === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "host"
    }
  }, [_vm._v("SMTP主机地址")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.host),
      expression: "options.host"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "host",
      "id": "host",
      "placeholder": "请输入SMTP主机地址",
      "aria-describedby": "host-help"
    },
    domProps: {
      "value": (_vm.options.host)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.host = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(0)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "port"
    }
  }, [_vm._v("SMTP主机端口")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.port),
      expression: "options.port"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "port",
      "id": "port",
      "placeholder": "请输入SMTP主机端口",
      "aria-describedby": "port-help"
    },
    domProps: {
      "value": (_vm.options.port)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.port = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(1)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "address"
    }
  }, [_vm._v("邮件地址")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.from.address),
      expression: "options.from.address"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "address",
      "id": "address",
      "placeholder": "请输入发送邮件地址",
      "aria-describedby": "address-help"
    },
    domProps: {
      "value": (_vm.options.from.address)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.from.address = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(2)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v("发送名称")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.from.name),
      expression: "options.from.name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "name",
      "id": "name",
      "placeholder": "请输入发送名称",
      "aria-describedby": "name-help"
    },
    domProps: {
      "value": (_vm.options.from.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.from.name = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(3)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "encryption"
    }
  }, [_vm._v("传输协议加密方式")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.encryption),
      expression: "options.encryption"
    }],
    attrs: {
      "type": "radio",
      "name": "encryption",
      "value": "tls"
    },
    domProps: {
      "checked": _vm._q(_vm.options.encryption, "tls")
    },
    on: {
      "__c": function($event) {
        _vm.options.encryption = "tls"
      }
    }
  }), _vm._v("TLS   \n            "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.encryption),
      expression: "options.encryption"
    }],
    attrs: {
      "type": "radio",
      "name": "encryption",
      "value": "ssl"
    },
    domProps: {
      "checked": _vm._q(_vm.options.encryption, "ssl")
    },
    on: {
      "__c": function($event) {
        _vm.options.encryption = "ssl"
      }
    }
  }), _vm._v("SSL\t \n          ")]), _vm._v(" "), _vm._m(4)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "username"
    }
  }, [_vm._v("SMTP服务器用户名")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.username),
      expression: "options.username"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "username",
      "id": "username",
      "placeholder": "请输入SMTP服务器用户名",
      "aria-describedby": "username-help"
    },
    domProps: {
      "value": (_vm.options.username)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.username = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(5)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "password"
    }
  }, [_vm._v("SMTP服务器密码")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.password),
      expression: "options.password"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "password",
      "name": "password",
      "id": "password",
      "placeholder": "请输入SMTP服务器密码",
      "aria-describedby": "password-help"
    },
    domProps: {
      "value": (_vm.options.password)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.password = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(6)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [(_vm.submit.state === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n              提交...\n            ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.submitHandle($event)
      }
    }
  }, [_vm._v("提交")])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6 help-block"
  }, [_c('span', {
    class: ("text-" + (_vm.submit.type))
  }, [_vm._v(_vm._s(_vm.submit.message))])])])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.loadding.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.request($event)
      }
    }
  }, [_vm._v("刷新")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "host-help"
    }
  }, [_vm._v("输入SMTP主机地址")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "port-help"
    }
  }, [_vm._v("请输入SMTP主机端口")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "address-help"
    }
  }, [_vm._v("请输入发送邮件地址")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "name-help"
    }
  }, [_vm._v("请输入发送名称")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "encryption-help"
    }
  }, [_vm._v("选择邮件传输协议加密方式")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "username-help"
    }
  }, [_vm._v("请输入SMTP服务器用户名")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "password-help"
    }
  }, [_vm._v("请输入SMTP服务器密码")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-4d981e04", esExports)
  }
}

/***/ }),
/* 86 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_SendMail_vue__ = __webpack_require__(87);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_SendMail_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_SendMail_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_1a206d26_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_SendMail_vue__ = __webpack_require__(88);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_SendMail_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_1a206d26_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_SendMail_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/SendMail.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] SendMail.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1a206d26", Component.options)
  } else {
    hotAPI.reload("data-v-1a206d26", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 87 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var AlidayuComponent = {
  data: function data() {
    return {
      submit: {
        state: false,
        type: 'muted',
        message: ''
      },
      options: {}
    };
  },
  methods: {
    submitHandle: function submitHandle() {
      var _this = this;

      this.submit.state = true;
      _request2.default.post((0, _request.createRequestURI)('site/sendmail'), this.options, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref) {
        var _ref$data$message = _ref.data.message,
            message = _ref$data$message === undefined ? '提交成功' : _ref$data$message;

        _this.submit.state = false;
        _this.submit.type = 'success';
        _this.submit.message = message;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            _ref2$response$data$m3 = _ref2$response$data$m2[0],
            message = _ref2$response$data$m3 === undefined ? '提交失败' : _ref2$response$data$m3;

        _this.submit.state = false;
        _this.submit.type = 'danger';
        _this.submit.message = message;
      });
    }
  }
};

exports.default = AlidayuComponent;

/***/ }),
/* 88 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("测试发送邮件\n\t\t    "), _c('router-link', {
    attrs: {
      "to": "/setting/mail"
    }
  }, [_c('button', {
    staticClass: "btn btn-primary btn-xs pull-right",
    attrs: {
      "type": "button"
    }
  }, [_vm._v("返 回")])])], 1), _vm._v(" "), _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "email"
    }
  }, [_vm._v("邮件地址")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.email),
      expression: "options.email"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "email",
      "id": "email",
      "placeholder": "请输入邮件地址"
    },
    domProps: {
      "value": (_vm.options.email)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.email = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "content"
    }
  }, [_vm._v("邮件内容")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.content),
      expression: "options.content"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "content",
      "id": "content",
      "placeholder": "请输入发送内容"
    },
    domProps: {
      "value": (_vm.options.content)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.content = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [(_vm.submit.state === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n              提交...\n            ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.submitHandle($event)
      }
    }
  }, [_vm._v("发送")])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6 help-block"
  }, [_c('span', {
    class: ("text-" + (_vm.submit.type))
  }, [_vm._v(_vm._s(_vm.submit.message))])])])])])])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1a206d26", esExports)
  }
}

/***/ }),
/* 89 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Server_vue__ = __webpack_require__(91);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Server_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Server_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_7c3124ca_hasScoped_true_node_modules_vue_loader_lib_selector_type_template_index_0_Server_vue__ = __webpack_require__(92);
var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(90)
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-7c3124ca"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Server_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_7c3124ca_hasScoped_true_node_modules_vue_loader_lib_selector_type_template_index_0_Server_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/Server.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Server.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7c3124ca", Component.options)
  } else {
    hotAPI.reload("data-v-7c3124ca", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 90 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 91 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _types = __webpack_require__(5);

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var System = {
  data: function data() {
    return {
      translates: {
        php_version: 'PHP版本',
        os: '操作系统',
        server: '运行环境',
        domain_ip: '域名/IP',
        db: '数据库',
        root: '根目录',
        laravel_version: 'Laravel版本',
        max_upload_size: '最大上传限制',
        server_date: '服务器时间',
        local_date: '本地时间',
        protocol: '通信协议',
        port: '监听端口',
        method: '请求方法',
        execute_time: '执行时间',
        agent: '你使用的浏览器',
        user_ip: '你的IP',
        disk: '服务端剩余磁盘空间'
      }
    };
  },
  created: function created() {
    this.getSystemInfo();
  },


  computed: {
    system: function system() {
      return this.$store.state.system;
    }
  },

  methods: {
    getSystemInfo: function getSystemInfo() {
      var _this = this;

      _request2.default.get((0, _request.createRequestURI)('site/systeminfo'), {
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;

        _this.$store.commit(_types.SETTINGS_SYSTEM_UPDATE, _extends({}, data));
        _this.loadding = false;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message,
            message = _ref2$response$data$m === undefined ? '加载失败' : _ref2$response$data$m;

        _this.loadding = false;
        _this.error = true;
        window.alert(message);
      });
    }
  }
};

exports.default = System;

/***/ }),
/* 92 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("服务器信息")]), _vm._v(" "), _c('ul', {
    staticClass: "list-group"
  }, _vm._l((_vm.system), function(per, index) {
    return _c('li', {
      key: index,
      staticClass: "list-group-item"
    }, [_c('span', {
      staticStyle: {
        "font-size": "16px"
      }
    }, [_vm._v(_vm._s(_vm.translates[index]) + " : ")]), _vm._v(" "), _c('span', [_vm._v(_vm._s(per))])])
  }))])])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7c3124ca", esExports)
  }
}

/***/ }),
/* 93 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Tags_vue__ = __webpack_require__(94);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Tags_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Tags_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_a555c340_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Tags_vue__ = __webpack_require__(95);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-a555c340\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Tags.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(22)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-a555c340")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(22)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Tags_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_a555c340_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Tags_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/Tags.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Tags.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-a555c340", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-a555c340", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 94 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _vuex = __webpack_require__(8);

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Tags = {
  data: function data() {
    return {
      tags: [],
      page: 1,
      from: 1,
      last_page: 0,
      prev_page_url: null,
      total: 0,
      per_page: 20,
      loadding: true,
      cate: 0
    };
  },
  methods: {
    getTags: function getTags() {
      var _this = this;

      _request2.default.get((0, _request.createRequestURI)('site/tags'), {
        params: _extends({}, this.queryParams)
      }, {
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;

        _this.tags = data.data;
        _this.last_page = data.last_page;
        _this.page = data.current_page;
        _this.total = data.total;
        _this.loadding = false;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message,
            message = _ref2$response$data$m === undefined ? '加载失败' : _ref2$response$data$m;

        _this.loadding = false;
        _this.error = true;
        window.alert(message);
      });
    }
  },

  watch: {
    '$route': function $route(to) {
      var _to$query = to.query,
          _to$query$last_page = _to$query.last_page,
          last_page = _to$query$last_page === undefined ? 1 : _to$query$last_page,
          _to$query$per_page = _to$query.per_page,
          per_page = _to$query$per_page === undefined ? 20 : _to$query$per_page,
          _to$query$page = _to$query.page,
          page = _to$query$page === undefined ? 1 : _to$query$page,
          _to$query$cate = _to$query.cate,
          cate = _to$query$cate === undefined ? 0 : _to$query$cate;


      this.last_page = parseInt(last_page);
      this.per_page = parseInt(per_page);
      this.page = parseInt(page);
      this.cate = parseInt(cate);

      this.getTags();
    }
  },

  computed: {
    empty: function empty() {
      return !(this.tags.length > 0);
    },
    queryParams: function queryParams() {
      var per_page = this.per_page,
          page = this.page,
          cate = this.cate;

      return { per_page: per_page, page: page, cate: cate };
    },
    prevQuery: function prevQuery() {
      var page = parseInt(this.page);
      return _extends({}, this.queryParams, {
        last_page: this.last_page,
        page: page > 1 ? page - 1 : page
      });
    },
    nextQuery: function nextQuery() {
      var page = parseInt(this.page);
      var last_page = parseInt(this.last_page);
      return _extends({}, this.queryParams, {
        last_page: last_page,
        page: page < last_page ? page + 1 : last_page
      });
    }
  },

  created: function created() {
    var _$route$query = this.$route.query,
        _$route$query$last_pa = _$route$query.last_page,
        last_page = _$route$query$last_pa === undefined ? 1 : _$route$query$last_pa,
        _$route$query$page = _$route$query.page,
        page = _$route$query$page === undefined ? 1 : _$route$query$page,
        _$route$query$per_pag = _$route$query.per_page,
        per_page = _$route$query$per_pag === undefined ? 20 : _$route$query$per_pag,
        _$route$query$cate = _$route$query.cate,
        cate = _$route$query$cate === undefined ? 0 : _$route$query$cate;
    // set state.

    this.last_page = last_page;
    this.current_page = page;
    this.per_page = per_page;
    this.cate = cate;
    this.getTags();
  }
};

exports.default = Tags;

/***/ }),
/* 95 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid"
  }, [_c('div', {
    staticClass: "page-header"
  }, [_c('h4', [_vm._v("标签列表"), _c('small', {
    class: _vm.$style.link
  }, [_c('router-link', {
    attrs: {
      "to": "/setting/addtag"
    }
  }, [_vm._v("添加标签")])], 1)])]), _vm._v(" "), (!_vm.empty) ? _c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', [_c('tr', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }]
  }, [_c('td', {
    class: _vm.$style.loadding,
    attrs: {
      "colspan": "6"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })])]), _vm._v(" "), _vm._l((_vm.tags), function(tag) {
    return _c('tr', {
      key: tag.id
    }, [_c('td', [_vm._v(_vm._s(tag.id))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(tag.name))]), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": ("/setting/tags?cate=" + (tag.category.id))
      }
    }, [_vm._v("\n              " + _vm._s(tag.category.name) + "\n            ")])], 1), _vm._v(" "), _c('td', [_vm._v(_vm._s(tag.taggable_count))]), _vm._v(" "), _c('td', [_c('router-link', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "to": ("/setting/updatetag/" + (tag.id))
      }
    }, [_vm._v("编辑")]), _vm._v(" "), _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          _vm.deleteTag(tag.id)
        }
      }
    }, [_vm._v("删除")])], 1)])
  })], 2)]) : _c('p', {
    staticStyle: {
      "text-align": "center",
      "padding": "8px"
    }
  }, [_vm._v("还没有添加标签")]), _vm._v(" "), _c('ul', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.page >= 1 && _vm.last_page > 1),
      expression: "page >= 1 && last_page > 1"
    }],
    staticClass: "pager"
  }, [_c('li', {
    staticClass: "previous",
    class: _vm.page <= 1 ? 'disabled' : ''
  }, [_c('router-link', {
    attrs: {
      "to": {
        path: '/setting/tags',
        query: _vm.prevQuery
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("←")]), _vm._v("\n          上一页\n        ")])], 1), _vm._v(" "), _c('li', [_vm._v("\n        共 " + _vm._s(_vm.total) + "个标签，总共" + _vm._s(_vm.last_page) + "页，当前为第" + _vm._s(_vm.page) + "页\n      ")]), _vm._v(" "), _c('li', {
    staticClass: "next",
    class: _vm.page >= _vm.last_page ? 'disabled' : ''
  }, [_c('router-link', {
    attrs: {
      "to": {
        path: '/setting/tags',
        query: _vm.nextQuery
      }
    }
  }, [_vm._v("\n          下一页\n          "), _c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("→")])])], 1)])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("标签ID")]), _vm._v(" "), _c('th', [_vm._v("标签")]), _vm._v(" "), _c('th', [_vm._v("所属分类")]), _vm._v(" "), _c('th', [_vm._v("使用量")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-a555c340", esExports)
  }
}

/***/ }),
/* 96 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_TagCategories_vue__ = __webpack_require__(97);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_TagCategories_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_TagCategories_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_5fa5199f_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_TagCategories_vue__ = __webpack_require__(98);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-5fa5199f\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./TagCategories.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(23)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-5fa5199f")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(23)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_TagCategories_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_5fa5199f_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_TagCategories_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/TagCategories.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] TagCategories.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5fa5199f", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-5fa5199f", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 97 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var TagCategories = {
  data: function data() {
    return {
      tag_categories: [],
      last_page: 1,
      per_page: 20,
      page: 1,
      total: 1,
      loadding: true
    };
  },

  methods: {
    getTagCategories: function getTagCategories() {
      var _this = this;

      _request2.default.get((0, _request.createRequestURI)('site/tags/tag_categories'), {
        params: _extends({}, this.queryParams)
      }, {
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;

        _this.tag_categories = data.data;
        _this.last_page = data.last_page;
        _this.page = data.current_page;
        _this.total = data.total;
        _this.loadding = false;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message,
            message = _ref2$response$data$m === undefined ? '加载失败' : _ref2$response$data$m;

        _this.loadding = false;
        // this.error = true;
        // window.alert(message);
      });
    }
  },

  watch: {
    '$route': function $route(to) {
      var _to$query = to.query,
          _to$query$last_page = _to$query.last_page,
          last_page = _to$query$last_page === undefined ? 1 : _to$query$last_page,
          _to$query$per_page = _to$query.per_page,
          per_page = _to$query$per_page === undefined ? 20 : _to$query$per_page,
          _to$query$page = _to$query.page,
          page = _to$query$page === undefined ? 1 : _to$query$page;


      this.last_page = parseInt(last_page);
      this.per_page = parseInt(per_page);
      this.page = parseInt(page);

      this.getTagCategories();
    }
  },

  computed: {
    empty: function empty() {
      return !(this.tag_categories.length > 0);
    },
    queryParams: function queryParams() {
      var per_page = this.per_page,
          page = this.page;

      return { per_page: per_page, page: page };
    },
    prevQuery: function prevQuery() {
      var page = parseInt(this.page);
      return _extends({}, this.queryParams, {
        last_page: this.last_page,
        page: page > 1 ? page - 1 : page
      });
    },
    nextQuery: function nextQuery() {
      var page = parseInt(this.page);
      var last_page = parseInt(this.last_page);
      return _extends({}, this.queryParams, {
        last_page: last_page,
        page: page < last_page ? page + 1 : last_page
      });
    }
  },

  created: function created() {
    var _$route$query = this.$route.query,
        _$route$query$per_pag = _$route$query.per_page,
        per_page = _$route$query$per_pag === undefined ? 20 : _$route$query$per_pag,
        _$route$query$last_pa = _$route$query.last_page,
        last_page = _$route$query$last_pa === undefined ? 1 : _$route$query$last_pa,
        _$route$query$page = _$route$query.page,
        page = _$route$query$page === undefined ? 1 : _$route$query$page;
    // set state.

    this.last_page = last_page;
    this.current_page = page;
    this.per_page = per_page;

    this.getTagCategories();
  }
};

exports.default = TagCategories;

/***/ }),
/* 98 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid"
  }, [_c('div', {
    staticClass: "page-header"
  }, [_c('h4', [_vm._v("标签分类"), _c('small', {
    class: _vm.$style.link
  }, [_c('router-link', {
    attrs: {
      "to": "/setting/addTagCate"
    }
  }, [_vm._v("添加分类")])], 1)])]), _vm._v(" "), (!_vm.empty) ? _c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', [_c('tr', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }]
  }, [_c('td', {
    class: _vm.$style.loadding,
    attrs: {
      "colspan": "6"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })])]), _vm._v(" "), _vm._l((_vm.tag_categories), function(category) {
    return _c('tr', {
      key: category.id
    }, [_c('td', [_vm._v(_vm._s(category.id))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(category.name))]), _vm._v(" "), _c('td', [_c('router-link', {
      attrs: {
        "to": ("/setting/tags?cate=" + (category.id))
      }
    }, [_vm._v("\n              " + _vm._s(category.tags_count) + "\n            ")])], 1), _vm._v(" "), _c('td', [_c('router-link', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "to": ("/setting/updatetag/" + (category.id))
      }
    }, [_vm._v("编辑")]), _vm._v(" "), _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          _vm.deleteUser(category.id)
        }
      }
    }, [_vm._v("删除")])], 1)])
  })], 2)]) : _c('p', {
    staticStyle: {
      "text-align": "center",
      "padding": "8px"
    }
  }, [_vm._v("还没有添加分类")]), _vm._v(" "), _c('ul', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.page >= 1 && _vm.last_page > 1),
      expression: "page >= 1 && last_page > 1"
    }],
    staticClass: "pager"
  }, [_c('li', {
    staticClass: "previous",
    class: _vm.page <= 1 ? 'disabled' : ''
  }, [_c('router-link', {
    attrs: {
      "to": {
        path: '/setting/tag-categories',
        query: _vm.prevQuery
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("←")]), _vm._v("\n          上一页\n        ")])], 1), _vm._v(" "), _c('li', [_vm._v("\n        共 " + _vm._s(_vm.total) + "个标签，总共" + _vm._s(_vm.last_page) + "页，当前为第" + _vm._s(_vm.page) + "页\n      ")]), _vm._v(" "), _c('li', {
    staticClass: "next",
    class: _vm.page >= _vm.last_page ? 'disabled' : ''
  }, [_c('router-link', {
    attrs: {
      "to": {
        path: '/setting/tag-categories',
        query: _vm.nextQuery
      }
    }
  }, [_vm._v("\n          下一页\n          "), _c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("→")])])], 1)])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("分类ID")]), _vm._v(" "), _c('th', [_vm._v("分类")]), _vm._v(" "), _c('th', [_vm._v("拥有标签数量")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-5fa5199f", esExports)
  }
}

/***/ }),
/* 99 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _User = __webpack_require__(100);

var _User2 = _interopRequireDefault(_User);

var _UserAdd = __webpack_require__(102);

var _UserAdd2 = _interopRequireDefault(_UserAdd);

var _UserManage = __webpack_require__(105);

var _UserManage2 = _interopRequireDefault(_UserManage);

var _Manage = __webpack_require__(108);

var _Manage2 = _interopRequireDefault(_Manage);

var _Roles = __webpack_require__(111);

var _Roles2 = _interopRequireDefault(_Roles);

var _RoleManage = __webpack_require__(114);

var _RoleManage2 = _interopRequireDefault(_RoleManage);

var _Permissions = __webpack_require__(117);

var _Permissions2 = _interopRequireDefault(_Permissions);

var _Setting = __webpack_require__(120);

var _Setting2 = _interopRequireDefault(_Setting);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * The file is defined "/setting" route.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

var routers = {
  path: 'users',
  component: _User2.default,
  children: [{ path: '', component: _Manage2.default }, { path: 'manage/:userId', component: _UserManage2.default }, { path: 'add', component: _UserAdd2.default }, { path: 'roles', component: _Roles2.default }, { path: 'roles/:role', component: _RoleManage2.default }, { path: 'permissions', component: _Permissions2.default }, { path: 'setting', component: _Setting2.default }]
};

exports.default = routers;

/***/ }),
/* 100 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_bd3f1f9a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_User_vue__ = __webpack_require__(101);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-bd3f1f9a\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./User.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(24)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-bd3f1f9a")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(24)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_bd3f1f9a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_User_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/User.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] User.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-bd3f1f9a", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-bd3f1f9a", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 101 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('ul', {
    staticClass: "nav nav-tabs",
    class: _vm.$style.nav
  }, [_c('router-link', {
    attrs: {
      "to": "/users",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("用户管理")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/users/setting",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("基础设置")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/users/roles",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("角色管理")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/users/permissions",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("权限管理")])])], 1), _vm._v(" "), _c('router-view')], 1)
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-bd3f1f9a", esExports)
  }
}

/***/ }),
/* 102 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserAdd_vue__ = __webpack_require__(103);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserAdd_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserAdd_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_3c69873a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_UserAdd_vue__ = __webpack_require__(104);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserAdd_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_3c69873a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_UserAdd_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/user/UserAdd.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] UserAdd.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3c69873a", Component.options)
  } else {
    hotAPI.reload("data-v-3c69873a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 103 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } } //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var UserAddComponent = {
  data: function data() {
    return {
      name: '',
      phone: '',
      email: '',
      password: '',
      adding: false,
      errorMessage: ''
    };
  },
  methods: {
    createUser: function createUser() {
      var _this = this;

      this.adding = true;
      _request2.default.post((0, _request.createRequestURI)('users'), { name: this.name, phone: this.phone, password: this.password }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref) {
        var userId = _ref.data.user_id;

        _this.$router.replace({ path: '/users/manage/' + userId });
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data,
            data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _data$name = data.name,
            name = _data$name === undefined ? [] : _data$name,
            _data$phone = data.phone,
            phone = _data$phone === undefined ? [] : _data$phone,
            _data$email = data.email,
            email = _data$email === undefined ? [] : _data$email,
            _data$password = data.password,
            password = _data$password === undefined ? [] : _data$password,
            _data$message = data.message,
            message = _data$message === undefined ? [] : _data$message;

        var _ref3 = [].concat(_toConsumableArray(name), _toConsumableArray(phone), _toConsumableArray(password), _toConsumableArray(message)),
            errorMessage = _ref3[0];

        _this.errorMessage = errorMessage;
        _this.adding = false;
      });
    },
    dismisError: function dismisError() {
      this.errorMessage = '';
    }
  }
};

exports.default = UserAddComponent;

/***/ }),
/* 104 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v("用户名")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.name),
      expression: "name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "name",
      "aria-describedby": "name-help-block",
      "placeholder": "请输入用户名"
    },
    domProps: {
      "value": (_vm.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "name-help-block"
    }
  }, [_vm._v("\n        请输入用户名，只能以非特殊字符和数字开头！\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "phone"
    }
  }, [_vm._v("手机号码")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.phone),
      expression: "phone"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "phone",
      "aria-describedby": "phone-help-block",
      "placeholder": "请输入手机号码"
    },
    domProps: {
      "value": (_vm.phone)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.phone = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "phone-help-block"
    }
  }, [_vm._v("\n        可选，手机号码\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "email"
    }
  }, [_vm._v("邮箱")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.email),
      expression: "email"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "email",
      "aria-describedby": "phone-help-block",
      "placeholder": "请输入邮箱地址"
    },
    domProps: {
      "value": (_vm.email)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.email = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "email-help-block"
    }
  }, [_vm._v("\n        可选，电子邮箱\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "password"
    }
  }, [_vm._v("密码")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.password),
      expression: "password"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "password",
      "autocomplete": "new-password",
      "id": "password",
      "aria-describedby": "password-help-block",
      "placeholder": "请输入用户密码"
    },
    domProps: {
      "value": (_vm.password)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.password = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "password-help-block"
    }
  }, [_vm._v("\n        用户密码\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-10"
  }, [(_vm.adding) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  })]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.createUser
    }
  }, [_vm._v("添加用户")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.errorMessage),
      expression: "errorMessage"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n      " + _vm._s(_vm.errorMessage) + "\n    ")])])])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3c69873a", esExports)
  }
}

/***/ }),
/* 105 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserManage_vue__ = __webpack_require__(106);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserManage_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserManage_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_712ba55c_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_UserManage_vue__ = __webpack_require__(107);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-712ba55c\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./UserManage.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(25)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-712ba55c")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(25)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UserManage_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_712ba55c_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_UserManage_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/user/UserManage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] UserManage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-712ba55c", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-712ba55c", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 106 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } } //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var UserManageComponent = {
  data: function data() {
    return {
      changeIn: false,
      password: '',
      error: null,
      user: {},
      roles: [],
      selecedRoles: []
    };
  },
  methods: {
    updateUser: function updateUser() {
      var _this = this;

      this.changeIn = true;
      var _user = this.user,
          id = _user.id,
          name = _user.name,
          phone = _user.phone,
          email = _user.email;

      var roles = this.selecedRoles;
      var password = this.password;
      _request2.default.patch((0, _request.createRequestURI)('users/' + id), { name: name, phone: phone, email: email, password: password, roles: roles }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this.changeIn = false;
      }).catch(function (_ref) {
        var _ref$response = _ref.response;
        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data,
            data = _ref$response$data === undefined ? {} : _ref$response$data;
        var _data$phone = data.phone,
            phone = _data$phone === undefined ? [] : _data$phone,
            _data$name = data.name,
            name = _data$name === undefined ? [] : _data$name,
            _data$email = data.email,
            email = _data$email === undefined ? [] : _data$email,
            _data$roles = data.roles,
            roles = _data$roles === undefined ? [] : _data$roles,
            _data$message = data.message,
            message = _data$message === undefined ? [] : _data$message;

        var _ref2 = [].concat(_toConsumableArray(phone), _toConsumableArray(name), _toConsumableArray(email), _toConsumableArray(roles), _toConsumableArray(message)),
            errorMessage = _ref2[0];

        _this.error = errorMessage;
        _this.changeIn = false;
      });
    },
    dismisError: function dismisError() {
      this.error = null;
    }
  },
  created: function created() {
    var _this2 = this;

    var userId = this.$route.params.userId;

    _request2.default.get((0, _request.createRequestURI)('users/' + userId), {
      params: { show_role: 1 },
      validateStatus: function validateStatus(status) {
        return status === 200;
      }
    }).then(function (_ref3) {
      var _ref3$data = _ref3.data,
          user = _ref3$data.user,
          roles = _ref3$data.roles;

      _this2.user = user;
      _this2.roles = roles;

      var selecedRoles = [];
      user.roles.forEach(function (role) {
        return selecedRoles.push(role.id);
      });
      _this2.selecedRoles = selecedRoles;
    }).catch(function (_ref4) {
      var _ref4$response = _ref4.response;
      _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
      var _ref4$response$data = _ref4$response.data;
      _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
      var _ref4$response$data$e = _ref4$response$data.errors,
          errors = _ref4$response$data$e === undefined ? [] : _ref4$response$data$e;

      var _errors = _slicedToArray(errors, 1),
          _errors$ = _errors[0],
          errorMessage = _errors$ === undefined ? '获取失败，请刷新重试！' : _errors$;

      _this2.error = errorMessage;
    });
  }
};

exports.default = UserManageComponent;

/***/ }),
/* 107 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v("用户名")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.user.name),
      expression: "user.name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "name",
      "aria-describedby": "name-help-block",
      "placeholder": "请输入用户名"
    },
    domProps: {
      "value": (_vm.user.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.user.name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "name-help-block"
    }
  }, [_vm._v("\n        请输入用户名，只能以非特殊字符和数字开头！\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "phone"
    }
  }, [_vm._v("手机号码")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.user.phone),
      expression: "user.phone"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "phone",
      "aria-describedby": "phone-help-block",
      "placeholder": "请输入手机号码"
    },
    domProps: {
      "value": (_vm.user.phone)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.user.phone = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "phone-help-block"
    }
  }, [_vm._v("\n        可选，手机号码\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "email"
    }
  }, [_vm._v("电子邮件")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.user.email),
      expression: "user.email"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "email",
      "aria-describedby": "email-help-block",
      "placeholder": "请输入邮箱地址"
    },
    domProps: {
      "value": (_vm.user.email)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.user.email = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "email-help-block"
    }
  }, [_vm._v("\n        可选，电子邮箱\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "password"
    }
  }, [_vm._v("新密码")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.password),
      expression: "password"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "password",
      "autocomplete": "new-password",
      "id": "password",
      "aria-describedby": "password-help-block",
      "placeholder": "请输入新的用户密码"
    },
    domProps: {
      "value": (_vm.password)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.password = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "password-help-block"
    }
  }, [_vm._v("\n        输入新密码，并提交后会改变当前用户的密码，留空则表示不变更。\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("角色")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, _vm._l((_vm.roles), function(role) {
    return _c('label', {
      key: role.id,
      class: _vm.$style.roleItem
    }, [_c('input', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.selecedRoles),
        expression: "selecedRoles"
      }],
      attrs: {
        "type": "checkbox"
      },
      domProps: {
        "value": role.id,
        "checked": Array.isArray(_vm.selecedRoles) ? _vm._i(_vm.selecedRoles, role.id) > -1 : (_vm.selecedRoles)
      },
      on: {
        "__c": function($event) {
          var $$a = _vm.selecedRoles,
            $$el = $event.target,
            $$c = $$el.checked ? (true) : (false);
          if (Array.isArray($$a)) {
            var $$v = role.id,
              $$i = _vm._i($$a, $$v);
            if ($$el.checked) {
              $$i < 0 && (_vm.selecedRoles = $$a.concat($$v))
            } else {
              $$i > -1 && (_vm.selecedRoles = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
            }
          } else {
            _vm.selecedRoles = $$c
          }
        }
      }
    }), _vm._v(" " + _vm._s(role.display_name) + "\n        ")])
  })), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "role-help-block"
    }
  }, [_vm._v("\n        选择用户拥有的角色\n      ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-10"
  }, [(_vm.changeIn) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  })]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.updateUser
    }
  }, [_vm._v("修改资料")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.error),
      expression: "error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n      " + _vm._s(_vm.error) + "\n    ")])])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-712ba55c", esExports)
  }
}

/***/ }),
/* 108 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Manage_vue__ = __webpack_require__(109);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Manage_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Manage_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_6a384d9e_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Manage_vue__ = __webpack_require__(110);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-6a384d9e\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Manage.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(26)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-6a384d9e")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(26)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Manage_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_6a384d9e_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Manage_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/user/Manage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Manage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-6a384d9e", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-6a384d9e", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 109 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var ManageComponent = {
  /**
   * 定义当前组件状态数据
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  data: function data() {
    return {
      userId: '',
      sort: 'down',
      email: '',
      name: '',
      role: 0,
      phone: '',
      lastPage: 1,
      page: 1,
      perPage: 20,
      total: 0,
      users: [],
      loadding: false,
      showRole: true,
      roles: [],
      deleteIds: [],
      error: null
    };
  },
  computed: {
    queryParams: function queryParams() {
      var userId = this.userId,
          sort = this.sort,
          email = this.email,
          name = this.name,
          phone = this.phone,
          role = this.role,
          perPage = this.perPage,
          page = this.page;

      return { userId: userId, sort: sort, email: email, name: name, phone: phone, role: role, perPage: perPage, page: page };
    },
    prevQuery: function prevQuery() {
      var page = parseInt(this.page);
      return _extends({}, this.queryParams, {
        lastPage: this.lastPage,
        page: page > 1 ? page - 1 : page
      });
    },
    nextQuery: function nextQuery() {
      var page = parseInt(this.page);
      var lastPage = parseInt(this.lastPage);
      return _extends({}, this.queryParams, {
        lastPage: lastPage,
        page: page < lastPage ? page + 1 : lastPage
      });
    },
    searchQuery: function searchQuery() {
      return _extends({}, this.queryParams, {
        page: 1
      });
    }
  },
  watch: {
    '$route': function $route(to) {
      var _to$query = to.query,
          _to$query$email = _to$query.email,
          email = _to$query$email === undefined ? '' : _to$query$email,
          _to$query$name = _to$query.name,
          name = _to$query$name === undefined ? '' : _to$query$name,
          _to$query$phone = _to$query.phone,
          phone = _to$query$phone === undefined ? '' : _to$query$phone,
          _to$query$role = _to$query.role,
          role = _to$query$role === undefined ? 0 : _to$query$role,
          _to$query$sort = _to$query.sort,
          sort = _to$query$sort === undefined ? 'down' : _to$query$sort,
          _to$query$userId = _to$query.userId,
          userId = _to$query$userId === undefined ? '' : _to$query$userId,
          _to$query$lastPage = _to$query.lastPage,
          lastPage = _to$query$lastPage === undefined ? 1 : _to$query$lastPage,
          _to$query$perPage = _to$query.perPage,
          perPage = _to$query$perPage === undefined ? 20 : _to$query$perPage,
          _to$query$page = _to$query.page,
          page = _to$query$page === undefined ? 1 : _to$query$page;


      this.email = email;
      this.name = name;
      this.phone = phone;
      this.role = role;
      this.sort = sort;
      this.userId = userId;
      this.lastPage = parseInt(lastPage);
      this.perPage = parseInt(perPage);
      this.page = parseInt(page);

      this.getUsers();
    }
  },
  /**
   * 定义方法组.
   *
   * @type {Object}
   */
  methods: {
    deleteIdsUnTo: function deleteIdsUnTo(userId) {
      var deleteIds = [];
      this.deleteIds.forEach(function (id) {
        if (parseInt(id) !== parseInt(userId)) {
          deleteIds.push(id);
        }
      });
      this.deleteIds = deleteIds;
    },
    deleteUser: function deleteUser(userId) {
      var _this = this;

      if (window.confirm('确定要删除用户吗？')) {
        this.deleteIds = [].concat(_toConsumableArray(this.deleteIds), [userId]);
        _request2.default.delete((0, _request.createRequestURI)('users/' + userId), { validateStatus: function validateStatus(status) {
            return status === 204;
          } }).then(function () {
          _this.deleteIdsUnTo(userId);
          var users = [];
          _this.users.forEach(function (user) {
            if (user.id !== userId) {
              users.push(user);
            }
          });
          _this.users = users;
        }).catch(function (_ref) {
          var _ref$response = _ref.response;
          _ref$response = _ref$response === undefined ? {} : _ref$response;
          var _ref$response$data = _ref$response.data;
          _ref$response$data = _ref$response$data === undefined ? {} : _ref$response$data;
          var _ref$response$data$er = _ref$response$data.errors,
              errors = _ref$response$data$er === undefined ? ['删除失败'] : _ref$response$data$er;

          _this.deleteIdsUnTo(userId);
          _this.error = _lodash2.default.values(errors).pop();
        });
      }
    },

    /**
     * 改变用户排序状态方法.
     *
     * @enum {up, down}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    changeUserIdSort: function changeUserIdSort(sort) {
      this.sort = sort;
    },

    /**
     * 获取列表用户.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    getUsers: function getUsers() {
      var _this2 = this;

      this.loadding = true;
      _request2.default.get((0, _request.createRequestURI)('users'), {
        params: _extends({}, this.queryParams, { show_role: this.showRole }),
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (response) {
        var _response$data = response.data,
            data = _response$data.page,
            roles = _response$data.roles;


        _this2.users = data || [];
        _this2.lastPage = parseInt(data.last_page);
        _this2.total = parseInt(data.total);
        _this2.loadding = false;
        _this2.showRole = false;

        _this2.roles = roles;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$e = _ref2$response$data.errors,
            errors = _ref2$response$data$e === undefined ? ['加载用户失败'] : _ref2$response$data$e;

        _this2.error = _lodash2.default.values(errors).pop();
        _this2.loadding = false;
      });
    },
    dismisError: function dismisError() {
      this.error = null;
    }
  },
  /**
   * 组件创建完成后.
   *
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  created: function created() {
    var _$route$query = this.$route.query,
        _$route$query$email = _$route$query.email,
        email = _$route$query$email === undefined ? '' : _$route$query$email,
        _$route$query$name = _$route$query.name,
        name = _$route$query$name === undefined ? '' : _$route$query$name,
        _$route$query$phone = _$route$query.phone,
        phone = _$route$query$phone === undefined ? '' : _$route$query$phone,
        _$route$query$role = _$route$query.role,
        role = _$route$query$role === undefined ? 0 : _$route$query$role,
        _$route$query$sort = _$route$query.sort,
        sort = _$route$query$sort === undefined ? 'down' : _$route$query$sort,
        _$route$query$userId = _$route$query.userId,
        userId = _$route$query$userId === undefined ? '' : _$route$query$userId,
        _$route$query$lastPag = _$route$query.lastPage,
        lastPage = _$route$query$lastPag === undefined ? 1 : _$route$query$lastPag,
        _$route$query$perPage = _$route$query.perPage,
        perPage = _$route$query$perPage === undefined ? 20 : _$route$query$perPage,
        _$route$query$page = _$route$query.page,
        page = _$route$query$page === undefined ? 1 : _$route$query$page;
    // set state.

    this.email = email;
    this.name = name;
    this.phone = phone;
    this.role = role;
    this.sort = sort;
    this.userId = userId;
    this.lastPage = parseInt(lastPage);
    this.perPage = parseInt(perPage);
    this.page = parseInt(page);
    this.showRole = true;

    this.getUsers();
  }
};

exports.default = ManageComponent;

/***/ }),
/* 110 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    class: _vm.$style.container
  }, [_c('div', {
    staticClass: "well well-sm"
  }, [_vm._v("\n    检索用户\n    "), _c('router-link', {
    staticClass: "btn btn-link pull-right btn-xs",
    attrs: {
      "tag": "a",
      "to": "/users/add",
      "role": "button"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-plus"
  }), _vm._v("\n      添加用户\n    ")])], 1), _vm._v(" "), _c('div', {
    staticClass: "form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "search-input-id"
    }
  }, [_vm._v("用户ID")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-8"
  }, [_c('div', {
    staticClass: "input-group"
  }, [_c('div', {
    staticClass: "input-group-btn"
  }, [_c('button', {
    staticClass: "btn btn-default dropdown-toggle",
    attrs: {
      "type": "button",
      "data-toggle": "dropdown",
      "aria-haspopup": "true",
      "aria-expanded": "false"
    }
  }, [_vm._v("\n              排序 "), _c('span', {
    staticClass: "glyphicon",
    class: (_vm.sort === 'up' ? 'glyphicon-triangle-top' : 'glyphicon-triangle-bottom')
  })]), _vm._v(" "), _c('ul', {
    staticClass: "dropdown-menu"
  }, [_c('li', [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeUserIdSort('up')
      }
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-triangle-top"
  }), _vm._v("\n                由小到大\n              ")])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeUserIdSort('down')
      }
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-triangle-bottom"
  }), _vm._v("\n                由大到小\n              ")])])])]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.userId),
      expression: "userId"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "number",
      "id": "search-input-id",
      "placeholder": "按照用户ID搜索"
    },
    domProps: {
      "value": (_vm.userId)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.userId = $event.target.value
      }
    }
  })])])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "search-input-email"
    }
  }, [_vm._v("邮箱")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-8"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.email),
      expression: "email"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "search-input-email",
      "placeholder": "请输入搜索邮箱地址，支持模糊搜索"
    },
    domProps: {
      "value": (_vm.email)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.email = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "search-input-phone"
    }
  }, [_vm._v("手机号码")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-8"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.phone),
      expression: "phone"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "tel",
      "id": "search-input-phone",
      "placeholder": "请输入搜索手机号码，支持模糊搜索"
    },
    domProps: {
      "value": (_vm.phone)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.phone = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "search-input-name"
    }
  }, [_vm._v("用户名")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-8"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.name),
      expression: "name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "search-input-name",
      "placeholder": "请输入搜索用户名，支持模糊搜索"
    },
    domProps: {
      "value": (_vm.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.name = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "search-input-name"
    }
  }, [_vm._v("角色")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-8"
  }, [_c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.role),
      expression: "role"
    }],
    staticClass: "form-control",
    attrs: {
      "id": "search-input-name"
    },
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.role = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, [_c('option', {
    attrs: {
      "value": "0"
    }
  }, [_vm._v("全部")]), _vm._v(" "), _vm._l((_vm.roles), function(ref) {
    var id = ref.id;
    var display_name = ref.display_name;

    return _c('option', {
      key: id,
      domProps: {
        "value": id
      }
    }, [_vm._v(_vm._s(display_name))])
  })], 2)])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-10"
  }, [_c('router-link', {
    staticClass: "btn btn-default",
    attrs: {
      "tag": "button",
      "to": {
        path: '/users',
        query: _vm.searchQuery
      }
    }
  }, [_vm._v("\n          搜索\n        ")])], 1)])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.error),
      expression: "error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n    " + _vm._s(_vm.error) + "\n  ")]), _vm._v(" "), _c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', [_c('tr', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }]
  }, [_c('td', {
    class: _vm.$style.loadding,
    attrs: {
      "colspan": "6"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })])]), _vm._v(" "), _vm._l((_vm.users), function(user) {
    return _c('tr', {
      key: user.id
    }, [_c('td', [_vm._v(_vm._s(user.id))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(user.name))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(user.email))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(user.phone))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(user.created_at))]), _vm._v(" "), _c('td', [_c('router-link', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "to": ("/users/manage/" + (user.id))
      }
    }, [_vm._v("编辑")]), _vm._v(" "), (_vm.deleteIds.indexOf(user.id) !== -1) ? _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_c('span', {
      staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
    })]) : _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          _vm.deleteUser(user.id)
        }
      }
    }, [_vm._v("删除")])], 1)])
  })], 2)]), _vm._v(" "), _c('ul', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.page >= 1 && _vm.lastPage > 1),
      expression: "page >= 1 && lastPage > 1"
    }],
    staticClass: "pager"
  }, [_c('li', {
    staticClass: "previous",
    class: _vm.page <= 1 ? 'disabled' : ''
  }, [_c('router-link', {
    attrs: {
      "to": {
        path: '/users',
        query: _vm.prevQuery
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("←")]), _vm._v("\n        上一页\n      ")])], 1), _vm._v(" "), _c('li', {
    staticClass: "next",
    class: _vm.page >= _vm.lastPage ? 'disabled' : ''
  }, [_c('router-link', {
    attrs: {
      "to": {
        path: '/users',
        query: _vm.nextQuery
      }
    }
  }, [_vm._v("\n        下一页\n        "), _c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("→")])])], 1)])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("用户ID")]), _vm._v(" "), _c('th', [_vm._v("用户名")]), _vm._v(" "), _c('th', [_vm._v("邮箱")]), _vm._v(" "), _c('th', [_vm._v("手机号码")]), _vm._v(" "), _c('th', [_vm._v("注册时间")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-6a384d9e", esExports)
  }
}

/***/ }),
/* 111 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Roles_vue__ = __webpack_require__(112);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Roles_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Roles_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_695690a1_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Roles_vue__ = __webpack_require__(113);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-695690a1\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Roles.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(27)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-695690a1")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(27)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Roles_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_695690a1_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Roles_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/user/Roles.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Roles.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-695690a1", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-695690a1", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 112 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } } //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var RolesComponent = {
  /**
   * The component state tree.
   *
   * @return {Object} state tree
   * @author Seven Du <shiweidu@outlook.com>
   */
  data: function data() {
    return {
      /**
       * roles
       *
       * @type {Array}
       */
      roles: [],
      /**
       * is loadding.
       *
       * @type {Boolean}
       */
      loadding: true,
      /**
       * delete role ids.
       *
       * @type {Array}
       */
      deleteIds: {},
      add: {
        name: '',
        display_name: '',
        description: '',
        adding: false
      },
      error: null
    };
  },
  /**
   * methods.
   *
   * @type {Object}
   */
  methods: {
    /**
     * 创建角色
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    postRole: function postRole() {
      var _this = this;

      this.add.adding = true;
      var _add = this.add,
          name = _add.name,
          display_name = _add.display_name,
          description = _add.description;

      _request2.default.post((0, _request.createRequestURI)('roles'), { name: name, display_name: display_name, description: description }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref) {
        var data = _ref.data;

        _this.roles = [].concat(_toConsumableArray(_this.roles), [data]);
        _this.add = {
          name: '',
          display_name: '',
          description: '',
          adding: false
        };
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data,
            data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _data$errors = data.errors,
            errors = _data$errors === undefined ? ['添加失败'] : _data$errors;

        var errorMessage = _lodash2.default.values(errors).pop();
        _this.add.adding = false;
        _this.error = errorMessage;
      });
    },

    /**
     * delete this.deleteIds item.
     *
     * @param {Number} id
     * @author Seven Du <shiweidu@outlook.com>
     */
    deleteIdsItem: function deleteIdsItem(id) {
      var ids = {};
      for (var _id in this.deleteIds) {
        if (parseInt(_id) !== parseInt(id)) {
          ids[_id] = id;
        }
      }

      this.deleteIds = ids;
    },

    /**
     * delete role.
     *
     * @param {Number} id
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    deleteRole: function deleteRole(id) {
      var _this2 = this;

      if (window.confirm('是否确认删除？')) {
        this.deleteIds = _extends({}, this.deleteIds, _defineProperty({}, id, id));
      }

      _request2.default.delete((0, _request.createRequestURI)('roles/' + id), { validateStatus: function validateStatus(status) {
          return status === 204;
        } }).then(function () {
        _this2.deleteIdsItem(id);
        var roles = [];
        _this2.roles.forEach(function (role) {
          if (role.id !== id) {
            roles.push(role);
          }
        });
        _this2.roles = roles;
      }).catch(function (_ref3) {
        var _ref3$response = _ref3.response;
        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data;
        _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
        var _ref3$response$data$e = _ref3$response$data.errors,
            errors = _ref3$response$data$e === undefined ? ['删除失败'] : _ref3$response$data$e;

        _this2.deleteIdsItem(id);
        _this2.error = _lodash2.default.values(errors).pop();
      });
    },
    dismisError: function dismisError() {
      this.error = null;
    }
  },
  /**
   * The component created run.
   *
   * @author Seven Du <shiweidu@outlook.com>
   */
  created: function created() {
    var _this3 = this;

    this.loadding = true;
    _request2.default.get((0, _request.createRequestURI)('roles'), { validateStatus: function validateStatus(status) {
        return status === 200;
      } }).then(function (_ref4) {
      var data = _ref4.data;

      _this3.loadding = false;
      _this3.roles = data;
    }).catch(function (_ref5) {
      var _ref5$response = _ref5.response;
      _ref5$response = _ref5$response === undefined ? {} : _ref5$response;
      var _ref5$response$data = _ref5$response.data;
      _ref5$response$data = _ref5$response$data === undefined ? {} : _ref5$response$data;
      var _ref5$response$data$e = _ref5$response$data.errors,
          errors = _ref5$response$data$e === undefined ? ['加载失败,请刷新重试！'] : _ref5$response$data$e;

      _this3.loadding = false;
      _this3.error = _lodash2.default.values(errors).pop();
    });
  }
};

exports.default = RolesComponent;

/***/ }),
/* 113 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    class: _vm.$style.container
  }, [_c('div', {
    staticClass: "alert alert-success",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n    尽量不要删除用户组～删除用户组会造成用户组混乱！请谨慎编辑。\n  ")]), _vm._v(" "), _c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', [_vm._l((_vm.roles), function(role) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(role.name))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(role.display_name))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(role.description))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(role.updated_at))]), _vm._v(" "), _c('td', [_c('router-link', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "to": ("" + (role.id)),
        "tag": "button",
        "append": "",
        "exact": "",
        "type": "button"
      }
    }, [_vm._v("管理")]), _vm._v(" "), (_vm.deleteIds.hasOwnProperty(role.id)) ? _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_c('span', {
      staticClass: "glyphicon glyphicon-refresh",
      class: _vm.$style.loaddingIcon
    })]) : _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          $event.preventDefault();
          _vm.deleteRole(role.id)
        }
      }
    }, [_vm._v("删除")])], 1)])
  }), _vm._v(" "), _c('tr', [_c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.name),
      expression: "add.name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入角色名称"
    },
    domProps: {
      "value": (_vm.add.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.name = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.display_name),
      expression: "add.display_name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入显示名称"
    },
    domProps: {
      "value": (_vm.add.display_name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.display_name = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.description),
      expression: "add.description"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入节点描述"
    },
    domProps: {
      "value": (_vm.add.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.description = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td'), _vm._v(" "), _c('td', [(_vm.add.adding) ? _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })]) : _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "pervent")) { return null; }
        _vm.postRole($event)
      }
    }
  }, [_vm._v("添加")])])])], 2)]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.error),
      expression: "error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n    " + _vm._s(_vm.error) + "\n  ")]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }],
    class: _vm.$style.loadding
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("角色名称")]), _vm._v(" "), _c('th', [_vm._v("显示名称")]), _vm._v(" "), _c('th', [_vm._v("描述")]), _vm._v(" "), _c('th', [_vm._v("更新时间")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-695690a1", esExports)
  }
}

/***/ }),
/* 114 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RoleManage_vue__ = __webpack_require__(115);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RoleManage_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RoleManage_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_9076bf72_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_RoleManage_vue__ = __webpack_require__(116);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RoleManage_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_9076bf72_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_RoleManage_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/user/RoleManage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] RoleManage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-9076bf72", Component.options)
  } else {
    hotAPI.reload("data-v-9076bf72", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 115 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var RoleManageComponent = {
  data: function data() {
    return {
      perms: [],
      seleced: [],
      role: {},
      loadding: false,
      submit: false,
      error: null
    };
  },
  computed: {
    checkBoxSelectAll: {
      get: function get() {
        return this.perms.length === this.seleced.length;
      },
      set: function set(value) {
        if (value === false) {
          this.seleced = [];
          return;
        }

        var seleced = [];
        this.perms.forEach(function (perm) {
          return seleced.push(perm.id);
        });
        this.seleced = seleced;
      }
    }
  },
  methods: {
    postPerms: function postPerms() {
      var _this = this;

      var seleced = this.seleced;
      var id = this.role.id;

      this.submit = true;
      _request2.default.patch((0, _request.createRequestURI)('roles/' + id), { perms: seleced }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this.submit = false;
      }).catch(function (_ref) {
        var _ref$response = _ref.response;
        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data;
        _ref$response$data = _ref$response$data === undefined ? {} : _ref$response$data;
        var _ref$response$data$er = _ref$response$data.errors,
            errors = _ref$response$data$er === undefined ? ['更新失败'] : _ref$response$data$er;

        _this.submit = false;
        _this.error = _lodash2.default.values(errors).pop();
      });
    },
    goBack: function goBack() {
      this.$router.back();
    },
    dismisError: function dismisError() {
      this.error = null;
    }
  },
  created: function created() {
    var _this2 = this;

    var id = this.$route.params.role;

    this.loadding = true;
    _request2.default.get((0, _request.createRequestURI)('roles/' + id), {
      params: {
        all_perms: true,
        perms: true
      },
      validateStatus: function validateStatus(status) {
        return status === 200;
      }
    }).then(function (_ref2) {
      var data = _ref2.data;
      var perms = data.perms,
          role = data.role;

      _this2.perms = perms;
      _this2.role = role;

      var seleced = [];
      role.perms.forEach(function (perm) {
        return seleced.push(perm.id);
      });
      _this2.seleced = seleced;
      _this2.loadding = false;
    }).catch(function (_ref3) {
      var _ref3$response = _ref3.response;
      _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
      var _ref3$response$data = _ref3$response.data;
      _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
      var _ref3$response$data$e = _ref3$response$data.errors,
          errors = _ref3$response$data$e === undefined ? ['加载失败，请刷新重试！'] : _ref3$response$data$e;

      _this2.loadding = false;
      _this2.error = _lodash2.default.values(errors).pop();
    });
  }
};

exports.default = RoleManageComponent;

/***/ }),
/* 116 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('button', {
    staticClass: "btn btn-default",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.goBack
    }
  }, [_vm._v("返回")]), _vm._v(" "), _c('table', {
    staticClass: "table table-striped"
  }, [_c('thead', [_c('tr', [_c('th', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.checkBoxSelectAll),
      expression: "checkBoxSelectAll"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.checkBoxSelectAll) ? _vm._i(_vm.checkBoxSelectAll, null) > -1 : (_vm.checkBoxSelectAll)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.checkBoxSelectAll,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$el.checked) {
            $$i < 0 && (_vm.checkBoxSelectAll = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.checkBoxSelectAll = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.checkBoxSelectAll = $$c
        }
      }
    }
  })]), _vm._v(" "), _c('th', [_vm._v("节点名称")]), _vm._v(" "), _c('th', [_vm._v("显示名称")]), _vm._v(" "), _c('th', [_vm._v("描述")])])]), _vm._v(" "), _c('tbody', _vm._l((_vm.perms), function(perm) {
    return _c('tr', {
      on: {
        "key": perm.id
      }
    }, [_c('th', [_c('input', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.seleced),
        expression: "seleced"
      }],
      attrs: {
        "type": "checkbox"
      },
      domProps: {
        "value": perm.id,
        "checked": Array.isArray(_vm.seleced) ? _vm._i(_vm.seleced, perm.id) > -1 : (_vm.seleced)
      },
      on: {
        "__c": function($event) {
          var $$a = _vm.seleced,
            $$el = $event.target,
            $$c = $$el.checked ? (true) : (false);
          if (Array.isArray($$a)) {
            var $$v = perm.id,
              $$i = _vm._i($$a, $$v);
            if ($$el.checked) {
              $$i < 0 && (_vm.seleced = $$a.concat($$v))
            } else {
              $$i > -1 && (_vm.seleced = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
            }
          } else {
            _vm.seleced = $$c
          }
        }
      }
    })]), _vm._v(" "), _c('td', [_vm._v(_vm._s(perm.name))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(perm.display_name))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(perm.description))])])
  }))]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }],
    staticClass: "component-loadding"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  })]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.error),
      expression: "error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n    " + _vm._s(_vm.error) + "\n  ")]), _vm._v(" "), (_vm.submit) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  })]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.postPerms
    }
  }, [_vm._v("提交")])])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-9076bf72", esExports)
  }
}

/***/ }),
/* 117 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Permissions_vue__ = __webpack_require__(118);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Permissions_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Permissions_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_38579c30_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Permissions_vue__ = __webpack_require__(119);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-38579c30\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Permissions.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(28)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-38579c30")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(28)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Permissions_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_38579c30_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Permissions_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/user/Permissions.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Permissions.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-38579c30", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-38579c30", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 118 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } } //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var PermissionsComponent = {
  data: function data() {
    return {
      perms: [],
      deleteIds: {},
      add: {
        name: '',
        display_name: '',
        description: '',
        adding: false
      },
      loadding: true,
      error: null
    };
  },
  methods: {
    postPerm: function postPerm() {
      var _this = this;

      console.log(2);
      var _add = this.add,
          name = _add.name,
          display_name = _add.display_name,
          description = _add.description;

      this.add.adding = true;
      _request2.default.post((0, _request.createRequestURI)('perms'), { name: name, display_name: display_name, description: description }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref) {
        var data = _ref.data;

        _this.perms = [].concat(_toConsumableArray(_this.perms), [data]);
        _this.add = {
          name: '',
          display_name: '',
          description: '',
          adding: false
        };
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data,
            data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _data$errors = data.errors,
            errors = _data$errors === undefined ? ['更新失败'] : _data$errors;

        var errorMessage = _lodash2.default.values(errors).pop();
        _this.error = errorMessage;
        _this.add.adding = false;
      });
    },
    updatePerm: function updatePerm(id, key, value) {
      var _this2 = this;

      _request2.default.patch((0, _request.createRequestURI)('perms/' + id), { key: key, value: value }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        // todo
        // 因为没有用到状态管理～无序重新设置！
      }).catch(function (_ref3) {
        var _ref3$response = _ref3.response;
        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data,
            data = _ref3$response$data === undefined ? {} : _ref3$response$data;
        var _data$errors2 = data.errors,
            errors = _data$errors2 === undefined ? ['更新失败'] : _data$errors2;

        var errorMessage = _lodash2.default.values(errors).pop();
        _this2.error = errorMessage;
      });
    },
    deletePerm: function deletePerm(id) {
      var _this3 = this;

      if (window.confirm('确认删除节点？')) {
        this.deleteIds = _extends({}, this.deleteIds, _defineProperty({}, id, id));

        var deleteId = function deleteId(id) {
          var ids = {};
          for (var _id in _this3.deleteIds) {
            if (parseInt(_id) !== parseInt(id)) {
              ids = _extends({}, ids, _defineProperty({}, _id, _id));
            }
          }
          _this3.deleteIds = ids;
        };

        _request2.default.delete((0, _request.createRequestURI)('perms/' + id), { validateStatus: function validateStatus(status) {
            return status === 204;
          } }).then(function () {
          deleteId(id);
          _this3.deletePermToState(id);
        }).catch(function (_ref4) {
          var _ref4$response = _ref4.response;
          _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
          var _ref4$response$data = _ref4$response.data,
              data = _ref4$response$data === undefined ? {} : _ref4$response$data;
          var _data$errors3 = data.errors,
              errors = _data$errors3 === undefined ? ['删除失败'] : _data$errors3;

          var errorMessage = _lodash2.default.values(errors).pop();
          deleteId(id);
          _this3.error = errorMessage;
        });
      }
    },
    deletePermToState: function deletePermToState(id) {
      var perms = [];
      this.perms.forEach(function (perm) {
        if (parseInt(perm.id) !== parseInt(id)) {
          perms.push(perm);
        }
      });
      this.perms = perms;
    },
    dismisError: function dismisError() {
      this.error = null;
    }
  },
  created: function created() {
    var _this4 = this;

    _request2.default.get((0, _request.createRequestURI)('perms'), { validateStatus: function validateStatus(status) {
        return status === 200;
      } }).then(function (_ref5) {
      var data = _ref5.data;

      _this4.perms = data;
      _this4.loadding = false;
    }).catch(function (_ref6) {
      var _ref6$response = _ref6.response;
      _ref6$response = _ref6$response === undefined ? {} : _ref6$response;
      var _ref6$response$data = _ref6$response.data;
      _ref6$response$data = _ref6$response$data === undefined ? {} : _ref6$response$data;
      var _ref6$response$data$e = _ref6$response$data.errors,
          errors = _ref6$response$data$e === undefined ? ['获取失败，请刷新重试！'] : _ref6$response$data$e;

      _this4.error = _lodash2.default.values(errors).pop();
      _this4.loadding = false;
    });
  }
};

exports.default = PermissionsComponent;

/***/ }),
/* 119 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    class: _vm.$style.container
  }, [_vm._m(0), _vm._v(" "), _c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(1), _vm._v(" "), _c('tbody', [_vm._l((_vm.perms), function(perm) {
    return _c('tr', {
      key: perm.id
    }, [_c('td', [_vm._v(_vm._s(perm.name))]), _vm._v(" "), _c('td', [_c('div', {
      staticClass: "input-group"
    }, [_c('input', {
      staticClass: "form-control",
      attrs: {
        "type": "text",
        "placeholder": "输入名称"
      },
      domProps: {
        "value": perm.display_name
      },
      on: {
        "change": function($event) {
          if (!('button' in $event) && _vm._k($event.keyCode, "lazy")) { return null; }
          _vm.updatePerm(perm.id, 'display_name', $event.target.value)
        }
      }
    })])]), _vm._v(" "), _c('td', [_c('div', {
      staticClass: "input-group"
    }, [_c('input', {
      staticClass: "form-control",
      attrs: {
        "type": "text",
        "placeholder": "输入名称"
      },
      domProps: {
        "value": perm.description
      },
      on: {
        "change": function($event) {
          if (!('button' in $event) && _vm._k($event.keyCode, "lazy")) { return null; }
          _vm.updatePerm(perm.id, 'description', $event.target.value)
        }
      }
    })])]), _vm._v(" "), _c('td', [_vm._v(_vm._s(perm.updated_at))]), _vm._v(" "), _c('td', [(_vm.deleteIds.hasOwnProperty(perm.id)) ? _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_c('span', {
      staticClass: "glyphicon glyphicon-refresh",
      class: _vm.$style.loaddingIcon
    })]) : _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          $event.preventDefault();
          _vm.deletePerm(perm.id)
        }
      }
    }, [_vm._v("删除")])])])
  }), _vm._v(" "), _c('tr', [_c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.name),
      expression: "add.name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入节点名称"
    },
    domProps: {
      "value": (_vm.add.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.name = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.display_name),
      expression: "add.display_name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入显示名称"
    },
    domProps: {
      "value": (_vm.add.display_name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.display_name = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td', [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.description),
      expression: "add.description"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入节点描述"
    },
    domProps: {
      "value": (_vm.add.description)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.description = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('td'), _vm._v(" "), _c('td', [(_vm.add.adding) ? _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })]) : _c('button', {
    staticClass: "btn btn-primary btn-sm",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "pervent")) { return null; }
        _vm.postPerm($event)
      }
    }
  }, [_vm._v("添加")])])])], 2)]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadding),
      expression: "loadding"
    }],
    class: _vm.$style.loadding
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh",
    class: _vm.$style.loaddingIcon
  })]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.error),
      expression: "error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n    " + _vm._s(_vm.error) + "\n  ")])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "alert alert-success",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n    权限节点，用于各个位置标示用户权限的配置～配置需要配合程序。尽量不要删除权限节点～以为节点name是在程序中赢编码的～\n    这里提供管理，只是方便技术人员对节点进行管理。\n    "), _c('p', [_vm._v("编辑节点内容，修改完成后可直接回车或者留任不管～失去焦点后会自动保存。")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("节点名称")]), _vm._v(" "), _c('th', [_vm._v("显示名称")]), _vm._v(" "), _c('th', [_vm._v("描述")]), _vm._v(" "), _c('th', [_vm._v("更新时间")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-38579c30", esExports)
  }
}

/***/ }),
/* 120 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Setting_vue__ = __webpack_require__(121);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Setting_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Setting_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_e8d66898_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Setting_vue__ = __webpack_require__(122);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Setting_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_e8d66898_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Setting_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/user/Setting.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Setting.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-e8d66898", Component.options)
  } else {
    hotAPI.reload("data-v-e8d66898", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 121 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request2 = __webpack_require__(1);

var _request3 = _interopRequireDefault(_request2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var UserSettingComponent = {
  data: function data() {
    return {
      loadding: {
        state: 0,
        message: ''
      },
      option: {},
      roles: [],
      submit: {
        state: false,
        type: 'muted',
        message: ''
      }
    };
  },
  methods: {
    request: function request() {
      var _this = this;

      this.loadding.state = 0;
      _request3.default.get((0, _request2.createRequestURI)('user/setting'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;
        var _data$roles = data.roles,
            roles = _data$roles === undefined ? [] : _data$roles,
            current_role = data.current_role;

        _this.roles = roles;
        _this.option = _extends({}, _this.option, {
          role: current_role
        });
        _this.loadding.state = 1;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            _ref2$response$data$m3 = _ref2$response$data$m2[0],
            message = _ref2$response$data$m3 === undefined ? '加载失败' : _ref2$response$data$m3;

        _this.loadding = {
          state: 2,
          message: message
        };
      });
    },
    submitHandle: function submitHandle() {
      var _this2 = this;

      var role = this.option.role;

      this.submit.state = true;
      _request3.default.patch((0, _request2.createRequestURI)('user/setting'), { role: role }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref3) {
        var _ref3$data = _ref3.data,
            data = _ref3$data === undefined ? {} : _ref3$data;
        var _data$message = data.message;
        _data$message = _data$message === undefined ? [] : _data$message;

        var _data$message2 = _slicedToArray(_data$message, 1),
            message = _data$message2[0];

        _this2.submit = {
          state: false,
          type: 'success',
          message: message
        };
        window.setTimeout(function () {
          _this2.submit.message = '';
        }, 1500);
      }).catch(function (_ref4) {
        var _ref4$response = _ref4.response;
        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$m = _ref4$response$data.message,
            message = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m,
            _ref4$response$data$r = _ref4$response$data.role,
            role = _ref4$response$data$r === undefined ? [] : _ref4$response$data$r;

        var _ref5 = [].concat(_toConsumableArray(role), _toConsumableArray(message)),
            _ref5$ = _ref5[0],
            currentMessage = _ref5$ === undefined ? '提交失败' : _ref5$;

        _this2.submit = {
          state: false,
          type: danger,
          message: currentMessage
        };
      });
    }
  },
  created: function created() {
    this.request();
  }
};

exports.default = UserSettingComponent;

/***/ }),
/* 122 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("设置用户相关基础信息")]), _vm._v(" "), (_vm.loadding.state === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.loadding.state === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "app-key"
    }
  }, [_vm._v("默认用户组")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.option.role),
      expression: "option.role"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.option.role = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, _vm._l((_vm.roles), function(role) {
    return _c('option', {
      key: role.id,
      domProps: {
        "value": role.id
      }
    }, [_vm._v(_vm._s(role.display_name))])
  }))]), _vm._v(" "), _vm._m(0)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [(_vm.submit.state === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n            提交...\n          ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.submitHandle($event)
      }
    }
  }, [_vm._v("提交")])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6 help-block"
  }, [_c('span', {
    class: ("text-" + (_vm.submit.type))
  }, [_vm._v(_vm._s(_vm.submit.message))])])])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.loadding.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.request($event)
      }
    }
  }, [_vm._v("刷新")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "app-key-help"
    }
  }, [_vm._v("选择用户注册的默认用户组")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-e8d66898", esExports)
  }
}

/***/ }),
/* 123 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Main = __webpack_require__(124);

var _Main2 = _interopRequireDefault(_Main);

var _Home = __webpack_require__(126);

var _Home2 = _interopRequireDefault(_Home);

var _Alidayu = __webpack_require__(129);

var _Alidayu2 = _interopRequireDefault(_Alidayu);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var smsRouter = {
  path: 'sms',
  component: _Main2.default,
  children: [{ path: '', component: _Home2.default }, { path: 'alidayu', component: _Alidayu2.default }]
}; //
// The file is defined "/sms" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//

exports.default = smsRouter;

/***/ }),
/* 124 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_3f846317_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__ = __webpack_require__(125);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_3f846317_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/sms/Main.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Main.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3f846317", Component.options)
  } else {
    hotAPI.reload("data-v-3f846317", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 125 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('ul', {
    staticClass: "nav nav-tabs component-controller-nav"
  }, [_c('router-link', {
    attrs: {
      "to": "/sms",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("验证码记录")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/sms/alidayu",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("阿里大于")])])], 1), _vm._v(" "), _c('router-view')], 1)
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3f846317", esExports)
  }
}

/***/ }),
/* 126 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue__ = __webpack_require__(127);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_230a4f3d_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Home_vue__ = __webpack_require__(128);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_230a4f3d_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Home_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/sms/Home.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Home.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-230a4f3d", Component.options)
  } else {
    hotAPI.reload("data-v-230a4f3d", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 127 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var SmsMainComponent = {
  data: function data() {
    return {
      search: {
        state: -1,
        keyword: ''
      },
      loading: false,
      logs: [],
      error: null,
      currentPage: 1
    };
  },
  methods: {
    nextPage: function nextPage() {
      this.requestLogs(parseInt(this.currentPage) + 1);
    },
    prevPage: function prevPage() {
      this.requestLogs(parseInt(this.currentPage) - 1);
    },
    searchHandle: function searchHandle() {
      this.requestLogs(1);
    },
    dismisError: function dismisError() {
      this.error = null;
    },
    changeState: function changeState(state) {
      this.search.after = [];
      this.search.state = state;
    },
    requestLogs: function requestLogs(page) {
      var _this = this;

      var params = { page: page };

      if (this.search.state >= 0) {
        params['state'] = this.search.state;
      }

      if (this.search.keyword.length) {
        params['phone'] = this.search.keyword;
      }

      this.loading = true;

      _request2.default.get((0, _request.createRequestURI)('sms'), { params: params, validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;

        _this.loading = false;

        var _data$current_page = data.current_page,
            currentPage = _data$current_page === undefined ? 1 : _data$current_page,
            _data$data = data.data,
            logs = _data$data === undefined ? [] : _data$data;


        if (!logs.length) {
          _this.error = '没有数据可加载';
          return;
        }

        _this.currentPage = currentPage;
        _this.logs = logs;
      }).catch();
    }
  },
  created: function created() {
    this.searchHandle();
  }
}; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

exports.default = SmsMainComponent;

/***/ }),
/* 128 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.error),
      expression: "error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v("\n    " + _vm._s(_vm.error) + "\n  ")]), _vm._v(" "), _c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-xs-6"
  }, [_c('div', {
    staticClass: "input-group",
    staticStyle: {
      "max-width": "356px"
    }
  }, [_c('div', {
    staticClass: "input-group-btn"
  }, [_vm._m(0), _vm._v(" "), _c('ul', {
    staticClass: "dropdown-menu",
    attrs: {
      "aria-labelledby": "state"
    }
  }, [_c('li', [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeState(-1)
      }
    }
  }, [(_vm.search.state === -1) ? _c('span', {
    staticClass: "glyphicon glyphicon-ok-circle"
  }) : _c('span', {
    staticClass: "glyphicon glyphicon-record"
  }), _vm._v("\n                    全部状态\n                  ")])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeState(0)
      }
    }
  }, [(_vm.search.state === 0) ? _c('span', {
    staticClass: "glyphicon glyphicon-ok-circle"
  }) : _c('span', {
    staticClass: "glyphicon glyphicon-record"
  }), _vm._v("\n                    未发送\n                  ")])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeState(1)
      }
    }
  }, [(_vm.search.state === 1) ? _c('span', {
    staticClass: "glyphicon glyphicon-ok-circle"
  }) : _c('span', {
    staticClass: "glyphicon glyphicon-record"
  }), _vm._v("\n                    发送成功\n                  ")])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeState(2)
      }
    }
  }, [(_vm.search.state === 2) ? _c('span', {
    staticClass: "glyphicon glyphicon-ok-circle"
  }) : _c('span', {
    staticClass: "glyphicon glyphicon-record"
  }), _vm._v("\n                    发送失败\n                  ")])])])]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.keyword),
      expression: "search.keyword"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "aria-label": "input-group-btn",
      "placeholder": "输入要搜索的手机号码"
    },
    domProps: {
      "value": (_vm.search.keyword)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search.keyword = $event.target.value
      }
    }
  }), _vm._v(" "), _c('div', {
    staticClass: "input-group-btn"
  }, [(_vm.loading === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n                搜索...\n              ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.searchHandle($event)
      }
    }
  }, [_vm._v("搜索")])])])]), _vm._v(" "), _c('div', {
    staticClass: "col-xs-6 text-right"
  }, [_c('ul', {
    staticClass: "pagination",
    staticStyle: {
      "margin": "0"
    }
  }, [_c('li', {
    class: parseInt(this.currentPage) <= 1 ? 'disabled' : null
  }, [_c('a', {
    attrs: {
      "href": "#",
      "aria-label": "Previous"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.prevPage($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("«")])])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "#",
      "aria-label": "Next"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.nextPage($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("»")])])])])])])]), _vm._v(" "), _c('table', {
    staticClass: "table table-hove"
  }, [_vm._m(1), _vm._v(" "), _c('tbody', _vm._l((_vm.logs), function(log) {
    return _c('tr', {
      key: log.id
    }, [_c('td', [_vm._v(_vm._s(log.account))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(log.code))]), _vm._v(" "), (log.state === 0) ? _c('td', {
      staticStyle: {
        "color": "#5bc0de"
      }
    }, [_vm._v("未发送")]) : (log.state === 1) ? _c('td', {
      staticStyle: {
        "color": "#449d44"
      }
    }, [_vm._v("发送成功")]) : (log.state === 2) ? _c('td', {
      staticStyle: {
        "color": "#d9534f"
      }
    }, [_vm._v("发送失败")]) : _c('td', [_vm._v("未知状态")]), _vm._v(" "), _c('td', [_vm._v(_vm._s(log.created_at))])])
  }))])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('button', {
    staticClass: "btn btn-default dropdown-toggle",
    attrs: {
      "type": "button",
      "id": "state",
      "data-toggle": "dropdown",
      "aria-haspopup": "true",
      "aria-expanded": "false"
    }
  }, [_vm._v("状态 "), _c('span', {
    staticClass: "caret"
  })])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("手机号码")]), _vm._v(" "), _c('th', [_vm._v("验证码")]), _vm._v(" "), _c('th', [_vm._v("状态")]), _vm._v(" "), _c('th', [_vm._v("时间")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-230a4f3d", esExports)
  }
}

/***/ }),
/* 129 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Alidayu_vue__ = __webpack_require__(130);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Alidayu_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Alidayu_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_d8dfe5ee_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Alidayu_vue__ = __webpack_require__(131);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Alidayu_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_d8dfe5ee_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Alidayu_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/sms/Alidayu.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Alidayu.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d8dfe5ee", Component.options)
  } else {
    hotAPI.reload("data-v-d8dfe5ee", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 130 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request2 = __webpack_require__(1);

var _request3 = _interopRequireDefault(_request2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var AlidayuComponent = {
  data: function data() {
    return {
      loadding: {
        state: 0,
        message: ''
      },
      submit: {
        state: false,
        type: 'muted',
        message: ''
      },
      options: {}
    };
  },
  methods: {
    request: function request() {
      var _this = this;

      this.loadding.state = 0;
      _request3.default.get((0, _request2.createRequestURI)('sms/driver/alidayu'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? {} : _ref$data;

        _this.loadding.state = 1;
        _this.options = data;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            _ref2$response$data$m3 = _ref2$response$data$m2[0],
            message = _ref2$response$data$m3 === undefined ? '加载失败' : _ref2$response$data$m3;

        _this.loadding.state = 2;
        _this.loadding.message = message;
      });
    },
    submitHandle: function submitHandle() {
      var _this2 = this;

      var _options = this.options,
          _options$app_key = _options.app_key,
          app_key = _options$app_key === undefined ? null : _options$app_key,
          _options$app_secret = _options.app_secret,
          app_secret = _options$app_secret === undefined ? null : _options$app_secret,
          _options$sign_name = _options.sign_name,
          sign_name = _options$sign_name === undefined ? null : _options$sign_name,
          _options$verify_templ = _options.verify_template_id,
          verify_template_id = _options$verify_templ === undefined ? null : _options$verify_templ;

      this.submit.state = true;
      _request3.default.patch((0, _request2.createRequestURI)('sms/driver/alidayu'), { app_key: app_key, app_secret: app_secret, sign_name: sign_name, verify_template_id: verify_template_id }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref3) {
        var _ref3$data$message = _ref3.data.message;
        _ref3$data$message = _ref3$data$message === undefined ? [] : _ref3$data$message;

        var _ref3$data$message2 = _slicedToArray(_ref3$data$message, 1),
            _ref3$data$message2$ = _ref3$data$message2[0],
            message = _ref3$data$message2$ === undefined ? '提交成功' : _ref3$data$message2$;

        _this2.submit.state = false;
        _this2.submit.type = 'success';
        _this2.submit.message = message;
      }).catch(function (_ref4) {
        var _ref4$response = _ref4.response;
        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$m = _ref4$response$data.message;
        _ref4$response$data$m = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m;

        var _ref4$response$data$m2 = _slicedToArray(_ref4$response$data$m, 1),
            _ref4$response$data$m3 = _ref4$response$data$m2[0],
            message = _ref4$response$data$m3 === undefined ? '提交失败' : _ref4$response$data$m3;

        _this2.submit.state = false;
        _this2.submit.type = 'danger';
        _this2.submit.message = message;
      });
    }
  },
  created: function created() {
    var _this3 = this;

    window.setTimeout(function () {
      return _this3.request();
    }, 500);
  }
};

exports.default = AlidayuComponent;

/***/ }),
/* 131 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("阿里大于 - 驱动配置")]), _vm._v(" "), (_vm.loadding.state === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.loadding.state === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "app-key"
    }
  }, [_vm._v("App Key")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.app_key),
      expression: "options.app_key"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "app_key",
      "id": "app-key",
      "placeholder": "请输入应用 AppKey",
      "aria-describedby": "app-key-help"
    },
    domProps: {
      "value": (_vm.options.app_key)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.app_key = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(0)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "app-secret"
    }
  }, [_vm._v("App Secret")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.app_secret),
      expression: "options.app_secret"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "app_secret",
      "id": "app-secret",
      "placeholder": "请输入应用 App Secret",
      "aria-describedby": "app-secret-help"
    },
    domProps: {
      "value": (_vm.options.app_secret)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.app_secret = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(1)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "sign-name"
    }
  }, [_vm._v("短信签名")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.sign_name),
      expression: "options.sign_name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "sign_name",
      "id": "sign-name",
      "placeholder": "请输入短信签名名称",
      "aria-describedby": "sign-name-help"
    },
    domProps: {
      "value": (_vm.options.sign_name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.sign_name = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(2)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "template-id"
    }
  }, [_vm._v("模板ID")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.options.verify_template_id),
      expression: "options.verify_template_id"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "name": "template_id",
      "id": "template-id",
      "placeholder": "请输入短信模板id",
      "aria-describedby": "template-id-help"
    },
    domProps: {
      "value": (_vm.options.verify_template_id)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.options.verify_template_id = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(3)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [(_vm.submit.state === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n            提交...\n          ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.submitHandle($event)
      }
    }
  }, [_vm._v("提交")])]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6 help-block"
  }, [_c('span', {
    class: ("text-" + (_vm.submit.type))
  }, [_vm._v(_vm._s(_vm.submit.message))])])])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.loadding.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.request($event)
      }
    }
  }, [_vm._v("刷新")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "app-key-help"
    }
  }, [_vm._v("输入应用 App Key 信息")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "app-secret-help"
    }
  }, [_vm._v("输入应用 App Secret 信息")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "sign-name-help"
    }
  }, [_vm._v("请输入短信签名的名称")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "template-id-help"
    }
  }, [_vm._v("请输入短信模板id")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-d8dfe5ee", esExports)
  }
}

/***/ }),
/* 132 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Main = __webpack_require__(133);

var _Main2 = _interopRequireDefault(_Main);

var _Report = __webpack_require__(135);

var _Report2 = _interopRequireDefault(_Report);

var _Accounts = __webpack_require__(137);

var _Accounts2 = _interopRequireDefault(_Accounts);

var _Cash = __webpack_require__(140);

var _Cash2 = _interopRequireDefault(_Cash);

var _CashSetting = __webpack_require__(143);

var _CashSetting2 = _interopRequireDefault(_CashSetting);

var _PayOption = __webpack_require__(146);

var _PayOption2 = _interopRequireDefault(_PayOption);

var _PayRule = __webpack_require__(149);

var _PayRule2 = _interopRequireDefault(_PayRule);

var _RechargeType = __webpack_require__(152);

var _RechargeType2 = _interopRequireDefault(_RechargeType);

var _PingPlusPlus = __webpack_require__(155);

var _PingPlusPlus2 = _interopRequireDefault(_PingPlusPlus);

var _PayRatio = __webpack_require__(158);

var _PayRatio2 = _interopRequireDefault(_PayRatio);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
// The file is defined "/wallet" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//

var walletRouter = {
  path: 'wallet',
  component: _Main2.default,
  children: [{ path: '', component: _Report2.default }, { path: 'accounts', component: _Accounts2.default }, { path: 'cash', component: _Cash2.default }, { path: 'cash/setting', component: _CashSetting2.default }, { path: 'pay/option', component: _PayOption2.default }, { path: 'pay/rule', component: _PayRule2.default }, { path: 'pay/ratio', component: _PayRatio2.default }, { path: 'pay/type', component: _RechargeType2.default }, { path: 'pay/pingpp', component: _PingPlusPlus2.default }]
};

exports.default = walletRouter;

/***/ }),
/* 133 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_3e5fcc17_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__ = __webpack_require__(134);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_3e5fcc17_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/Main.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Main.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3e5fcc17", Component.options)
  } else {
    hotAPI.reload("data-v-3e5fcc17", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 134 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('ul', {
    staticClass: "nav nav-tabs component-controller-nav"
  }, [_c('router-link', {
    attrs: {
      "to": "/wallet",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("统计")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/wallet/accounts",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("流水")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/wallet/cash",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("提现审批")])]), _vm._v(" "), _c('li', {
    staticClass: "dropdown",
    attrs: {
      "role": "presentation"
    }
  }, [_vm._m(0), _vm._v(" "), _c('ul', {
    staticClass: "dropdown-menu"
  }, [_c('router-link', {
    attrs: {
      "to": "/wallet/pay/option",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("充值选项")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/wallet/pay/rule",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("规则设置")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/wallet/pay/ratio",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("转换比例")])])], 1)]), _vm._v(" "), _c('li', {
    staticClass: "dropdown",
    attrs: {
      "role": "presentation"
    }
  }, [_vm._m(1), _vm._v(" "), _c('ul', {
    staticClass: "dropdown-menu"
  }, [_c('router-link', {
    attrs: {
      "to": "/wallet/pay/type",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("支付选项")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/wallet/pay/pingpp",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("Ping++")])])], 1)])], 1), _vm._v(" "), _c('router-view')], 1)
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('a', {
    staticClass: "dropdown-toggle",
    attrs: {
      "data-toggle": "dropdown",
      "href": "#",
      "role": "button",
      "aria-haspopup": "true",
      "aria-expanded": "false"
    }
  }, [_vm._v("\n        基础设置 "), _c('span', {
    staticClass: "caret"
  })])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('a', {
    staticClass: "dropdown-toggle",
    attrs: {
      "data-toggle": "dropdown",
      "href": "#",
      "role": "button",
      "aria-haspopup": "true",
      "aria-expanded": "false"
    }
  }, [_vm._v("\n        支付设置 "), _c('span', {
    staticClass: "caret"
  })])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3e5fcc17", esExports)
  }
}

/***/ }),
/* 135 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_880c26dc_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Report_vue__ = __webpack_require__(136);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_880c26dc_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Report_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/Report.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Report.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-880c26dc", Component.options)
  } else {
    hotAPI.reload("data-v-880c26dc", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 136 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._v("报表")])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-880c26dc", esExports)
  }
}

/***/ }),
/* 137 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Accounts_vue__ = __webpack_require__(138);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Accounts_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Accounts_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_024810c4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Accounts_vue__ = __webpack_require__(139);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Accounts_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_024810c4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Accounts_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/Accounts.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Accounts.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-024810c4", Component.options)
  } else {
    hotAPI.reload("data-v-024810c4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 138 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

var channelTypes = {
  applepay_upacp: 'Apple Pay',
  alipay: '支付宝 APP',
  alipay_pc_direct: '支付宝电脑网站',
  alipay_qr: '支付宝扫码',
  alipay_wap: '支付宝手机网页',
  wx: '微信 APP',
  wx_wap: '微信 WAP'
};

exports.default = {
  data: function data() {
    return {
      // Search state.
      search: {
        user: null, // 搜索用户
        account: null, // 搜索账户
        chargeId: null, // Ping++ 凭据ID
        transactionNo: null, // 第三方平台订单ID
        action: null, // 动作
        status: null // 状态
      },

      // Page
      page: {
        current: 1,
        total: 1
      },

      // 凭据
      charges: [],

      // Load
      load: {
        status: 0,
        message: ''
      }
    };
  },
  computed: {
    /**
     * Compute search query.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    searchQuery: function searchQuery() {
      return _lodash2.default.reduce(this.search, function (search, value, key) {
        if (!!value) {
          search[key] = value;
        }
        return search;
      }, {});
    },

    /**
     * Pagination.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    pagination: function pagination() {
      // 当前页
      var current = 1;
      current = isNaN(current = parseInt(this.page.current)) ? 1 : current;

      // 最后页码
      var last = 1;
      last = isNaN(last = parseInt(this.page.total)) ? 1 : last;

      // 是否显示
      var show = last > 1;

      // 前三页
      var minPage = current - 3;
      minPage = minPage <= 1 ? 1 : minPage;

      // 后三页
      var maxPage = current + 3;
      maxPage = maxPage >= last ? last : maxPage;

      // 是否有上一页
      var isPrevPage = false;
      // 前页页码
      var prevPages = [];

      // 前页计算
      if (current > minPage) {
        // 有上一页按钮
        isPrevPage = current - 1; // 如果有，传入上一页页码.

        // 回归第一页
        if (minPage > 1) {
          prevPages.push({
            name: current < 6 ? 1 : '1...',
            page: 1
          });
        }

        // 前页码
        for (var i = minPage; i < current; i++) {
          prevPages.push({
            name: i,
            page: i
          });
        }
      }

      // 是否有下一页
      var isNextPage = false;
      // 后页页码
      var nextPages = [];

      // 后页计算
      if (current < maxPage) {
        // 后页码
        for (var _i = current + 1; _i <= maxPage; _i++) {
          nextPages.push({
            name: _i,
            page: _i
          });
        }

        // 进入最后页
        if (maxPage < last) {
          nextPages.push({
            name: current + 4 === last ? last : '...' + last,
            page: last
          });
        }

        // 是否有下一页按钮
        isNextPage = current + 1;
      }

      return {
        isPrevPage: isPrevPage,
        isNextPage: isNextPage,
        current: current,
        show: show,
        prevPages: prevPages,
        nextPages: nextPages
      };
    }
  },
  watch: {
    /**
     * The this.$route watcher.
     *
     * @param {Object} options.query
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    '$route': function $route() {
      var _ref = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
          _ref$query = _ref.query,
          query = _ref$query === undefined ? {} : _ref$query;

      this.requestCharge(this.resolveQuery(query));
    }
  },
  methods: {
    /**
     * Builder page route object.
     *
     * @param {Number} page
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolvePaginationRoute: function resolvePaginationRoute(page) {
      return {
        path: '/wallet/accounts',
        query: _extends({}, this.resolveQuery(), { page: page })
      };
    },


    /**
     * 返回显示的频道类型.
     *
     * @param {String} channel
     * @return {String}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolvePayChannel: function resolvePayChannel(channel) {
      var _channelTypes$channel = channelTypes[channel],
          displayChannel = _channelTypes$channel === undefined ? '余额' : _channelTypes$channel;


      return displayChannel;
    },

    /**
     * 解决用户信息显示.
     *
     * @param {Object} user
     * @return {String|null}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveChargeUserDisplay: function resolveChargeUserDisplay() {
      var user = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      var id = user.id,
          name = user.name;

      if (!!id) {
        return name + ' (' + id + ')';
      }

      return null;
    },

    /**
     * Request refresh.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestRefresh: function requestRefresh() {
      this.requestCharge(_extends({
        page: this.page.current
      }, this.resolveQuery()));
    },

    /**
     * Resolve route query.
     *
     * @param {Object} query
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveQuery: function resolveQuery() {
      var query = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.$route.query;

      return _lodash2.default.reduce(query, function (query, value, key) {
        switch (key) {
          case 'user':
          case 'action':
          case 'status':
            if (!!value) {
              query[key] = isNaN(value = parseInt(value)) ? null : value;
              break;
            }
            query[key] = value;
            break;

          case 'page':
            query[key] = isNaN(value = parseInt(value)) || value <= 1 ? 1 : value;

          default:
            query[key] = value;
            break;
        }

        return query;
      }, {});
    },


    /**
     * Request the charges.
     *
     * @param {Object} params
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCharge: function requestCharge() {
      var _this = this;

      var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

      this.load.status = 0;
      _request2.default.get((0, _request.createRequestURI)('wallet/charges'), { params: params, validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref2) {
        var _ref2$data = _ref2.data,
            _ref2$data$total = _ref2$data.total,
            total = _ref2$data$total === undefined ? 1 : _ref2$data$total,
            _ref2$data$current = _ref2$data.current,
            current = _ref2$data$current === undefined ? 1 : _ref2$data$current,
            _ref2$data$items = _ref2$data.items,
            items = _ref2$data$items === undefined ? [] : _ref2$data$items;

        _this.load.status = 1;
        _this.page = { total: total, current: current };
        _this.charges = items;
      }).catch(function () {
        var _ref3 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref3$response = _ref3.response;

        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data;
        _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
        var _ref3$response$data$m = _ref3$response$data.message;
        _ref3$response$data$m = _ref3$response$data$m === undefined ? [] : _ref3$response$data$m;

        var _ref3$response$data$m2 = _slicedToArray(_ref3$response$data$m, 1),
            _ref3$response$data$m3 = _ref3$response$data$m2[0],
            anyMessage = _ref3$response$data$m3 === undefined ? '加载失败，请刷新重试' : _ref3$response$data$m3;

        _this.load = { message: anyMessage, status: 2 };
      });
    }
  },
  /**
   * The component created handle.
   * Init the component.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created: function created() {
    var _resolveQuery = this.resolveQuery(),
        _resolveQuery$page = _resolveQuery.page,
        page = _resolveQuery$page === undefined ? 1 : _resolveQuery$page,
        query = _objectWithoutProperties(_resolveQuery, ['page']);

    this.search = _extends({}, this.search, query);
    this.page.current = page;

    // Request Charges.
    this.requestCharge(_extends({}, query, { page: page }));
  }
};

/***/ }),
/* 139 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("订单流水")]), _vm._v(" "), _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("用户")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.user),
      expression: "search.user"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "number"
    },
    domProps: {
      "value": (_vm.search.user)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search.user = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "help-block col-sm-6"
  }, [_vm._v("输入交易账户的用户 ID")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("交易账户")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.account),
      expression: "search.account"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text"
    },
    domProps: {
      "value": (_vm.search.account)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search.account = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("输入交易账户，应用内交易则为用户ID")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("Ping++ 凭据")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.chargeId),
      expression: "search.chargeId"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text"
    },
    domProps: {
      "value": (_vm.search.chargeId)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search.chargeId = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("输入来自于 Ping++ 平台的凭据 ID")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("订单 ID")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.transactionNo),
      expression: "search.transactionNo"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text"
    },
    domProps: {
      "value": (_vm.search.transactionNo)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.search.transactionNo = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("输入第三方平台的订单 ID，例如支付宝订单")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("类型")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.action),
      expression: "search.action"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.search.action = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, [_c('option', {
    domProps: {
      "value": null
    }
  }, [_vm._v("全部")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "1"
    }
  }, [_vm._v("增项")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "0"
    }
  }, [_vm._v("减项")])])]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("选择订单交易类型")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("状态")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.search.status),
      expression: "search.status"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.search.status = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, [_c('option', {
    domProps: {
      "value": null
    }
  }, [_vm._v("全部")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "0"
    }
  }, [_vm._v("等待")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "1"
    }
  }, [_vm._v("成功")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "2"
    }
  }, [_vm._v("失败")])])]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("选择订单的交易状态")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-10"
  }, [_c('router-link', {
    staticClass: "btn btn-primary",
    attrs: {
      "tag": "button",
      "to": {
        path: '/wallet/accounts',
        query: _vm.searchQuery
      }
    }
  }, [_vm._v("搜索")])], 1)])]), _vm._v(" "), (_vm.load.status === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.load.status === 1) ? _c('table', {
    staticClass: "table table-striped table-hover"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', _vm._l((_vm.charges), function(charge) {
    return _c('tr', {
      key: charge.id,
      class: charge.status === 2 ? 'danger' : charge.status === 1 ? 'success' : ''
    }, [_c('td', [_vm._v(_vm._s(charge.id))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(_vm.resolveChargeUserDisplay(charge.user)))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(_vm.resolvePayChannel(charge.channel)))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(charge.account))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(charge.charge_id))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(charge.transaction_no))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(charge.action == 1 ? '+' : '-') + _vm._s(charge.amount / 100))]), _vm._v(" "), _c('td', [_vm._v("\n            " + _vm._s(charge.subject)), _c('br'), _vm._v(" "), _c('small', [_vm._v(_vm._s(charge.body))])]), _vm._v(" "), (charge.status === 0) ? _c('td', [_vm._v("等待")]) : (charge.status === 1) ? _c('td', [_vm._v("成功")]) : (charge.status === 2) ? _c('td', [_vm._v("失败")]) : _c('td', [_vm._v("未知")]), _vm._v(" "), _c('td', [_vm._v(_vm._s(charge.created_at))])])
  }))]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.load.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.requestRefresh
    }
  }, [_vm._v("重试")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.pagination.show),
      expression: "pagination.show"
    }],
    staticClass: "text-center"
  }, [_c('ul', {
    staticClass: "pagination"
  }, [_c('router-link', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!!_vm.pagination.isPrevPage),
      expression: "!!pagination.isPrevPage"
    }],
    attrs: {
      "tag": "li",
      "to": _vm.resolvePaginationRoute(_vm.pagination.isPrevPage)
    }
  }, [_c('a', {
    attrs: {
      "aria-label": "Previous"
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("«")])])]), _vm._v(" "), _vm._l((_vm.pagination.prevPages), function(item) {
    return _c('router-link', {
      key: item.page,
      attrs: {
        "tag": "li",
        "to": _vm.resolvePaginationRoute(item.page)
      }
    }, [_c('a', [_vm._v(_vm._s(item.name))])])
  }), _vm._v(" "), _c('router-link', {
    staticClass: "active",
    attrs: {
      "tag": "li",
      "to": _vm.resolvePaginationRoute(_vm.pagination.current)
    }
  }, [_c('a', [_vm._v("\n          " + _vm._s(_vm.pagination.current) + "\n        ")])]), _vm._v(" "), _vm._l((_vm.pagination.nextPages), function(item) {
    return _c('router-link', {
      key: item.page,
      attrs: {
        "tag": "li",
        "to": _vm.resolvePaginationRoute(item.page)
      }
    }, [_c('a', [_vm._v(_vm._s(item.name))])])
  }), _vm._v(" "), _c('router-link', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!!_vm.pagination.isNextPage),
      expression: "!!pagination.isNextPage"
    }],
    attrs: {
      "tag": "li",
      "to": _vm.resolvePaginationRoute(_vm.pagination.isNextPage)
    }
  }, [_c('a', {
    attrs: {
      "aria-label": "Next"
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("»")])])])], 2)])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("ID")]), _vm._v(" "), _c('th', [_vm._v("用户（ID）")]), _vm._v(" "), _c('th', [_vm._v("支付频道")]), _vm._v(" "), _c('th', [_vm._v("交易账户")]), _vm._v(" "), _c('th', [_vm._v("Ping++")]), _vm._v(" "), _c('th', [_vm._v("订单ID")]), _vm._v(" "), _c('th', [_vm._v("金额")]), _vm._v(" "), _c('th', [_vm._v("交易信息")]), _vm._v(" "), _c('th', [_vm._v("状态")]), _vm._v(" "), _c('th', [_vm._v("时间")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-024810c4", esExports)
  }
}

/***/ }),
/* 140 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Cash_vue__ = __webpack_require__(141);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Cash_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Cash_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_404ff491_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Cash_vue__ = __webpack_require__(142);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-404ff491\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Cash.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(29)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-404ff491")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(29)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Cash_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_404ff491_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Cash_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/Cash.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Cash.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-404ff491", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-404ff491", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 141 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

exports.default = {
  data: function data() {
    return {
      cashes: [],
      query: {},
      queryTemp: {
        user: null,
        status: 'all',
        order: 'desc'
      },
      page: {
        last: 0,
        current: 1,
        first: 1
      },
      alert: {
        status: false,
        message: '',
        type: 'info',
        interval: null
      },
      loading: true,
      ratio: 100,
      actions: {},
      remarks: {},
      modal: {
        status: false,
        interval: null,
        type: false,
        message: ''
      }
    };
  },
  computed: {
    pagination: function pagination() {
      // 当前页
      var current = 1;
      current = isNaN(current = parseInt(this.page.current)) ? 1 : current;

      // 最后页码
      var last = 1;
      last = isNaN(last = parseInt(this.page.last)) ? 1 : last;

      // 是否显示
      var show = last > 1;

      // 前三页
      var minPage = current - 3;
      minPage = minPage <= 1 ? 1 : minPage;

      // 后三页
      var maxPage = current + 3;
      maxPage = maxPage >= last ? last : maxPage;

      // 是否有上一页
      var isPrevPage = false;
      // 前页页码
      var prevPages = [];

      // 前页计算
      if (current > minPage) {
        // 有上一页按钮
        isPrevPage = current - 1; // 如果有，传入上一页页码.

        // 回归第一页
        if (minPage > 1) {
          prevPages.push({
            name: current < 6 ? 1 : '1...',
            page: 1
          });
        }

        // 前页码
        for (var i = minPage; i < current; i++) {
          prevPages.push({
            name: i,
            page: i
          });
        }
      }

      // 是否有下一页
      var isNextPage = false;
      // 后页页码
      var nextPages = [];

      // 后页计算
      if (current < maxPage) {
        // 后页码
        for (var _i = current + 1; _i <= maxPage; _i++) {
          nextPages.push({
            name: _i,
            page: _i
          });
        }

        // 进入最后页
        if (maxPage < last) {
          nextPages.push({
            name: current + 4 === last ? last : '...' + last,
            page: last
          });
        }

        // 是否有下一页按钮
        isNextPage = current + 1;
      }

      return {
        isPrevPage: isPrevPage,
        isNextPage: isNextPage,
        current: current,
        show: show,
        prevPages: prevPages,
        nextPages: nextPages
      };
    },
    searchQuery: function searchQuery() {
      return _extends({}, this.query, this.queryTemp, {
        page: 1
      });
    }
  },
  watch: {
    '$route': function $route(to) {
      var _resolveQueryString = this.resolveQueryString(to),
          _resolveQueryString$p = _resolveQueryString.page,
          page = _resolveQueryString$p === undefined ? this.page.current : _resolveQueryString$p,
          _objectWithoutPropert = _objectWithoutProperties(_resolveQueryString, ['page']),
          _objectWithoutPropert2 = _objectWithoutPropert,
          query = _objectWithoutPropert2 === undefined ? this.query : _objectWithoutPropert2;

      this.query = query;
      this.page.current = page;
      this.requestCashes(_extends({}, query, {
        page: page
      }));
    }
  },
  methods: {
    /**
     * 驳回提现申请
     *
     * @param {Number} id
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashRefuse: function requestCashRefuse(id) {
      var _this = this;

      // 备注
      var remark = this.remarks[id];

      if (!remark) {
        this.sendModal('请输入备注内容', false);

        return;
      }

      // 添加到正在被执行当中
      this.actions = _extends({}, this.actions, _defineProperty({}, id, 2));

      // 请求通过
      _request2.default.patch((0, _request.createRequestURI)('wallet/cashes/' + id + '/refuse'), { remark: remark }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this.actions = _lodash2.default.reduce(_this.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            actions[key] = item;
          }

          return actions;
        }, {});
        _this.cashes = _lodash2.default.reduce(_this.cashes, function (cashes, cash) {
          if (id === cash.id) {
            cash.remark = remark;
            cash.status = 2;
          }
          cashes.push(cash);

          return cashes;
        }, []);
        _this.sendModal('操作成功！');
      }).catch(function () {
        var _ref = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref$response = _ref.response;

        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data;
        _ref$response$data = _ref$response$data === undefined ? {} : _ref$response$data;
        var _ref$response$data$re = _ref$response$data.remark,
            remark = _ref$response$data$re === undefined ? [] : _ref$response$data$re,
            _ref$response$data$me = _ref$response$data.message,
            message = _ref$response$data$me === undefined ? [] : _ref$response$data$me;

        var _ref2 = [].concat(_toConsumableArray(remark), _toConsumableArray(message)),
            _ref2$ = _ref2[0],
            currentMessage = _ref2$ === undefined ? '提交失败，请刷新网页重试！' : _ref2$;

        _this.actions = _lodash2.default.reduce(_this.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            actions[key] = item;
          }

          return actions;
        }, {});
        _this.sendModal(currentMessage, false);
      });
    },


    /**
     * 请求审批通过
     *
     * @param {Number} id
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashPassed: function requestCashPassed(id) {
      var _this2 = this;

      // 备注
      var remark = this.remarks[id];

      if (!remark) {
        this.sendModal('请输入备注内容', false);

        return;
      }

      // 添加到正在被执行当中
      this.actions = _extends({}, this.actions, _defineProperty({}, id, 1));

      // 请求通过
      _request2.default.patch((0, _request.createRequestURI)('wallet/cashes/' + id + '/passed'), { remark: remark }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this2.actions = _lodash2.default.reduce(_this2.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            actions[key] = item;
          }

          return actions;
        }, {});
        _this2.cashes = _lodash2.default.reduce(_this2.cashes, function (cashes, cash) {
          if (id === cash.id) {
            cash.remark = remark;
            cash.status = 1;
          }
          cashes.push(cash);

          return cashes;
        }, []);
        _this2.sendModal('审核成功！');
      }).catch(function () {
        var _ref3 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref3$response = _ref3.response;

        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data;
        _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
        var _ref3$response$data$r = _ref3$response$data.remark,
            remark = _ref3$response$data$r === undefined ? [] : _ref3$response$data$r,
            _ref3$response$data$m = _ref3$response$data.message,
            message = _ref3$response$data$m === undefined ? [] : _ref3$response$data$m;

        var _ref4 = [].concat(_toConsumableArray(remark), _toConsumableArray(message)),
            _ref4$ = _ref4[0],
            currentMessage = _ref4$ === undefined ? '提交失败，请刷新网页重试！' : _ref4$;

        _this2.actions = _lodash2.default.reduce(_this2.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            actions[key] = item;
          }

          return actions;
        }, {});
        _this2.sendModal(currentMessage, false);
      });
    },


    /**
     * 请求列表数据.
     *
     * @param {Object} query
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashes: function requestCashes() {
      var _this3 = this;

      var query = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

      this.loading = true;
      this.cashes = [];
      this.alert.status = false;
      _request2.default.get((0, _request.createRequestURI)('wallet/cashes'), {
        params: this.resolveStatus2Query(query),
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref5) {
        var _ref5$data = _ref5.data,
            data = _ref5$data === undefined ? {} : _ref5$data;
        var _data$ratio = data.ratio,
            ratio = _data$ratio === undefined ? 100 : _data$ratio,
            _data$cashes = data.cashes,
            cashes = _data$cashes === undefined ? [] : _data$cashes,
            _data$current_page = data.current_page,
            current = _data$current_page === undefined ? _this3.page.current : _data$current_page,
            _data$first_page = data.first_page,
            first = _data$first_page === undefined ? _this3.page.first : _data$first_page,
            _data$last_page = data.last_page,
            last = _data$last_page === undefined ? thus.page.last : _data$last_page;

        _this3.loading = false;
        _this3.cashes = cashes;
        _this3.page = { last: last, current: current, first: first };
        _this3.ratio = ratio;
      }).catch(function () {
        var _ref6 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref6$response = _ref6.response;

        _ref6$response = _ref6$response === undefined ? {} : _ref6$response;
        var _ref6$response$data = _ref6$response.data;
        _ref6$response$data = _ref6$response$data === undefined ? {} : _ref6$response$data;
        var _ref6$response$data$m = _ref6$response$data.message;
        _ref6$response$data$m = _ref6$response$data$m === undefined ? [] : _ref6$response$data$m;

        var _ref6$response$data$m2 = _slicedToArray(_ref6$response$data$m, 1),
            _ref6$response$data$m3 = _ref6$response$data$m2[0],
            message = _ref6$response$data$m3 === undefined ? '加载失败' : _ref6$response$data$m3;

        _this3.loading = false;
        _this3.page = { last: 0, current: 1, first: 1 };
        _this3.sendAlert('danger', message, false);
      });
    },


    /**
     * 发送模糊框提示
     *
     * @param {[type]} message [description]
     * @param {Boolean} success [description]
     * @return {[type]} [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendModal: function sendModal(message) {
      var _this4 = this;

      var success = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      var time = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1500;

      window.clearInterval(this.modal.interval);
      this.modal = {
        type: !!success,
        message: message,
        status: true,
        interval: window.setInterval(function () {
          _this4.modal.status = false;
          window.clearInterval(_this4.modal.interval);
        }, time)
      };
    },


    /**
     * 发送 alert 提示.
     *
     * @param {string} type
     * @param {string} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert: function sendAlert(type, message) {
      var _this5 = this;

      var hide = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;

      window.clearInterval(this.alert.interval);
      this.loading = false;
      this.alert = _extends({}, this.alert, {
        type: type,
        message: message,
        status: true,
        interval: !hide ? null : window.setInterval(function () {
          window.clearInterval(_this5.alert.interval);
          _this5.alert.status = false;
        }, 2000)
      });
    },


    /**
     * 将状态转换为可供查询的查询对象.
     *
     * @param {Object} query
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveStatus2Query: function resolveStatus2Query() {
      var query = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

      return _extends({}, this.query, { page: this.page.current }, query);
    },


    /**
     * 解决网页请求参数.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveQueryString: function resolveQueryString() {
      var route = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      var _ref7 = route !== false ? route.query : this.$route.query,
          _ref7$page = _ref7.page,
          page = _ref7$page === undefined ? this.page.current : _ref7$page,
          user = _ref7.user,
          status = _ref7.status,
          order = _ref7.order;

      var query = {};

      // 用户
      if (!!user) {
        query['user'] = user;
      }

      // 状态
      if (!!status) {
        query['status'] = status;
      }

      // 排序
      if (!!order) {
        query['order'] = order;
      }

      query['page'] = parseInt(page);

      return query;
    }
  },
  created: function created() {
    this.requestCashes(this.resolveQueryString());
    var _$route$query = this.$route.query,
        user = _$route$query.user,
        status = _$route$query.status,
        order = _$route$query.order;

    this.queryTemp = { user: user, status: status, order: order };
  }
};

/***/ }),
/* 142 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("\n      提现审批\n      "), _c('router-link', {
    staticClass: "pull-right",
    attrs: {
      "to": "setting",
      "append": "",
      "replace": ""
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-cog"
  }), _vm._v("\n        提现设置\n      ")])], 1), _vm._v(" "), _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "form-inline"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', [_vm._v("用户：")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model.lazy",
      value: (_vm.queryTemp.user),
      expression: "queryTemp.user",
      modifiers: {
        "lazy": true
      }
    }],
    staticClass: "form-control",
    attrs: {
      "type": "number",
      "placeholder": "User ID",
      "min": "1"
    },
    domProps: {
      "value": (_vm.queryTemp.user)
    },
    on: {
      "change": function($event) {
        _vm.queryTemp.user = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', [_vm._v("状态")]), _vm._v(" "), _c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.queryTemp.status),
      expression: "queryTemp.status"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.queryTemp.status = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, [_c('option', {
    attrs: {
      "value": "all"
    }
  }, [_vm._v("全部")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "0"
    }
  }, [_vm._v("待审批")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "1"
    }
  }, [_vm._v("已审批")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "2"
    }
  }, [_vm._v("被拒绝")])])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', [_vm._v("排序")]), _vm._v(" "), _c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.queryTemp.order),
      expression: "queryTemp.order"
    }],
    staticClass: "form-control",
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.queryTemp.order = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, [_c('option', {
    attrs: {
      "value": "desc"
    }
  }, [_vm._v("最新申请")]), _vm._v(" "), _c('option', {
    attrs: {
      "value": "asc"
    }
  }, [_vm._v("时间排序")])])]), _vm._v(" "), _c('router-link', {
    staticClass: "btn btn-default",
    attrs: {
      "tag": "button",
      "to": {
        path: '/wallet/cash',
        query: _vm.searchQuery
      }
    }
  }, [_vm._v("\n          搜索\n        ")])], 1)]), _vm._v(" "), _c('table', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.cashes.length),
      expression: "cashes.length"
    }],
    staticClass: "table table-striped table-hover"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', _vm._l((_vm.cashes), function(cash) {
    return _c('tr', {
      key: cash.id,
      class: cash.status === 2 ? 'danger' : cash.status === 1 ? 'success' : ''
    }, [_c('td', [_vm._v(_vm._s(cash.user.name) + " (" + _vm._s(cash.user.id) + ")")]), _vm._v(" "), _c('td', [_vm._v(_vm._s(cash.value / 100 * _vm.ratio / 100) + " (" + _vm._s(cash.value / 100) + ")")]), _vm._v(" "), (cash.type === 'alipay') ? _c('td', [_vm._v("支付宝：" + _vm._s(cash.account))]) : (cash.type === 'wechat') ? _c('td', [_vm._v("微信：" + _vm._s(cash.account))]) : _c('td', [_vm._v("未知：" + _vm._s(cash.account))]), _vm._v(" "), (cash.status === 1) ? _c('td', [_vm._v("已审批")]) : (cash.status === 2) ? _c('td', [_vm._v("被拒绝")]) : _c('td', [_vm._v("待审批")]), _vm._v(" "), (_vm.actions[cash.id]) ? _c('td', [_vm._v(_vm._s(_vm.remarks[cash.id]))]) : (cash.status === 0) ? _c('td', [_c('div', {
      staticClass: "input-group"
    }, [_c('input', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.remarks[cash.id]),
        expression: "remarks[cash.id]"
      }],
      staticClass: "form-control",
      attrs: {
        "type": "text",
        "placeholder": "备注"
      },
      domProps: {
        "value": (_vm.remarks[cash.id])
      },
      on: {
        "input": function($event) {
          if ($event.target.composing) { return; }
          _vm.$set(_vm.remarks, cash.id, $event.target.value)
        }
      }
    })])]) : _c('td', [_vm._v(_vm._s(cash.remark))]), _vm._v(" "), (cash.status === 0) ? _c('td', [(_vm.actions[cash.id] === 1) ? _c('button', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_c('span', {
      staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
    })]) : (_vm.actions[cash.id] === 2) ? _c('button', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_vm._v("通过")]) : _c('button', {
      staticClass: "btn btn-primary btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          _vm.requestCashPassed(cash.id)
        }
      }
    }, [_vm._v("通过")]), _vm._v(" "), (_vm.actions[cash.id] === 2) ? _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_vm._v("拒绝")]) : (_vm.actions[cash.id] === 1) ? _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button",
        "disabled": "disabled"
      }
    }, [_vm._v("拒绝")]) : _c('button', {
      staticClass: "btn btn-danger btn-sm",
      attrs: {
        "type": "button"
      },
      on: {
        "click": function($event) {
          _vm.requestCashRefuse(cash.id)
        }
      }
    }, [_vm._v("拒绝")])]) : _c('td')])
  }))]), _vm._v(" "), (_vm.loading) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.alert.status) ? _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    class: ['alert', ("alert-" + (_vm.alert.type)), _vm.$style.alert],
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.alert.message) + "\n      ")]), _vm._v(" "), _c('br'), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.requestCashes
    }
  }, [_vm._v("重试")])]) : _vm._e()]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.pagination.show),
      expression: "pagination.show"
    }],
    staticClass: "text-center"
  }, [_c('ul', {
    staticClass: "pagination"
  }, [_c('router-link', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!!_vm.pagination.isPrevPage),
      expression: "!!pagination.isPrevPage"
    }],
    attrs: {
      "tag": "li",
      "to": {
        path: '/wallet/cash',
        query: _vm.resolveStatus2Query({
          page: _vm.pagination.isPrevPage
        })
      }
    }
  }, [_c('a', {
    attrs: {
      "aria-label": "Previous"
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("«")])])]), _vm._v(" "), _vm._l((_vm.pagination.prevPages), function(item) {
    return _c('router-link', {
      key: item.page,
      attrs: {
        "tag": "li",
        "to": {
          path: '/wallet/cash',
          query: _vm.resolveStatus2Query({
            page: item.page
          })
        }
      }
    }, [_c('a', [_vm._v(_vm._s(item.name))])])
  }), _vm._v(" "), _c('router-link', {
    staticClass: "active",
    attrs: {
      "tag": "li",
      "to": {
        path: '/wallet/cash',
        query: _vm.resolveStatus2Query({
          page: _vm.pagination.current
        })
      }
    }
  }, [_c('a', [_vm._v("\n          " + _vm._s(_vm.pagination.current) + "\n        ")])]), _vm._v(" "), _vm._l((_vm.pagination.nextPages), function(item) {
    return _c('router-link', {
      key: item.page,
      attrs: {
        "tag": "li",
        "to": {
          path: '/wallet/cash',
          query: _vm.resolveStatus2Query({
            page: item.page
          })
        }
      }
    }, [_c('a', [_vm._v(_vm._s(item.name))])])
  }), _vm._v(" "), _c('router-link', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!!_vm.pagination.isNextPage),
      expression: "!!pagination.isNextPage"
    }],
    attrs: {
      "tag": "li",
      "to": {
        path: '/wallet/cash',
        query: _vm.resolveStatus2Query({
          page: _vm.pagination.isNextPage
        })
      }
    }
  }, [_c('a', {
    attrs: {
      "aria-label": "Next"
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("»")])])])], 2)]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.modal.status),
      expression: "modal.status"
    }],
    class: _vm.$style.modal
  }, [_c('div', {
    class: _vm.$style.modalContent
  }, [_c('div', {
    class: _vm.$style.modalIcon
  }, [(_vm.modal.type) ? _c('span', {
    staticClass: "glyphicon glyphicon-ok-sign",
    staticStyle: {
      "color": "#449d44"
    }
  }) : _c('span', {
    staticClass: "glyphicon glyphicon-warning-sign"
  })]), _vm._v("\n      " + _vm._s(_vm.modal.message) + "\n    ")])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("用户(用户ID)")]), _vm._v(" "), _c('th', [_vm._v("金额(真实金额)")]), _vm._v(" "), _c('th', [_vm._v("提现账户")]), _vm._v(" "), _c('th', [_vm._v("状态")]), _vm._v(" "), _c('th', [_vm._v("备注")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-404ff491", esExports)
  }
}

/***/ }),
/* 143 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_CashSetting_vue__ = __webpack_require__(144);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_CashSetting_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_CashSetting_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_502004af_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_CashSetting_vue__ = __webpack_require__(145);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-502004af\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./CashSetting.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(30)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-502004af")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(30)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_CashSetting_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_502004af_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_CashSetting_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/CashSetting.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] CashSetting.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-502004af", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-502004af", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 144 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

exports.default = {
  data: function data() {
    return {
      alert: {
        open: false,
        interval: null,
        type: 'info',
        message: null
      },
      cashType: [],
      minAmount: 1,
      load: {
        status: 0,
        message: ''
      },
      update: false
    };
  },
  computed: {
    minAmountCompute: {
      set: function set(minAmount) {
        this.minAmount = minAmount * 100;
      },
      get: function get() {
        return this.minAmount / 100;
      }
    }
  },
  methods: {
    /**
     * 发送提示.
     *
     * @param {string} type
     * @param {string} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert: function sendAlert(type, message) {
      var _this = this;

      window.clearInterval(this.alert.interval);
      this.alert = _extends({}, this.alert, {
        type: type,
        message: message,
        open: true,
        interval: window.setInterval(function () {
          window.clearInterval(_this.alert.interval);
          _this.alert.open = false;
        }, 2000)
      });
    },


    /**
     * 请求提现方式数据.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashSetting: function requestCashSetting() {
      var _this2 = this;

      _request2.default.get((0, _request.createRequestURI)('wallet/cash'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data;
        _ref$data = _ref$data === undefined ? {} : _ref$data;
        var _ref$data$types = _ref$data.types,
            types = _ref$data$types === undefined ? [] : _ref$data$types,
            _ref$data$min_amount = _ref$data.min_amount,
            minAmount = _ref$data$min_amount === undefined ? 1 : _ref$data$min_amount;

        _this2.cashType = types;
        _this2.minAmount = minAmount;
        _this2.load.status = 1;
      }).catch(function () {
        var _ref2 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref2$response = _ref2.response;

        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            _ref2$response$data$m3 = _ref2$response$data$m2[0],
            message = _ref2$response$data$m3 === undefined ? '加载失败' : _ref2$response$data$m3;

        _this2.load = {
          message: message,
          status: 2
        };
      });
    },


    /**
     * 更新设置.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateHandle: function updateHandle() {
      var _this3 = this;

      this.update = true;
      _request2.default.patch((0, _request.createRequestURI)('wallet/cash'), { types: this.cashType, min_amount: this.minAmount }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref3) {
        var _ref3$data = _ref3.data;
        _ref3$data = _ref3$data === undefined ? {} : _ref3$data;
        var _ref3$data$message = _ref3$data.message;
        _ref3$data$message = _ref3$data$message === undefined ? [] : _ref3$data$message;

        var _ref3$data$message2 = _slicedToArray(_ref3$data$message, 1),
            _ref3$data$message2$ = _ref3$data$message2[0],
            message = _ref3$data$message2$ === undefined ? '更新成功' : _ref3$data$message2$;

        _this3.update = false;
        _this3.sendAlert('success', message);
      }).catch(function (_ref4) {
        var _ref4$response = _ref4.response;
        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$m = _ref4$response$data.message,
            anyMessage = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m,
            _ref4$response$data$t = _ref4$response$data.types,
            typeMessage = _ref4$response$data$t === undefined ? [] : _ref4$response$data$t,
            _ref4$response$data$m2 = _ref4$response$data.min_amount,
            amountMessage = _ref4$response$data$m2 === undefined ? [] : _ref4$response$data$m2;

        _this3.update = false;

        var _ref5 = [].concat(_toConsumableArray(anyMessage), _toConsumableArray(typeMessage), _toConsumableArray(amountMessage)),
            _ref5$ = _ref5[0],
            message = _ref5$ === undefined ? '更新失败，请刷新重试' : _ref5$;

        _this3.sendAlert('danger', message);
      });
    }
  },
  created: function created() {
    this.requestCashSetting();
  }
};

/***/ }),
/* 145 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_c('router-link', {
    attrs: {
      "to": "/wallet/cash",
      "replace": ""
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-menu-left"
  }), _vm._v("\n        返回\n      ")]), _vm._v(" "), _c('span', {
    staticClass: "pull-right"
  }, [_vm._v("提现审批 - 提现设置")])], 1), _vm._v(" "), (_vm.load.status === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.load.status === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("提现方式")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('div', {
    staticClass: "checkbox"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.cashType),
      expression: "cashType"
    }],
    attrs: {
      "type": "checkbox",
      "value": "alipay"
    },
    domProps: {
      "checked": Array.isArray(_vm.cashType) ? _vm._i(_vm.cashType, "alipay") > -1 : (_vm.cashType)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.cashType,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "alipay",
            $$i = _vm._i($$a, $$v);
          if ($$el.checked) {
            $$i < 0 && (_vm.cashType = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.cashType = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.cashType = $$c
        }
      }
    }
  }), _vm._v(" 支付宝\n            ")])]), _vm._v(" "), _c('div', {
    staticClass: "checkbox"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.cashType),
      expression: "cashType"
    }],
    attrs: {
      "type": "checkbox",
      "value": "wechat"
    },
    domProps: {
      "checked": Array.isArray(_vm.cashType) ? _vm._i(_vm.cashType, "wechat") > -1 : (_vm.cashType)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.cashType,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "wechat",
            $$i = _vm._i($$a, $$v);
          if ($$el.checked) {
            $$i < 0 && (_vm.cashType = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.cashType = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.cashType = $$c
        }
      }
    }
  }), _vm._v(" 微信\n            ")])])]), _vm._v(" "), _vm._m(0)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("最低提现")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.minAmountCompute),
      expression: "minAmountCompute"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "number"
    },
    domProps: {
      "value": (_vm.minAmountCompute)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.minAmountCompute = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("设置最低用户提现金额，这里设置真实金额，验证的时候会自动验证转换后金额。")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [(_vm.update === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n            提交...\n          ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.updateHandle
    }
  }, [_vm._v("提交")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.alert.open),
      expression: "alert.open"
    }],
    class: ['alert', ("alert-" + (_vm.alert.type)), _vm.$style.alert],
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.alert.message) + "\n      ")])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.load.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.requestCashSetting
    }
  }, [_vm._v("刷新")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block"
  }, [_vm._v("选择用户提现支持的提现方式，如果都不勾选，则表示关闭提现功能。")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-502004af", esExports)
  }
}

/***/ }),
/* 146 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayOption_vue__ = __webpack_require__(147);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayOption_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayOption_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_97c91262_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PayOption_vue__ = __webpack_require__(148);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-97c91262\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./PayOption.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(31)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-97c91262")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(31)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayOption_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_97c91262_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PayOption_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/PayOption.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] PayOption.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-97c91262", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-97c91262", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 147 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(7);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } } //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

exports.default = {
  data: function data() {
    return {
      loadding: {
        status: 0,
        message: ''
      },
      labels: [],
      add: {
        inputStatus: false,
        adding: false,
        value: ''
      },
      alert: {
        open: false,
        interval: null,
        type: 'info',
        message: null
      },
      del: {
        label: null,
        open: false,
        ing: false
      }
    };
  },
  methods: {
    openAddInput: function openAddInput() {
      this.add.inputStatus = true;
    },
    addLabel: function addLabel() {
      var _this = this;

      var label = this.add.value;

      label = parseInt(label * 100);

      if (!label) {
        this.sendAlert('danger', '输入选项不能为空！');
        return;
      } else if (isNaN(label)) {
        this.sendAlert('danger', '输入的选项存在错误字符');
        return;
      } else if (this.labels.indexOf(label) !== -1) {
        this.sendAlert('danger', '输入的选项已经存在');
        return;
      }

      this.add.adding = true;

      _request2.default.post((0, _request.createRequestURI)('wallet/labels'), { label: label }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this.add = {
          adding: false,
          inputStatus: false,
          value: ''
        };
        _this.labels = [].concat(_toConsumableArray(_this.labels), [label]);
        _this.sendAlert('success', '创建选项成功!');
      }).catch(function (_ref) {
        var _ref$response = _ref.response;
        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data;
        _ref$response$data = _ref$response$data === undefined ? {} : _ref$response$data;
        var _ref$response$data$me = _ref$response$data.message,
            message = _ref$response$data$me === undefined ? [] : _ref$response$data$me,
            _ref$response$data$la = _ref$response$data.label,
            label = _ref$response$data$la === undefined ? [] : _ref$response$data$la;

        var _ref2 = [].concat(_toConsumableArray(message), _toConsumableArray(label)),
            _ref2$ = _ref2[0],
            currentMessage = _ref2$ === undefined ? '创建失败，请检查网络！' : _ref2$;

        _this.add.adding = false;
        _this.sendAlert('danger', currentMessage);
      });
    },
    sendAlert: function sendAlert(type, message) {
      var _this2 = this;

      window.clearInterval(this.alert.interval);
      this.alert = _extends({}, this.alert, {
        type: type,
        message: message,
        open: true,
        interval: window.setInterval(function () {
          window.clearInterval(_this2.alert.interval);
          _this2.alert.open = false;
        }, 2000)
      });
    },
    requestLabel: function requestLabel() {
      var _this3 = this;

      this.loadding.status = 0;
      _request2.default.get((0, _request.createRequestURI)('wallet/labels'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref3) {
        var _ref3$data = _ref3.data,
            data = _ref3$data === undefined ? [] : _ref3$data;

        _this3.labels = _lodash2.default.reduce(data, function (labels, label) {
          labels.push(parseInt(label));

          return labels;
        }, []);
        _this3.loadding.status = 1;
      }).catch(function (_ref4) {
        var _ref4$response = _ref4.response;
        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$m = _ref4$response$data.message;
        _ref4$response$data$m = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m;

        var _ref4$response$data$m2 = _slicedToArray(_ref4$response$data$m, 1),
            message = _ref4$response$data$m2[0];

        _this3.loadding = {
          status: 2,
          message: message || '加载失败!'
        };
      });
    },
    sendDeleteLabel: function sendDeleteLabel() {
      var _this4 = this;

      this.del.ing = true;
      var label = this.del.label;

      _request2.default.delete((0, _request.createRequestURI)('wallet/labels/' + label), { validateStatus: function validateStatus(status) {
          return status === 204;
        } }).then(function () {
        _this4.del = {
          open: false,
          ing: false,
          label: null
        };
        _this4.labels = _lodash2.default.reduce(_this4.labels, function (labels, item) {
          if (item !== label) {
            labels.push(item);
          }

          return labels;
        }, []);
        _this4.sendAlert('success', '删除成功!');
      }).catch(function (_ref5) {
        var _ref5$response = _ref5.response;
        _ref5$response = _ref5$response === undefined ? {} : _ref5$response;
        var _ref5$response$data = _ref5$response.data;
        _ref5$response$data = _ref5$response$data === undefined ? {} : _ref5$response$data;
        var _ref5$response$data$m = _ref5$response$data.message,
            message = _ref5$response$data$m === undefined ? [] : _ref5$response$data$m;

        var _message = _slicedToArray(message, 1),
            _message$ = _message[0],
            currentMessage = _message$ === undefined ? '删除失败！' : _message$;

        _this4.del.ing = false;
        _this4.sendAlert('danger', currentMessage);
      });
    },
    unDeleteLabel: function unDeleteLabel() {
      if (this.del.ing) {
        return false;
      }

      this.del = {
        ing: false,
        open: false,
        label: null
      };
    },
    deleteLabel: function deleteLabel(label) {
      this.del = {
        open: true,
        ing: false,
        label: label
      };
    }
  },
  created: function created() {
    this.requestLabel();
  }
};

/***/ }),
/* 148 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("基础设置 - 充值选项")]), _vm._v(" "), (_vm.loadding.status === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.loadding.status === 1) ? _c('div', {
    staticClass: "panel-body"
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    class: _vm.$style.labelBox
  }, [_vm._l((_vm.labels), function(label) {
    return _c('span', {
      staticClass: "label label-info",
      class: _vm.$style.label
    }, [_vm._v("\n          " + _vm._s(label / 100) + "\n          "), _c('span', {
      class: _vm.$style.labelDelete,
      attrs: {
        "title": "删除",
        "aria-hidden": "true"
      },
      on: {
        "click": function($event) {
          _vm.deleteLabel(label)
        }
      }
    }, [_vm._v("×")])])
  }), _vm._v(" "), _c('span', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.add.inputStatus === false),
      expression: "add.inputStatus === false"
    }],
    staticClass: "label label-danger",
    class: _vm.$style.add,
    on: {
      "click": _vm.openAddInput
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-plus"
  }), _vm._v(" 添加\n        ")])], 2), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.add.inputStatus === true),
      expression: "add.inputStatus === true"
    }],
    staticClass: "input-group",
    class: _vm.$style.addLabel
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.add.value),
      expression: "add.value"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "number",
      "min": "1",
      "placeholder": "输入新的选项",
      "disabled": _vm.add.adding
    },
    domProps: {
      "value": (_vm.add.value)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.add.value = $event.target.value
      }
    }
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-btn"
  }, [(_vm.add.adding === false) ? _c('button', {
    staticClass: "btn btn-success",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.addLabel
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-plus"
  }), _vm._v(" 添加\n          ")]) : _c('button', {
    staticClass: "btn btn-success",
    attrs: {
      "type": "button",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v(" 添加...\n          ")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.alert.open),
      expression: "alert.open"
    }],
    class: ['alert', ("alert-" + (_vm.alert.type)), _vm.$style.alert],
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.alert.message) + "\n      ")]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.del.open),
      expression: "del.open"
    }],
    staticClass: "alert alert-danger",
    class: _vm.$style.alert,
    attrs: {
      "role": "alert"
    }
  }, [_c('p', [_vm._v("是否删除 「"), _c('strong', [_vm._v(_vm._s(_vm.del.label / 100))]), _vm._v("」 选项？")]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-default",
    attrs: {
      "type": "button",
      "disabled": _vm.del.ing
    },
    on: {
      "click": _vm.unDeleteLabel
    }
  }, [_vm._v("取消")]), _vm._v(" "), (_vm.del.ing === false) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.sendDeleteLabel
    }
  }, [_vm._v("删除")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v(" 删除...\n        ")])])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.loadding.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.requestLabel
    }
  }, [_vm._v("刷新")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('blockquote', [_c('p', [_vm._v("设置充值选项可以让用户在充值页面快速选择充值金额(只能输入整数)，用户也可以选择输入自定义金额进行充值。")]), _vm._v(" "), _c('footer', [_vm._v("在使用 Apple Pay 充值是非常好的选择，因为苹果支付有这样要的要求。")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-97c91262", esExports)
  }
}

/***/ }),
/* 149 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRule_vue__ = __webpack_require__(150);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRule_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRule_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_63a6db54_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PayRule_vue__ = __webpack_require__(151);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-63a6db54\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./PayRule.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(32)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-63a6db54")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(32)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRule_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_63a6db54_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PayRule_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/PayRule.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] PayRule.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-63a6db54", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-63a6db54", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 150 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
  data: function data() {
    return {
      load: {
        status: 0,
        message: null
      },
      rule: null,
      update: false,
      alert: {
        open: false,
        interval: null,
        type: 'info',
        message: null
      }
    };
  },
  methods: {
    /**
     * 加载规则.
     *
     * @return {vodi}
     * @author Seven Du <shiweidu@outlook.com>
     */
    loadRule: function loadRule() {
      var _this = this;

      this.load.status = 0;
      _request2.default.get((0, _request.createRequestURI)('wallet/rule'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data$rule = _ref.data.rule,
            rule = _ref$data$rule === undefined ? null : _ref$data$rule;

        _this.load.status = 1;
        _this.rule = rule;
      }).catch(function (_ref2) {
        var _ref3;

        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message,
            message = _ref2$response$data$m === undefined ? (_ref3 = [], message = _ref3[0], _ref3) : _ref2$response$data$m;

        _this.load = {
          status: 2,
          message: message
        };
      });
    },


    /**
     * 发送更新规则.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateRule: function updateRule() {
      var _this2 = this;

      var rule = this.rule;
      this.update = true;
      _request2.default.patch((0, _request.createRequestURI)('wallet/rule'), { rule: rule }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this2.update = false;
        _this2.sendAlert('success', '更新成功！');
      }).catch(function (_ref4) {
        var _ref4$response = _ref4.response;
        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$m = _ref4$response$data.message;
        _ref4$response$data$m = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m;

        var _ref4$response$data$m2 = _slicedToArray(_ref4$response$data$m, 1),
            _ref4$response$data$m3 = _ref4$response$data$m2[0],
            message = _ref4$response$data$m3 === undefined ? '提交失败，请重试！' : _ref4$response$data$m3;

        _this2.update = false;
        _this2.sendAlert('danger', message);
      });
    },


    /**
     * 发送提示.
     *
     * @param {string} type
     * @param {string} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert: function sendAlert(type, message) {
      var _this3 = this;

      window.clearInterval(this.alert.interval);
      this.alert = _extends({}, this.alert, {
        type: type,
        message: message,
        open: true,
        interval: window.setInterval(function () {
          window.clearInterval(_this3.alert.interval);
          _this3.alert.open = false;
        }, 2000)
      });
    }
  },
  created: function created() {
    this.loadRule();
  }
};

/***/ }),
/* 151 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("基础设置 - 规则描述设置")]), _vm._v(" "), (_vm.load.status === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.load.status === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "wallet-rule"
    }
  }, [_vm._v("规则描述")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.rule),
      expression: "rule"
    }],
    staticClass: "form-control",
    attrs: {
      "id": "wallet-rule",
      "rows": "3",
      "aria-describedby": "wallet-rule-help",
      "placeholder": "输入规则"
    },
    domProps: {
      "value": (_vm.rule)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.rule = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(0)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [(_vm.update === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n            提交...\n          ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.updateRule
    }
  }, [_vm._v("提交")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.alert.open),
      expression: "alert.open"
    }],
    class: ['alert', ("alert-" + (_vm.alert.type)), _vm.$style.alert],
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.alert.message) + "\n      ")])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.load.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.loadRule
    }
  }, [_vm._v("刷新")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "wallet-rule"
    }
  }, [_vm._v("输入充值、提现等描述规则。")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-63a6db54", esExports)
  }
}

/***/ }),
/* 152 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RechargeType_vue__ = __webpack_require__(153);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RechargeType_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RechargeType_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_297b5a42_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_RechargeType_vue__ = __webpack_require__(154);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_RechargeType_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_297b5a42_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_RechargeType_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/RechargeType.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] RechargeType.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-297b5a42", Component.options)
  } else {
    hotAPI.reload("data-v-297b5a42", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 153 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

exports.default = {
  data: function data() {
    return {
      load: {
        status: 0,
        message: ''
      },
      support: {},
      types: [],
      updating: false,
      alert: {
        status: false,
        type: 'info',
        message: '',
        interval: null
      }
    };
  },
  methods: {
    /**
     * Send alert message tip.
     *
     * @param {String} type
     * @param {String} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert: function sendAlert(type, message) {
      var _this = this;

      window.clearInterval(this.alert.interval);
      this.alert = { type: type, message: message, status: true, interval: window.setInterval(function () {
          window.clearInterval(_this.alert.interval);
          _this.alert.status = false;
        }, 2000) };
    },


    /**
     * Request support types.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestTypes: function requestTypes() {
      var _this2 = this;

      this.load.status = 0;
      _request2.default.get((0, _request.createRequestURI)('wallet/recharge/types'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data,
            _ref$data$support = _ref$data.support,
            support = _ref$data$support === undefined ? {} : _ref$data$support,
            _ref$data$types = _ref$data.types,
            types = _ref$data$types === undefined ? [] : _ref$data$types;

        _this2.load.status = 1;
        _this2.support = support;
        _this2.types = types;
      }).catch(function () {
        var _ref2 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref2$response = _ref2.response;

        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            _ref2$response$data$m3 = _ref2$response$data$m2[0],
            message = _ref2$response$data$m3 === undefined ? '加载失败，请刷新页面重试' : _ref2$response$data$m3;

        _this2.load = {
          status: 2,
          message: message
        };
      });
    },


    /**
     * Update support types.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateType: function updateType() {
      var _this3 = this;

      this.updating = true;
      _request2.default.patch((0, _request.createRequestURI)('wallet/recharge/types'), { types: this.types }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref3) {
        var _ref3$data$message = _ref3.data.message;
        _ref3$data$message = _ref3$data$message === undefined ? [] : _ref3$data$message;

        var _ref3$data$message2 = _slicedToArray(_ref3$data$message, 1),
            _ref3$data$message2$ = _ref3$data$message2[0],
            message = _ref3$data$message2$ === undefined ? '更新成功' : _ref3$data$message2$;

        _this3.updating = false;
        _this3.sendAlert('success', message);
      }).catch(function () {
        var _ref4 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref4$response = _ref4.response;

        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$t = _ref4$response$data.types,
            typeMessage = _ref4$response$data$t === undefined ? [] : _ref4$response$data$t,
            _ref4$response$data$m = _ref4$response$data.message,
            anyMessage = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m;

        var _ref5 = [].concat(_toConsumableArray(typeMessage), [anyMessage]),
            _ref5$ = _ref5[0],
            message = _ref5$ === undefined ? '提交失败，请刷新网页重试' : _ref5$;

        _this3.updating = false;
        _this3.sendAlert('danger', message);
      });
    }
  },

  /**
   * The page created.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created: function created() {
    this.requestTypes();
  }
};

/***/ }),
/* 154 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("支付设置 - 支付选项")]), _vm._v(" "), (_vm.load.status === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.load.status === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("充值选项")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, _vm._l((_vm.support), function(name, type) {
    return _c('div', {
      key: type,
      staticClass: "checkbox"
    }, [_c('label', [_c('input', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.types),
        expression: "types"
      }],
      attrs: {
        "type": "checkbox",
        "disabled": _vm.updating
      },
      domProps: {
        "value": type,
        "checked": Array.isArray(_vm.types) ? _vm._i(_vm.types, type) > -1 : (_vm.types)
      },
      on: {
        "__c": function($event) {
          var $$a = _vm.types,
            $$el = $event.target,
            $$c = $$el.checked ? (true) : (false);
          if (Array.isArray($$a)) {
            var $$v = type,
              $$i = _vm._i($$a, $$v);
            if ($$el.checked) {
              $$i < 0 && (_vm.types = $$a.concat($$v))
            } else {
              $$i > -1 && (_vm.types = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
            }
          } else {
            _vm.types = $$c
          }
        }
      }
    }), _vm._v(" " + _vm._s(name) + "\n            ")])])
  })), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block"
  }, [_vm._v("选择不同场景下开启的支付方式，选择后对应场景则无此支付功能。")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-10"
  }, [(_vm.updating) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  })]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.updateType
    }
  }, [_vm._v("提交")])])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.alert.status),
      expression: "alert.status"
    }],
    class: ['alert', ("alert-" + (_vm.alert.type))],
    staticStyle: {
      "margin-top": "16px"
    },
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.alert.message) + "\n      ")])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.load.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.requestTypes
    }
  }, [_vm._v("重试")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('blockquote', [_c('p', [_vm._v("如果将充值选项全部取消，则表示关闭充值功能")]), _vm._v(" "), _c('footer', [_vm._v("单个平台多个选择时针对不同场景的分类，关闭某个场景，某个场景则无支付。")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-297b5a42", esExports)
  }
}

/***/ }),
/* 155 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PingPlusPlus_vue__ = __webpack_require__(156);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PingPlusPlus_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PingPlusPlus_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_f17a7078_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PingPlusPlus_vue__ = __webpack_require__(157);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PingPlusPlus_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_f17a7078_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PingPlusPlus_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/PingPlusPlus.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] PingPlusPlus.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-f17a7078", Component.options)
  } else {
    hotAPI.reload("data-v-f17a7078", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 156 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

exports.default = {
  data: function data() {
    return {
      appId: null,
      secretKey: null,
      publicKey: null,
      privateKey: null,
      load: {
        status: 0,
        message: ''
      },
      alert: {
        status: false,
        type: 'info',
        message: '',
        interval: null
      },
      updating: false
    };
  },
  methods: {
    /**
     * Request Ping++ config.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestConfig: function requestConfig() {
      var _this = this;

      this.load.status = 0;
      _request2.default.get((0, _request.createRequestURI)('wallet/pingpp'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data;
        _ref$data = _ref$data === undefined ? {} : _ref$data;
        var appId = _ref$data.app_id,
            secretKey = _ref$data.secret_key,
            publicKey = _ref$data.public_key,
            privateKey = _ref$data.private_key;

        _this.load.status = 1;
        _this.appId = appId;
        _this.secretKey = secretKey;
        _this.publicKey = publicKey;
        _this.privateKey = privateKey;
      }).catch(function () {
        var _ref2 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref2$response = _ref2.response;

        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            message = _ref2$response$data$m2[0];

        _this.load = {
          status: 2,
          message: message
        };
      });
    },


    /**
     * Update Ping++ config.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateConfig: function updateConfig() {
      var _this2 = this;

      this.updating = true;
      _request2.default.patch((0, _request.createRequestURI)('wallet/pingpp'), { app_id: this.appId, secret_key: this.secretKey, public_key: this.publicKey, private_key: this.privateKey }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function (_ref3) {
        var _ref3$data$message = _ref3.data.message;
        _ref3$data$message = _ref3$data$message === undefined ? [] : _ref3$data$message;

        var _ref3$data$message2 = _slicedToArray(_ref3$data$message, 1),
            _ref3$data$message2$ = _ref3$data$message2[0],
            message = _ref3$data$message2$ === undefined ? '更新成功' : _ref3$data$message2$;

        _this2.updating = false;
        _this2.sendAlert('success', message);
      }).catch(function () {
        var _ref4 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
            _ref4$response = _ref4.response;

        _ref4$response = _ref4$response === undefined ? {} : _ref4$response;
        var _ref4$response$data = _ref4$response.data;
        _ref4$response$data = _ref4$response$data === undefined ? {} : _ref4$response$data;
        var _ref4$response$data$a = _ref4$response$data.app_id,
            appIdMessage = _ref4$response$data$a === undefined ? [] : _ref4$response$data$a,
            _ref4$response$data$s = _ref4$response$data.secret_key,
            secretKeyMessage = _ref4$response$data$s === undefined ? [] : _ref4$response$data$s,
            _ref4$response$data$p = _ref4$response$data.public_key,
            publicKeyMessage = _ref4$response$data$p === undefined ? [] : _ref4$response$data$p,
            _ref4$response$data$p2 = _ref4$response$data.private_key,
            privateKeyMessage = _ref4$response$data$p2 === undefined ? [] : _ref4$response$data$p2,
            _ref4$response$data$m = _ref4$response$data.message,
            anyMessage = _ref4$response$data$m === undefined ? [] : _ref4$response$data$m;

        var _ref5 = [].concat(_toConsumableArray(appIdMessage), _toConsumableArray(secretKeyMessage), _toConsumableArray(publicKeyMessage), _toConsumableArray(privateKeyMessage), _toConsumableArray(anyMessage)),
            _ref5$ = _ref5[0],
            message = _ref5$ === undefined ? '提交失败，请刷新重试！' : _ref5$;

        _this2.updating = false;
        _this2.sendAlert('danger', message);
      });
    },


    /**
     * Send alert message tip.
     *
     * @param {String} type
     * @param {String} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert: function sendAlert(type, message) {
      var _this3 = this;

      window.clearInterval(this.alert.interval);
      this.alert = { type: type, message: message, status: true, interval: window.setInterval(function () {
          window.clearInterval(_this3.alert.interval);
          _this3.alert.status = false;
        }, 1500) };
    }
  },
  /**
   * The page created.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created: function created() {
    this.requestConfig();
  }
};

/***/ }),
/* 157 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("支付设置 -  Ping++")]), _vm._v(" "), (_vm.load.status === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.load.status === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("应用 ID")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.appId),
      expression: "appId"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "输入应用 ID"
    },
    domProps: {
      "value": (_vm.appId)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.appId = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("\n          请输入应用ID。\n        ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("Secret Key")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.secretKey),
      expression: "secretKey"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "placeholder": "请输入 Secret Key"
    },
    domProps: {
      "value": (_vm.secretKey)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.secretKey = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("\n          输入 Secret Key，非上线环境请输入 Test Secret Key，正式环境请输入 Live Secret Key。\n        ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("Ping++ 公钥")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.publicKey),
      expression: "publicKey"
    }],
    staticClass: "form-control",
    attrs: {
      "rows": "3"
    },
    domProps: {
      "value": (_vm.publicKey)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.publicKey = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("\n          用于 Webhooks 回调时验证其正确性，不设置或者错误设置会造成所有异步通知的订单用户支付成功，但是不会到账。\n        ")])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("商户私钥")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.privateKey),
      expression: "privateKey"
    }],
    staticClass: "form-control",
    attrs: {
      "rows": "3"
    },
    domProps: {
      "value": (_vm.privateKey)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.privateKey = $event.target.value
      }
    }
  })]), _vm._v(" "), _vm._m(1)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-10"
  }, [(_vm.updating) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  })]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.updateConfig
    }
  }, [_vm._v("提交")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.alert.status),
      expression: "alert.status"
    }],
    class: ['alert', ("alert-" + (_vm.alert.type))],
    staticStyle: {
      "margin-top": "16px"
    },
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.alert.message) + "\n      ")])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.load.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.requestConfig
    }
  }, [_vm._v("重试")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('blockquote', [_c('p', [_vm._v("ThinkSNS+ 使用 "), _c('a', {
    attrs: {
      "href": "https://www.pingxx.com/",
      "target": "block"
    }
  }, [_vm._v("Ping++")]), _vm._v(" 进行支付集成，以提供统一的支付接口使其方便拓展。")]), _vm._v(" "), _c('footer', [_vm._v("因为使用 RSA 进行认证，所以请服务器安装 OpenSSL 的 PHP 拓展。")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("\n          商户私钥是与 Ping++ 服务器交互的认证凭据，可以「"), _c('a', {
    attrs: {
      "href": ""
    }
  }, [_vm._v("点击这里")]), _vm._v("」获取一对 公／私钥，获取后倾妥善保管，公钥设置到 Ping++ 中，私钥设置在这里。\n        ")])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-f17a7078", esExports)
  }
}

/***/ }),
/* 158 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRatio_vue__ = __webpack_require__(159);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRatio_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRatio_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_e97292be_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PayRatio_vue__ = __webpack_require__(160);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-e97292be\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./PayRatio.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(33)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-e97292be")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(33)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_PayRatio_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_e97292be_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_PayRatio_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/wallet/PayRatio.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] PayRatio.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-e97292be", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-e97292be", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 159 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
  data: function data() {
    return {
      ratio: 100,
      load: {
        status: 0,
        message: null
      },
      update: false,
      alert: {
        open: false,
        interval: null,
        type: 'info',
        message: null
      }
    };
  },
  methods: {
    /**
     * 请求转换值.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestRatio: function requestRatio() {
      var _this = this;

      this.load.status = 0;
      _request2.default.get((0, _request.createRequestURI)('wallet/ratio'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var ratio = _ref.data.ratio;

        _this.load.status = 1;
        _this.ratio = ratio;
      }).catch(function (_ref2) {
        var _ref2$response = _ref2.response;
        _ref2$response = _ref2$response === undefined ? {} : _ref2$response;
        var _ref2$response$data = _ref2$response.data;
        _ref2$response$data = _ref2$response$data === undefined ? {} : _ref2$response$data;
        var _ref2$response$data$m = _ref2$response$data.message;
        _ref2$response$data$m = _ref2$response$data$m === undefined ? [] : _ref2$response$data$m;

        var _ref2$response$data$m2 = _slicedToArray(_ref2$response$data$m, 1),
            message = _ref2$response$data$m2[0];

        _this.load = {
          status: 2,
          message: message
        };
      });
    },


    /**
     * 发送转换值到服务端.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateRatio: function updateRatio() {
      var _this2 = this;

      var ratio = this.ratio;
      this.update = true;
      _request2.default.patch((0, _request.createRequestURI)('wallet/ratio'), { ratio: ratio }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this2.update = false;
        _this2.sendAlert('success', '更新成功');
      }).catch(function (_ref3) {
        var _ref3$response = _ref3.response;
        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data;
        _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
        var _ref3$response$data$m = _ref3$response$data.message;
        _ref3$response$data$m = _ref3$response$data$m === undefined ? [] : _ref3$response$data$m;

        var _ref3$response$data$m2 = _slicedToArray(_ref3$response$data$m, 1),
            message = _ref3$response$data$m2[0];

        _this2.update = false;
        _this2.sendAlert('danger', message);
      });
    },
    sendAlert: function sendAlert(type, message) {
      var _this3 = this;

      window.clearInterval(this.alert.interval);
      this.alert = _extends({}, this.alert, {
        type: type,
        message: message,
        open: true,
        interval: window.setInterval(function () {
          window.clearInterval(_this3.alert.interval);
          _this3.alert.open = false;
        }, 2000)
      });
    }
  },
  /**
   * 组件创建成功事件.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created: function created() {
    this.requestRatio();
  }
};

/***/ }),
/* 160 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("基础设置 - 转换比例")]), _vm._v(" "), (_vm.load.status === 0) ? _c('div', {
    staticClass: "panel-body text-center"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n      加载中...\n    ")]) : (_vm.load.status === 1) ? _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "wallet-ratio"
    }
  }, [_vm._v("转换比例")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('div', {
    staticClass: "input-group"
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.ratio),
      expression: "ratio"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "number",
      "name": "ratio",
      "id": "wallet-ratio",
      "placeholder": "输入转换比例",
      "aria-describedby": "wallet-ratio-help",
      "min": "1",
      "max": "1000",
      "step": "1"
    },
    domProps: {
      "value": (_vm.ratio)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.ratio = $event.target.value
      }
    }
  }), _vm._v(" "), _c('span', {
    staticClass: "input-group-addon",
    attrs: {
      "id": "basic-addon2"
    }
  }, [_vm._v("%")])])]), _vm._v(" "), _vm._m(1)]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('div', {
    staticClass: "col-sm-offset-2 col-sm-4"
  }, [(_vm.update === true) ? _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "submit",
      "disabled": "disabled"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  }), _vm._v("\n            提交...\n          ")]) : _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.stopPropagation();
        $event.preventDefault();
        _vm.updateRatio($event)
      }
    }
  }, [_vm._v("提交")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.alert.open),
      expression: "alert.open"
    }],
    class: ['alert', ("alert-" + (_vm.alert.type)), _vm.$style.alert],
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.alert.message) + "\n      ")])]) : _c('div', {
    staticClass: "panel-body"
  }, [_c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.load.message))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-primary",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.requestRatio
    }
  }, [_vm._v("刷新")])])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('blockquote', [_c('p', [_vm._v("转换比例为「真实货币」如人民币，美元等与钱包系统「用户余额」比例的设置。")]), _vm._v(" "), _c('footer', [_vm._v("以「CNY」为例，比例设置为 200% 则充值 1CNY 则得到 2 余额。")])])
},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "col-sm-6"
  }, [_c('span', {
    staticClass: "help-block",
    attrs: {
      "id": "wallet-ratio-help"
    }
  }, [_vm._v("输入转换比例，不理只能是正整数，范围在 1 - 1000 之间。")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-e97292be", esExports)
  }
}

/***/ }),
/* 161 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Main = __webpack_require__(162);

var _Main2 = _interopRequireDefault(_Main);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
  path: 'ad',
  component: _Main2.default
}; //
// The file is defined "/ad" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//

/***/ }),
/* 162 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_69458b2d_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__ = __webpack_require__(163);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_69458b2d_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/ad/Main.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Main.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-69458b2d", Component.options)
  } else {
    hotAPI.reload("data-v-69458b2d", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 163 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('ul', {
    staticClass: "nav nav-tabs component-controller-nav"
  }, [_c('router-link', {
    attrs: {
      "to": "/ad",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("统计")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/ad/list",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("广告管理")])])], 1), _vm._v(" "), _c('router-view')], 1)
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-69458b2d", esExports)
  }
}

/***/ }),
/* 164 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Main = __webpack_require__(165);

var _Main2 = _interopRequireDefault(_Main);

var _Home = __webpack_require__(167);

var _Home2 = _interopRequireDefault(_Home);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * The file defuned /paid route.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

exports.default = {
  path: 'paid',
  component: _Main2.default,
  children: [{ path: '', component: _Home2.default }]
};

/***/ }),
/* 165 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_217f2ea4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__ = __webpack_require__(166);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_217f2ea4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Main_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/paid/Main.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Main.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-217f2ea4", Component.options)
  } else {
    hotAPI.reload("data-v-217f2ea4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 166 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('ul', {
    staticClass: "nav nav-tabs component-controller-nav"
  }, [_c('router-link', {
    attrs: {
      "to": "/paid",
      "tag": "li",
      "active-class": "active",
      "exact": ""
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("基础设置")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/paid/manage",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', [_vm._v("付费管理")])])], 1), _vm._v(" "), _c('div', {
    staticClass: "component-container container-fluid"
  }, [_c('router-view')], 1)])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-217f2ea4", esExports)
  }
}

/***/ }),
/* 167 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue__ = __webpack_require__(168);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_05051aca_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Home_vue__ = __webpack_require__(169);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_05051aca_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Home_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/paid/Home.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Home.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-05051aca", Component.options)
  } else {
    hotAPI.reload("data-v-05051aca", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 168 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

exports.default = {
  data: function data() {
    return {
      paidOpen: 0
    };
  }
};

/***/ }),
/* 169 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "panel panel-default"
  }, [_c('div', {
    staticClass: "panel-heading"
  }, [_vm._v("基础设置")]), _vm._v(" "), _c('div', {
    staticClass: "panel-body form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("开启付费")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-4"
  }, [_c('div', {
    staticClass: "radio"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.paidOpen),
      expression: "paidOpen"
    }],
    attrs: {
      "type": "radio",
      "name": "open",
      "value": "0"
    },
    domProps: {
      "checked": _vm._q(_vm.paidOpen, "0")
    },
    on: {
      "__c": function($event) {
        _vm.paidOpen = "0"
      }
    }
  }), _vm._v(" 关闭\n          ")])]), _vm._v(" "), _c('div', {
    staticClass: "radio"
  }, [_c('label', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.paidOpen),
      expression: "paidOpen"
    }],
    attrs: {
      "type": "radio",
      "name": "open",
      "value": "1"
    },
    domProps: {
      "checked": _vm._q(_vm.paidOpen, "1")
    },
    on: {
      "__c": function($event) {
        _vm.paidOpen = "1"
      }
    }
  }), _vm._v(" 开启\n          ")])])]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-6 help-block"
  }, [_vm._v("如果需要启动内容付费，则开启，设置关闭则关闭全部付费")])])])])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-05051aca", esExports)
  }
}

/***/ }),
/* 170 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Login_vue__ = __webpack_require__(172);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Login_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Login_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_12970031_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Login_vue__ = __webpack_require__(173);
var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(171)
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Login_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_12970031_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Login_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/Login.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Login.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-12970031", Component.options)
  } else {
    hotAPI.reload("data-v-12970031", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 171 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 172 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _types = __webpack_require__(5);

var _auth = __webpack_require__(13);

var _auth2 = _interopRequireDefault(_auth);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var login = {
  data: function data() {
    return {
      access: '',
      password: '',
      error: null
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      var access = this.access,
          password = this.password;

      this.error = null;
      _auth2.default.login(access, password).then(function (response) {
        _this.$store.dispatch(_types.USER_UPDATE, function (cb) {
          cb(response.data);
          _this.$router.replace(_this.$route.query.redirect || '/');
        });
      }).catch(function (_ref) {
        var _ref$response$data = _ref.response.data,
            data = _ref$response$data === undefined ? {} : _ref$response$data;
        var id = data.id,
            name = data.name,
            phone = data.phone,
            email = data.email,
            password = data.password;

        _this.error = id || name || phone || email || password || '登录失败！';
      });
    }
  }
};

exports.default = login;

/***/ }),
/* 173 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container"
  }, [_c('div', {
    staticClass: "center-block login"
  }, [_c('form', {
    attrs: {
      "role": "form"
    },
    on: {
      "submit": function($event) {
        $event.preventDefault();
        _vm.submit($event)
      }
    }
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    attrs: {
      "for": "access"
    }
  }, [_vm._v("账户")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.access),
      expression: "access"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "access",
      "placeholder": "输入账号"
    },
    domProps: {
      "value": (_vm.access)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.access = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    attrs: {
      "for": "password"
    }
  }, [_vm._v("密码")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.password),
      expression: "password"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "password",
      "id": "password",
      "placeholder": "输入密码"
    },
    domProps: {
      "value": (_vm.password)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.password = $event.target.value
      }
    }
  })]), _vm._v(" "), (_vm.error) ? _c('div', {
    staticClass: "alert alert-danger",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v(_vm._s(_vm.error))]) : _vm._e(), _vm._v(" "), _c('button', {
    staticClass: "btn btn-lg btn-primary btn-block",
    attrs: {
      "type": "submit"
    }
  }, [_vm._v("登录")])])])])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-12970031", esExports)
  }
}

/***/ }),
/* 174 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue__ = __webpack_require__(176);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_d7706172_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Home_vue__ = __webpack_require__(183);
var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(175)
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Home_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_d7706172_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Home_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/Home.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Home.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d7706172", Component.options)
  } else {
    hotAPI.reload("data-v-d7706172", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 175 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 176 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

// components.


var _vuex = __webpack_require__(8);

var _request = __webpack_require__(1);

var _getterTypes = __webpack_require__(6);

var _defaultAvatar = __webpack_require__(177);

var _defaultAvatar2 = _interopRequireDefault(_defaultAvatar);

var _Nav = __webpack_require__(179);

var _Nav2 = _interopRequireDefault(_Nav);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var home = {
  data: function data() {
    return {
      logout: (0, _request.createRequestURI)('logout')
    };
  },
  computed: _extends({}, (0, _vuex.mapGetters)([_getterTypes.USER]), {
    user: function user() {
      return this[_getterTypes.USER];
    }
  }),
  components: {
    'system-nav': _Nav2.default,
    'default-avatar': _defaultAvatar2.default
  }
};

exports.default = home;

/***/ }),
/* 177 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_92071920_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_default_avatar_vue__ = __webpack_require__(178);
var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = null
/* template */

/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __WEBPACK_IMPORTED_MODULE_0__node_modules_vue_loader_lib_template_compiler_index_id_data_v_92071920_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_default_avatar_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/icons/default-avatar.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] default-avatar.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-92071920", Component.options)
  } else {
    hotAPI.reload("data-v-92071920", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 178 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('svg', {
    attrs: {
      "xmlns": "http://www.w3.org/2000/svg",
      "viewBox": "0 0 100 100"
    }
  }, [_c('circle', {
    attrs: {
      "fill": "#ececec",
      "cx": "50",
      "cy": "50",
      "r": "50"
    }
  }), _vm._v(" "), _c('circle', {
    attrs: {
      "fill": "#fff",
      "cx": "50",
      "cy": "32",
      "r": "19"
    }
  }), _vm._v(" "), _c('path', {
    attrs: {
      "fill": "#fff",
      "d": "M67.3,49c-4.5,4.3-10.6,7-17.3,7c-6.6,0-12.5-2.5-17-6.7c-7.4,3.9-13.4,10.3-16.8,18C22.5,79.6,35.3,88,50,88\n    c15.2,0,28.2-8.9,34.3-21.7C80.7,58.8,74.7,52.7,67.3,49z"
    }
  })])
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-92071920", esExports)
  }
}

/***/ }),
/* 179 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Nav_vue__ = __webpack_require__(181);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Nav_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Nav_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_604b8e2a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Nav_vue__ = __webpack_require__(182);
var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(180)
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Nav_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_604b8e2a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Nav_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/Nav.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Nav.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-604b8e2a", Component.options)
  } else {
    hotAPI.reload("data-v-604b8e2a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);


/***/ }),
/* 180 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 181 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

var _types = __webpack_require__(5);

var _getterTypes = __webpack_require__(6);

var _vuex = __webpack_require__(8);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var nav = {
  computed: _extends({}, (0, _vuex.mapGetters)({
    manages: _getterTypes.MANAGES_GET
  })),
  created: function created() {
    this.$store.dispatch(_types.MANAGES_SET, function (cb) {
      return _request2.default.get((0, _request.createRequestURI)('manages'), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref) {
        var _ref$data = _ref.data,
            data = _ref$data === undefined ? [] : _ref$data;

        cb(data);
      }).catch(function () {
        window.alert('加载导航失败，请刷新页面！');
      });
    });
  }
};

exports.default = nav;

/***/ }),
/* 182 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "list-group app-nav"
  }, [_c('router-link', {
    staticClass: "list-group-item __button",
    attrs: {
      "to": "/setting",
      "active-class": "active"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-cog __icon"
  }), _vm._v("\n    系统设置\n  ")]), _vm._v(" "), _c('router-link', {
    staticClass: "list-group-item __button",
    attrs: {
      "to": "/users",
      "active-class": "active"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-user __icon"
  }), _vm._v("\n    用户中心\n  ")]), _vm._v(" "), _c('router-link', {
    staticClass: "list-group-item __button",
    attrs: {
      "to": "/sms",
      "active-class": "active"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-phone __icon"
  }), _vm._v("\n    短信设置\n  ")]), _vm._v(" "), _c('router-link', {
    staticClass: "list-group-item __button",
    attrs: {
      "to": "/wallet",
      "active-class": "active"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-credit-card __icon"
  }), _vm._v("\n    钱包\n  ")]), _vm._v(" "), _c('router-link', {
    staticClass: "list-group-item __button",
    attrs: {
      "to": "/ad",
      "active-class": "active"
    }
  }, [_c('span', {
    staticClass: "__icon"
  }, [_vm._v("AD")]), _vm._v("\n    广告管理\n  ")]), _vm._v(" "), _vm._l((_vm.manages), function(item, index) {
    return _c('router-link', {
      key: index,
      staticClass: "list-group-item __button",
      attrs: {
        "to": ("/package/" + index),
        "active-class": "active",
        "exact": ""
      }
    }, [(item['icon'].substr(4, 3) === '://' || item['icon'].substr(5, 3) === '://') ? _c('img', {
      staticClass: "__icon-img",
      attrs: {
        "src": item['icon']
      }
    }) : _c('span', {
      staticClass: "__icon"
    }, [_vm._v(_vm._s(item['icon']))]), _vm._v("\n    " + _vm._s(item['name']) + "\n  ")])
  })], 2)
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-604b8e2a", esExports)
  }
}

/***/ }),
/* 183 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "app-container clearfix"
  }, [_c('div', {
    staticClass: "left-nav pull-left"
  }, [(_vm.user.avatar) ? _c('img', {
    staticClass: "img-responsive img-circle center-block user-avatar",
    attrs: {
      "src": _vm.user.avatar
    }
  }) : _c('default-avatar', {
    staticClass: "img-responsive img-circle center-block user-avatar"
  }), _vm._v(" "), _c('div', {
    staticClass: "dropdown"
  }, [_c('button', {
    staticClass: "btn dropdown-toggle username-btn",
    attrs: {
      "type": "button",
      "id": "userDropdownMune",
      "data-toggle": "dropdown",
      "aria-haspopup": "true",
      "aria-expanded": "false"
    }
  }, [_vm._v("\n        " + _vm._s(_vm.user.name) + "\n        "), _c('span', {
    staticClass: "caret"
  })]), _vm._v(" "), _c('ul', {
    staticClass: "dropdown-menu dropdown-menu-right",
    attrs: {
      "aria-labelledby": "userDropdownMune"
    }
  }, [_vm._m(0), _vm._v(" "), _c('li', {
    staticClass: "divider",
    attrs: {
      "role": "separator"
    }
  }), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": _vm.logout
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-log-in"
  }), _vm._v("\n            退出登录\n          ")])])])]), _vm._v(" "), _c('system-nav')], 1), _vm._v(" "), _c('router-view', {
    staticClass: "pull-right context-container"
  })], 1)
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('li', [_c('a', {
    attrs: {
      "href": "/",
      "target": "_blank"
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-new-window"
  }), _vm._v("\n            打开前台\n          ")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-d7706172", esExports)
  }
}

/***/ }),
/* 184 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Package_vue__ = __webpack_require__(185);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Package_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Package_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_20cabae4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Package_vue__ = __webpack_require__(186);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-20cabae4\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Package.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(34)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-20cabae4")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(34)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Package_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_20cabae4_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Package_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/Package.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Package.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-20cabae4", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-20cabae4", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 185 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _getterTypes = __webpack_require__(6);

var _vuex = __webpack_require__(8);

exports.default = {
  computed: _extends({}, (0, _vuex.mapGetters)({
    manages: _getterTypes.MANAGES_GET
  }), {
    uri: function uri() {
      var key = this.$route.params.key;
      var _manages$key = this.manages[key];
      _manages$key = _manages$key === undefined ? {} : _manages$key;
      var uri = _manages$key.uri;


      return uri;
    }
  })
};

/***/ }),
/* 186 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('iframe', {
    class: _vm.$style.appIframe,
    attrs: {
      "src": _vm.uri
    }
  })
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-20cabae4", esExports)
  }
}

/***/ }),
/* 187 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Component_vue__ = __webpack_require__(188);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Component_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Component_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_8e6b2876_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Component_vue__ = __webpack_require__(189);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-8e6b2876\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Component.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(35)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-8e6b2876")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(35)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_Component_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_8e6b2876_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_Component_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/Component.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Component.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-8e6b2876", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-8e6b2876", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 188 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var menus = window.TS.menus || {};

var component = {
  data: function data() {
    return _extends({}, menus);
  },
  computed: {
    uri: function uri() {
      var component = this.$route.params.component;


      if (!this[component]) {
        this.$router.replace('/');
        return;
      }

      return this[component].admin;
    }
  }
};

exports.default = component;

/***/ }),
/* 189 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('iframe', {
    class: _vm.$style.appIframe,
    attrs: {
      "src": _vm.uri
    }
  })
}
var staticRenderFns = []
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-8e6b2876", esExports)
  }
}

/***/ }),
/* 190 */,
/* 191 */,
/* 192 */,
/* 193 */,
/* 194 */,
/* 195 */,
/* 196 */,
/* 197 */,
/* 198 */,
/* 199 */,
/* 200 */,
/* 201 */,
/* 202 */,
/* 203 */,
/* 204 */,
/* 205 */,
/* 206 */,
/* 207 */,
/* 208 */,
/* 209 */,
/* 210 */,
/* 211 */,
/* 212 */,
/* 213 */,
/* 214 */,
/* 215 */,
/* 216 */,
/* 217 */,
/* 218 */,
/* 219 */,
/* 220 */,
/* 221 */,
/* 222 */,
/* 223 */,
/* 224 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 225 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_AddTag_vue__ = __webpack_require__(226);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_AddTag_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_AddTag_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_031fec80_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_AddTag_vue__ = __webpack_require__(227);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-031fec80\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./AddTag.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(224)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-031fec80")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(224)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_AddTag_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_031fec80_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_AddTag_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/AddTag.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] AddTag.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-031fec80", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-031fec80", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 226 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var AddTag = {
  data: function data() {
    return {
      name: '',
      category: 0,
      categories: [],
      add: {
        loadding: false,
        error: false,
        error_message: ''
      }
    };
  },

  methods: {
    send: function send() {
      var _this = this;

      var _name = this.name,
          name = _name === undefined ? '' : _name,
          _category = this.category,
          category = _category === undefined ? 0 : _category;

      if (!name || !category) {
        this.add.error = true;
        this.add.error_message = '参数不完整';
        return false;
      }
      var btn = $("#myButton").button('loading');

      _request2.default.post((0, _request.createRequestURI)('site/tags'), {
        name: name, category: category
      }, {
        validateStatus: function validateStatus(status) {
          return status === 201;
        }
      }).then(function (response) {
        _this.sendComplate(btn);
      }).catch(function (_ref) {
        var _ref$response = _ref.response;
        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data,
            data = _ref$response$data === undefined ? {} : _ref$response$data;

        btn.button('reset');

        var error = '添加标签失败';
        if (data.name) {
          error = data.name[0];
        }
        if (data.category) {
          error = data.category[0];
        }
        _this.add.loadding = false;
        _this.add.error = true;
        _this.add.error_message = error;
      });
    },
    sendComplate: function sendComplate(btn) {
      var _this2 = this;

      btn.button('complete');
      setTimeout(function () {
        btn.button('reset');
        _this2.name = '', _this2.category = 0;
      }, 2000);
    },
    dismisAddAreaError: function dismisAddAreaError() {
      this.add.error = false;
    },
    setCategory: function setCategory(id) {
      this.category = id;
    },


    // 获取标签分类
    getCategories: function getCategories() {
      var _this3 = this;

      _request2.default.get((0, _request.createRequestURI)('site/tags/categories'), {
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref2) {
        var _ref2$data = _ref2.data,
            data = _ref2$data === undefined ? [] : _ref2$data;

        _this3.categories = data;
      }).catch(function () {});
    }
  },

  computed: {
    // 按钮是否处于激活状态
    canSend: function canSend() {
      return this.name != '' && this.category != 0;
    }
  },
  created: function created() {
    this.getCategories();
  }
}; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

exports.default = AddTag;

/***/ }),
/* 227 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid"
  }, [_vm._m(0), _vm._v(" "), _c('form', {
    staticStyle: {
      "margin-bottom": "16px"
    }
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    attrs: {
      "for": "exampleInputEmail1"
    }
  }, [_vm._v("标签名字")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.name),
      expression: "name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "exampleInputEmail1",
      "placeholder": "标签名称"
    },
    domProps: {
      "value": (_vm.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    attrs: {
      "for": "exampleInputPassword1"
    }
  }, [_vm._v("标签分类")]), _vm._v(" "), _c('div', {
    staticClass: "btn-toolbar",
    attrs: {
      "role": "group",
      "aria-label": "cate"
    }
  }, _vm._l((_vm.categories), function(cate) {
    return _c('button', {
      key: cate.id,
      staticClass: " btn btn-group btn-group-sm btn-default",
      class: {
        'btn-info': _vm.category === cate.id
      },
      attrs: {
        "type": "button",
        "aria-label": "cate",
        "role": "group"
      },
      on: {
        "click": function($event) {
          _vm.setCategory(cate.id)
        }
      }
    }, [_vm._v("\n          " + _vm._s(cate.name) + "\n        ")])
  }))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-default",
    attrs: {
      "type": "submit",
      "id": "myButton",
      "data-complete-text": "添加成功",
      "data-loading-text": "提交中...",
      "autocomplete": "off"
    },
    on: {
      "click": function($event) {
        _vm.send()
      }
    }
  }, [_vm._v("\n      添加\n    ")])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.add.error),
      expression: "add.error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisAddAreaError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v(" "), _c('strong', [_vm._v("Error:")]), _vm._v(" "), _c('p', [_vm._v(_vm._s(_vm.add.error_message))])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page-header"
  }, [_c('h4', [_vm._v("添加标签")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-031fec80", esExports)
  }
}

/***/ }),
/* 228 */,
/* 229 */,
/* 230 */,
/* 231 */,
/* 232 */,
/* 233 */,
/* 234 */,
/* 235 */,
/* 236 */,
/* 237 */,
/* 238 */,
/* 239 */,
/* 240 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 241 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UpdateTag_vue__ = __webpack_require__(242);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UpdateTag_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UpdateTag_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_7d16095a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_UpdateTag_vue__ = __webpack_require__(243);
var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/dist/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-7d16095a\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./UpdateTag.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(240)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(3).rerender("data-v-7d16095a")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(240)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var normalizeComponent = __webpack_require__(0)
/* script */

/* template */

/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __WEBPACK_IMPORTED_MODULE_0__babel_loader_node_modules_vue_loader_lib_selector_type_script_index_0_UpdateTag_vue___default.a,
  __WEBPACK_IMPORTED_MODULE_1__node_modules_vue_loader_lib_template_compiler_index_id_data_v_7d16095a_hasScoped_false_node_modules_vue_loader_lib_selector_type_template_index_0_UpdateTag_vue__["a" /* default */],
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/admin/component/setting/UpdateTag.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] UpdateTag.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7d16095a", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-7d16095a", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

/* harmony default export */ __webpack_exports__["default"] = (Component.exports);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(2)(module)))

/***/ }),
/* 242 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; //
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var _request = __webpack_require__(1);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var UpdateTag = {
  data: function data() {
    return {
      tag: {},
      tag_id: 0,
      name: '',
      category: 0,
      categories: [],
      add: {
        loadding: false,
        error: false,
        error_message: ''
      }
    };
  },

  methods: {
    send: function send() {
      var _this = this;

      var _name = this.name,
          name = _name === undefined ? '' : _name,
          _category = this.category,
          category = _category === undefined ? 0 : _category,
          _tag_id = this.tag_id,
          tag_id = _tag_id === undefined ? 0 : _tag_id;

      if (!name || !category || !tag_id) {
        this.add.error = true;
        this.add.error_message = '参数不完整';
        return false;
      }
      var btn = $("#myButton").button('loading');

      _request2.default.patch((0, _request.createRequestURI)('site/tags/' + tag_id), {
        name: name, category: category
      }, {
        validateStatus: function validateStatus(status) {
          return status === 201;
        }
      }).then(function (response) {
        _this.sendComplate(btn);
      }).catch(function (_ref) {
        var _ref$response = _ref.response;
        _ref$response = _ref$response === undefined ? {} : _ref$response;
        var _ref$response$data = _ref$response.data,
            data = _ref$response$data === undefined ? {} : _ref$response$data;

        btn.button('reset');

        var error = '修改标签失败';
        if (data.name) {
          error = data.name[0];
        }
        if (data.category) {
          error = data.category[0];
        }
        _this.add.loadding = false;
        _this.add.error = true;
        _this.add.error_message = error;
      });
    },
    sendComplate: function sendComplate(btn) {
      var _this2 = this;

      btn.button('complete');
      setTimeout(function () {
        btn.button('reset');
        _this2.name = '', _this2.category = 0;
      }, 2000);
    },
    dismisAddAreaError: function dismisAddAreaError() {
      this.add.error = false;
    },
    setCategory: function setCategory(id) {
      this.category = id;
    },


    // 获取标签分类
    getCategories: function getCategories() {
      var _this3 = this;

      _request2.default.get((0, _request.createRequestURI)('site/tags/categories'), {
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref2) {
        var _ref2$data = _ref2.data,
            data = _ref2$data === undefined ? [] : _ref2$data;

        _this3.categories = data;
      }).catch(function () {});
    },


    // 获取标签详情
    getTag: function getTag() {
      var _this4 = this;

      _request2.default.get((0, _request.createRequestURI)('site/tags/' + this.tag_id), {
        validateStatus: function validateStatus(status) {
          return status === 200;
        }
      }).then(function (_ref3) {
        var _ref3$data = _ref3.data,
            data = _ref3$data === undefined ? {} : _ref3$data;

        _this4.tag = _extends({}, data);
        _this4.name = data.name;
        _this4.category = data.tag_category_id;
      }).catch(function () {});
    }
  },

  computed: {
    // 按钮是否处于激活状态
    canSend: function canSend() {
      return this.name != '' && this.name != this.tag.name && this.category != 0 && this.tag.tag_category_id != this.category;
    }
  },
  created: function created() {
    var _this5 = this;

    var _$route$params$tag_id = this.$route.params.tag_id,
        tag_id = _$route$params$tag_id === undefined ? 0 : _$route$params$tag_id;


    if (!tag_id) {
      window.alert('参数错误');
      setTimeout(function () {
        _this5.$router.go(-1);
      }, 2000);
    }
    this.tag_id = tag_id;
    this.getTag();
    this.getCategories();
  }
};

exports.default = UpdateTag;

/***/ }),
/* 243 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid"
  }, [_vm._m(0), _vm._v(" "), _c('form', {
    staticStyle: {
      "margin-bottom": "16px"
    }
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    attrs: {
      "for": "exampleInputEmail1"
    }
  }, [_vm._v("标签名字")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.name),
      expression: "name"
    }],
    staticClass: "form-control",
    attrs: {
      "type": "text",
      "id": "exampleInputEmail1",
      "placeholder": "标签名称"
    },
    domProps: {
      "value": (_vm.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    attrs: {
      "for": "exampleInputPassword1"
    }
  }, [_vm._v("标签分类")]), _vm._v(" "), _c('div', {
    staticClass: "btn-toolbar",
    attrs: {
      "role": "group",
      "aria-label": "cate"
    }
  }, _vm._l((_vm.categories), function(cate) {
    return _c('button', {
      key: cate.id,
      staticClass: " btn btn-group btn-group-sm btn-default",
      class: {
        'btn-info': _vm.category === cate.id
      },
      attrs: {
        "type": "button",
        "aria-label": "cate",
        "role": "group"
      },
      on: {
        "click": function($event) {
          _vm.setCategory(cate.id)
        }
      }
    }, [_vm._v("\n          " + _vm._s(cate.name) + "\n        ")])
  }))]), _vm._v(" "), _c('button', {
    staticClass: "btn btn-default",
    attrs: {
      "type": "submit",
      "id": "myButton",
      "data-complete-text": "修改成功",
      "data-loading-text": "提交中...",
      "autocomplete": "off"
    },
    on: {
      "click": function($event) {
        _vm.send()
      }
    }
  }, [_vm._v("\n      修改\n    ")])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.add.error),
      expression: "add.error"
    }],
    staticClass: "alert alert-danger alert-dismissible",
    attrs: {
      "role": "alert"
    }
  }, [_c('button', {
    staticClass: "close",
    attrs: {
      "type": "button"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.dismisAddAreaError($event)
      }
    }
  }, [_c('span', {
    attrs: {
      "aria-hidden": "true"
    }
  }, [_vm._v("×")])]), _vm._v(" "), _c('strong', [_vm._v("Error:")]), _vm._v(" "), _c('p', [_vm._v(_vm._s(_vm.add.error_message))])])])
}
var staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "page-header"
  }, [_c('h4', [_vm._v("添加标签")])])
}]
render._withStripped = true
var esExports = { render: render, staticRenderFns: staticRenderFns }
/* harmony default export */ __webpack_exports__["a"] = (esExports);
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7d16095a", esExports)
  }
}

/***/ })
],[36]);
//# sourceMappingURL=admin.js.map