<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- 短信记录面板 -->
      <div class="panel-heading">
        <div class="input-group" style="
          max-width: 356px;
        ">
          <div class="input-group-btn">
            <!-- 状态 -->
            <button type="button" class="btn btn-default dropdown-toggle" id="state" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">状态 <span class="caret"></span></button>
            <ul class="dropdown-menu" aria-labelledby="state">
              <li><a href="#">全部状态</a></li>
              <li><a href="#">未发送</a></li>
              <li><a href="#">发送成功</a></li>
              <li><a href="#">发送失败</a></li>
            </ul>
          </div>
          <input type="text" class="form-control" aria-label="input-group-btn" placeholder="输入要搜索的手机号码" v-model="search.keyword">
          <div class="input-group-btn">
            <button class="btn btn-primary" type="submit">搜索</button>
          </div>
        </div>
      </div>
      <!-- Table -->
      <table class="table table-hove">
        <thead>
          <tr>
            <th>手机号码</th>
            <th>验证码</th>
            <th>状态</th>
            <th>时间</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>18781993582</td>
            <td>1234</td>
            <td>未发送</td>
            <td>2017-12.1.d</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
const SmsMainComponent = {
  data: () => ({
    search: {
      state: -1,
      keyword: '',
    }
  }),
};

export default SmsMainComponent;
</script>
