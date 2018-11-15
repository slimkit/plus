<template>

  <nav class="c-nav-tab">
    <ul>
      <router-link
        v-for="(label, name) in nav"
        :key="name"
        :to="{name: route.name, query: {[route.query]: name }}"
        tag="li"
        replace>
        {{ label }}
      </router-link>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "NavTab",
  props: {
    nav: { type: Object, required: true },
    route: { type: Object, required: true },
    default: { type: String, default: "" }
  }
};
</script>

<style lang="less" scoped>
.c-nav-tab {
  display: flex;
  align-items: center;
  height: 90px;
  width: 100%;
  border-bottom: 1px solid @border-color;
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

      &.router-link-exact-active {
        border-bottom: 4px solid @primary;
      }
    }
  }
}
</style>
