<template>
  <div class="p-profile-certification">
    <CommonHeader :pinned="true">{{ `certificate.${type}.name` | t }}</CommonHeader>

    <main class="m-box-model main">
      <div
        v-if="verified.status === 0"
        class="info-bar"
      >
        {{ $t('certificate.reviewing', [7]) }}
      </div>
      <div class="info-main">
        <template v-if="type !== 'user'">
          <div class="row">
            <span class="label">{{ formInfo[type].orgName }}</span>
            <span class="value">{{ verified.data.org_name }}</span>
          </div>
          <div class="row">
            <span class="label">{{ formInfo[type].orgAddress }}</span>
            <span class="value">{{ verified.data.org_address }}</span>
          </div>
        </template>
        <div class="row">
          <span class="label">{{ formInfo[type].name }}</span>
          <span class="value">{{ verified.data.name }}</span>
        </div>
        <div class="row">
          <span class="label">{{ formInfo[type].number }}</span>
          <span class="value">{{ verified.data.number }}</span>
        </div>
        <div class="row">
          <span class="label">{{ formInfo[type].phone }}</span>
          <span class="value">{{ verified.data.phone }}</span>
        </div>
        <div class="row">
          <span class="label">{{ formInfo[type].desc }}</span>
          <span class="value">{{ verified.data.desc }}</span>
        </div>
        <div class="row">
          <span class="label">{{ $t('certificate.data') }}</span>
          <span class="value">
            <img
              v-for="(image, index) in images"
              :key="image.id"
              :src="`${getImageSrc(image.id)}?w=250&h=185`"
              @click="viewImage(index)"
            >
          </span>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
/**
 * 认证状态页面
 */

import _ from 'lodash'
import { mapState } from 'vuex'
import i18n from '@/i18n'

const formInfo = {
  user: {
    name: i18n.t('certificate.user.label.name'),
    number: i18n.t('certificate.user.label.number'),
    phone: i18n.t('certificate.user.label.phone'),
    desc: i18n.t('certificate.user.label.desc'),
  },
  org: {
    name: i18n.t('certificate.org.label.name'),
    number: i18n.t('certificate.org.label.number'),
    phone: i18n.t('certificate.org.label.phone'),
    desc: i18n.t('certificate.org.label.desc'),
    orgName: i18n.t('certificate.org.label.org_name'),
    orgAddress: i18n.t('certificate.org.label.org_address'),
  },
}

export default {
  name: 'Certification',
  data () {
    return {
      formInfo,
    }
  },
  computed: {
    ...mapState({
      verified: state => state.USER_VERIFY,
    }),
    type () {
      const { certification_name: type = 'user' } = this.verified
      return type
    },
    images () {
      const files = this.verified.data.files || []
      return files.map((item, index) => ({
        id: item,
        src: this.getImageSrc(item),
        w: 250,
        h: 185,
      }))
    },
  },
  watch: {
    verified (to) {
      // 如果被驳回或没有数据则返回个人页面
      if (to.id && ![0, 1].includes(to.status)) {
        this.$router.replace('/profile')
      }
    },
  },
  created () {
    // 如果 store 中没有数据则重新获取认证数据，用于首屏加载
    if (_.isEmpty(this.verified) || !this.verified.id) {
      this.$store.dispatch('FETCH_USER_VERIFY')
    }
  },
  methods: {
    /**
     * @param {number} id
     * @returns {string}
     */
    getImageSrc (id) {
      return `${this.$http.defaults.baseURL}/files/${id}`
    },
    viewImage (index) {
      this.$bus.$emit('mvGallery', { index, images: this.images })
    },
  },
}
</script>

<style lang="less" scoped>
.p-profile-certification {
  height: 100%;
  background-color: #fff;

  main {
    .info-bar {
      background-color: #4bb893;
      color: #fff;
      font-size: 24px;
      padding: 18px;
      text-align: center;
    }

    .info-main {
      background-color: #fff;
    }

    .row {
      display: flex;
      font-size: 30px;
      line-height: 36px;
      margin: 40px;

      .label {
        flex: none;
        display: block;
        width: 5em;
        margin-right: 1em;
        color: #999;
      }

      .value {
        flex: auto;
        word-wrap: break-word;
        word-break: break-all;
      }

      img {
        width: ~"calc(50% - 4em)";
        margin-right: 4px;
      }
    }
  }
}
</style>
