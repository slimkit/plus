<template>
  <div class="p-wechat-signin">
    <header class="m-box m-aln-center m-head-top m-pos-f m-main m-bb1">
      <router-link
        tag="div"
        to="/"
        class="m-box m-aln-center m-flex-grow1 m-flex-base0">
        <svg class="m-style-svg m-svg-def">
          <use
            xmlns:xlink="http://www.w3.org/1999/xlink"
            xlink:href="#icon-back"/>
        </svg>
      </router-link>
      <div class="m-box m-aln-center m-justify-center m-flex-grow1 m-flex-base0 m-head-top-title">
        <span>绑定账号</span>
      </div>
      <div class="m-box m-aln-center m-justify-end m-flex-grow1 m-flex-base0"/>
    </header>
    <!-- loading -->
    <div
      v-if="loading"
      class="m-spinner pos-f">
      <div/>
      <div/>
    </div>

    <div v-else>
      <transition name="toast">
        <div class="m-pop-box"/>
      </transition>
      <transition name="pop">
        <div class="m-lim-width m-pos-f m-wechat-box">
          <router-link
            tag="button"
            to="/wechat/signup">
            <a>注册新用户</a>
          </router-link>
          <router-link
            tag="button"
            to="/wechat/bind">
            <a>绑定已有用户</a>
          </router-link>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  name: "WechatSignin",
  data() {
    return {
      loading: true,
      accessToken: "",
      WechatUname: ""
    };
  },

  watch: {
    // 根据获取到access_token检查用户是否已经被楚泽
    showBind(val) {
      this.showBind = val;
      if (val) {
        this.showRegister = !val;
      }
    },
    showRegister(val) {
      this.showRegister = val;
      if (val) {
        this.showBind = !val;
      }
    }
  },
  mounted() {
    const { code } = this.$route.query;
    this.resolveUser(code);
  },
  methods: {
    goDefault() {
      this.showRegister = false;
      this.showBind = false;
    },
    action(action) {
      this[action] = true;
    },
    async resolveUser(code) {
      let openId = this.$lstore.getData("H5_WECHAT_MP_OPENID");
      let accessToken = this.$lstore.getData("H5_WECHAT_MP_ASTOKEN");

      if (!accessToken || !openId) {
        const { data: { access_token, openid } = {} } = await this.$http.get(
          `socialite/getAccess/${code}`,
          {
            validateStatus: status => status === 200
          }
        );

        openId = openid;
        accessToken = access_token;

        this.$lstore.setData("H5_WECHAT_MP_OPENID", openid);
        this.$lstore.setData("H5_WECHAT_MP_ASTOKEN", accessToken);
      }
      this.accessToken = accessToken;
      this.$http
        .post(
          "socialite/wechat",
          {
            access_token: accessToken
          },
          {
            validateStatus: s => s === 201
          }
        )
        .then(({ data: { token = "", user = {} } = {} }) => {
          // 保存用户信息 并跳转
          this.$router.push(this.$route.query.redirect || "/feeds?type=hot");
          this.$nextTick(() => {
            this.$lstore.removeData("H5_WECHAT_MP_OPENID");
            this.$lstore.removeData("H5_WECHAT_MP_ASTOKEN");
            this.$store.commit("SAVE_USER", user);
            this.$store.dispatch("GET_UNREAD_COUNT");
            this.$store.dispatch("GET_NEW_UNREAD_COUNT");
            this.$lstore.setData("H5_ACCESS_TOKEN", `Bearer ${token}`);
            this.$store.commit("SAVE_CURRENTUSER", user);
          });
        })
        .catch(() => {
          this.loading = false;
          this.getWechatUserInfo(accessToken, openId);
        });
    },

    getWechatUserInfo(access_token, openid) {
      this.$http
        .post(
          "socialite/getWechatUser",
          {
            openid,
            access_token
          },
          {
            validateStatus: s => s === 200
          }
        )
        .then(({ data: { nickname = "" } = {} }) => {
          this.WechatUname = nickname;
          this.$lstore.setData("H5_WECHAT_NICKNAME", nickname);
        });
    }
  }
};
</script>

<style lang="less">
.m-wechat-box {
  overflow: hidden;
  width: 70%;
  height: 95 * 2px;
  border-radius: 12px;
  margin: auto;
  button {
    background-color: #fff;
    width: 100%;
    height: 95px;
    + button {
      border-top: 1px solid @border-color;
    }
  }
}
</style>
