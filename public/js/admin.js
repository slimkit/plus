webpackJsonp([0],[
/* 0 */,
/* 1 */,
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.createAPI = exports.createRequestURI = undefined;

var _axios = __webpack_require__(33);

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

/***/ }),
/* 6 */,
/* 7 */
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
// export const SITE_TITLE = 'settings/site/title';
// export const SITE_KEYWORDS = 'settings/site/keywords';
// export const SITE_DESCRIPTION = 'settings/site/description';
// export const SITE_ICP = 'settings/site/icp';

// The conts is defined [setting/area]
var SETTINGS_AREA = exports.SETTINGS_AREA = 'settings/area';

// Users.
var USERS = exports.USERS = 'user/GET-LIST';

/***/ }),
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

var _vue = __webpack_require__(8);

var _vue2 = _interopRequireDefault(_vue);

var _vuex = __webpack_require__(10);

var _vuex2 = _interopRequireDefault(_vuex);

var _user = __webpack_require__(71);

var _user2 = _interopRequireDefault(_user);

var _site = __webpack_require__(70);

var _site2 = _interopRequireDefault(_site);

var _area = __webpack_require__(69);

var _area2 = _interopRequireDefault(_area);

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
  area: _area2.default
};

var store = new _vuex2.default.Store({
  modules: modules
});

exports.default = store;

/***/ }),
/* 13 */,
/* 14 */,
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */
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

var _getterTypes = __webpack_require__(7);

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var login = function login(access, password) {
  return _request2.default.post((0, _request.createRequestURI)('login'), { phone: access, password: password }, { validateStatus: function validateStatus(status) {
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
/* 19 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"checkboxAndRadioInput":"_3_PJzR8yknYsK4O4paifqR_0"};

/***/ }),
/* 20 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"lWxMHASIe22tHRzHw5Jrt_0","loadding":"_24gnTDmaU_22fnRx6b_rdh_0","loaddingIcon":"_1XxUsqzTSJmFcRgogAR05X_0"};

/***/ }),
/* 21 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_1rfKjF_Jbsrt-NPuVUF68J_0","loadding":"_12izD6_COs4Sv6Mf_3NeY4_0","loaddingIcon":"_2QJw2UnKca7-Q7P6_KVdU0_0"};

/***/ }),
/* 22 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_3dmnCvozpw3m8fFish2esW_0","loadding":"_2r5QYXEH13EfeIfzZcabFJ_0","loaddingIcon":"_1z3mStIIgVBALloTE-yh_I_0"};

/***/ }),
/* 23 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"roleItem":"_31HzegN0N_MKNx8ek-ewdS_0"};

/***/ }),
/* 24 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_1KZThGFAzqMZHWZXSnyyqW_0","containerAround":"_3mmv8-PuOxpsgKq-h6Hil__0"};

/***/ }),
/* 25 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"container":"_1z4RhTrWlanBvCuyqi6fBV_0","loadding":"_1mZzobTI0IkBqSC5lgNrlj_0","loaddingIcon":"_4_lrzbUDzh31qV7YzKgKI_0","breadcrumbNotActvie":"_179TtcPFaYQhDG3qbBUqaq_0"};

/***/ }),
/* 26 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"appIframe":"_3ktd3GaQ51QNxBbNqAhp9r_0"};

/***/ }),
/* 27 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"nav":"_3aSlbEE3z6zx2l_uo3HI0c_0"};

/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _vue = __webpack_require__(8);

var _vue2 = _interopRequireDefault(_vue);

var _vueRouter = __webpack_require__(120);

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _auth = __webpack_require__(18);

var _setting = __webpack_require__(66);

var _setting2 = _interopRequireDefault(_setting);

var _user = __webpack_require__(68);

var _user2 = _interopRequireDefault(_user);

var _sms = __webpack_require__(67);

var _sms2 = _interopRequireDefault(_sms);

var _Login = __webpack_require__(85);

var _Login2 = _interopRequireDefault(_Login);

var _Home = __webpack_require__(84);

var _Home2 = _interopRequireDefault(_Home);

var _Component = __webpack_require__(83);

var _Component2 = _interopRequireDefault(_Component);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// routes.
_vue2.default.use(_vueRouter2.default);

// components.


var router = new _vueRouter2.default({
  mode: 'hash',
  base: '/admin/',
  routes: [{
    path: '/',
    component: _Home2.default,
    beforeEnter: _auth.requireAuth,
    children: [
    // root.
    { path: '', redirect: '/setting' },
    // Setting router.
    _setting2.default, _user2.default, _sms2.default, { path: 'component/:component(.*)', component: _Component2.default }]
  }, { path: '/login', component: _Login2.default, beforeEnter: _auth.loggedAuth }]
});

exports.default = router;

/***/ }),
/* 29 */,
/* 30 */,
/* 31 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(76)
}
var Component = __webpack_require__(0)(
  /* script */
  null,
  /* template */
  __webpack_require__(107),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/App.vue"
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

module.exports = Component.exports


/***/ }),
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */,
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */,
/* 45 */,
/* 46 */,
/* 47 */,
/* 48 */,
/* 49 */,
/* 50 */,
/* 51 */
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
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

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


var _vuex = __webpack_require__(10);

var _request = __webpack_require__(2);

var _getterTypes = __webpack_require__(7);

var _defaultAvatar = __webpack_require__(100);

var _defaultAvatar2 = _interopRequireDefault(_defaultAvatar);

var _Nav = __webpack_require__(86);

var _Nav2 = _interopRequireDefault(_Nav);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var home = {
  data: function data() {
    return {
      logout: (0, _request.createRequestURI)('logout')
    };
  },
  computed: _extends({}, (0, _vuex.mapGetters)([_getterTypes.USER, _getterTypes.USER_DATA]), {
    avatar: function avatar() {
      var _ref = this[_getterTypes.USER_DATA] || {},
          avatar = _ref.avatar;

      if ((typeof avatar === 'undefined' ? 'undefined' : _typeof(avatar)) === 'object') {
        return (0, _request.createAPI)('storages/' + avatar.value);
      }

      return '';
    },
    user: function user() {
      return this[_getterTypes.USER];
    }
  }),
  methods: {
    openWebsite: function openWebsite(e) {
      e.stopPropagation();
      e.preventDefault();

      return false;
    }
  },
  components: {
    'system-nav': _Nav2.default,
    'default-avatar': _defaultAvatar2.default
  }
};

exports.default = home;

/***/ }),
/* 53 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _types = __webpack_require__(5);

var _auth = __webpack_require__(18);

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
        var phone = data.phone,
            password = data.password;

        _this.error = phone || password || '登录失败！';
      });
    }
  }
};

exports.default = login;

/***/ }),
/* 54 */
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
var nav = {
  data: function data() {
    return {
      menus: menus
    };
  }
};

exports.default = nav;

/***/ }),
/* 55 */
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

var _vuex = __webpack_require__(10);

var _lodash = __webpack_require__(6);

var _lodash2 = _interopRequireDefault(_lodash);

var _getterTypes = __webpack_require__(7);

var _types = __webpack_require__(5);

var _request = __webpack_require__(2);

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
/* 56 */
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

var _request = __webpack_require__(2);

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
/* 57 */
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

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(6);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var StorageManageComponent = {
  data: function data() {
    return {
      engines: {},
      selected: 'local',
      allOptionsValues: {},
      changeIn: false,
      error: null,
      loadingOption: false
    };
  },
  computed: {
    showOption: function showOption() {
      return !!this.options.length;
    },
    options: function options() {
      var selected = this.selected;
      var _engines$selected = this.engines[selected];
      _engines$selected = _engines$selected === undefined ? {} : _engines$selected;
      var _engines$selected$opt = _engines$selected.option,
          option = _engines$selected$opt === undefined ? [] : _engines$selected$opt;


      return this.eventOptions(option);
    },
    optionsValues: function optionsValues() {
      var selected = this.selected;
      var optionsValues = this.allOptionsValues[selected];


      if (optionsValues === undefined) {
        optionsValues = {};
        this.options.forEach(function (_ref) {
          var name = _ref.name,
              value = _ref.value;

          optionsValues[name] = value;
        });
        this.allOptionsValues = _extends({}, this.allOptionsValues, _defineProperty({}, selected, optionsValues));
      }

      return optionsValues;
    }
  },
  watch: {
    selected: function selected(engine) {
      var optionsValues = this.allOptionsValues[engine];

      if (optionsValues === undefined && this.showOption) {
        this.requestEngineOption(engine);
      }
    }
  },
  methods: {
    eventOptions: function eventOptions(options) {
      return options.map(function (_ref2) {
        var name = _ref2.name,
            type = _ref2.type,
            tip = _ref2.tip,
            _ref2$value = _ref2.value,
            value = _ref2$value === undefined ? '' : _ref2$value,
            _ref2$items = _ref2.items,
            items = _ref2$items === undefined ? {} : _ref2$items,
            option = _objectWithoutProperties(_ref2, ['name', 'type', 'tip', 'value', 'items']);

        if (type === 'checkbox' || type === 'select' && !!option.multiple) {
          value = [value];
        }

        return { name: name, type: type, tip: tip, items: items, value: value, option: option };
      });
    },
    updateEngineOption: function updateEngineOption() {
      var _this = this;

      var engine = this.selected;
      var options = this.optionsValues;
      this.changeIn = true;
      _request2.default.patch((0, _request.createRequestURI)('storages/engines/' + engine), { options: options }, { validateStatus: function validateStatus(status) {
          return status === 201;
        } }).then(function () {
        _this.changeIn = false;
      }).catch(function (_ref3) {
        var _ref3$response = _ref3.response;
        _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
        var _ref3$response$data = _ref3$response.data,
            data = _ref3$response$data === undefined ? ['更新失败'] : _ref3$response$data;

        _this.error = _lodash2.default.values(data).pop();
        _this.changeIn = false;
      });
    },
    requestEngineOption: function requestEngineOption(engine) {
      var _this2 = this;

      this.loadingOption = true;
      _request2.default.get((0, _request.createRequestURI)('storages/engines/' + engine), { validateStatus: function validateStatus(status) {
          return status === 200;
        } }).then(function (_ref4) {
        var data = _ref4.data;

        _this2.allOptionsValues = _extends({}, _this2.allOptionsValues, _defineProperty({}, engine, _extends({}, _this2.optionsValues, data)));
        _this2.loadingOption = false;
      }).catch(function () {
        _this2.loadingOption = true;
        _this2.error = '加载储存引擎配置失败，请刷新网页重现尝试。如果此时忽略本条警告强行提交，可能会造成数据错误。';
      });
    }
  },
  created: function created() {
    var _this3 = this;

    _request2.default.get((0, _request.createRequestURI)('storages/engines'), { validateStatus: function validateStatus(status) {
        return status === 200;
      } }).then(function (_ref5) {
      var data = _ref5.data;

      _this3.engines = data.engines;
      _this3.allOptionsValues = _defineProperty({}, _this3.selected, data.validateStatus);
      _this3.selected = data.selected;
    }).catch(function (_ref6) {
      var _ref6$response = _ref6.response;
      _ref6$response = _ref6$response === undefined ? {} : _ref6$response;
      var _ref6$response$data = _ref6$response.data;
      _ref6$response$data = _ref6$response$data === undefined ? {} : _ref6$response$data;
      var _ref6$response$data$m = _ref6$response$data.message,
          message = _ref6$response$data$m === undefined ? '加载失败，请刷新重试' : _ref6$response$data$m;

      _this3.error = message;
    });
  }
};

exports.default = StorageManageComponent;

/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(2);

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
/* 59 */
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

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(6);

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


        _this2.users = data.data || [];
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
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(6);

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
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(6);

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
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

var _lodash = __webpack_require__(6);

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
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

var _codes = __webpack_require__(72);

var _lodash = __webpack_require__(6);

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var UserAddComponent = {
  data: function data() {
    return {
      name: '',
      phone: '',
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
        var _data$errors = data.errors,
            errors = _data$errors === undefined ? ['添加失败'] : _data$errors,
            code = data.code;

        _this.errorMessage = (0, _codes.code2message)(code, _lodash2.default.values(errors).pop());
        _this.adding = false;
      });
    },
    dismisError: function dismisError() {
      this.errorMessage = '';
    }
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

exports.default = UserAddComponent;

/***/ }),
/* 64 */
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

var _request = __webpack_require__(2);

var _request2 = _interopRequireDefault(_request);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

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
        var _ref$response$data = _ref$response.data;
        _ref$response$data = _ref$response$data === undefined ? {} : _ref$response$data;
        var _ref$response$data$er = _ref$response$data.errors,
            errors = _ref$response$data$er === undefined ? [] : _ref$response$data$er;

        var _errors = _slicedToArray(errors, 1),
            _errors$ = _errors[0],
            errorMessage = _errors$ === undefined ? '更新失败' : _errors$;

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
    }).then(function (_ref2) {
      var _ref2$data = _ref2.data,
          user = _ref2$data.user,
          roles = _ref2$data.roles;

      _this2.user = user;
      _this2.roles = roles;

      var selecedRoles = [];
      user.roles.forEach(function (role) {
        return selecedRoles.push(role.id);
      });
      _this2.selecedRoles = selecedRoles;
    }).catch(function (_ref3) {
      var _ref3$response = _ref3.response;
      _ref3$response = _ref3$response === undefined ? {} : _ref3$response;
      var _ref3$response$data = _ref3$response.data;
      _ref3$response$data = _ref3$response$data === undefined ? {} : _ref3$response$data;
      var _ref3$response$data$e = _ref3$response$data.errors,
          errors = _ref3$response$data$e === undefined ? [] : _ref3$response$data$e;

      var _errors2 = _slicedToArray(errors, 1),
          _errors2$ = _errors2[0],
          errorMessage = _errors2$ === undefined ? '获取失败，请刷新重试！' : _errors2$;

      _this2.error = errorMessage;
    });
  }
};

exports.default = UserManageComponent;

/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.store = exports.router = exports.app = undefined;

var _vue = __webpack_require__(8);

var _vue2 = _interopRequireDefault(_vue);

var _vuexRouterSync = __webpack_require__(32);

var _App = __webpack_require__(31);

var _App2 = _interopRequireDefault(_App);

var _store = __webpack_require__(12);

var _store2 = _interopRequireDefault(_store);

var _router = __webpack_require__(28);

var _router2 = _interopRequireDefault(_router);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// jQuery and Bootstrap-SASS
// -------------------------
// Questions: Why use CommonJS require?
// Answer: Because es6 module export lead to jquery plug-in can not run.
// -------------------------
window.$ = window.jQuery = __webpack_require__(30);
__webpack_require__(29);

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
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Setting = __webpack_require__(87);

var _Setting2 = _interopRequireDefault(_Setting);

var _Base = __webpack_require__(90);

var _Base2 = _interopRequireDefault(_Base);

var _Area = __webpack_require__(89);

var _Area2 = _interopRequireDefault(_Area);

var _StoreageManage = __webpack_require__(91);

var _StoreageManage2 = _interopRequireDefault(_StoreageManage);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
// The file is defined "/setting" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//
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
    path: 'storeages',
    component: _StoreageManage2.default
  }]
};

exports.default = settingRouter;

/***/ }),
/* 67 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Main = __webpack_require__(93);

var _Main2 = _interopRequireDefault(_Main);

var _Home = __webpack_require__(92);

var _Home2 = _interopRequireDefault(_Home);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
// The file is defined "/sms" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//

var smsRouter = {
  path: 'sms',
  component: _Main2.default,
  children: [{ path: '', component: _Home2.default }]
};

exports.default = smsRouter;

/***/ }),
/* 68 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _User = __webpack_require__(88);

var _User2 = _interopRequireDefault(_User);

var _UserAdd = __webpack_require__(98);

var _UserAdd2 = _interopRequireDefault(_UserAdd);

var _UserManage = __webpack_require__(99);

var _UserManage2 = _interopRequireDefault(_UserManage);

var _Manage = __webpack_require__(94);

var _Manage2 = _interopRequireDefault(_Manage);

var _Roles = __webpack_require__(97);

var _Roles2 = _interopRequireDefault(_Roles);

var _RoleManage = __webpack_require__(96);

var _RoleManage2 = _interopRequireDefault(_RoleManage);

var _Permissions = __webpack_require__(95);

var _Permissions2 = _interopRequireDefault(_Permissions);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var routers = {
  path: 'users',
  component: _User2.default,
  children: [{ path: '', component: _Manage2.default }, { path: 'manage/:userId', component: _UserManage2.default }, { path: 'add', component: _UserAdd2.default }, { path: 'roles', component: _Roles2.default }, { path: 'roles/:role', component: _RoleManage2.default }, { path: 'permissions', component: _Permissions2.default }]
}; /**
    * The file is defined "/setting" route.
    *
    * @author Seven Du <shiweidu@outlook.com>
    */

exports.default = routers;

/***/ }),
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _mutations, _actions;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _types = __webpack_require__(5);

var _getterTypes = __webpack_require__(7);

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
/* 70 */
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
/* 71 */
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

var _getterTypes = __webpack_require__(7);

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
/* 72 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.code2message = code2message;
var codes = {
  1000: '手机号码格式不正确',
  1002: '用户名长度不正确，长度必须大于1个长度且小于12个长度',
  1003: '用户名格式不正确，不能特殊字符或者数字开头',
  1004: '用户名已经被使用',
  1010: '手机号码已经被使用'
};

function code2message(code, defaultMessage) {
  var _codes$parseInt = codes[parseInt(code)],
      message = _codes$parseInt === undefined ? defaultMessage : _codes$parseInt;


  return message;
};

exports.default = codes;

/***/ }),
/* 73 */,
/* 74 */,
/* 75 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 76 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 77 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 78 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 79 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 80 */,
/* 81 */,
/* 82 */,
/* 83 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-8e6b2876\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Component.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(26)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-8e6b2876")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(26)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(51),
  /* template */
  __webpack_require__(114),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/Component.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 84 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(79)
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(52),
  /* template */
  __webpack_require__(119),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/Home.vue"
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

module.exports = Component.exports


/***/ }),
/* 85 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(75)
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(53),
  /* template */
  __webpack_require__(101),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/Login.vue"
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

module.exports = Component.exports


/***/ }),
/* 86 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(77)
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(54),
  /* template */
  __webpack_require__(108),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/Nav.vue"
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

module.exports = Component.exports


/***/ }),
/* 87 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(78)
}
var Component = __webpack_require__(0)(
  /* script */
  null,
  /* template */
  __webpack_require__(117),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/Setting.vue"
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

module.exports = Component.exports


/***/ }),
/* 88 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-bd3f1f9a\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./User.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(27)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-bd3f1f9a")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(27)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  null,
  /* template */
  __webpack_require__(118),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/User.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 89 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-768160d4\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Area.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(25)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-768160d4")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(25)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(55),
  /* template */
  __webpack_require__(113),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/setting/Area.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 90 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-7334d518\",\"scoped\":false,\"hasInlineConfig\":false}!sass-loader?{\"sourceMap\":true}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Base.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(24)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-7334d518")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(24)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(56),
  /* template */
  __webpack_require__(112),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/setting/Base.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 91 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-2bb91bec\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./StoreageManage.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(19)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-2bb91bec")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(19)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(57),
  /* template */
  __webpack_require__(103),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/setting/StoreageManage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] StoreageManage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2bb91bec", Component.options)
  } else {
    if (module.hot.data.cssModules && Object.keys(module.hot.data.cssModules) !== Object.keys(cssModules)) {
      delete Component.options._Ctor
    }
    hotAPI.reload("data-v-2bb91bec", Component.options)
  }
  module.hot.dispose(function (data) {
    data.cssModules = cssModules
    disposed = true
  })
})()}

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 92 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(58),
  /* template */
  __webpack_require__(102),
  /* styles */
  null,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/sms/Home.vue"
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

module.exports = Component.exports


/***/ }),
/* 93 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var Component = __webpack_require__(0)(
  /* script */
  null,
  /* template */
  __webpack_require__(106),
  /* styles */
  null,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/sms/Main.vue"
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

module.exports = Component.exports


/***/ }),
/* 94 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-6a384d9e\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Manage.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(22)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-6a384d9e")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(22)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(59),
  /* template */
  __webpack_require__(110),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/user/Manage.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 95 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-38579c30\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Permissions.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(20)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-38579c30")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(20)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(60),
  /* template */
  __webpack_require__(104),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/user/Permissions.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 96 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(61),
  /* template */
  __webpack_require__(115),
  /* styles */
  null,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/user/RoleManage.vue"
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

module.exports = Component.exports


/***/ }),
/* 97 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-695690a1\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./Roles.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(21)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-695690a1")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(21)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(62),
  /* template */
  __webpack_require__(109),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/user/Roles.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 98 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(63),
  /* template */
  __webpack_require__(105),
  /* styles */
  null,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/user/UserAdd.vue"
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

module.exports = Component.exports


/***/ }),
/* 99 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var disposed = false
var cssModules = {}
module.hot && module.hot.accept(["!!../../../../../node_modules/extract-text-webpack-plugin/loader.js?{\"omit\":1,\"remove\":true}!vue-style-loader!css-loader?{\"minimize\":false,\"sourceMap\":true,\"localIdentName\":\"[hash:base64]_0\",\"modules\":true,\"importLoaders\":true}!../../../../../node_modules/vue-loader/lib/style-compiler/index?{\"vue\":true,\"id\":\"data-v-712ba55c\",\"scoped\":false,\"hasInlineConfig\":false}!../../../../../node_modules/vue-loader/lib/selector?type=styles&index=0!./UserManage.vue"], function () {
  var oldLocals = cssModules["$style"]
  if (!oldLocals) return
  var newLocals = __webpack_require__(23)
  if (JSON.stringify(newLocals) === JSON.stringify(oldLocals)) return
  cssModules["$style"] = newLocals
  __webpack_require__(4).rerender("data-v-712ba55c")
})
function injectStyle (ssrContext) {
  if (disposed) return
  cssModules["$style"] = __webpack_require__(23)
Object.defineProperty(this, "$style", { get: function () { return cssModules["$style"] }})
}
var Component = __webpack_require__(0)(
  /* script */
  __webpack_require__(64),
  /* template */
  __webpack_require__(111),
  /* styles */
  injectStyle,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/component/user/UserManage.vue"
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

module.exports = Component.exports

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(3)(module)))

/***/ }),
/* 100 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var Component = __webpack_require__(0)(
  /* script */
  null,
  /* template */
  __webpack_require__(116),
  /* styles */
  null,
  /* scopeId */
  null,
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "/usr/local/var/www/thinksns-plus/resources/assets/admin/icons/default-avatar.vue"
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

module.exports = Component.exports


/***/ }),
/* 101 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
      "type": "tel",
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-12970031", module.exports)
  }
}

/***/ }),
/* 102 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("手机号码")]), _vm._v(" "), _c('th', [_vm._v("验证码")]), _vm._v(" "), _c('th', [_vm._v("状态")]), _vm._v(" "), _c('th', [_vm._v("时间")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-230a4f3d", module.exports)
  }
}

/***/ }),
/* 103 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "component-container container-fluid form-horizontal"
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label",
    attrs: {
      "for": "storage-engine"
    }
  }, [_vm._v("选择储存")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-6"
  }, [_c('select', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.selected),
      expression: "selected"
    }],
    staticClass: "form-control",
    attrs: {
      "id": "storage-engine",
      "aria-describedby": "storages-engine-help-block"
    },
    on: {
      "change": function($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
          return o.selected
        }).map(function(o) {
          var val = "_value" in o ? o._value : o.value;
          return val
        });
        _vm.selected = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
      }
    }
  }, _vm._l((_vm.engines), function(engine, value) {
    return _c('option', {
      key: value,
      domProps: {
        "value": value
      }
    }, [_vm._v(_vm._s(engine.name))])
  }))]), _vm._v(" "), _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "storages-engine-help-block"
    }
  }, [_vm._v("\n      选择 ThinkSNS+ 中所使用的资源储存引擎。\n    ")])]), _vm._v(" "), _vm._l((_vm.options), function(ref) {
    var name = ref.name;
    var type = ref.type;
    var tip = ref.tip;
    var items = ref.items;
    var value = ref.value;
    var option = ref.option;

    return (_vm.showOption) ? _c('div', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (!_vm.loadingOption),
        expression: "!loadingOption"
      }],
      key: ("engine-option-" + _vm.selected),
      staticClass: "form-group"
    }, [_c('label', {
      staticClass: "col-sm-2 control-label",
      attrs: {
        "for": ("engine-option-" + _vm.selected + "-" + name)
      }
    }, [_vm._v(_vm._s(name))]), _vm._v(" "), _c('div', {
      staticClass: "col-sm-6",
      class: type
    }, [(type === 'text') ? _c('input', _vm._b({
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.optionsValues[name]),
        expression: "optionsValues[name]"
      }],
      staticClass: "form-control",
      attrs: {
        "type": "text",
        "name": name,
        "id": ("engine-option-" + _vm.selected + "-" + name),
        "ariaDescribedby": ("storages-option-" + _vm.selected + "-" + name + "-help-block")
      },
      domProps: {
        "value": value,
        "value": (_vm.optionsValues[name])
      },
      on: {
        "input": function($event) {
          if ($event.target.composing) { return; }
          var $$exp = _vm.optionsValues,
            $$idx = name;
          if (!Array.isArray($$exp)) {
            _vm.optionsValues[name] = $event.target.value
          } else {
            $$exp.splice($$idx, 1, $event.target.value)
          }
        }
      }
    }, 'input', option)) : (type === 'checkbox') ? _vm._l((items), function(display_name, value) {
      return _c('label', {
        key: (_vm.selected + "-" + name + "-" + display_name + "-{$value}"),
        class: _vm.$style.checkboxAndRadioInput
      }, [_c('input', _vm._b({
        directives: [{
          name: "model",
          rawName: "v-model",
          value: (_vm.optionsValues[name]),
          expression: "optionsValues[name]"
        }],
        attrs: {
          "type": "checkbox",
          "name": name,
          "id": ("engine-option-" + _vm.selected + "-" + name),
          "ariaDescribedby": ("storages-option-" + _vm.selected + "-" + name + "-help-block")
        },
        domProps: {
          "value": value,
          "checked": Array.isArray(_vm.optionsValues[name]) ? _vm._i(_vm.optionsValues[name], value) > -1 : (_vm.optionsValues[name])
        },
        on: {
          "__c": function($event) {
            var $$a = _vm.optionsValues[name],
              $$el = $event.target,
              $$c = $$el.checked ? (true) : (false);
            if (Array.isArray($$a)) {
              var $$v = value,
                $$i = _vm._i($$a, $$v);
              if ($$c) {
                $$i < 0 && (_vm.optionsValues[name] = $$a.concat($$v))
              } else {
                $$i > -1 && (_vm.optionsValues[name] = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
              }
            } else {
              var $$exp = _vm.optionsValues,
                $$idx = name;
              if (!Array.isArray($$exp)) {
                _vm.optionsValues[name] = $$c
              } else {
                $$exp.splice($$idx, 1, $$c)
              }
            }
          }
        }
      }, 'input', option)), _vm._v(" " + _vm._s(display_name) + "\n        ")])
    }) : (type === 'radio') ? _vm._l((items), function(display_name, value) {
      return _c('label', {
        key: (_vm.selected + "-" + name + "-" + display_name + "-{$value}"),
        class: _vm.$style.checkboxAndRadioInput
      }, [_c('input', _vm._b({
        directives: [{
          name: "model",
          rawName: "v-model",
          value: (_vm.optionsValues[name]),
          expression: "optionsValues[name]"
        }],
        attrs: {
          "type": "radio",
          "name": name,
          "id": ("engine-option-" + _vm.selected + "-" + name),
          "ariaDescribedby": ("storages-option-" + _vm.selected + "-" + name + "-help-block")
        },
        domProps: {
          "value": value,
          "checked": _vm._q(_vm.optionsValues[name], value)
        },
        on: {
          "__c": function($event) {
            var $$exp = _vm.optionsValues,
              $$idx = name;
            if (!Array.isArray($$exp)) {
              _vm.optionsValues[name] = value
            } else {
              $$exp.splice($$idx, 1, value)
            }
          }
        }
      }, 'input', option)), _vm._v(" " + _vm._s(display_name) + "\n        ")])
    }) : (type === 'select') ? _c('select', _vm._b({
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.optionsValues[name]),
        expression: "optionsValues[name]"
      }],
      staticClass: "form-control",
      attrs: {
        "id": ("engine-option-" + _vm.selected + "-" + name),
        "ariaDescribedby": ("storages-option-" + _vm.selected + "-" + name + "-help-block"),
        "name": name
      },
      domProps: {
        "value": value
      },
      on: {
        "change": function($event) {
          var $$selectedVal = Array.prototype.filter.call($event.target.options, function(o) {
            return o.selected
          }).map(function(o) {
            var val = "_value" in o ? o._value : o.value;
            return val
          });
          var $$exp = _vm.optionsValues,
            $$idx = name;
          if (!Array.isArray($$exp)) {
            _vm.optionsValues[name] = $event.target.multiple ? $$selectedVal : $$selectedVal[0]
          } else {
            $$exp.splice($$idx, 1, $event.target.multiple ? $$selectedVal : $$selectedVal[0])
          }
        }
      }
    }, 'select', option), _vm._l((items), function(display_name, value) {
      return _c('option', {
        key: (_vm.selected + "-" + name + "-" + display_name + "-{$value}"),
        domProps: {
          "value": value
        }
      }, [_vm._v(_vm._s(display_name))])
    })) : _vm._e()], 2), _vm._v(" "), _c('span', {
      staticClass: "col-sm-4 help-block",
      attrs: {
        "id": ("storages-option-" + _vm.selected + "-" + name + "-help-block")
      }
    }, [_vm._v(_vm._s(tip))])]) : _vm._e()
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loadingOption),
      expression: "loadingOption"
    }],
    staticClass: "component-loadding"
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-refresh component-loadding-icon"
  })]), _vm._v(" "), _c('div', {
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
      "click": _vm.updateEngineOption
    }
  }, [_vm._v("提交")])])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\n    " + _vm._s(_vm.error) + "\n  ")])], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-2bb91bec", module.exports)
  }
}

/***/ }),
/* 104 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "alert alert-success",
    attrs: {
      "role": "alert"
    }
  }, [_vm._v("\n    权限节点，用于各个位置标示用户权限的配置～配置需要配合程序。尽量不要删除权限节点～以为节点name是在程序中赢编码的～\n    这里提供管理，只是方便技术人员对节点进行管理。\n    "), _c('p', [_vm._v("编辑节点内容，修改完成后可直接回车或者留任不管～失去焦点后会自动保存。")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("节点名称")]), _vm._v(" "), _c('th', [_vm._v("显示名称")]), _vm._v(" "), _c('th', [_vm._v("描述")]), _vm._v(" "), _c('th', [_vm._v("更新时间")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-38579c30", module.exports)
  }
}

/***/ }),
/* 105 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
  }, [_vm._v("\n        请输入用户名，只能以非特殊字符和数字抬头！\n      ")])]), _vm._v(" "), _c('div', {
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
      "id": "phonepassword",
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
  }, [_vm._v("\n        手机号码\n      ")])]), _vm._v(" "), _c('div', {
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3c69873a", module.exports)
  }
}

/***/ }),
/* 106 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
      "to": "/sms/driver",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("短信驱动")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/sms/alidayu",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("阿里大鱼")])])], 1), _vm._v(" "), _c('router-view')], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3f846317", module.exports)
  }
}

/***/ }),
/* 107 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    attrs: {
      "id": "app"
    }
  }, [_c('router-view')], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-577de07b", module.exports)
  }
}

/***/ }),
/* 108 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
  }), _vm._v("\n    短信设置\n  ")]), _vm._v(" "), _vm._l((_vm.menus), function(ref, component) {
    var name = ref.name;
    var icon = ref.icon;

    return _c('router-link', {
      key: component,
      staticClass: "list-group-item __button",
      attrs: {
        "to": ("/component/" + component),
        "active-class": "active",
        "exact": ""
      }
    }, [_c('img', {
      staticClass: "__icon-img",
      attrs: {
        "src": icon
      }
    }), _vm._v("\n    " + _vm._s(name) + "\n  ")])
  })], 2)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-604b8e2a", module.exports)
  }
}

/***/ }),
/* 109 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("角色名称")]), _vm._v(" "), _c('th', [_vm._v("显示名称")]), _vm._v(" "), _c('th', [_vm._v("描述")]), _vm._v(" "), _c('th', [_vm._v("更新时间")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-695690a1", module.exports)
  }
}

/***/ }),
/* 110 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
      },
      "blur": function($event) {
        _vm.$forceUpdate()
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("用户ID")]), _vm._v(" "), _c('th', [_vm._v("用户名")]), _vm._v(" "), _c('th', [_vm._v("邮箱")]), _vm._v(" "), _c('th', [_vm._v("手机号码")]), _vm._v(" "), _c('th', [_vm._v("注册时间")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-6a384d9e", module.exports)
  }
}

/***/ }),
/* 111 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
  }, [_vm._v("\n        请输入用户名，只能以非特殊字符和数字抬头！\n      ")])]), _vm._v(" "), _c('div', {
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
      "id": "phonepassword",
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
  }, [_vm._v("\n        手机号码\n      ")])]), _vm._v(" "), _c('div', {
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
      "id": "phonepassword",
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
  }, [_vm._v("\n        电子邮箱\n      ")])]), _vm._v(" "), _c('div', {
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
            if ($$c) {
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-712ba55c", module.exports)
  }
}

/***/ }),
/* 112 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('span', {
    staticClass: "col-sm-4 help-block",
    attrs: {
      "id": "site-keywords-help-block"
    }
  }, [_vm._v("\n      网站关键词，是通过搜索引擎检索网站的重要信息，多个关键词使用英文半角符号“"), _c('strong', [_vm._v(",")]), _vm._v("”分割。\n    ")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7334d518", module.exports)
  }
}

/***/ }),
/* 113 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "container-fluid",
    class: _vm.$style.container
  }, [_c('div', {
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('p', [_vm._v("2. 拓展信息：拓展信息赋予单条信息而外的数据，例如国家设置，"), _c('strong', [_vm._v("中国")]), _vm._v("的拓展信息设置的"), _c('strong', [_vm._v("3")]), _vm._v(",用于在app开发中UI层展示几级选择菜单，所以，只有在业务需求下，设置拓展信息才是有用的。其他情况下留空即可。")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("名称")]), _vm._v(" "), _c('th', [_vm._v("拓展(无需设置)")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-768160d4", module.exports)
  }
}

/***/ }),
/* 114 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('iframe', {
    class: _vm.$style.appIframe,
    attrs: {
      "src": _vm.uri
    }
  })
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-8e6b2876", module.exports)
  }
}

/***/ }),
/* 115 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
          if ($$c) {
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
            if ($$c) {
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-9076bf72", module.exports)
  }
}

/***/ }),
/* 116 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-92071920", module.exports)
  }
}

/***/ }),
/* 117 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
      "to": "/setting/storeages",
      "tag": "li",
      "active-class": "active"
    }
  }, [_c('a', {
    attrs: {
      "href": "#"
    }
  }, [_vm._v("储存管理")])])], 1), _vm._v(" "), _c('router-view')], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-9bb730d0", module.exports)
  }
}

/***/ }),
/* 118 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-bd3f1f9a", module.exports)
  }
}

/***/ }),
/* 119 */
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "app-container clearfix"
  }, [_c('div', {
    staticClass: "left-nav pull-left"
  }, [(_vm.avatar) ? _c('img', {
    staticClass: "img-responsive img-circle center-block user-avatar",
    attrs: {
      "src": _vm.avatar
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
  }, [_c('li', {
    staticClass: "disabled"
  }, [_c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": _vm.openWebsite
    }
  }, [_c('span', {
    staticClass: "glyphicon glyphicon-new-window"
  }), _vm._v("\n            打开前台\n          ")])]), _vm._v(" "), _c('li', {
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-d7706172", module.exports)
  }
}

/***/ })
],[65]);
//# sourceMappingURL=admin.js.map