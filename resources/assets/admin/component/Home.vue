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
      <img class="img-responsive img-circle center-block user-avatar" src="http://medz.cn/windid/attachment/avatar/000/00/00/1_middle.jpg">

      <div class="dropdown">
        <button class="btn dropdown-toggle username-btn" type="button" id="userDropdownMune" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
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
            <a href="#">
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
