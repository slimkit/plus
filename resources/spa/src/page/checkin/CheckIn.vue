<template>
  <div @touchmove.prevent>
    <Transition name="toast">
      <div
        v-if="show"
        class="m-pop-box"
        @click="cancel"
      />
    </Transition>
    <Transition>
      <!-- 屏蔽出场动画 -->
      <!-- enter-active-class="animated jello" -->
      <div v-if="show" class="m-box-model m-main m-check-in-box">
        <header class="m-box-model m-aln-center m-justify-center m-check-in-head">
          <h2>{{ $t('checkin.title') }}</h2>
          <p>{{ last_checkin_count | t('checkin.count') }}</p>
          <a class="m-check-in-close" @click="cancel">
            <svg
              viewBox="0 0 1024 1024"
              class="m-style-svg m-svg-def"
              style="fill:#fff;overflow:hidden;padding:3px;border-radius:100%;background:rgba(255, 255, 255, .2);box-shadow: 1px 1px 2px 1px rgba(0, 0, 0,.1);"
            >
              <path d="M176.662 817.173c-8.19 8.471-7.96 21.977 0.51 30.165 8.472 8.19 21.978 7.96 30.166-0.51l618.667-640c8.189-8.472 7.96-21.978-0.511-30.166-8.471-8.19-21.977-7.96-30.166 0.51l-618.666 640z" />
              <path d="M795.328 846.827c8.19 8.471 21.695 8.7 30.166 0.511 8.471-8.188 8.7-21.694 0.511-30.165l-618.667-640c-8.188-8.471-21.694-8.7-30.165-0.511-8.471 8.188-8.7 21.694-0.511 30.165l618.666 640z" />
            </svg>
          </a>
        </header>
        <main class="m-box-model m-aln-center m-check-in-body">
          <section class="m-check-in-con">
            <h2>+{{ attach_balance }}</h2>
            <p>{{ $t('checkin.tips') }}</p>
          </section>
          <button
            :disabled="checked_in"
            class="m-check-in-btn"
            @click="fetchCheckIn"
          >
            {{ checked_in ? "checkin.already" : "checkin.name" | t }}
          </button>
          <div class="m-lim-width">
            <ul class="m-box m-lan-center m-justify-center m-check-in-user-list">
              <li
                v-for="(user, index) in rank_users"
                v-if="user.id"
                :key="user.id"
                class="m-box-model m-aln-center"
                @click="cancel"
              >
                <RouterLink
                  :to="`/users/${user.id}?from=checkin`"
                  :class="[`m-avatar-box-tiny`, `m-avatar-box-${user.sex}`]"
                  class="m-flex-shrink0 m-flex-grow0 m-avatar-box"
                >
                  <img
                    v-if="user.avatar"
                    :src="user.avatar.url"
                    class="m-avatar-img"
                  >
                </RouterLink>
                <span>{{ index + 1 }}</span>
              </li>
            </ul>
          </div>
        </main>
      </div>
    </Transition>
  </div>
</template>

<script>
export default {
  name: 'CheckIn',
  data () {
    return {
      show: false,
      scrollTop: 0,
      rank_users: [],
      checked_in: true,
      attach_balance: 0,
      last_checkin_count: 0,
    }
  },
  created () {
    this.$bus.$on('check-in', () => {
      this.updateDate()
      this.show = true
      this.scrollTop = document.scrollingElement.scrollTop
      document.body.classList.add('m-pop-open')
      document.body.style.top = -this.scrollTop + 'px'
    })
  },
  methods: {
    cancel () {
      this.show = false
      document.body.style.top = ''
      document.body.classList.remove('m-pop-open')
      document.scrollingElement.scrollTop = this.scrollTop
    },
    updateDate () {
      this.$http
        .get(`/user/checkin`)
        .then(({ data = {} }) => {
          const users = data.rank_users || []
          this.checked_in = data.checked_in
          this.attach_balance = ~~data.attach_balance
          this.last_checkin_count = data.last_checkin_count || 0
          users.length && (this.rank_users = users)
        })
    },
    fetchCheckIn () {
      if (this.checked_in) return
      this.$http
        .put('/user/checkin/currency', { validateStatus: s => s === 204 })
        .then(() => {
          this.checked_in = true
          this.updateDate()
        })
        .catch(() => {
          this.$Message.error(this.$t('checkin.failed'))
        })
    },
  },
}
</script>

<style>
.m-check-in-box {
  overflow: hidden;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  margin: auto;
  height: 600px;
  width: 500px;
  border-radius: 10px;
}

.m-check-in-head {
  color: #fff;
  height: 200px;
  font-size: 24px;
  background-image: linear-gradient(
    148deg,
    #efb946 0%,
    #efa046 50%,
    #ef8a46 100%
  );
}
.m-check-in-head h2 {
  font-size: 44px;
}
.m-check-in-head p {
  margin-top: 20px;
  padding: 0 45px;
  color: #d46c28;
  height: 44px;
  line-height: 44px;
  border-radius: 22px;
  background-color: rgba(255, 255, 255, 0.2);
}
.m-check-in-close {
  display: block;
  width: 36px;
  height: 36px;
  position: absolute;
  top: 15px;
  right: 15px;
  border-radius: 100%;
  overflow: hidden;
  color: rgba(255, 255, 255, 0.8);
}
.m-check-in-close .m-svg-def {
  width: 36px;
  height: 36px;
}
.m-check-in-body {
  padding: 50px;
  font-size: 24px;
  color: #999;
  line-height: 40px;
}
.m-check-in-con {
  text-align: center;
}
.m-check-in-con h2 {
  font-size: 60px;
  color: #ff9400;
  margin-bottom: 20px;
}
.m-check-in-btn {
  margin-top: 40px;
  width: 100%;
  height: 70px;
  line-height: 70px;
  font-size: 30px;
  color: #fff;
  background-image: linear-gradient(92deg, #efab46 0%, #ef8a46 100%);
  border-radius: 6px;
}
.m-check-in-btn[disabled="disabled"] {
  background: #ccc;
}
.m-check-in-user-list {
  margin-top: 30px;
  font-size: 20px;
}
.m-check-in-user-list li + li {
  margin-left: 20px;
}
</style>
