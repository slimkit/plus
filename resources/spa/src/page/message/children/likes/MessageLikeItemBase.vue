<script>
const prefixCls = 'msgList'

export default {
  name: 'MessageLikeItemBase',
  props: {
    like: { type: Object, default: () => {} },
  },
  data () {
    return {
      prefixCls,
    }
  },
  computed: {
    getImage () {
      const { like } = this
      const { length } = like.likeable.images || []
      if (length > 0) {
        const { 0: img = {} } = like.likeable.images
        return `${this.$http.defaults.baseURL}/files/${img.id}`
      }

      return false
    },
    user () {
      const { user } = this.like
      return user || {}
    },
  },
  methods: {
    viewUser (userId) {
      this.$router.push({ name: 'UserDetail', params: { userId } })
    },
  },
}
</script>
