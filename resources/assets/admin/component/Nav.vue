<style lang="scss">
.app-nav {
  background-color: #37474f;
  width: 100%;
  min-height: 100%;
  padding-top: 16px;
  margin: 0;
  .__button {
    background: none;
    border: none;
    color: #a6aeb2;
    color: rgba(255, 255, 255, 0.56);
    padding: 16px 40px;
    font-weight: 500;
    &:last-child,
    &:first-child {
      border-radius: 0;
    }
    &:focus {
      outline: none;
      background: none;
      color: #a6aeb2;
      color: rgba(255, 255, 255, 0.56);
    }
    &:hover {
      background: #000;
      color: #fff;
    }
    .__icon {
      margin-right: 32px;
      color: #78909c;
    }
    .__icon-img {
      width: 14px;
      height: 14px;
      border: none;
      margin-right: 32px;
    }
  }
}
</style>

<template>
  <div class="list-group app-nav">
    <router-link class="list-group-item __button" to="/setting/base">
      <span class="glyphicon glyphicon-cog __icon"></span>
      系统
    </router-link>
    <router-link class="list-group-item __button" to="/users">
      <span class="glyphicon glyphicon-user __icon"></span>
      用户
    </router-link>
    <router-link class="list-group-item __button" v-for="({ name, icon }, component) in menus" :key="component" :to="`/component/${component}`">
      <img class="__icon-img" :src="icon">
      {{ name }}
    </router-link>
    <router-link
      class="list-group-item __button"
      v-for="rootName in vendorMenus"
      :key="rootName"
      :to="'/vendor/'+rootName"
    >
      {{ rootName }}
    </router-link>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import lodash from 'lodash';
import { FORM_ALL } from '../store/getter-types';

const menus = window.TS.menus || {};
const nav = {
  data: () => ({
    menus
  }),
  computed: {
    ...mapGetters({
      forms: FORM_ALL,
    }),
    vendorMenus() {
      return lodash.reduce(lodash.keys(this.forms), function (forms, name) {
        if (name != '系统' || name != '用户') {
          forms.push(name);
        }
        
        return forms;
      }, []);
    }
  },
  created() {
    console.log(this);
  }
};

export default nav;
</script>
