<template>
  <div class="p-setting">
    <CommonHeader>{{ $t('setting.name') }}</CommonHeader>

    <main>
      <ul class="m-box-model m-entry-group padding">
        <RouterLink
          to="/changePassword"
          tag="li"
          class="m-entry"
        >
          <span>{{ $t('auth.change_password.name') }}</span>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right" />
          </svg>
        </RouterLink>
        <li class="m-entry" @click="aboutUs">
          <span class="m-box m-text-box m-flex-grow1">{{ $t('setting.about.name') }}</span>
          <span class="m-box m-text-box m-flex-grow1 m-justify-end m-entry-extra">v{{ version }}</span>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right" />
          </svg>
        </li>
        <li class="m-entry" @click="switchLocale">
          <span>{{ $t('setting.locale.name') }}</span>
          <span class="m-box m-text-box m-flex-grow1 m-justify-end m-entry-extra">{{ locale === 'en' ? 'English' : '简体中文' }}</span>
        </li>
        <li class="m-entry" @click="signOut">
          <a>{{ $t('setting.logout.name') }}</a>
          <svg class="m-style-svg m-svg-def m-entry-append">
            <use xlink:href="#icon-arrow-right" />
          </svg>
        </li>
      </ul>
    </main>
  </div>
</template>

<script>
import { version } from '@/main'

export default {
  name: 'Settings',
  data () {
    return {
      version,
    }
  },
  computed: {
    locale () {
      return this.$i18n.locale
    },
  },
  methods: {
    signOut () {
      const actions = [
        {
          text: this.$t('setting.logout.label'),
          style: { color: '#f4504d' },
          method: () => {
            this.$store.dispatch('SIGN_OUT')
            this.$nextTick(() => {
              this.$router.push('/signin')
            })
          },
        },
      ]
      this.$bus.$emit('actionSheet', actions, this.$t('cancel'), this.$t('setting.logout.confirm'))
    },
    aboutUs () {
      const { aboutUs = {} } = this.$store.state.CONFIG.site
      if (aboutUs.url) return (location.href = aboutUs.url)
      this.$router.push('/about')
    },
    switchLocale () {
      const target = this.locale === 'en' ? 'zh-CN' : 'en'
      this.$lstore.setData('I18N_LOCALE', target)
      this.$i18n.locale = target
      location.reload()
    },
  },
}
</script>
