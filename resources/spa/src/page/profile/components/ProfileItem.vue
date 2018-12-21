<template>
  <li class="m-entry" @click="onClick">
    <svg class="m-style-svg m-svg-def m-entry-prepend">
      <use :xlink:href="icon" />
    </svg>
    <span class="m-text-box m-flex-grow1">{{ label }}</span>
    <span class="m-entry-extra"><slot /></span>
    <svg class="m-style-svg m-svg-def m-entry-append">
      <use xlink:href="#icon-arrow-right" />
    </svg>
  </li>
</template>

<script>
export default {
  name: 'ProfileItem',
  props: {
    label: { type: String, default: '' },
    icon: { type: String, required: true },
    to: { type: [String, Object], default: null },
  },
  methods: {
    onClick () {
      if (this.to) this.$router.push(this.to)
      this.$emit('click')
    },
  },
}
</script>
