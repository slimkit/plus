import Forgot from "@/page/sign/Forgot.vue";
import ChangePassword from "@/page/sign/ChangePassword.vue";
import Signup from "@/page/sign/Signup.vue";
import Signin from "@/page/sign/Signin.vue";
import SigninDynamic from "@/page/sign/SigninDynamic.vue";
import RegisterProtocol from "@/page/sign/RegisterProtocol.vue";

export default [
  {
    path: "/signin",
    component: Signin,
    meta: {
      title: "登录",
      forGuest: true
    }
  },
  {
    path: "/signin/dynamic",
    component: SigninDynamic,
    meta: {
      title: "一键登录",
      forGuest: true
    }
  },
  {
    path: "/signup",
    component: Signup,
    meta: {
      title: "注册",
      forGuest: true
    }
  },
  {
    path: "/signup/protocol",
    component: RegisterProtocol,
    meta: {
      title: "用户注册协议"
    }
  },
  {
    path: "/forgot",
    component: Forgot,
    meta: {
      title: "忘记密码"
    }
  },
  {
    path: "/changePassword",
    component: ChangePassword,
    meta: {
      title: "修改密码",
      requiresAuth: true
    }
  }
];
