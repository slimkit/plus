<script>
/**
 * 提取动态评论置顶申请的状态控制组件
 */
export default {
  props: {
    audit: { type: Object, required: true },
  },
  methods: {
    accept () {},
    reject () {},
    showOperations (audit) {
      if (audit.feed === null) {
        this.$Message.error(this.$t('article.deleted'))
        return false
      }
      const actions = [
        { text: this.$t('message.audit.top.accept'), method: () => this.accept },
        { text: this.$t('message.audit.top.reject'), method: () => this.reject },
      ]
      this.$bus.$emit('actionSheet', actions)
    },
  },
}
</script>

<style lang="less">
@import url("../style.less");
</style>
