<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  padding-top: 100px;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
.areaTab{
  margin-bottom: 10px;
}
</style>

<template>

  <div class="container-fluid" :class="$style.container">
  <ul class="nav nav-tabs" :class="$style.areaTab">
    <router-link to="/setting/area" tag="li" active-class="active">
      <a href="#">地区管理</a>
    </router-link>
    <router-link to="/setting/hots" tag="li" active-class="active" exact>
      <a href="#">热门城市</a>
    </router-link>
  </ul>

    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
      <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
    <!-- 整体盒子 -->
    <div v-if="!message" v-show="!loadding">

      <!-- 提示 -->
      <div class="alert alert-success" role="alert">
        <p>添加：直接输入地区名以空格分开， 例如：中国 四川省 成都市</p>
      </div>

      <!-- 列表表格 -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th>名称</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="item in list">
              <td>
                <div class="input-group">{{item}}</div>
              </td>
              <td>
                <button type="button" class="btn btn-danger btn-sm" @click.prevent="deleteArea(item)">删除</button>
              </td>
            </tr>
            <tr>
              <td>
                <div class="input-group">
                  <input v-model="add.content" type="text" class="form-control" placeholder="输入名称">
                </div>
              </td>
              <td>
                <button v-if="!add.loadding" @click.prevent="doHotsArea" type="button" class="btn btn-primary btn-sm">添加</button>
                <button v-else class="btn btn-primary btn-sm" disabled="disabled">
                  <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                </button>
                <span :class="`text-${add.type}`">{{ add.message }}</span>
              </td>
            </tr>
        </tbody>
      </table>
    </div>
    <!-- Loading Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ message }}</div>
        <button type="button" class="btn btn-primary" @click.stop.prevent="request">刷新</button>
      </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const HotsComponent = {

  data: () => ({
    loadding: false,
    message: '',
    add: {
      content: '',
      message: '',
      update: 0,
      loadding: false,
      type: 'muted'
    },
    list: {}
  }),
  methods: {
    doHotsArea () {
      this.add.loadding = true;
      const data = {
        content: this.add.content,
        update: this.add.update
      };
      request.post(
          createRequestURI('site/areas/hots'),
          data,
          { validateStatus: status => status === 201 }
        ).then(({ data: { message = '提交成功', status = '' } }) => {
          const index = this.list.indexOf(this.add.content);
          this.add.loadding = false;
          this.add.type = 'success';
          if (status == 1 && (index < 0)) {
            this.list.push(this.add.content);
            this.add.message = message;
          } else {
            this.add.message = '已存在此地区';
          }
          if (status == 2) {
            if (index > -1) {
              this.list.splice(index, 1);
            }
            this.add.message = '删除成功';
          }
        }).catch(({ response: { data: { error: [ error = '提交失败' ] = [] } = {} } = {} }) => {
          this.add.loadding = false;
          this.add.type = 'danger';
          this.add.message = error;
      });
    },
    deleteArea (area) {
      if (window.confirm('确认删除?')) {
        this.add.content = area;
        this.add.update = 1;
        this.doHotsArea();
      }
    },
    request() {
      this.loadding = true;
      request.get(
        createRequestURI('site/areas/hots'),
        { validateStatus: status => status === 200 }
      ).then(({ data: { data = {} } }) => {
        this.loadding = false;
        this.list = data;
      }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} }) => {
        this.loadding = false;
        this.message = message;
      });
    }
  },
  created() {
    window.setTimeout(() => this.request(), 500);
  }
};

export default HotsComponent;
</script>
