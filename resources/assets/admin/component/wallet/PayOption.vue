<style lang="scss" module>
  .input {
    padding-top: 16px;
  }
  .labelBox {
    display: block;
    .label {
      display: inline-block;
      font-size: 14px;
      margin: 5px;
      padding: .4em .8em .5em;
      &:first-child {
        margin-left: 0;
      }
      &:last-child {
        margin-right: 0;
      }
    }
    .add {
      @extend .label;
      cursor: pointer;
    }
  }
  .labelDelete {
    color: #bf5329;
    cursor: pointer;
  }
  .addLabel {
    padding-top: 16px;
  }
  .alert {
    margin: 22px 0 0;
  }
</style>

<template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <!-- title -->
      <div class="panel-heading">基础设置 - 充值选项</div>

      <!-- Body -->
      <div  class="panel-body">
        <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
      </div>
    </div>

  </div>
</template>
