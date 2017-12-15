<template>
<div class="component-container container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      投稿配置
    </div>
    <div class="panel-body">
      <div class="form-horizontal">
        <div class="col-md-8 col-md-offset-2">
          <!-- 认证 -->
          <div class="form-group">
            <label class="control-label col-md-2">开启认证投稿</label>
            <div class="col-md-6">
              <label class="radio-inline">
                <input type="radio"
                       v-model="contribute.verified"
                       @change="contributeChange"
                       :value="true"> 开启
              </label>
              <label class="radio-inline">
                <input type="radio"
                       v-model="contribute.verified"
                       @change="contributeChange"
                       :value="false"> 关闭
              </label>
            </div>
            <div class="col-md-4">
              <span class="help-block">开启后只允许认证用户投稿</span>
            </div>
          </div>
          <!-- /认证 -->
          <!-- 付费 -->
          <div class="form-group">
            <label class="control-label col-md-2">开启付费投稿</label>
            <div class="col-md-6">
              <label class="radio-inline">
                <input type="radio"
                       v-model="contribute.pay"
                       @change="contributeChange"
                       :value="true"> 开启
              </label>
              <label class="radio-inline">
                <input type="radio"
                       v-model="contribute.pay"
                       @change="contributeChange"
                       :value="false"> 关闭
              </label>
            </div>
            <div class="col-md-4">
              <span class="help-block">开启后投稿将扣取相应费用</span>
            </div>
          </div>
          <!-- /付费 -->
          <!-- 金额 -->
          <div class="form-group">
            <label class="control-label col-md-2">投稿金额 <b class="text-danger">(单位：分)</b></label>
            <div class="col-md-6">
              <input type="tel"
                     maxlength="7"
                     class="form-control"
                     id="title"
                     aria-describedby="title-help-block"
                     placeholder="请输入投稿金额"
                     @blur="pay_contributeChange"
                     v-model.number.trim="pay_contribute">
            </div>
            <div class="col-md-4">
              <span class="help-block">开启后投稿将扣取相应费用</span>
            </div>
          </div>
          <!-- /金额 -->
        </div>
      </div>
    </div>
  </div>
  <ModelTips ref="modelTips" />
</div>

</template>

<script>
import request, { createRequestURI } from '../../util/request';

import Layer from '../Layer/Layer.vue';
export default {
  name: 'NewsSetting',
  data() {
    return ({
      open: false,
      contribute: {
        verified: false,
        pay: false
      },
      pay_contribute: null
    })
  },
  methods: {

    //  显示提示信息
    showTips(type, mesg) {
      if (type && mesg) {
        this.$refs.modelTips.show({
          type,
          mesg
        })
      }
    },
    // 清除提示
    clearTips() {
      this.$refs.modelTips.clear()
    },

    contributeChange() {

      let param = Object.keys(this.contribute).filter(k => {
        return this.contribute[k] === true
      })

      request.patch(`/news/admin/news/config/contribute`, {
        contribute: param
      }, {
        validateStatus: state => state === 204
      }).then(({status}) => {
        status && this.showTips('info', '配置成功')
      }).catch(({response: {data: {message: [err = '操作成功!'] = []} = {}}}) => {
        err && this.showTips('error', err)
      })
    },
    pay_contributeChange() {

      const pay_contribute = this.pay_contribute

      if (pay_contribute < 1 || pay_contribute > 9999999) {
        this.showTips('error', '投稿金额最小为 1，最大为 9999999')
        return
      }

      if (Number.isNaN(+pay_contribute)) {
        return
      }
      request.patch(`/news/admin/news/config/pay_contribute`, {
        pay_contribute
      }, {
        validateStatus: state => state === 204
      }).then(({status}) => {
        status && this.showTips('info', '配置成功')
      }).catch(({response: {data: {message: [err = '操作失败!'] = []} = {}}}) => {
        err && this.showTips('error', err)
      })
    }
  },
  created() {
    request.get(`/news/admin/news/config`)
      .then((({data: {contribute = [], pay_contribute = 100} = {}}) => {
        contribute.forEach((key) => {
          this.contribute[key] = true
        })

        this.pay_contribute = pay_contribute
      }))
      .catch(({response: {data: {message: [err = '获取系统配置信息失败'] = []} = {}}}) => {
        err && this.showTips('error', err)
      })
  }
}

</script>
