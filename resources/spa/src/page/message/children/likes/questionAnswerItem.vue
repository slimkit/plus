<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <avatar :user="user" />
      <section class="userInfo">
        <span v-if="!user.id" :class="`${prefixCls}-item-top-link`">未知用户</span>
        <router-link
          v-else
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${user._id}`">{{ user.name || "未知用户" }} </router-link>
        <span>赞了你的回答</span>
        <p>{{ like.created_at | time2tips }}</p>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <section v-if="like.likeable !== null" @click="goToFeedDetail()">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          {{ like.likeable.body }}
        </div>
        <!-- <div :class="`${prefixCls}-item-bottom-img`" v-else>
          <div class="img">
            <img :src="getImage" :alt="user.name">
          </div>
          <div class="content">
            {{ like.likeable.feed_content }}
          </div>
        </div> -->
      </section>
      <section v-if="like.likeable === null">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          回答已被删除
        </div>
      </section>
    </div>
  </section>
</template>

<script>
const prefixCls = "msgList";
export default {
  name: "QuestionAnswerItem",
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
      const { length } = like.likeable.images;
      if (length > 0) {
        const { 0: img = {} } = like.likeable.images;
        return `${this.$http.defaults.baseURL}/files/${img.id}`;
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
        likeable: { id }
      } = this.like;
      this.$router.push(`/questions/${id}`);
    }
  }
};
</script>
