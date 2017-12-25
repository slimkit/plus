<template>
  <router-link class="label label-info module-question-topic" :to="`/topics/${topic.id}`">{{ topic.name }}</router-link>
</template>

<script>
export default {
  name: 'module-question-topic',
  props: { topic: Object, required: true }
}
</script>

<style>
.module-question-topic {
  display: inline-block;
  display: inline-grid;
  margin: .2em;
}
</style>
