<style lang="css" module>
  .containerAround {
    animation-name: "TurnAround";
    animation-duration: 1.6s;
    animation-timing-function: linear;
    animation-direction: alternate;
    animation-iteration-count: infinite;
  }
</style>
<template>
  <div  class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <div class="panel-heading">
        后台登录页面配置
      </div>
      <div class="panel-body">
        <loading :loadding="loadding"></loading>
        <form class="form-horizontal" @submit.prevent="submit" v-show="!loadding">
          <!-- Site name. -->
          <div class="form-group">
            <label for="back-end-logo" class="col-sm-2 control-label">页面logo</label>
            <div class="col-sm-6">
              <post-cover :img="logo_src" v-model="logo_src"></post-cover>
            </div>
            <span class="col-sm-4 help-block" id="back-end-logo-help-block"></span>
          </div>
          <!-- End site name. -->         

          <!-- Button -->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button v-if="btnLoading" class="btn btn-primary" disabled="disabled">
                <span class="glyphicon glyphicon-refresh" :class="$style.containerAround"></span>
              </button>
              <button v-else-if="error" @click.prevent="requestSiteInfo" class="btn btn-danger">{{ error_message }}</button>
              <button v-else type="submit" class="btn btn-primary">{{ message }}</button>
            </div>
          </div>
          <!-- End button -->
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import PostCover from './PostCover';
const backEndSetting = {
  components:{
    PostCover
  },
  data: () => ({
    btnLoading: false,
    loadding: true,
    error: false,
    error_message: '重新加载',
    message: '提交',
    logo_src: 0
  }),
  watch:{
    logo_src(val){
        console.log(val)
    }
  },
  computed: {
  },
  methods: {
    getLogo () {

        request.get('/admin/site/background').then(({ data:{ logo_src } }) =>{
            this.logo_src = logo_src;
            this.loadding = false
        })
    },
    submit () {
        request.patch('/admin/site/background',{
            logo_src: this.logo_src
        }).then(data=>{
            console.log('修改成功')
        })
    }
  },
  created () {
    this.getLogo();
  }
};

export default backEndSetting;
</script>
