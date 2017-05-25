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
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- title -->
      <div class="panel-heading">基础设置 - 充值选项</div>

      <!-- Loading -->
      <div v-if="loadding.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="loadding.status === 1" class="panel-body">
        <blockquote>
          <p>设置充值选项可以让用户在充值页面快速选择充值金额(只能输入整数)，用户也可以选择输入自定义金额进行充值。</p>
          <footer>在使用 Apple Pay 充值是非常好的选择，因为苹果支付有这样要的要求。</footer>
        </blockquote>

        <!-- 选项组 -->
        <div :class="$style.labelBox">
          <span class="label label-info" :class="$style.label" v-for="label in labels">
            {{ label / 100 }}
            <span :class="$style.labelDelete" title="删除" aria-hidden="true" @click="deleteLabel(label)">&times;</span>
          </span>
          <span class="label label-danger" :class="$style.add" v-show="add.inputStatus === false" @click="openAddInput">
            <span class="glyphicon glyphicon-plus"></span> 添加
          </span>
        </div>

        <!-- 添加按钮 -->
        <div class="input-group" :class="$style.addLabel" v-show="add.inputStatus === true">
          <input type="number" min="1" class="form-control" placeholder="输入新的选项" v-model="add.value" :disabled="add.adding">
          <span class="input-group-btn">
            <button v-if="add.adding === false" class="btn btn-success" type="button" @click="addLabel">
              <span class="glyphicon glyphicon-plus"></span> 添加
            </button>
            <button v-else class="btn btn-success" type="button" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span> 添加...
            </button>
          </span>
        </div>

        <!-- 警告框 -->
        <div v-show="alert.open" :class="['alert', `alert-${alert.type}`, $style.alert]" role="alert">
          {{ alert.message }}
        </div>

        <!-- 删除 充值选项 -->
        <div v-show="del.open" class="alert alert-danger" :class="$style.alert" role="alert">
          <p>是否删除 「<strong>{{ del.label / 100 }}</strong>」 选项？</p>
          <button type="button" class="btn btn-default" :disabled="del.ing" @click="unDeleteLabel">取消</button>
          <button v-if="del.ing === false" type="button" class="btn btn-primary" @click="sendDeleteLabel">删除</button>
          <button v-else type="button" class="btn btn-primary" disabled="disabled">
            <span class="glyphicon glyphicon-refresh component-loadding-icon"></span> 删除...
          </button>
        </div>

      </div>

      <!-- Loading Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ loadding.message }}</div>
        <button type="button" class="btn btn-primary" @click="requestLabel">刷新</button>
      </div>

    </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';
export default {
  data: () => ({
    loadding: {
      status: 0,
      message: '',
    },
    labels: [],
    add: {
      inputStatus: false,
      adding: false,
      value: ''
    },
    alert: {
      open: false,
      interval: null,
      type: 'info',
      message: null,
    },
    del: {
      label: null,
      open: false,
      ing: false,
    }
  }),
  methods: {
    openAddInput() {
      this.add.inputStatus = true;
    },
    addLabel() {
      let { value: label } = this.add;
      label = parseInt(label * 100);

      if (! label) {
        this.sendAlert('danger', '输入选项不能为空！');
        return;
      } else if (isNaN(label)) {
        this.sendAlert('danger', '输入的选项存在错误字符');
        return;
      } else if (this.labels.indexOf(label) !== -1) {
        this.sendAlert('danger', '输入的选项已经存在');
        return ;
      }

      this.add.adding = true;

      request.post(
        createRequestURI('wallet/labels'),
        { label },
        { validateStatus: status => status === 201 }
      ).then(() => {
        this.add = {
          adding: false,
          inputStatus: false,
          value: ''
        };
        this.labels = [
          ...this.labels,
          label
        ];
        this.sendAlert('success', '创建选项成功!');
      }).catch(({ response: { data: { message = [], label = [] } = {} } = {} }) => {
        const [ currentMessage = '创建失败，请检查网络！' ] = [ ...message, ...label ];
        this.add.adding = false;
        this.sendAlert('danger', currentMessage);
      });
    },
    sendAlert(type, message) {
      window.clearInterval(this.alert.interval);
      this.alert = {
        ...this.alert,
        type,
        message,
        open: true,
        interval: window.setInterval(() => {
          window.clearInterval(this.alert.interval);
          this.alert.open = false;
        }, 2000)
      };
    },
    requestLabel() {
      this.loadding.status = 0;
      request.get(
        createRequestURI('wallet/labels'),
        { validateStatus: status => status === 200 }
      ).then(({ data = [] }) => {
        this.labels = lodash.reduce(data, function (labels, label) {
          labels.push(parseInt(label));

          return labels;
        }, []);
        this.loadding.status = 1;
      }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
        this.loadding = {
          status: 2,
          message: message || '加载失败!'
        };
      });
    },
    sendDeleteLabel() {
      this.del.ing = true;
      const { label } = this.del;
      request.delete(
        createRequestURI(`wallet/labels/${label}`),
        { validateStatus: status => status === 204 }
      ).then(() => {
        this.del = {
          open: false,
          ing: false,
          label: null,
        };
        this.labels = lodash.reduce(this.labels, function (labels, item) {
          if (item !== label) {
            labels.push(item);
          }

          return labels;
        }, []);
        this.sendAlert('success', '删除成功!');
      }).catch(({ response: { data: { message = [] } = {} } = {} }) => {
        const [ currentMessage = '删除失败！' ] = message;
        this.del.ing = false;
        this.sendAlert('danger', currentMessage);
      });
    },
    unDeleteLabel() {
      if (this.del.ing) {
        return false;
      }

      this.del = {
        ing: false,
        open: false,
        label: null
      };
    },
    deleteLabel(label) {
      this.del = {
        open: true,
        ing: false,
        label
      };
    }
  },
  created() {
    this.requestLabel();
  }
};
</script>
