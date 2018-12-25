<template>
  <nav v-if="nav.length" class="c-nav-tab">
    <ul>
      <RouterLink
        v-for="item in nav"
        :key="item.name"
        :to="item.route"
        tag="li"
        replace
      >
        {{ item.label || item.name }}
      </RouterLink>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'NavTab',
  props: {
    nav: { type: Array, default: () => [] },
  },
}
</script>

<style lang="less" scoped>
.c-nav-tab {
  position: relative;
  display: flex;
  align-items: center;
  height: 90px;
  width: 100%;
  border-bottom: 1px solid @border-color; /* no */
  background-color: #fff;
  z-index: 10;

  > ul {
    display: flex;
    width: 100%;
    height: 100%;
    justify-content: space-around;

    > li {
      display: flex;
      align-items: center;
      height: 100%;
      padding: 0 0.5em;

      &.router-link-exact-active {
        border-bottom: 4px solid @primary;
      }
    }
  }
}
</style>
