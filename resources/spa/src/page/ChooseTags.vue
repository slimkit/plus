<template>
  <Transition name="popr">
    <div
      v-if="show"
      class="m-box-model m-pos-f p-choose-tags m-main"
    >
      <CommonHeader :back="nextFuc">{{ $t('tags.select') }}</CommonHeader>

      <main class="m-box-model m-flex-grow1 m-flex-shrink1">
        <section class="m-flex-grow0 m-flex-shrink0 m-tags-group selected m-bb1">
          <span class="m-tags-label">{{ $t('tags.placeholder', [5, chooseTags.length]) }}</span>
          <TransitionGroup
            tag="ul"
            class="m-tags"
          >
            <li
              v-for="tag in chooseTags"
              v-if="tag.id"
              :key="`tags-selected-${tag.id}`"
              class="m-tag"
              @click="removeTag(tag)"
            >
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-clean" />
              </svg>
              <span>{{ tag.name }}</span>
            </li>
          </TransitionGroup>
        </section>
        <div
          class="m-flex-grow1 m-flex-shrink1"
          style="overflow-y: auto;"
        >
          <section
            v-for="(group, Gindex) in tags"
            :key="group.id"
            class="m-tags-group"
          >
            <span class="m-tags-label">{{ group.name }}</span>
            <TransitionGroup
              tag="ul"
              class="m-tags"
            >
              <li
                v-for="(tag, Tindex) in group.tags"
                v-if="tag.id"
                :key="tag.id"
                :class="{ selected: tag.selected }"
                class="m-tag"
                @click="addTag(tag, Gindex, Tindex)"
              >
                <span>{{ tag.name }}</span>
              </li>
            </TransitionGroup>
          </section>
        </div>
      </main>
    </div>
  </Transition>
</template>

<script>
import { noop } from '@/util'

/**
 * 打开选择标签页面 (钩子 -> "choose-tags")
 * @author mutoe <mutoe@foxmail.com>
 * @param {Object} options
 * @param {number[]} options.chooseTags 初始选择值, 只需传 [tag.id], eg: [1, 2, 3,...]
 * @param {Function} options.nextStep 点击下一步的回调, 注入已选择的 tags
 * @param {Function} options.onSelect 选择某个标签时执行的回调函数
 * @param {Function} options.onRemove 取消选择某个标签时执行的回调函数
 */
function onChooseTags ({ chooseTags = [], nextStep, onSelect, onRemove }) {
  this.isFirst = !this.$lstore.hasData('H5_CHOOSE_TAGS_FIRST')
  this.nextStep = nextStep || noop
  this.onSelect = onSelect || noop
  this.onRemove = onRemove || noop

  if (chooseTags && chooseTags.length > 0) {
    this.tags.forEach((g, Gindex) => {
      g.tags.forEach((t, Tindex) => {
        t.Gindex = Gindex
        t.Tindex = Tindex
        if (chooseTags.indexOf(t.id) > -1) {
          t.selected = true
          this.chooseTags.push(t)
        }
      })
    })
  }

  this.show = true
  this.scrollable = false

  // 首次进入标签选择页面时弹框提示
  if (this.isFirst && this.$route.name !== 'groupCreate') {
    this.$nextTick(() => {
      this.$bus.$emit('popupDialog', {
        title: this.$t('tags.first_tips.title'),
        content: this.$t('tags.first_tips.content'),
        onClose: () => {
          this.onReadTips()
        },
      })
    })
  }
}

export default {
  name: 'ChooseTags',
  data () {
    return {
      show: false,
      isFirst: this.$lstore.getData('H5_CHOOSE_TAGS_FIRST') || true,
      chooseTags: [],
      loading: false,
      tags: [],
    }
  },
  computed: {
    disabled () {
      return this.chooseTags.length === 0
    },
  },
  created () {
    this.fetchTags()

    // 注册钩子
    this.$bus.$on('choose-tags', onChooseTags.bind(this))
  },
  methods: {
    nextFuc () {
      this.nextStep(this.chooseTags)
      this.$nextTick(this.cancel)
    },
    nextStep: noop,
    onRemove: noop,
    onSelect: noop,
    fetchTags () {
      this.$http.get('/tags').then(({ data }) => {
        this.tags = data
      })
    },
    addTag (tag, Gindex, Tindex) {
      const obj = this.tags[Gindex].tags[Tindex]
      if (obj.selected) return

      if (this.chooseTags.length >= 5) { return this.$Message.error(this.$t('tags.max_tips', [5])) }

      const status = { selected: true, Gindex, Tindex }
      Object.assign(obj, status)
      this.chooseTags.push(obj)

      // emit hooks
      this.onSelect(tag.id)
    },
    removeTag (tag) {
      this.chooseTags.splice(this.chooseTags.indexOf(tag), 1)
      this.tags[tag.Gindex]['tags'][tag.Tindex].selected = false

      // emit hooks
      this.onRemove(tag.id)
    },

    cancel () {
      this.chooseTags.forEach(tag => {
        delete tag.Gindex
        delete tag.Tindex
        delete tag.selected
      })

      this.show = false
      this.chooseTags = []
      this.scrollable = true
    },

    onReadTips () {
      this.$lstore.setData('H5_CHOOSE_TAGS_FIRST', false)
    },
  },
}
</script>
<style lang="less" scoped>
.p-choose-tags {
  main {
    overflow-y: auto;
    height: calc(~"100vh - 90px");
  }
  .m-tags-group {
    padding: 0 30px;
    margin-top: 30px;
    &:last-of-type {
      padding-bottom: 30px;
    }
  }
  .m-tags-label {
    font-size: 26px;
    color: @text-color3;
  }

  .m-tags {
    margin-left: -30px;
    margin-top: 0;
    min-height: 90px;

    .m-svg-def {
      position: absolute;
      top: 0;
      left: 0;
      width: 30px;
      height: 30px;
      fill: #b3b3b3;
      transform: translate(-50%, -50%);
    }
  }

  .m-tag {
    position: relative;
    margin: 30px 0 0 30px;
    width: calc((1 / 3 * 100%) ~" - 30px");
    height: 60px;
    line-height: 60px;
    border-radius: 3px;
    &.selected {
      background-color: rgba(89, 182, 215, 0.15);
      color: @primary;
    }
    span {
      overflow: hidden;
      width: 100%;
      display: inline-block;
      white-space: nowrap;
      text-overflow: ellipsis;
      text-align: center;
    }
  }
}
</style>
