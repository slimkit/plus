<template>
  <div class="panel panel-default">

    <!-- Title -->
    <div class="panel-heading">基础设置</div>

    <!-- Body -->
    <div class="panel-body form-horizontal">

      <!-- 开关 -->
      <div class="form-group">
        <label class="col-sm-2 control-label">开启付费</label>
        <div class="col-sm-4">
          <div class="radio">
            <label>
              <input type="radio" name="open" value="0" v-model="paidOpen"> 关闭
            </label>
          </div>
          <div class="radio">
            <label>
              <input type="radio" name="open" value="1" v-model="paidOpen"> 开启
            </label>
          </div>
        </div>
        <span class="col-sm-6 help-block">如果需要启动内容付费，则开启，设置关闭则关闭全部付费</span>
      </div>

    </div>

  </div>
</template>

<script>
export default {
  data: () => ({
    paidOpen: 0
  })
};
</script>
