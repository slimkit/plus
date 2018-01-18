<style lang="css" module>
    .loadding {
        text-align: center;
        font-size: 42px;
        padding-top: 100px;
    }
    .loaddingIcon {
        animation-name: "TurnAround";
        animation-duration: 1.4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
    .image {
        max-width:200px;
        margin-bottom: 10px;
    }
</style>

<template>
<div class="container-fluid" style="margin:15px;">
    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
        <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
    <div class="panel panel-default" v-show="!loadding">
      <div class="panel-heading">
        金币类型添加
        <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/gold" role="button">
          返回
        </router-link>
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <div class="col-md-8 col-md-offset-2">
            <div class="form-group">
              <label class="control-label col-md-2">名称</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="type.name">
              </div>
              <div class="col-md-4">
                <span class="help-block">类型名称</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">单位</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="type.unit">
              </div>
              <div class="col-md-4">
                <span class="help-block">类型单位</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">状态</label>
              <div class="col-md-6">
                <label class="radio-inline">
                  <input type="radio" value="1" v-model="type.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" value="0" v-model="type.status"> 关闭
                </label>
              </div>
              <div class="col-md-4">
                <span class="help-block">状态开启与关闭</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2"></label>
              <div class="col-md-6">
                <button class="btn btn-primary btn-sm" data-loading-text="提交中..." id="submit-btn" @click.prevent="storeGoldType">确认</button>
              </div>
              <div class="col-md-4">
                 <span class="text-success"  v-show="message.success">{{ message.success }}</span>
                 <span class="text-danger" v-show="message.error">{{ message.error }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';
const AddGoldType = {
    
    data: () => ({

        loadding: false,

        type: {
          name: '',
          unit: '',
          status: 0,
        },

        message: {
          error: null,
          success: null,
        }


    }),

    methods: {

      storeGoldType () {
          this.resetMessage();
          let btn = $('#submit-btn');
          btn.button('loading');
          request.post(
            createRequestURI('gold/types'),
            { ...this.type },
            { validateStatus: status => status === 201 }
          ).then(({ data: { message: [ message ] = [] } }) => {
            btn.button('reset');
            this.message.success = message;
            let _vue = this;
            setTimeout(() => {
              _vue.$router.replace({ path: '/gold' });
            }, 500);
          }).catch(({ response: { data = {} } = {} }) => {
            btn.button('reset');
            this.message.error = plusMessageFirst(data);
          });
      },

      resetMessage () {
        let msg = this.message;
        msg.success = msg.error = null;
      }
    },

    created () {

    },

};
export default AddGoldType;
</script>