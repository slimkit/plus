<template>
  <div :id="id">
    <VTabs
      v-if="navs.length > 0"
      :class="{fixed}"
      :value="curTab"
      @change="handleTabChange"
    >
      <VTab
        v-for="({ path, title }, index) in navs"
        v-if="path"
        :key="index"
        :title="title"
        :value="path"
      />
    </VTabs>
    <Transition :name="transitionName">
      <KeepAlive>
        <RouterView />
      </KeepAlive>
    </Transition>
  </div>
</template>

<script>
import vTab from './tab.vue'
import vTabs from './tabs.vue'

export default {
  name: 'NavTabs',
  components: {
    vTab,
    vTabs,
  },
  props: {
    fixed: { type: Boolean, default: false },
    id: { type: String, default: '' },
    navs: { type: Array, required: true },
    value: { type: Object, required: true },
  },
  data () {
    return {
      curTab: this.value,
      transitionName: 'router-fade-in-left',
    }
  },
  watch: {
    value (val) {
      this.curTab = val
    },
    $route (to, from) {
      if (to.meta.index < from.meta.index) {
        this.transitionName = 'router-fade-in-left'
      } else {
        this.transitionName = 'router-fade-in-right'
      }
    },
  },
  methods: {
    handleTabChange (val) {
      this.curTab = val
      this.$router.push({
        path: val,
      })
      this.$emit('input', val)
    },
  },
}
</script>

<style src='./tabs.less' lang='less'>
</style>
