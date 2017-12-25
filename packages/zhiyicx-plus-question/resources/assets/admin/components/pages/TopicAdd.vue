<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      <router-link to="/topics">
        <span class="glyphicon glyphicon-menu-left"></span>
        返回
      </router-link>
    </div>

    <div class="panel-body">
      <div class="form-horizontal">

        <!-- name -->
        <div class="form-group">
          <label class="col-sm-2 control-label">话题</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" placeholder="输入话题" v-model="name">
          </div>
          <span class="col-sm-4 help-block">
            请输入需要创建的话题。
          </span>
        </div>

        <!-- 描述 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">描述</label>
          <div class="col-sm-6">
            <textarea class="form-control" placeholder="输入话题描述" v-model="description"></textarea>
          </div>
          <span class="col-sm-4 help-block">
            请输入话题描述。
          </span>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">

            <ui-process-button type="button" class="btn btn-primary" @click="handleSubmit">
              <template slot-scope="{ processing }">
                <template v-if="processing">
                  <ui-loading></ui-loading>
                  创建中...
                </template>
                <template v-else>创建话题</template>
              </template>
            </ui-process-button>

          </div>
        </div>

      </div>

      <ui-alert :type="message.type" v-show="message.open">
        {{ message.data | plusMessageFirst('操作失败！') }}
      </ui-alert>

    </div>

  </div>
</template>

<script>
import { admin } from '../../axios';
export default {
  name: 'topic-add',
  data: () => ({
    name: '',
    description: '',
    message: {
      open: false,
      type: '',
      data: {},
    },
    interval: null,
  }),
  methods: {
    publishMessage (data, type, ms = 3000) {
      clearInterval(this.interval);

      this.message = { open: true, type, data };
      this.interval = setInterval(() => {
        this.message.open = false;
      }, ms);
    },

    handleSubmit ({ stopProcessing = () => {} }) {
      admin.post('/topics', { name: this.name, description: this.description }, {
        validateStatus: status => status === 201,
      }).then(({ data }) => {
        this.$router.back();
        // console.log(data);
        // stopProcessing();
      }).catch(({ response: { data = { message: '创建失败' } } = {} }) => {
        this.publishMessage(data, 'danger');
        stopProcessing();
      });
    }
  }
};
</script>
