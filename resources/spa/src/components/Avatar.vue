<template>
  <span
    v-if="anonymity"
    :class="styles"
    class="m-avatar-box anonymity"
  >
    {{ $t('hide') }}
  </span>
  <RouterLink
    v-else
    :to="path"
    :class="styles"
    class="m-avatar-box c-avatar"
    @click="viewUser"
  >
    <img
      :src="avatar"
      class="m-avatar-img"
      @error="handelError"
    >
    <i
      v-if="icon"
      :style="icon"
      :class="iconClass"
      class="m-avatar-icon"
    />
  </RouterLink>
</template>

<script>
import _ from 'lodash'
import * as userApi from '@/api/user'

export default {
  name: 'Avatar',
  props: {
    size: { type: String, default: 'def', validator: val => ['def', 'big', 'nano', 'small', 'tiny'].includes(val) },
    user: { type: Object, required: true },
    anonymity: { type: [Boolean, Number], default: false },
    readonly: { type: Boolean, default: false },
  },
  data () {
    return {
      localUser: {},
    }
  },
  computed: {
    sex () {
      return ~~this.user.sex
    },
    iconClass () {
      if (this.anonymity) return false
      const { verified = {} } = this.user
      return verified.type
    },
    icon () {
      // 如果是匿名用户 不显示
      if (this.anonymity) return false

      // 如果没有认证 不显示
      const { verified = {} } = this.user
      if (_.isEmpty(verified)) return false

      // 如果有设置图标 使用设置的图标
      if (verified.icon) return { 'background-image': `url("${verified.icon}")` }
      // 否则根据认证类型使用相应的默认图标
      else if (verified.type) return {}
      else return false
    },
    path () {
      return this.user.id ? `/users/${this.user.id}` : 'javascript:;'
    },
    styles () {
      const sex = ['secret', 'man', 'woman']
      return this.avatar || this.anonymity
        ? [`m-avatar-box-${this.size}`]
        : [`m-avatar-box-${this.size}`, `m-avatar-box-${sex[this.sex]}`]
    },
    avatar: {
      get () {
        const avatar = (this.user || {}).avatar || this.localUser.avatar || {}
        return avatar.url || null
      },
      set (val) {
        this.user.avatar.url = val
      },
    },
  },
  created () {
    if (!this.anonymity && this.user.id && !this.user.avatar) this.getUserAvatar()
  },
  methods: {
    handelError () {
      this.avatar = null
    },
    viewUser () {
      const userId = this.user.id
      if (this.readonly || !userId) return
      this.$router.push({ name: 'UserDetail', params: { userId } })
    },
    async getUserAvatar () {
      this.localUser = await userApi.getUserInfoById(this.user.id)
    },
  },
}
</script>

<style lang="less" scoped>
.m-avatar-box {
  &.anonymity {
    background-color: #ccc;
  }

  .m-avatar-icon {
    &.user {
      background-image: url('~@/images/cert_user.png');
    }
    &.org {
      background-image: url('~@/images/cert_org.png');
    }
  }
}
</style>
