<template>
  <div class="p-topic-create">
    <CommonHeader>
      {{ topicId ? '编辑话题' : '创建话题' }}
      <span
        slot="left"
        class="primary"
        @click="beforeBack"
      >
        取消
      </span>
      <span
        slot="right"
        class="create-btn"
        :class="{active: !disabled}"
        @click="beforeSubmit"
        v-text="topicId ? '保存' : '创建'"
      />
    </CommonHeader>

    <div v-if="loading" class="m-pos-f m-spinner" />

    <main>
      <form class="form" onsubmit="return false">
        <div
          v-if="!src"
          class="coverage"
          @click="$refs.uploader.select()"
        >
          <svg class="m-style-svg m-svg-big">
            <use xlink:href="#icon-camera" />
          </svg>
          上传话题封面
        </div>
        <div
          v-else
          class="coverage"
          :style="{'background-image': `url(${src})`}"
          @click="$refs.uploader.select()"
        >
          <div class="decor">
            <svg class="m-style-svg m-svg-big">
              <use xlink:href="#icon-camera" />
            </svg>
            更换封面
          </div>
        </div>
        <label class="title">
          <input
            v-model="name"
            type="text"
            maxlength="10"
            :disabled="topicId"
            placeholder="输入话题标题，10字以内（必填）"
          >
        </label>
        <label class="description">
          <TextareaInput
            v-model="desc"
            type="text"
            placeholder="简单介绍一下话题内容"
            :maxlength="50"
            :rows="4"
          />
        </label>
      </form>
      <p class="tips">话题创建成功后，标题不可更改</p>
    </main>

    <ImageUploader
      ref="uploader"
      v-model="node"
      type="storage"
      :ratio="800/350"
      @update:src="src = $event"
    />
  </div>
</template>

<script>
import * as api from '@/api/topic'
import ImageUploader from '@/components/common/ImageUploader'
import TextareaInput from '@/components/common/TextareaInput'

export default {
  name: 'TopicCreate',
  components: {
    ImageUploader,
    TextareaInput,
  },
  data () {
    return {
      name: '',
      node: null,
      desc: '',
      src: '',
      pending: false,
      loading: false,

      // 用于判断修改话题是是否有字段变更
      origin: {
        desc: '',
        src: null,
      },
    }
  },
  computed: {
    topicId () {
      return this.$route.params.topicId || false
    },
    disabled () {
      return !this.name || !['desc', 'src'].some(key => this.$data[key] !== this.origin[key])
    },
  },
  created () {
    if (this.topicId) this.fetchTopic()
  },
  methods: {
    fetchTopic () {
      this.loading = true
      api.getTopicDetail(this.topicId)
        .then(({ data }) => {
          this.loading = false
          this.name = this.origin.name = data.name
          this.desc = this.origin.desc = data.desc
          this.src = this.origin.src = data.logo.url
        })
    },
    beforeSubmit () {
      if (this.topicId) this.onUpdate()
      else this.onCreate()
    },
    onCreate () {
      if (this.disabled || this.pending) return
      this.pending = true
      const params = {
        name: this.name,
        desc: this.desc,
        logo: this.node,
      }
      api.createTopic(params)
        .then(({ data }) => {
          const { id, need_review: needReview } = data
          if (needReview) {
            this.$Message.success('创建成功，请等待审核')
            return this.goBack()
          }
          this.$router.push({ name: 'TopicDetail', params: { topicId: id } })
        })
        .finally(() => {
          this.pending = false
        })
    },
    onUpdate () {
      if (this.disabled || this.pending) return
      this.pending = true
      const params = {}
      if (this.desc !== this.origin.desc) params.desc = this.desc
      if (this.node) params.logo = this.node
      api.editTopic(this.topicId, params)
        .then(() => {
          this.$Message.success('修改成功')
          this.$router.push({ name: 'TopicDetail', params: { id: this.topicId } })
        })
        .finally(() => {
          this.pending = false
        })
    },
    beforeBack () {
      const isModified = this.topicId
        ? !this.disabled
        : (this.title || this.desc || this.node)
      if (!isModified) return this.goBack()
      const actions = [
        {
          text: '放弃创建',
          method: () => void this.goBack(),
        },
      ]
      this.$bus.$emit('actionSheet', actions, '取消', '确认取消创建话题？')
    },
  },
}
</script>

<style lang="less" scoped>
.p-topic-create {
  background-color: #fff;
  height: 100%;

  .primary {
    color: @primary;
  }

  .create-btn {
    color: @gray;

    &.active {
      color: @primary;
    }
  }

  .coverage {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 380px;
    color: #b3b3b3;
    background: #ededed no-repeat center;
    background-size: cover;

    .m-svg-big {
      margin-right: 10px;
      width: 56px;
      height: 56px;
    }

    .decor {
      position: absolute;
      bottom: 30px;
      left: 30px;
      color: #000;
      opacity: 0.2;
      letter-spacing: 1px;/*no*/
    }
  }

  .form {
    display: flex;
    flex-direction: column;

    .title {
      height: 120px;
      padding: 0 20px;
      border-bottom: 1px solid @border-color; /* no */

      input {
        font-size: 36px;
        height: 100%;
        width: 100%;
        font-weight: bold;
      }
    }

    .description {
      padding: 20px 20px 10px;
      border-bottom: 1px solid @border-color; /* no */
    }
  }

  .tips {
    position: fixed;
    bottom: 30px;
    left: 30px;
    color: #999;
    font-size: 26px;

    &::before {
      content: '* ';
      color: @error;
    }
  }
}
</style>

<style lang="less">
.p-topic-create {
  .description .textarea-wrap {
    .word-length {
      position: absolute;
      top: calc(100% + 20px);
      right: 0px;
    }
  }
}
</style>
