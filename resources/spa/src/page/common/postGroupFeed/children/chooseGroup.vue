<template>
  <transition name="router-fadeInRight" mode="out-in">
    <div class="choose-group">
      <head-top :go-back="close" :title="`选择圈子`"/>
      <div/>
      <div class="choose-group-list">
        <div
          v-for="group in groups"
          v-if="group.id"
          :key="group.id"
          :class="{active: selected.id === group.id}"
          class="choose-group-item ellipsis"
          @click="selectGroup(group)">{{ group.name }}</div>
      </div>
    </div>
  </transition>
</template>

<script>
import HeadTop from "@/components/HeadTop";
export default {
  name: "ChooseGroup",
  components: {
    HeadTop
  },
  props: {
    value: { type: Object, default: () => {} }
  },
  data() {
    return {
      groups: [],
      selected: 0
    };
  },
  mounted() {
    this.selected = this.value;
    this.getAllGroups();
  },
  methods: {
    close() {
      this.$emit("on-close");
      this.selected && this.$emit("input", this.selected);
    },
    selectGroup(group) {
      this.selected = group;
      this.close();
    },
    async getAllGroups() {
      const { data } = await this.$http.get(
        `/plus-group/user-groups?type=allow_post`
      );
      this.groups = data ? [...data] : [];
    }
  }
};
</script>

<style lang='less' scoped>
.choose-group {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: #fff;
  z-index: 110;
  &-list {
    margin-top: 40px;
    padding-left: 20px;
    padding-right: 20px;
    width: 100%;
    display: flex;
    flex-wrap: wrap;
  }

  &-item {
    height: 60px;
    width: calc(~"25% - 10px");
    margin: 0 10px 10px 0;
    padding: 0 20px;
    line-height: 60px;
    border-radius: 4px;
    /*no*/
    background: #f4f5f5;
    font-size: 28px;
    text-align: center;
    &.active {
      background-color: #d9eef6;
    }
  }
}
</style>
