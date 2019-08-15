<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      投稿配置
    </div>
    <div class="panel panel-body">
      <div class="form-group">
        <label class="col-sm-2 control-label">开启认证投稿</label>
        <div class="col-sm-6">
          <label class="radio-inline">
            <input type="radio" v-model="contribute.verified" @change="contributeChange" :value="true"> 开启
          </label>
          <label class="radio-inline">
            <input type="radio" v-model="contribute.verified" @change="contributeChange" :value="false"> 关闭
          </label>
        </div>
        <div class="col-sm-4">
          <span class="help-block">开启后只允许认证用户投稿</span>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">开启付费投稿</label>
        <div class="col-md-6">
          <label class="radio-inline">
            <input type="radio" v-model="contribute.pay" @change="contributeChange" :value="true"> 开启
          </label>
          <label class="radio-inline">
            <input type="radio" v-model="contribute.pay" @change="contributeChange" :value="false"> 关闭
          </label>
        </div>
        <div class="col-md-4">
          <span class="help-block">开启后投稿将扣取相应费用</span>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">投稿金额 <b class="text-danger">(单位：分)</b></label>
        <div class="col-md-6">
          <input type="number" maxlength="7" class="form-control" id="title" aria-describedby="title-help-block"
                 placeholder="请输入投稿金额" @blur="pay_contributeChange" v-model.number.trim="pay_contribute">
        </div>
        <div class="col-md-4">
          <span class="help-block">开启后投稿将扣取相应费用</span>
        </div>
      </div>
      <ui-alert :type="message.type" v-show="message.open">
        {{ message.data | plusMessageFirst('') }}
      </ui-alert>
    </div>
  </div>
</template>
<script>
    import { admin } from '../../axios/'

    export default {
    name: 'news-setting',
    data () {
      return ({
        contribute: {
          verified: false,
          pay: false
        },
        pay_contribute: null,

        message: {
          open: false,
          type: '',
          data: {}
        }
      })
    },
    methods: {

      publishMessage (data, type, ms = 5000) {
        this.message = { open: true, data, type }
        setTimeout(() => {
          this.message.open = false
        }, ms)
      },
      contributeChange () {

        admin.patch(`/news/config/contribute`, {
          contribute: this.contribute
        }, {
          validateStatus: state => state === 204
        }).then(() => {
          this.publishMessage({ message: '配置成功' }, 'success')
        }).catch(err => {
          console.log(err)
          this.publishMessage({ message: '配置失败' }, 'error')
        })
      },
      pay_contributeChange () {

        const pay_contribute = this.pay_contribute

        if (pay_contribute < 1 || pay_contribute > 9999999) {
          this.publishMessage({ input: '投稿金额最小为 1，最大为 9999999' }, 'error')
          return false
        }

        if (Number.isNaN(+pay_contribute)) {
          return
        }
        admin.patch(`/news/config/pay_contribute`, {
          pay_contribute
        }, {
          validateStatus: state => state === 204
        }).then(() => {
          this.publishMessage({ message: '配置成功' }, 'success')
        }).catch(err => {
          console.log(err)
          this.publishMessage({ message: '配置失败' }, 'error')
        })
      }
    },
    created () {
      admin.get(`/news/config`).then((({ data: { contribute = {}, pay_contribute = 100 } = {} }) => {

        this.contribute = { ...this.contribute, ...contribute }
        this.pay_contribute = pay_contribute
      })).catch(err => {
        console.log(err)
        this.publishMessage({ message: '获取配置信息失败' }, 'error')
      })
    }
  }
</script>
