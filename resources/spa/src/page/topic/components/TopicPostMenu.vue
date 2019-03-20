<template>
  <div @touchmove.prevent>
    <Transition name="toast">
      <div
        v-if="show"
        class="m-pop-box"
        @click="cancel"
      >
        <!-- <div class="logo" /> -->
      </div>
    </Transition>
    <Transition @after-enter="transitionComplete">
      <div v-if="show" class="m-box-model m-post-menu-con">
        <TransitionGroup
          tag="div"
          enter-active-class="animated bounceIn"
          class="m-box m-aln-center m-post-menu-list"
        >
          <template v-if="visible">
            <div
              key="ico_word"
              class="m-box-model m-aln-center m-post-menu-item"
              @click="to({name:'PostText', query: {topicId: topic.id, topicName: topic.name}})"
            >
              <svg class="m-style-svg m-svg-def menu-svg">
                <use xlink:href="#icon-release-text" />
              </svg>
              <span>{{ $t('release.text') }}</span>
            </div>
            <div
              key="ico_potoablum"
              class="m-box-model m-aln-center m-post-menu-item"
              @click="to({name:'PostImage', query: {topicId: topic.id, topicName: topic.name}})"
            >
              <svg class="m-style-svg m-svg-def menu-svg">
                <use xlink:href="#icon-release-pic" />
              </svg>
              <span>{{ $t('release.image') }}</span>
            </div>
          </template>
        </TransitionGroup>
        <Transition name="pop">
          <button class="m-post-menu-btn" @click="cancel">
            <svg class="m-style-svg m-svg-def">
              <use xlink:href="#icon-foot-close" />
            </svg>
          </button>
        </Transition>
      </div>
    </Transition>
  </div>
</template>

<script>
export default {
  name: 'TopicPostMenu',
  props: {
    topic: { type: Object, required: true },
  },
  data () {
    return {
      show: false,
      visible: false,
    }
  },
  computed: {
    login () {
      return !!this.$store.state.CURRENTUSER.id
    },
  },
  methods: {
    to (path) {
      this.$router.push(path)
      this.$nextTick(this.cancel)
    },
    open () {
      this.show = true
      this.scrollable = false
    },
    cancel () {
      this.show = false
      this.scrollable = true
      this.visible = false
    },
    transitionComplete () {
      this.$nextTick(() => {
        this.visible = true
      })
    },
  },
}
</script>

<style lang="less" scoped>
.m-pop-box {
  background: rgba(255, 255, 255, 0.95);

  .logo {
    position: absolute;
    top: 0;
    left: 20%;
    width: 60%;
    height: 40%;
    // background: url("~@/images/logo_thinksns+@2x.png") no-repeat center;
    background-size: cover;
  }
}
.m-post-menu-con {
  position: fixed;
  left: 0;
  bottom: 0;
  right: 0;
  z-index: 102;
}
.m-post-menu-list {
  padding: 6%;
  flex-wrap: wrap;
  justify-content: space-around;
}
.m-post-menu-item {
  margin: 3% 6%;
  width: 1/3 * 100 - 12%;
  font-size: 28px;
  img {
    width: 100%;
  }
  span {
    color: #575757;
    margin-top: 25px;
  }
  .menu-svg {
    width: 144px;
    height: 144px;
  }
}
.m-post-menu-btn {
  position: relative;
  height: 100px;
  width: 100%;
  background-color: #fff;
  box-shadow: 0 -1px 3px rgba(26, 26, 26, 0.1); /* no */
}

.fadeIn-enter-active,
.fadeIn-leave-active {
  transition: all 1s ease;
}
.fadeIn-enter-active,
.fadeIn-leave {
  opacity: 1;
}
.fadeIn-enter,
.fadeIn-leave-active {
  opacity: 0;
}
</style>
