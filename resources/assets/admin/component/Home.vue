<style lang="scss">

$lefyNavWidth: 240px;

.app-container {
  .left-nav {
    position: absolute;
    width: $lefyNavWidth;
    height: 100%;
    padding-top: 12px;
    background: #263238;
    z-index: 1;
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
      <img v-if="avatar" class="img-responsive img-circle center-block user-avatar" :src="avatar">
      <div v-else class="img-responsive img-circle center-block user-avatar"></div>

      <div class="dropdown">
        <button class="btn dropdown-toggle username-btn" type="button" id="userDropdownMune" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{ user.name }}
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdownMune">
          <li>
            <a href="#">
              <span class="glyphicon glyphicon-new-window"></span>
              打开前台
            </a>
          </li>
          <li>
            <a href="#">
              <span class="glyphicon glyphicon-off"></span>
              关闭后台
            </a>
          </li>
          <li role="separator" class="divider"></li>
          <li>
            <a :href="logout">
              <span class="glyphicon glyphicon-log-in"></span>
              退出登录
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="pull-right context-container">
      233333
    </div>
  </div>
</template>

<script>
import { createRequestURI, createAPI } from '../util/request';
import { mapGetters } from 'vuex';

const home = {
  data: () => ({
    logout: createRequestURI('logout')
  }),
  computed: {
    ...mapGetters([
      'user',
      'userDatas'
    ]),
    avatar () {
      let { avatar } = this.userDatas || {};

      if (typeof avatar === 'object') {
        return createAPI(`storages/${avatar.value}`);
      }

      return '';
    }
  }
};

export default home;
</script>
