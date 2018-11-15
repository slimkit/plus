<template>
  <div :class="prefixCls">
    <head-top
      :go-back="true"
      :append="true"
      title="发帖">
      <button
        slot="prepend"
        :class="`${prefixCls}-btn`"
        @click="goBack">取消</button>
      <button
        slot="append"
        :class="`${prefixCls}-btn`"
        :disabled="disabled"
        @click="postFeed">发布</button>
    </head-top>
    <div/>
    <div :class="`${prefixCls}-content`">
      <template v-if="!groupId || selectGroup.id">
        <div
          :class="`${prefixCls}-select`"
          @click.stop="showChoosePage">
          <div :class="`${prefixCls}-select-label`">{{ chooseTips }}</div>
          <svg class="m-style-svg m-svg-def">
            <use xlink:href="#icon-arrow-right" />
          </svg>
        </div>
      </template>
      <div :class="`${prefixCls}-title`">
        <input
          v-model="title"
          type="text"
          placeholder="输入标题, 20字以内"
          maxlength="20">
      </div>
      <div :class="`${prefixCls}-body`">
        <textarea
          v-txtautosize
          v-model="body"
          placeholder="输入要说的话, 图文结合更精彩哦" />
      </div>
    </div>
    <ChooseGroup
      v-show="showChoose"
      v-model="selectGroup"
      @on-close="closeChoosePage" />
  </div>
</template>
<script>
import HeadTop from "@/components/HeadTop";
import ChooseGroup from "./children/chooseGroup";
const prefixCls = "post--group-feed";
export default {
  name: "PostGroupFeed",
  components: {
    HeadTop,
    ChooseGroup
  },
  data() {
    return {
      prefixCls,
      title: "",
      body: "",
      showChoose: false,
      selectGroup: {}
    };
  },
  computed: {
    chooseTips() {
      return this.selectGroup.name || "选择圈子";
    },
    groupId() {
      return this.selectGroup.id || this.$route.params.groupId;
    },
    disabled() {
      return !(this.title.length > 0 && this.body.length > 0);
    }
  },
  methods: {
    showChoosePage() {
      this.showChoose = true;
    },
    closeChoosePage() {
      this.showChoose = false;
    },
    goBack() {
      this.$router.go(-1);
    },
    postFeed() {
      if (this.groupId) {
        const params = {
          title: this.title,
          body: this.body,
          summary: this.body,
          feed_from: 2
        };
        // /groups/:group/posts
        this.$http
          .post(`/plus-group/groups/${this.groupId}/posts`, {
            ...params
          })
          .then(({ data: { post } }) => {
            if (post.id) {
              this.$router.push(`/group/${this.groupId}/feed/${post.id}`);
            }
          });
      }
    }
  }
};
</script>
<style lang='less'>
@post-group-feed-prefix: post--group-feed;
.@{post-group-feed-prefix} {
  height: 100vh;
  overflow-y: auto;
  overflow-x: hidden;
  &-btn {
    font-size: 32px;
    background-color: #fff;
    color: @primary;
    &[disabled] {
      color: #ccc;
    }
  }
  &-select {
    display: flex;
    height: 100px;
    padding: 0 20px;
    font-size: 30px;
    align-items: center;
    justify-content: space-between;
  }
  &-content {
    padding: 0 30px;
    height: calc(~"100vh -" 90px);
    background-color: #fff;
  }

  &-title {
    display: flex;
    align-items: conter;
    height: 100px;
    border-bottom: 1px solid #dedede;
    /*no*/
    input {
      font-size: 30px;
      padding: 0 20px;
      height: 100%;
      width: 100%;
    }
  }

  &-body {
    max-height: calc(~"100% -" 100px);
    overflow-y: scroll;
    textarea {
      font-size: 30px;
      padding: 20px;
      width: 100%;
      min-height: 400px;
      max-height: 100%;
      resize: none;
    }
  }
}
</style>
