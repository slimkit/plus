<template>
  <div class="p-setting">

    <common-header>设置</common-header>

    <main>
      <ul class="m-box-model m-entry-group padding">
        <router-link
          to="/changePassword"
          tag="li"
          class="m-entry">
          <span>修改密码</span>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right"/>
          </svg>
        </router-link>
        <li class="m-entry" @click="aboutUs">
          <span class="m-box m-text-box m-flex-grow1">关于我们</span>
          <span class="m-box m-text-box m-flex-grow1 m-justify-end m-entry-extra">v{{ version }}</span>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right"/>
          </svg>
        </li>
        <li class="m-entry" @click="signOut">
          <a>退出登录</a>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right"/>
          </svg>
        </li>
      </ul>
    </main>
  </div>
</template>

<script>
import { version } from "@/main";

export default {
  name: "Settings",
  data() {
    return {
      version
    };
  },
  methods: {
    signOut() {
      const actions = [
        {
          text: "退出",
          style: { color: "#f4504d" },
          method: () => {
            this.$store.dispatch("SIGN_OUT");
            this.$nextTick(() => {
              this.$router.push("/signin");
            });
          }
        }
      ];
      this.$bus.$emit("actionSheet", actions, "取消", "确认退出?");
    },
    aboutUs() {
      const { aboutUs = {} } = this.$store.state.CONFIG.site;
      if (aboutUs.url) return (location.href = aboutUs.url);
      this.$router.push("/about");
    }
  }
};
</script>
