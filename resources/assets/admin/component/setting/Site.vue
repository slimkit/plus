<style lang="css" module>
    .container {
        padding: 15px;
    }
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
    .help-block {
      font-size: 12px !important;
    }
</style>

<template>
<div :class="$style.container">
    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
        <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
    <div class="panel panel-default" v-show="!loadding">
      <div class="panel-heading">
        站点配置
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <div class="col-md-10">
            <div class="form-group">
              <label class="control-label col-md-2">站点状态</label>
              <div class="col-md-7">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on" v-model="site.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off" v-model="site.status"> 关闭
                </label>
              </div>
              <div class="col-md-3">
                <span class="help-block" >站点开启与关闭，请谨慎操作</span>
              </div>
            </div>
            <div class="form-group"  v-show="!site.status">
              <label class="control-label col-md-2">关闭原因</label>
              <div class="col-md-7">
                <input type="text" class="form-control" v-model="site.off_reason">
              </div>
              <div class="col-md-3">
                <span class="help-block">站点关闭，需要填写关闭原因</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">APP端</label>
              <div class="col-md-7">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on" v-model="site.app.status" :disabled="!site.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off" v-model="site.app.status" :disabled="!site.status"> 关闭
                </label>
              </div>
              <div class="col-md-3">
                <span class="help-block" >APP端开启与关闭</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">H5端</label>
              <div class="col-md-7">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on" v-model="site.h5.status" :disabled="!site.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off" v-model="site.h5.status" :disabled="!site.status"> 关闭
                </label>
              </div>
              <div class="col-md-3">
                <span class="help-block">H5端开启与关闭</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">金币</label>
              <div class="col-md-7">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on"  v-model="site.gold.status" :disabled="!site.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off" v-model="site.gold.status" :disabled="!site.status"> 关闭
                </label>
              </div>
              <div class="col-md-3">
                <span class="help-block">启动规则，用户完成相应的节点操作可以获取对应的奖励<br/>关闭规则，用户完成相应的节点操作不能获取对应的奖励</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">打赏</label>
              <div class="col-md-7">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on"  v-model="site.reward.status" :disabled="!site.status"> 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off"  v-model="site.reward.status" :disabled="!site.status"> 关闭
                </label>
              </div>
              <div class="col-md-3">
                <span class="help-block">用户打赏开启与关闭</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">预留呢称</label>
              <div class="col-md-7">
                <input type="text" class="form-control" v-model="site.reserved_nickname">
              </div>
              <div class="col-md-3">
                <span class="help-block">预留呢称，多个呢称用“,”分割</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">客户邮箱</label>
              <div class="col-md-7">
                <input type="text" class="form-control" v-model="site.client_email">
              </div>
              <div class="col-md-3">
                <span class="help-block">客户邮箱</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">用户邀请模版</label>
              <div class="col-md-7">
                <textarea class="form-control" v-model="site.user_invite_template">
                </textarea>
              </div>
              <div class="col-md-3">
                <span class="help-block">用户邀请模版</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2"></label>
              <div class="col-md-7">
                <button class="btn btn-primary btn-block" 
                @click.prevent="updateSiteConfigure" 
                data-loading-text="提交中" 
                autocomplete="off" id="submit-btn">确认</button>
              </div>
              <div class="col-md-3">
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
import plusMessageBundle from 'plus-message-bundle';
const Site = {
    
    data: () => ({
        loadding: true,
        radio: {
          on: true,
          off: false,
        },
        site: {
          status: true,
          off_reason: '',
          app: {
            status: true,
          },
          h5: {
            status: true,
          },
          gold: {
            status: true,
          },
          reward: {
            status: true,
          },
          reserved_nickname: '',
          client_email: '',
          user_invite_template: '',
        },
        message: {
          error: null,
          success: null,
        }
    }),
    methods: {
      getSiteConfigures () {
        this.loadding = true;
        request.get(
          createRequestURI('site/configures'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.site = response.data;
        }).catch(({ response: { data: { errors = ['加载站点配置失败'] } = {} } = {} }) => {
          this.loadding = false;
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
        });
      },
      updateSiteConfigure () {
        if (!this.validate()) {
          this.message.error = '请填写关闭站点的原因';
          return;
        }
        $("#submit-btn").button('loading');
        request.put(
          createRequestURI('update/site/configure'),
          { site: this.site },
          { validateStatus: status => status === 201 }
        ).then(({ data: { message: [ message ] = [] } }) => {
          $("#submit-btn").button('reset');
          this.message.success = message;
        }).catch(({ response: { data = {} } = {} }) => {
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
        });
      },
      validate () {
        let site = this.site;
        return (site.status && !site.off_reason) ? false : true;
      },
    },
    created () {
      this.getSiteConfigures();
    },
};
export default Site;
</script>