<template>
  <div class="m-box-model p-user-info">
    <CommonHeader>
      个人资料
      <template slot="right">
        <CircleLoading v-if="loading" />
        <a
          v-else
          :class="{ disabled }"
          class="submit-btn"
          @click.prevent="handleOk"
        >
          完成
        </a>
      </template>
    </CommonHeader>

    <main>
      <form>
        <FormAvatarItem
          v-model="avatar"
          label="更换头像"
          type="storage"
        />

        <FormInputItem
          v-model="name"
          placeholder="请输入用户名"
          label="用户名"
        />

        <FormSelectItem
          v-model="sexMap[sex]"
          label="性别"
          @click="switchSex"
        />

        <FormLocationItem
          v-model="location"
          label="城市"
        />

        <FormTagsItem
          v-model="tags"
          label="标签"
          @select="onTagSelect"
          @delete="onTagRemove"
        />

        <FormInputItem
          v-model="bio"
          :maxlength="50"
          :warnlength="40"
          type="textarea"
          label="简介"
          placeholder="编辑简介"
        />
      </form>
    </main>
  </div>
</template>

<script>
import { mapState } from 'vuex'

const sexMap = { 0: '保密', 1: '男', 2: '女' }

export default {
  name: 'UserInfo',
  data () {
    return {
      loading: false,
      scrollHeight: 0,
      showCleanName: false,

      sexMap,
      sex: 0,
      bio: '',
      name: '',
      tags: [],
      location: { label: '请选择地理位置' },
      avatar: {},
      avatarNode: '',
      change: false,

      showPosition: false,
    }
  },
  computed: {
    ...mapState(['CURRENTUSER']),
    disabled () {
      if (!this.bio || !this.name) return true
      if (this.location.label !== this.CURRENTUSER.location) return false
      return !['sex', 'bio', 'name', 'avatar', this.change].some(
        key =>
          typeof key === 'string'
            ? this.$data[key] !== this.CURRENTUSER[key]
            : key
      )
    },
    sexTxt () {
      const sex = ['保密', '男', '女']
      return sex[this.sex] || '选择性别'
    },
  },
  created () {
    this.fetchUser()
  },
  methods: {
    fetchUser () {
      const {
        sex = 0,
        bio = '',
        location = '',
        avatar = '',
        tags = [],
        name = '',
      } = this.CURRENTUSER
      this.name = name
      this.sex = sex
      this.bio = bio || ''
      this.tags = tags || []
      this.avatar = avatar
      this.location.label = location || ''
      this.$http
        .get(`users/${this.CURRENTUSER.id}/tags`)
        .then(({ data = [] }) => {
          this.tags = data
          this.CURRENTUSER.tags = data
          this.$store.commit('SAVE_CURRENTUSER', this.CURRENTUSER)
        })
    },
    handleOk () {
      if (this.disabled) return
      if (this.loading) return
      this.change = false
      this.loading = true

      const param = {
        name: this.name,
        bio: this.bio,
        sex: this.sex,
        location: this.location.label,
      }
      if (typeof this.avatar === 'string') param.avatar = this.avatar
      this.$http
        .patch('/user', param, { validateStatus: s => s === 204 })
        .then(() => {
          this.$store.commit(
            'SAVE_CURRENTUSER',
            Object.assign(this.CURRENTUSER, param)
          )
          this.goBack()
        })
        .catch(err => err)
        .finally(() => {
          this.loading = false
        })
    },
    onTagSelect (tagId) {
      this.$http.put(`/user/tags/${tagId}`)
    },
    onTagRemove (tagId) {
      this.$http.delete(`/user/tags/${tagId}`)
    },
    switchPosition (val) {
      this.showPosition = !this.showPosition
      val && (this.location = val.label)
    },
    switchSex () {
      const options = [
        { text: '男', method: () => void (this.sex = 1) },
        { text: '女', method: () => void (this.sex = 2) },
        { text: '保密', method: () => void (this.sex = 0) },
      ]
      this.$bus.$emit('actionSheet', options, '取消')
    },
  },
}
</script>

<style lang="less" scoped>
.p-user-info {
  .submit-btn {
    color: @primary;

    &.disabled {
      color: @gray;
    }
  }

  main {
    background-color: #fff;
  }

  .m-entry-append {
    margin-right: 20px;
  }
}

.p-info-row {
  position: relative;
  padding: 35px 0 35px 0;
  margin-left: 140px;
  min-height: 100px;
  .input {
    font-size: 30px;
    line-height: 1;
  }
  label {
    display: flex;
    align-items: center;
    margin-left: -110px;
    width: 110px;
    font-size: 30px;
    line-height: inherit;
    color: @text-color3;
  }
  .m-wz-def {
    font-size: 30px;
    line-height: inherit;
    font-weight: 400;
    word-wrap: break-word;
  }
  .placeholder {
    color: #ccc;
  }
}
</style>
