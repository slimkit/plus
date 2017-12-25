<template>
  <table class="table table-hover table-responsive">

    <thead>
      <tr>
        <th># ID</th>
        <th>问题</th>
        <th>申请者</th>
        <th>审核者</th>
        <th>状态</th>
        <th>申请时间</th>
        <th>操作</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="excellence in excellences">
        <td># {{ excellence.id }}</td>
        <td>
        <ui-show-more :content="`${excellence.question ? excellence.question.id : 0}${excellence.question ? excellence.question.subject : null}`"></ui-show-more></td>
        <td>{{ excellence.user | user }}</td>
        <td>{{ excellence.examiner | user }}</td>
        <td>{{ excellence.status | status }}</td>
        <td>{{ excellence.updated_at | localDate }}</td>
        <td>
          
          <!-- 通过按钮 -->
          <ui-process-button v-if="excellence.status === 0" type="button" class="btn btn-primary btn-sm" @click="handlePass($event, excellence.id)">
            <template slot-scope="{ processing }">
              <template v-if="processing">
                <ui-loading></ui-loading>
                操作中...
              </template>
              <template v-else>通过</template>
            </template>
          </ui-process-button>

          <!-- 拒绝按钮 -->
          <ui-process-button v-if="excellence.status === 0" type="button" class="btn btn-danger btn-sm" @click="handleDeny($event, excellence.id)">
            <template slot-scope="{ processing }">
              <template v-if="processing">
                <ui-loading></ui-loading>
                操作中...
              </template>
              <template v-else>拒绝</template>
            </template>
          </ui-process-button>

        </td>
      </tr>
    </tbody>

  </table>
</template>

<script>
import { admin } from '../../../axios';
export default {
  name: 'module-excellence-list',
  props: {
    excellences: { type: Array, required: true },
    pass: { type: Function, required: true },
    deny: { type: Function, required: true },
    publishMessage: { type: Function, required: true },
  },
  filters: {
    status (status) {
      switch (status) {
        case 0:
          return '待推荐';

        case 1:
          return '已推荐';

        case 2:
          return '已驳回';
      }
    },
    user (user) {
      const { id, name } = user || {};

      if (! id) {
        return '';
      }

      return `${name} (${id})`;
    }
  },
  methods: {
    handlePass ({ stopProcessing = () => {} }, id) {
      admin.patch(`/application-records/${id}`, {}, {
        validateStatus: status => status === 204,
      }).then(() => {
        this.pass(id);
        this.publishMessage({ message: '通过成功' }, 'success');
        stopProcessing();
      }).catch(({ response: { data = {} } = {} }) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    },

    handleDeny ({ stopProcessing = () => {} }, id) {
      admin.delete(`/application-records/${id}`, {
        validateStatus: status => status === 204,
      }).then(() => {
        this.deny(id);
        stopProcessing();
      }).catch(({ response: { data = {} } = {} }) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    }
  }
};
</script>
