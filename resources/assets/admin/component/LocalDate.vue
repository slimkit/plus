<template>
  <span> {{ date }} </span>
</template>

<script>
export default {

  props: {
    utc: {
      type: String, required: true
    }
  },

  computed: {

    date() {
      return (new Date(`${this.utc}Z`)).toLocaleString();
    }

  }

};
</script>
