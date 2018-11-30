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
    <div>
      <GroupItem
        v-for="group in dataList"
        v-if="group.id"
        :key="`profile-group-${group.id}`"
        :role="true"
        :group="group"
      />
    </div>
  </div>
</template>

<script>
import GroupItem from '@/page/group/components/GroupItem.vue'

export default {
  name: 'ProfileGroupGroups',
  components: {
    GroupItem,
  },
  data () {
    return {
      curType: 'join',
      navs: [
        {
          type: 'join',
          label: '我加入的',
        },
        {
          type: 'audit',
          label: '待审核的',
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
        .get(`/plus-group/user-groups?type=${this.curType}`)
        .then(({ data = [] }) => {
          this.dataList = data
        })
    },
  },
}
</script>
<style lang='less'>
</style>
