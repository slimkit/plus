<template>
  <div>
    <div class="profile-group-nav">
      <div
        v-for="({ label, type },index) in navs"
        :key="`profile-group-nav-${index}`"
        :class="{active: curType === type}"
        class="profile-group-nav-item"
        @click="curType = type"
      >
        {{ label }}
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'ProfileGroupPosts',
  data () {
    return {
      curType: 1,
      navs: [
        {
          type: 1,
          label: '我发布的',
        },
        {
          type: 2,
          label: '已置顶',
        },
        {
          type: 3,
          label: '待审核置顶',
        },
      ],
      dataList: [],
    }
  },
  watch: {
    curType () {
      this.getData()
    },
  },
  created () {
    this.getData()
  },

  methods: {
    getData () {
      this.$http
        .get(`/plus-group/user-group-posts?type=${this.curType}`)
        .then(({ data = [] }) => {
          this.dataList = data
        })
    },
  },
}
</script>
<style lang='less'>
</style>
