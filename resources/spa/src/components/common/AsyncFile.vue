<template>
  <div>
    <slot :src="src">{{ null }}</slot>
  </div>
</template>

<script>
export default {
  name: 'AsyncFile',
  props: {
    file: { required: true, type: Number },
    w: { type: Number, default: 0 },
    h: { type: Number, default: 0 },
    q: { type: Number, default: 0 },
  },
  data: () => ({
    src: null,
  }),
  created () {
    this.fetch()
  },
  methods: {
    fetch () {
      let params = {
        json: true,
      }

      if (this.w) {
        params.w = this.w
      }

      if (this.h) {
        params.h = this.h
      }

      if (this.q) {
        params.q = this.q
      }

      this.$http
        .get(`/files/${this.file}`, {
          params,
        })
        .then(
          ({ data: { url } }) => {
            this.src = url
          },
          () => {
            this.src = ''
          }
        )
    },
  },
}
</script>
