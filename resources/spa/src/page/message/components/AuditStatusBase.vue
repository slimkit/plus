<script>
/**
 * 提取动态评论置顶申请的状态控制组件
 */
export default {
  props: {
    audit: { type: Object, required: true }
  },
  methods: {
    accept() {},
    reject() {},
    showOperations(audit) {
      if (audit.feed === null) {
        this.$Message.error("动态已被删除,无法设置该评论置顶");
        return false;
      }
      const actions = [
        { text: "同意置顶", method: () => this.accept },
        { text: "拒绝置顶", method: () => this.reject }
      ];
      this.$bus.$emit("actionSheet", actions);
    }
  }
};
</script>

<style lang="less">
@import url("../style.less");
</style>
