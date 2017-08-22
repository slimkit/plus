<style lang="scss">

$lefyNavWidth: 240px;

.app-container {
  .left-nav {
    position: fixed;
    width: $lefyNavWidth;
    height: 100%;
    padding-top: 12px;
    background: #263238;
    z-index: 1;
    overflow: hidden;
    overflow-x: hidden;
    overflow-y: auto;
    .user-avatar {
      width: 64px;
      height: 64px;
      background: #ededed;
    }
    .username-btn {
      width: 100%;
      background: none;
      border: none;
      color: #eceff1;
      outline: none;
      &:active {
        box-shadow: none;
      }
    }
  }
  .context-container {
    width: 100%;
    padding-left: $lefyNavWidth;
  }
}
</style>

<template>
  <div class="app-container clearfix">
    <div class="left-nav pull-left">

      <!-- User avatar. -->
      <img v-if="user.avatar" class="img-responsive img-circle center-block user-avatar" :src="user.avatar">
      <default-avatar v-else class="img-responsive img-circle center-block user-avatar" />
      <!-- End user avatar. -->

      <!-- Username and dropdown menu. -->
      <div class="dropdown">
        <button class="btn dropdown-toggle username-btn" type="button" id="userDropdownMune" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{ user.name }}
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdownMune">
          <li>
            <a href="/" target="_blank">
              <span class="glyphicon glyphicon-new-window"></span>
              打开前台
            </a>
          </li>
          <li role="separator" class="divider"></li>
          <li>
            <a href="/auth/logout" >
              <span class="glyphicon glyphicon-log-in"></span>
              退出登录
            </a>
          </li>
        </ul>
      </div>
      <!-- End username and dropdown menu. -->

      <system-nav />

    </div>
    <!-- The content container. -->
    <router-view class="pull-right context-container"></router-view>
    <!-- End content container. -->
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { USER } from '../store/getter-types';

import DefaultAvatar from '../icons/default-avatar';

// components.
import Nav from './Nav';

const home = {
  computed: {
    ...mapGetters([
      USER
    ]),
    user () {
      return this[USER];
    }
  },
  components: {
    'system-nav': Nav,
    'default-avatar': DefaultAvatar
  }
};

export default home;
</script>
