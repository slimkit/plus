<style lang="scss" module>
  .input {
    padding-top: 16px;
  }
  .labelBox {
    display: block;
    .label {
      font-size: 14px;
      margin: auto 5px;
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
      <!-- Body -->
      <div class="panel-body">
        <blockquote>
          <p>设置充值选项可以让用户在充值页面快速选择充值金额(只能输入整数)，用户也可以选择输入自定义金额进行充值。</p>
          <footer>在使用 Apple Pay 充值是非常好的选择，因为苹果支付有这样要的要求。</footer>
        </blockquote>

        <!-- 选项组 -->
        <div :class="$style.labelBox">
          <span class="label label-info" :class="$style.label" v-for="label in labels">
            {{ label }}元
            <span :class="$style.labelDelete" title="删除" aria-hidden="true">&times;</span>
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

      </div>
    </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
export default {
  data: () => ({
    labels: [10, 20, 30],
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
  }),
  methods: {
    openAddInput() {
      this.add.inputStatus = true;
    },
    addLabel() {
      let { value } = this.add;
      value = parseInt(value);

      if (! value) {
        this.sendAlert('danger', '输入选项不能为空！');

        return;
      } else if (this.labels.indexOf(value) !== -1) {
        this.sendAlert('danger', '输入的选项已经存在');
      }

      this.add.adding = true;

      // console.log(this.labels.indexOf(value) !== -1);
      // return;

      // this.add.adding = true;
      // window.setTimeout(() => {
      //   this.add.adding = false;
      //   this.labels = [
      //     ...this.labels,
      //     this.add.value,
      //   ];
      //   this.sendAlert('success', '添加成功！');
      // }, 1500);
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
    }
  }
};
</script>
