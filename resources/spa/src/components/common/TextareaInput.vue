<template>
  <div :class="{'auto-height': !rows}" class="textarea-wrap">
    <div v-if="!rows" class="textarea-shadow c-textarea-input">{{ value }}</div> <!-- 用于撑起文本框自适应高度 -->
    <textarea
      ref="textarea"
      :value="value"
      :placeholder="placeholder"
      :maxlength="maxlength"
      :readonly="readonly"
      :rows="rows ? rows : 1"
      class="c-textarea-input"
      @input="$emit('input', $event.target.value)" />
    <span v-show="maxlength && value.length > warnlength" class="word-length">{{ value.length }} / {{ maxlength }}</span>
  </div>
</template>

<script>
export default {
  name: "TextareaInput",
  props: {
    value: { type: String, default: "" },
    readonly: { type: Boolean, default: false },
    maxlength: { type: [Number, String], default: null },
    warnlength: { type: [Number, String], default: null },
    placeholder: { type: String, default: "" },
    rows: { type: [Number, String], default: 0 }
  }
};
</script>

<style lang="less" scoped>
.textarea-wrap {
  position: relative;
  width: 100%;
  height: calc(~"100% + 40px");
  padding-right: 20px;
  align-self: flex-start;

  .textarea-shadow {
    opacity: 0;
    min-height: 100px;
    word-wrap: break-word;
    word-break: break-all;
  }

  textarea {
    position: absolute;
    top: 0;
    display: block;
    font-size: 30px;
    padding-bottom: 28px;
    width: calc(~"100% - 20px");
    height: 100%;
    overflow: hidden;
    resize: none;
    outline: none;
    min-height: 100px;
    background-color: transparent;
    word-wrap: break-word;
    word-break: break-all;

    &[readonly] {
      color: #999;
      cursor: not-allowed;
    }
  }

  .word-length {
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
