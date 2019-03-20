<template>
  <section
    :class="`c-form-${type}-item`"
    class="c-form-item"
  >
    <label>{{ label }}</label>

    <!-- 普通文本输入框 -->
    <div
      v-if="type === 'input'"
      class="input-wrap"
    >
      <input
        :value="value"
        :maxlength="maxlength"
        :placeholder="placeholder"
        :readonly="readonly"
        type="text"
        @input="$emit('input', $event.target.value)"
      >
    </div>

    <!-- 多行文本输入框 -->
    <template v-else-if="type === 'textarea'">
      <TextareaInput
        v-model="textareaContent"
        :placeholder="placeholder"
        :maxlength="maxlength"
        :readonly="readonly"
        :warnlength="warnlength"
        :label="label"
      />
    </template>
  </section>
</template>

<script>
import TextareaInput from '@/components/common/TextareaInput.vue'

export default {
  name: 'FormInputItem',
  components: { TextareaInput },
  props: {
    value: { type: String, default: '' },
    label: { type: String, required: true },
    readonly: { type: Boolean, default: false },

    /**
     * 文本框类型
     * 只接受 'input' 'textarea'
     */
    type: {
      type: String,
      default: 'input',
      validator: value => {
        return ['input', 'textarea'].includes(value)
      },
    },
    maxlength: { type: [Number, String], default: null },
    warnlength: { type: [Number, String], default: null },
    placeholder: { type: String, default: '' },
  },
  data () {
    return {
      textareaContent: '',
    }
  },
  watch: {
    textareaContent (val) {
      this.$emit('input', val)
    },
  },
  mounted () {
    this.textareaContent = this.value || ''
  },
}
</script>

<style lang="less" scoped>
@import url("./formItem.less");

.c-form-input-item {
  .input-wrap {
    input {
      font-size: 30px;
      padding: 0;
      width: 100%;
      background-color: transparent;

      &[readonly] {
        color: #999;
        cursor: not-allowed;
      }
    }
  }
}

.c-form-textarea-item {
  height: auto !important;
  padding: 28px 20px;

  > label {
    align-self: flex-start;
  }

  + .c-form-item {
    .textarea-wrap {
      border-top: 1px solid @border-color; /* no */
    }
  }
}
</style>
