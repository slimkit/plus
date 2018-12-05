<template>
  <div class="p-topic-create">
    <CommonHeader>
      创建话题
      <span
        slot="right"
        class="create-btn"
        :class="{active: !disabled}"
        @click="beforeCreate"
        v-text="'创建'"
      />
    </CommonHeader>

    <main>
      <form class="form" onsubmit="return false">
        <div
          v-if="!node"
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
        />
        <label class="title">
          <input
            type="text"
            maxlength="10"
            placeholder="输入话题标题，10字以内（必填）"
          >
        </label>
        <label class="description">
          <TextareaInput
            v-model="description"
            type="text"
            placeholder="简单介绍一下话题内容"
            :maxlength="50"
            :warnlength="30"
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
      @update:src="src = $event"
    />
  </div>
</template>

<script>
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
      title: '',
      node: null,
      description: '',
      src: '',
    }
  },
  computed: {
    disabled () {
      return false
    },
    form () {
      return {
        name: this.title,
        logo: this.node,
        desc: this.description,
      }
    },
  },
  methods: {
    beforeCreate () {
      if (this.disabled) return
    },
  },
}
</script>

<style lang="less" scoped>
.p-topic-create {
  background-color: #fff;
  height: 100%;

  .create-btn {
    color: @gray;

    &.active {
      color: @primary;
    }
  }

  .coverage {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 380px;
    color: #b3b3b3;
    background: #ededed no-repeat center;
    background-size: cover;

    .m-svg-big {
      margin-right: 20px;
      width: 56px;
      height: 56px;
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
      }
    }

    .description {
      padding: 0 20px;
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
