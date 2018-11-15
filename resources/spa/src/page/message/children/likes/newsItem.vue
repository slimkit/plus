<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <avatar :user="user" />
      <section class="userInfo">
        <span v-if="!user.id" :class="`${prefixCls}-item-top-link`">未知用户</span>
        <router-link
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${user._id}`">{{ user.name }}</router-link>
        <span>赞了你的资讯</span>
        <p>{{ like.created_at | time2tips }}</p>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <!-- <section v-if="like.likeable !== null" @click="goToFeedDetail()"> -->
      <section v-if="like.likeable !== null">
        <div
          v-if="!getImage"
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content">
          {{ like.likeable.title }}
        </div>
        <div v-else :class="`${prefixCls}-item-bottom-img`">
          <div class="img">
            <img :src="getImage" :alt="user.name">
          </div>
          <div class="content">
            {{ like.likeable.title }}
          </div>
        </div>
      </section>
      <section v-if="like.likeable === null">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          资讯已被删除
        </div>
      </section>
    </div>
  </section>
</template>

<script>
const prefixCls = "msgList";
export default {
  name: "NewsItem",
  props: {
    like: { type: Object, default: () => {} }
  },
  data: () => ({
    prefixCls
  }),
  computed: {
    /**
     * 获取图片,并计算地址
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    getImage() {
      const { like } = this;
      const { id = 0 } = like.likeable.image || {};
      if (id > 0) {
        return `${this.$http.defaults.baseURL}/files/${id}`;
      }

      return false;
    },
    user() {
      return this.like.user || {};
    }
  },
  methods: {
    /**
     * 进入详情
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    goToFeedDetail() {
      const {
        likeable: { id = 0 }
      } = this.like;
      this.$router.push(`/news/${id}`);
    }
  }
};
</script>
