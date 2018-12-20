<template>
  <div :class="{'auto-height': !rows}" class="c-textarea-input">
    <div v-if="!rows" class="textarea-shadow textarea">
      {{ value }}
    </div> <!-- 用于撑起文本框自适应高度 -->
    <textarea
      ref="textarea"
      :value="value"
      :placeholder="placeholder"
      :maxlength="maxlength"
      :readonly="readonly"
      :rows="rows ? rows : 1"
      class="textarea"
      @input="onInput"
    />
    <span v-show="maxlength && value.length > warnlength" class="word-length">
      {{ value.length }} / {{ maxlength }}
    </span>
  </div>
</template>

<script>
export default {
  name: 'TextareaInput',
  props: {
    value: { type: String, default: '' },
    readonly: { type: Boolean, default: false },
    maxlength: { type: [Number, String], default: null },
    warnlength: { type: [Number, String], default: null },
    placeholder: { type: String, default: '' },
    rows: { type: [Number, String], default: 0 },
  },
  methods: {
    onInput ($event) {
      let content = $event.target.value
      // 解决 emoji 字符被用于 maxlength 时被算作1个字符的问题（而'😫'.length => 2）
      this.maxlength && (content = content.substr(0, Number(this.maxlength)))
      this.$emit('input', content)
    },
    focus () {
      this.$refs.textarea.focus()
    },
  },
}
</script>

<style lang="less" scoped>
.c-textarea-input {
  position: relative;
  width: 100%;
  margin-bottom: 40px;
  height: calc(~"100% + 40px");
  align-self: flex-start;

  .textarea {
    width: 100%;
    min-height: 100px;
    word-wrap: break-word;
    word-break: break-all;
    font-size: inherit;
    text-align: inherit;
    line-height: inherit;
  }

  .textarea-shadow {
    opacity: 0;
  }

  textarea {
    position: absolute;
    top: 0;
    display: block;
    height: 100%;
    overflow: hidden;
    resize: none;
    outline: none;
    background-color: transparent;

    &[readonly] {
      color: #999;
      cursor: not-allowed;
    }
  }

  .word-length {
    position: absolute;
    bottom: -34px;
    right: 10px;
    display: block;
    font-size: 22px;
    text-align: right;
  }

  &:not(.auto-height) {
    display: flex;
    flex-direction: column;

    textarea {
      position: relative;
      overflow: auto;
    }

    .word-length {
      margin-top: 0;
    }
  }
}
</style>
