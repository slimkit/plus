import AboutUs from "@/page/AboutUs.vue";

import SiteUpgrade from "@/page/SiteUpgrade.vue";

/* TODO */
import Settings from "@/page/Settings.vue";

import Profile from "@/page/Profile.vue";
import UserInfo from "@/page/UserInfo.vue";
import UserHome from "@/page/UserHome.vue";
import UserFans from "@/page/UserFans.vue";
/* TODO END */

import Discover from "@/page/Discover.vue";

import Find from "@/page/find/Find.vue";
import FindPop from "@/page/find/FindPop.vue";
import FindRec from "@/page/find/FindRec.vue";
import FindNew from "@/page/find/FindNew.vue";
import FindNer from "@/page/find/FindNer.vue";

import SearchUser from "@/page/find/SearchUser.vue";

import WechatSignin from "@/page/wechat/WechatSignin";
import WechatSignup from "@/page/wechat/WechatSignup.vue";
import WechatBindUser from "@/page/wechat/WechatBindUser.vue";

import Location from "@/page/Location.vue";

import $lstore from "@/plugins/lstore";

export default [
  {
    path: "/discover",
    component: Discover,
    meta: {
      title: "发现"
    }
  },
  {
    name: "find",
    path: "/find",
    redirect: "/find/pop",
    component: Find,
    meta: {
      title: "找人",
      requiresAuth: false
    },
    children: [
      {
        path: "pop",
        component: FindPop,
        meta: {
          keepAlive: true
        }
      },
      {
        path: "new",
        component: FindNew,
        meta: {
          keepAlive: true
        }
      },
      {
        path: "rec",
        component: FindRec,
        meta: {
          keepAlive: true
        }
      },
      {
        path: "ner",
        component: FindNer,
        meta: {
          keepAlive: true
        },
        beforeEnter(to, from, next) {
          to,
            from,
            $lstore.hasData("H5_CURRENT_POSITION") ? next() : next("/location");
        }
      }
    ]
  },
  {
    path: "/search/user",
    component: SearchUser,
    meta: {
      title: "找人",
      keepAlive: true
    }
  },
  {
    path: "/location",
    component: Location
  },
  {
    path: "/profile",
    component: Profile,
    meta: {
      title: "我",
      requiresAuth: true
    }
  },
  {
    name: "userDetail",
    path: "/users/:userId(\\d+)",
    component: UserHome,
    meta: {
      title: "个人主页",
      keepAlive: true,
      requiresAuth: true
    }
  },
  {
    name: "userfans",
    component: UserFans,
    path: "/users/:userID(\\d+)/:type(followers|followings)",
    meta: {
      title: "粉丝",
      keepAlive: true,
      requiresAuth: true
    }
  },
  {
    path: "/info",
    component: UserInfo,
    meta: {
      title: "个人资料",
      requiresAuth: true
    }
  },
  {
    path: "/setting",
    component: Settings,
    meta: {
      title: "设置",
      requiresAuth: true
    }
  },
  {
    path: "/about",
    component: AboutUs,
    meta: {
      title: "关于我们"
    }
  },
  {
    path: "/wechat",
    component: WechatSignin,
    meta: {
      title: "登录中...",
      forGuest: true
    },
    beforeEnter(to, from, next) {
      navigator.userAgent.toLowerCase().indexOf("micromessenger") > -1
        ? to.query.code
          ? next()
          : next("/signin")
        : next("/signin");
    }
  },
  {
    path: "/wechat/signup",
    component: WechatSignup,
    meta: {
      title: "完善资料",
      forGuest: true
    },
    beforeEnter(to, from, next) {
      const accessToken = window.$lstore.getData("H5_WECHAT_MP_ASTOKEN");
      const nickname = window.$lstore.getData("H5_WECHAT_NICKNAME");
      accessToken && nickname ? next() : next("/wechat");
    }
  },
  {
    path: "/wechat/bind",
    component: WechatBindUser,
    meta: {
      title: "绑定已有账号",
      forGuest: true
    },
    beforeEnter(to, from, next) {
      const accessToken = window.$lstore.getData("H5_WECHAT_MP_ASTOKEN");
      accessToken ? next() : next("/wechat");
    }
  },
  {
    path: "/upgrade",
    component: SiteUpgrade,
    meta: {
      title: "功能开发中..."
    }
  }
];
