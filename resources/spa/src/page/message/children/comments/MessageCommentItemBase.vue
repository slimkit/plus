
<script>
const prefixCls = 'msgList'

export default {
  name: 'MessageCommentItemBase',
  props: {
    comment: { type: Object, required: true },
  },
  data () {
    return {
      prefixCls,
    }
  },
  computed: {
    /**
     * 获取图片,并计算地址
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {false|Object}            [description]
     */
    getFirstImage () {
      const { comment } = this
      const { length } = comment.commentable.images
      if (length > 0) {
        const [img] = comment.commentable.images

        return img
      }

      return false
    },
    getVideo () {
      const { comment } = this
      const { video } = comment.commentable
      if (video != null) {
        return video.cover_id
      } else {
        return false
      }
    },
    user () {
      const { user } = this.comment
      return user || {}
    },
  },
  methods: {
    showCommentInput () {
      this.$bus.$emit('commentInput', {
        placeholder: `${this.$t('reply.name')}: ${this.comment.user.name}`,
        onOk: comment => {
          this.sendComment(comment)
        },
      })
    },
  },
}
</script>
