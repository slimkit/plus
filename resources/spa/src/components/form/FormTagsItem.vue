<template>
  <section class="c-form-item c-form-tags-item" @click="switchTags">
    <label>{{ label }}</label>
    <div class="input-wrap">
      <span v-if="value.length === 0" class="placeholder">
        {{ placeholder }}
      </span>
      <div v-else class="m-tag-list m-tags">
        <span
          v-for="tag in value"
          :key="tag.id"
          class="m-tag"
          v-text="tag.name"
        />
      </div>
      <svg v-if="!readonly" class="m-style-svg m-svg-def m-entry-append">
        <use xlink:href="#icon-arrow-right" />
      </svg>
    </div>
  </section>
</template>

<script>
export default {
  name: 'FormTagsItem',
  props: {
    value: { type: Array, default: () => [] },
    label: { type: String, default: '标签' },
    readonly: { type: Boolean, default: false },
    placeholder: { type: String, default: '选择标签' },
  },
  methods: {
    switchTags () {
      if (this.readonly) return
      const chooseTags = this.value.map(t => t.id)
      const nextStep = tags => {
        this.$emit('input', tags)
      }
      const onSelect = tagId => {
        this.$emit('select', tagId)
      }
      const onRemove = tagId => {
        this.$emit('delete', tagId)
      }
      this.$bus.$emit('choose-tags', {
        chooseTags,
        nextStep,
        onSelect,
        onRemove,
      })
    },
  },
}
</script>

<style lang="less" scoped>
@import url("./formItem.less");

.c-form-item.c-form-tags-item {
  min-height: 100px;
  height: auto;

  .input-wrap {
    padding: 28px 20px 28px 0;

    > svg {
      flex: none;
    }
  }
}
</style>
