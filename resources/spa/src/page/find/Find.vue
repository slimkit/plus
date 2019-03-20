<template>
  <div class="p-find-person">
    <header class="m-pos-f m-box m-aln-center m-justify-bet m-lim-width m-bb1 m-main m-head-top" style="padding: 0 10px;">
      <div class="m-box m-aln-center m-flex-grow0 m-flex-shrink0">
        <svg class="m-style-svg m-svg-def" @click="goBack">
          <use xlink:href="#icon-back" />
        </svg>
      </div>
      <div class="m-box m-aln-center m-flex-grow1 m-flex-shrink1 m-head-search-box" @click="showSearchUser">
        <svg class="m-style-svg m-svg-def placeholder">
          <use xlink:href="#icon-search" />
        </svg>
        <span class="placeholder">{{ $t('search') }}</span>
      </div>
      <div class="m-box m-aln-center m-flex-grow0 m-flex-shrink0 m-justify-end m-location" @click="switchLocation">
        <CircleLoading v-if="loading" />
        <svg v-else class="m-style-svg m-svg-def">
          <use xlink:href="#icon-find-location" />
        </svg>
        <span class="m-location-label m-text-cut">{{ location }}</span>
      </div>
    </header>

    <main style="padding-top: 0.9rem">
      <div class="m-pos-f m-box m-aln-center m-justify-bet m-sub-nav m-bb1 m-main">
        <RouterLink
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/find/pop"
          class="m-sub-nav-item"
        >
          <a>{{ $t('hot') }}</a>
        </RouterLink>
        <RouterLink
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/find/new"
          class="m-sub-nav-item"
        >
          <a>{{ $t('newest') }}</a>
        </RouterLink>
        <RouterLink
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/find/rec"
          class="m-sub-nav-item"
        >
          <a>{{ $t('recommend') }}</a>
        </RouterLink>
        <RouterLink
          replace
          exact
          tag="div"
          exact-active-class="active"
          to="/find/ner"
          class="m-sub-nav-item"
        >
          <a>{{ $t('nearby') }}</a>
        </RouterLink>
      </div>
      <KeepAlive>
        <RouterView
          class="p-find-body"
          style="padding-top: 0.9rem"
        />
      </KeepAlive>
    </main>
  </div>
</template>

<script>
import { getCurrentPosition } from '@/api/bootstrappers.js'

export default {
  name: 'Find',
  data () {
    return {
      loading: false,
    }
  },
  computed: {
    POSITION () {
      return this.$store.state.POSITION || { label: '' }
    },
    location () {
      const { label = '' } = this.POSITION
      // label.length > 5
      //   ? `${label.slice(0, 2)}…${label.slice(-2)}`
      //   :
      return label || this.$t('location.city')
    },
  },
  created () {
    this.getCurrentPosition()
  },
  methods: {
    goBack () {
      this.$router.push('/discover')
    },
    showSearchUser () {
      this.$router.push('/search/user')
    },
    switchLocation () {
      this.$router.push('/location')
    },
    getCurrentPosition () {
      if (this.$lstore.hasData('H5_CURRENT_POSITION')) return
      this.loading = true
      getCurrentPosition()
        .then(data => {
          this.$store.commit('SAVE_H5_POSITION', data)
        })
        .catch(err => {
          this.$Message.error(err.message)
        })
        .finally(() => {
          this.loading = false
        })
    },
  },
}
</script>

<style lang="less" scoped>
.p-find-person {
  .m-sub-nav {
    top: 90px;
    bottom: initial;
    padding: 0 30px;
    &-item {
      display: flex;
      align-items: center;
      justify-content: center;

      height: 100%;

      &.active {
        color: #333;
        border-bottom-color: @primary;
      }
    }
  }
  .m-tab {
    top: 180px;
    background: #134;
    height: 200px;
  }
  .p-find-main {
    color: inherit;
  }
  .p-find-body {
    .jo-loadmore-head {
      top: 90px;
    }
  }
}
.m-head-search-box {
  margin-left: 30px;
  margin-right: 30px;
  padding: 0 15px;
  height: 55px;
  border-radius: 10px;
  background-color: #ebebeb;
  .placeholder {
    color: #ccc;
    font-size: 24px;
    &.m-svg-def {
      width: 24px;
      height: 24px;
    }
    + .placeholder {
      margin-left: 15px;
    }
  }
}
.m-location {
  font-size: 24px;
  .m-svg-def {
    width: 42px;
    height: 42px;
  }

  .m-svg-small {
    width: 30px;
    height: 30px;
    color: #d1d1d1;
  }

  &-label {
    margin-left: 5px;
    display: inline-block;
    max-width: 5 * 24px;
  }
}
</style>
