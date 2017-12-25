<template>
  <table class="table table-hover table-responsive">

    <!-- Head -->
    <thead>
      <tr>
        <th># ID</th>
        <th>所属问题</th>
        <th>回答者</th>
        <th>内容</th>
        <th>评论数量</th>
        <th>喜欢数量</th>
        <th>发布时间</th>
        <th>修改时间</th>
        <th>类型</th>
        <th>打赏金额</th>
        <th>操作</th>
      </tr>
    </thead>

    <!-- body -->
    <tbody>
        
      <tr v-for="answer in answers" :key="answer.id">
        <td>{{ answer.id }}</td>
        <td>{{ answer.question ? answer.question.subject : '' }}</td>
        <td>{{ answer.user.name }}（{{ answer.user.id }}）</td>
        <td>
          <ui-show-more :content="answer.body"></ui-show-more>
        </td>
        <td>{{ answer.comments_count }}</td>
        <td>{{ answer.likes_count }}</td>
        <td>{{ answer.created_at | localDate }}</td>
        <td>{{ answer.updated_at | localDate }}</td>
        <td>{{ answer.adoption | answerType(answer.invited) }}</td>
        <td>¥{{ answer.rewards_amount / 100 | money }}</td>
        <td v-if="isTrash">
          
          <ui-process-button type="button" class="btn btn-warning btn-sm" @click="handleRestore($event, answer)">
            <template slot-scope="{ processing }">
              <template v-if="processing">
                <ui-loading></ui-loading>
                还原中...
              </template>
              <template v-else>还原</template>
            </template>
          </ui-process-button>

        </td>
        <td v-else>
          
          <!-- 删除 -->
          <ui-process-button type="button" class="btn btn-danger btn-sm" @click="handleDelete($event, answer)">
            <template slot-scope="{ processing }">
              <template v-if="processing">
                <ui-loading></ui-loading>
                删除中...
              </template>
              <template v-else>删除</template>
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
  name: 'module-answer-list',
  props: {
    answers: { type: Array, required: true },
    updateAnswer: { type: Function, required: true },
    publishMessage: { type: Function, required: true }
  },
  filters: {
    answerType(adoption, invited) {
      if (invited) {
        return '邀请悬赏';
      }

      if (adoption) {
        return '被采纳';
      }

      return '普通回答';
    }
  },
  computed: {
    isTrash() {
      const { trash } = this.$route.query;

      return !! trash;
    }
  },
  methods: {
    handleDelete({ stopProcessing = () => {} }, answer) {
      if (confirm('确定要删除吗？')) {
        admin.delete(`/answers/${answer.id}`, {
          validateStatus: status => status === 204,
        }).then(() => {
          stopProcessing();
          this.updateAnswer(answer.id, {}, true);
        }).catch(({ response: { data = {} } = {} } = {}) => {
          stopProcessing();
          this.publishMessage(data, 'danger');
        });
      }
    },
    handleRestore({ stopProcessing = () => {} }, answer) {

      admin.put(`/answers/${answer.id}`, {}, {
        validateStatus: status => status === 204
      }).then(() => {
        stopProcessing();
        this.updateAnswer(answer.id, {}, true);
      }).catch(({ response: { data = {} } ={} } = {}) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });

    }
  }
};
</script>
