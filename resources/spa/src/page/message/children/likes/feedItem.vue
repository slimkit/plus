<template>
  <section>
    <div :class="`${prefixCls}-item-top`" class="m-box m-aln-center m-justify-bet">
      <avatar :user="user" />
      <section class="userInfo m-flex-grow1 m-flex-shrink1 m-flex-base0">
        <span v-if="!user.id" :class="`${prefixCls}-item-top-link`">未知用户</span>
        <router-link
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${user.id}`">{{ user.name }}</router-link>
        <span> 赞了你的动态</span>
        <p>{{ like.created_at | time2tips }}</p>
      </section>
      <svg class="m-style-svg m-svg-def m-flex-grow0 m-shrink0">
        <use xlink:href="#icon-like"/>
      </svg>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <section v-if="like.likeable !== null" @click="goToFeedDetail()">
        <div
          v-if="!getImage && !getVideo"
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content">
          {{ like.likeable.feed_content }}
        </div>
        <div v-else :class="`${prefixCls}-item-bottom-img`">
          <div class="img">
            <img
              v-if="getImage"
              :src="getImage"
              :alt="user.name" >
            <img
              v-if="getVideo"
              :src="getVideo.cover"
              :alt="user.name" >
          </div>
          <div class="content">
            {{ like.likeable.feed_content }}
          </div>
        </div>
      </section>
      <section v-if="like.likeable === null">
        <div
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content">
          动态已被删除
        </div>
      </section>
    </div>
  </section>
</template>

<script>
const prefixCls = "msgList";
export default {
  name: "FeedsItem",
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
    getVideo() {
      const { like } = this.$props;
      const video = like.likeable.video;
      if (video !== null) {
        return {
          video: `${this.$http.defaults.baseURL}/files/${video.video_id}`,
          cover: `${this.$http.defaults.baseURL}/files/${video.cover_id}`
        };
      }
      return false;
    },
    user() {
      return this.like.user || {};
    }
  },
  methods: {
    /**
     * 进入动态详情
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    goToFeedDetail() {
      const {
        likeable: { id = 0 }
      } = this.like;
      this.$router.push(`/feeds/${id}`);
    }
  }
};
</script>
