<template>
  <section class="c-group-item" @click="beforeViewDetail">
    <div class="avatar">
      <img :src="avatar">
    </div>
    <div class="info">
      <div class="m-box m-aln-center m-text-cut">
        <h2 class="m-text-cut">{{ group.name }}</h2>
        <span v-if="mode === 'paid'" class="paid">{{ $t('group.pay.name') }}</span>
      </div>
      <p>
        <span>{{ $t('group.post.name') }} <span class="number">{{ feedCount | formatNum }}</span></span>
        <span>{{ $t('group.member') }} <span class="number">{{ memberCount | formatNum }}</span></span>
      </p>
    </div>

    <span v-if="isOwner" class="owner-badge">{{ $t('group.owner') }}</span>
    <span v-if="isAdmin" class="admin-badge">{{ $t('group.admin') }}</span>

    <div v-if="showAction" class="action">
      <button
        v-if="!joined || joined.audit === 0"
        :disabled="loading || joined.audit === 0"
        class="m-text-cut"
        @click.stop="beforeJoined"
      >
        <svg
          v-if="!(joined.audit ===0)"
          :style="loading ? {} : {width: '0.2rem', height:'0.2rem'}"
          class="m-style-svg m-svg-def"
        >
          <use :xlink:href="`#icon-${loading ? 'loading' : 'plus'}`" />
        </svg>
        <span>{{ joined.audit === 0 ? "group.audit" : "group.join" | t }}</span>
      </button>
    </div>
  </section>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'GroupItem',
  props: {
    group: { type: Object, required: true },
    showAction: { type: Boolean, default: true },
    showRole: { type: Boolean, default: true },
  },
  data () {
    return {
      loading: false,
    }
  },
  computed: {
    ...mapState(['CURRENTUSER']),
    avatar () {
      const avatar = this.group.avatar || {}
      return avatar.url || null
    },
    name () {
      return this.group.name
    },
    feedCount () {
      return this.group.posts_count || 0
    },
    memberCount () {
      return this.group.users_count || 0
    },
    mode () {
      return this.group.mode
    },
    money () {
      return this.group.money || 0
    },
    joined () {
      return this.group.joined || false
    },
    role () {
      return typeof this.group.joined === 'object' ? this.joined.role : false
    },
    isOwner () {
      return this.role === 'founder'
    },
    isAdmin () {
      return this.role === 'administrator'
    },
    needPaid () {
      return this.mode === 'paid' && this.money > 0
    },
  },
  methods: {
    beforeJoined () {
      if (this.joined || this.loading) return
      this.loading = true
      !this.needPaid
        ? this.joinGroup()
        : this.$bus.$emit('payfor', {
          title: this.$t('group.pay.apply'),
          confirmText: this.$t('group.pay.join'),
          amount: this.money,
          content: this.$t('group.pay.confirm', [this.money]),
          checkPassword: true,
          onOk: async password => {
            this.loading = false
            if (this.money <= this.CURRENTUSER.currency.sum) { this.joinGroup(password) } else this.$router.push({ name: 'currencyRecharge' })
          },
          onCancel: () => {
            this.loading = false
          },
        })
    },
    joinGroup (password) {
      this.$store
        .dispatch('group/joinGroup', {
          groupId: this.group.id,
          needPaid: this.needPaid,
          password,
        })
        .then(data => {
          this.loading = false
          this.$Message.success(data)
          this.group.joined = this.needPaid ? {} : { audit: 1 }
        })
        .catch(err => {
          this.loading = false
          this.$Message.error(err.response.data)
        })
    },
    beforeViewDetail () {
      this.joined
        ? this.joined.audit === 1
          ? this.$router.push(`/groups/${this.group.id}`)
          : this.$Message.error(this.$t('group.need_review'))
        : this.mode !== 'public'
          ? this.$Message.error(this.$t('group.need_join_first'))
          : this.$router.push(`/groups/${this.group.id}`)
    },
  },
}
</script>

<style lang="less" scoped>
.c-group-item {
  display: flex;
  align-items: center;
  padding: 30px 20px;
  background-color: #fff;

  .info {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    flex: auto;
    height: 100%;
    margin-left: 20px;
    margin-right: 20px;
    font-size: 28px;

    h2 {
      font-size: 32px;
      max-width: 11em;
    }

    .paid {
      display: inline-block;
      height: 30px;
      line-height: 30px;
      background: linear-gradient(135deg, #cea97a 49%, #c8a06c 50%);
      color: #fff;
      font-size: 22px;
      border-radius: 8px;
      padding: 0 6px;
      margin-left: 10px;
    }

    span {
      color: @text-color3;

      + span {
        margin-left: 25px;
      }
    }

    .number {
      color: @primary;
      margin: 0 5px;
    }
  }

  .owner-badge {
    flex: none;
    background-color: #fca308;
    color: #fff;
    font-size: 22px;
    border-radius: 200px;
    padding: 2px 20px;
  }

  .admin-badge {
    flex: none;
    background-color: #ccc;
    color: #fff;
    font-size: 22px;
    border-radius: 200px;
    padding: 2px 20px;
  }

  .avatar {
    overflow: hidden;
    flex: none;
    width: 120px;
    height: 120px;
    background-color: @gray-bg;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  .action {
    button {
      display: flex;
      align-items: center;
      justify-content: center;

      width: 126px;
      height: 50px;

      border: 1px solid currentColor; /* no */
      border-radius: 8px;
      background-color: #fff;

      font-size: 24px;
      color: @primary;
      transition: all 0.3s ease;
      span {
        transition: all 0.3s ease;
        margin-left: 5px;
      }
      &[disabled] {
        color: @border-color;
        cursor: not-allowed;
        span {
          color: @text-color3;
        }
        svg {
          opacity: 0.5;
        }
      }
    }
  }
}
</style>
