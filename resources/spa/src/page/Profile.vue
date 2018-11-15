<template>
  <div class="p-profile">

    <common-header>
      我
      <template slot="left"><span/></template>
    </common-header>

    <main class="m-box-model">
      <div class="m-box-model m-main m-pr-info">
        <router-link
          tag="section"
          class="m-box m-aln-center"
          to="/info">
          <avatar :user="user" size="big" />
          <div class="m-text-box m-flex-grow1 m-flex-shrink1 m-flex-base0 m-pr-user-info">
            <h4 class="m-pr-username">{{ user.name }}</h4>
            <p class="m-pr-bio m-text-cut-2">{{ user.bio || "这家伙很懒,什么也没有留下" }}</p>
          </div>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right"/>
          </svg>
        </router-link>
        <div class="m-box m-aln-center m-justify-aro m-bt1 m-pr-extra-box">
          <router-link
            :to="`/users/${user.id}/followers`"
            tag="div"
            class="m-box-model m-aln-center m-justify-center m-flex-grow1 m-pr-extra">
            <v-badge :count="new_followers">
              <a>{{ ~~(extra.followers_count) | formatNum }}</a>
            </v-badge>
            <p>粉丝</p>
          </router-link>
          <router-link
            :to="`/users/${user.id}/followings`"
            tag="div"
            class="m-box-model m-aln-center m-justify-center m-flex-grow1 m-pr-extra">
            <v-badge count="0">
              <a>{{ ~~(extra.followings_count) | formatNum }}</a>
            </v-badge>
            <p>关注</p>
          </router-link>
        </div>
      </div>
      <div class="m-box-model m-pr-entrys">
        <ul class="m-box-model m-entry-group">
          <router-link
            :to="`/users/${user.id}`"
            tag="li"
            class="m-entry">
            <svg class="m-style-svg m-svg-def m-entry-prepend">
              <use xlink:href="#icon-profile-home"/>
            </svg>
            <span class="m-text-box m-flex-grow1">个人主页</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
          <router-link
            to="/profile/news/released"
            tag="li"
            class="m-entry">
            <svg class="m-style-svg m-svg-def m-entry-prepend">
              <use xlink:href="#icon-profile-plane"/>
            </svg>
            <span class="m-text-box m-flex-grow1">我的投稿</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
          <!--
          <router-link to="/upgrade" tag="li" class="m-entry">
            <svg class='m-style-svg m-svg-def m-entry-prepend'>
              <use xlink:href="#icon-profile-question"></use>
            </svg>
            <span class="m-text-box m-flex-grow1">我的问答</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
          -->
          <!--
          <router-link to="/own/groups" tag="li" class="m-entry">
            <svg class='m-style-svg m-svg-def m-entry-prepend'>
              <use xlink:href="#icon-profile-group"></use>
            </svg>
            <span class="m-text-box m-flex-grow1">我的圈子</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
          -->
        </ul>
        <ul class="m-box-model m-entry-group">
          <router-link
            to="/wallet"
            class="m-entry"
            tag="li">
            <svg class="m-style-svg m-svg-def m-entry-prepend">
              <use xlink:href="#icon-profile-wallet"/>
            </svg>
            <span class="m-text-box m-flex-grow1">钱包</span>
            <span class="m-entry-extra">{{ new_balance }}</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
          <router-link
            tag="li"
            to="/currency"
            class="m-entry">
            <svg class="m-style-svg m-svg-def m-entry-prepend">
              <use xlink:href="#icon-profile-integral"/>
            </svg>
            <span class="m-text-box m-flex-grow1">{{ currencyUnit }}</span>
            <span class="m-entry-extra">{{ sum }}</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
          <router-link
            to="/profile/collection/feeds"
            tag="li"
            class="m-entry">
            <svg class="m-style-svg m-svg-def m-entry-prepend">
              <use xlink:href="#icon-profile-collect"/>
            </svg>
            <span class="m-text-box m-flex-grow1">收藏</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
        </ul>
        <ul class="m-box-model m-entry-group">
          <li class="m-entry" @click="selectCertType">
            <svg class="m-style-svg m-svg-def m-entry-prepend">
              <use xlink:href="#icon-profile-approve"/>
            </svg>
            <span class="m-text-box m-flex-grow1">认证</span>
            <span class="m-entry-extra">{{ verifiedText }}</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </li>
          <router-link
            to="/setting"
            tag="li"
            class="m-entry">
            <svg class="m-style-svg m-svg-def m-entry-prepend">
              <use xlink:href="#icon-profile-setting"/>
            </svg>
            <span class="m-text-box m-flex-grow1">设置</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </router-link>
        </ul>
      </div>
    </main>
    <foot-guide/>
  </div>
</template>

<script>
import _ from "lodash";
import { mapState } from "vuex";
import { resetUserCount } from "@/api/message.js";

export default {
  name: "Profile",
  data() {
    return {
      verifiedText: ""
    };
  },
  computed: {
    ...mapState({
      new_followers: state => state.MESSAGE.NEW_UNREAD_COUNT.following || 0,
      new_mutual: state => state.MESSAGE.NEW_UNREAD_COUNT.mutual || 0,
      user: state => state.CURRENTUSER,
      verified: state => state.USER_VERIFY
    }),
    extra() {
      return this.user.extra || {};
    },
    new_wallet() {
      return this.user.new_wallet || { balance: 0 };
    },
    new_balance() {
      return (this.new_wallet.balance / 100).toFixed(2);
    },
    currency() {
      return this.user.currency || { sum: 0 };
    },
    sum() {
      return this.currency.sum;
    }
  },
  watch: {
    verified(to) {
      if (to && to.status) to.status = Number(to.status);
      if (to && to.status === 0) {
        this.verifiedText = "待审核";
      } else if (to && to.status === 1) {
        this.verifiedText = "通过审核";
      } else if (to && to.status === 2) {
        this.verifiedText = "被驳回";
      } else {
        this.verifiedText = "未认证";
      }
    }
  },
  mounted() {
    this.$store.dispatch("fetchUserInfo");
    this.$store.dispatch("FETCH_USER_VERIFY");
    this.$store.dispatch("GET_NEW_UNREAD_COUNT");
  },
  beforeRouteLeave(to, from, next) {
    const {
      params: { type }
    } = to;
    const resetType =
      type === "followers" ? "following" : type === "mutual" ? "mutual" : "";
    resetType && resetUserCount(resetType);
    next();
  },
  methods: {
    selectCertType() {
      if (_.isEmpty(this.verified)) {
        const actions = [
          { text: "个人认证", method: () => this.certificate("user") },
          { text: "企业认证", method: () => this.certificate("org") }
        ];
        this.$bus.$emit("actionSheet", actions, "取消");
      } else if (this.verified.status === 2) {
        // 被驳回则补充填写表单
        const type = this.verified.certification_name || "user";
        this.certificate(type);
      } else {
        this.$router.push({ path: "/profile/certification" });
      }
    },
    /**
     * 认证
     * @param {string} type 认证类型 (user|org)
     */
    certificate(type) {
      this.$router.push({ path: "/profile/certificate", query: { type } });
    }
  }
};
</script>

<style lang="less" scoped>
.m-pr-info {
  padding: 30px;
}
.m-pr-extra {
  font-size: 28px;
  p {
    margin-top: 15px;
  }
  & + & {
    border-left: 1px solid @border-color; /*no*/
  }
  &-box {
    margin-top: 30px;
    padding-top: 30px;
  }
}
.m-pr-user-info {
  margin-left: 30px;
  margin-right: 30px;
  line-height: 40px;
  .m-pr-username {
    font-size: 32px;
    color: @text-color1;
  }
  .m-pr-bio {
    overflow: hidden;
    max-height: 40 * 2px;
    font-size: 28px;
    color: @text-color3;
    text-overflow: ellipsis;
  }
}
.m-pr-entrys {
  margin-top: 30px;
  margin-bottom: 30px;
  .m-entry-group {
    padding: 0 30px;
  }

  .m-entry-extra {
    margin: 0;

    + .m-entry-append {
      margin-left: 10px;
    }
  }
}

.p-profile {
  .m-entry-prepend {
    color: @primary;
    width: 36px;
    height: 36px;
  }
  .m-entry-append {
    color: #bfbfbf;
    width: 24px;
    height: 24px;
  }
}
</style>
