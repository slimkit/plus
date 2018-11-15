<template>
  <div class="p-post-text m-box-model">

    <common-header :pinned="true">
      发布动态
      <template slot="left">
        <a href="javascript:;" @click="beforeGoBack">取消</a>
      </template>
      <template slot="right">
        <circle-loading v-if="loading" />
        <a
          v-else
          :class="{ disabled }"
          class="m-send-btn"
          @click.prevent.stop="beforePost">发布</a>
      </template>
    </common-header>

    <main>
      <div style="height: 100%;">
        <textarea-input
          v-model="contentText"
          :maxlength="255"
          :warnlength="200"
          :rows="11"
          class="textarea-input" />
      </div>

    </main>

    <footer @click.capture.stop.prevent="popupBuyTS">
      <v-switch
        v-if="paycontrol"
        v-model="pinned"
        type="checkbox"
        class="m-box m-bt1 m-bb1 m-lim-width m-pinned-row">
        <slot>是否收费</slot>
      </v-switch>
    </footer>
  </div>
</template>

<script>
import TextareaInput from "@/components/common/TextareaInput.vue";

export default {
  name: "PostText",
  components: {
    TextareaInput
  },
  data() {
    return {
      loading: false,
      contentText: "",
      curpos: 0,
      scrollHeight: 0,
      pinned: false,

      amount: 0,
      customAmount: null,

      appBackgroundColor: null
    };
  },
  computed: {
    paycontrol() {
      return this.$store.state.CONFIG.feed.paycontrol;
    },
    disabled() {
      return !this.contentText.length;
    },
    items() {
      return this.$store.state.CONFIG.feed.items || [];
    },
    limit() {
      return this.$store.state.CONFIG.feed.limit || 50;
    }
  },
  watch: {
    customAmount(val) {
      if (val) this.amount = ~~val;
    }
  },
  mounted() {
    this.contentText = "";
  },
  methods: {
    beforeGoBack() {
      this.contentText.length === 0
        ? this.goBack()
        : this.$bus.$emit(
            "actionSheet",
            [
              {
                text: "确定",
                method: () => {
                  this.goBack();
                }
              }
            ],
            "取消",
            "你还有没有发布的内容,是否放弃发布?"
          );
    },
    chooseDefaultAmount(amount) {
      this.customAmount = null;
      this.amount = amount;
    },
    beforePost() {
      this.pinned
        ? this.amount === 0
          ? this.$Message.error("请设置收费金额")
          : this.contentText.length <= this.limit
            ? this.$Message.error(`正文内容不足${this.limit}字, 无法设置收费`)
            : this.postText()
        : ((this.amount = 0), this.postText());
    },
    postText() {
      if (this.loading) return;
      this.loading = true;
      this.$http
        .post(
          "feeds",
          {
            feed_content: this.contentText,
            feed_from: 2,
            feed_mark:
              new Date().valueOf() + "" + this.$store.state.CURRENTUSER.id,
            amount: this.amount
          },
          { validateStatus: s => s === 201 }
        )
        .then(() => {
          this.$router.replace("/feeds?type=new&refresh=1");
        })
        .catch(err => {
          this.$Message.error(err.response.data);
        })
        .finally(() => {
          this.loading = false;
        });
    }
  }
};
</script>

<style lang="less" scoped>
.p-post-text {
  background-color: #fff;
  height: 100%;

  main {
    flex: auto;
    padding-top: 90px;

    .textarea-input {
      padding-top: 20px;
      padding-left: 20px;
    }
  }

  footer {
    flex: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 10;
  }
}
</style>
