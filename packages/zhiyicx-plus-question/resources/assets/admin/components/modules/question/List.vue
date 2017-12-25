<template>
  <table class="table table-hover table-responsive">

    <thead>
      <tr>
        <th># ID</th>
        <th>标题</th>
        <th>发布者</th>
        <th>关注数量</th>
        <th>回答数量</th>
        <th>评论数量</th>
        <th>话题</th>
        <th>发布时间</th>
        <th>修改时间</th>
        <th>类型</th>
        <th>金额</th>
        <th>操作</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="question in questions">
        <td>{{ question.id }}</td>
        <td><ui-show-more :content="question.subject"></ui-show-more></td>
        <td>{{ question.user ? `${question.user.name} (${question.user_id})` : question.user_id }}</td>
        <td>{{ question.watchers_count }}</td>
        <td>{{ question.answers_count }}</td>
        <td>{{ question.comments_count }}</td>
        <td>
          <module-question-topic v-for="topic in question.topics" :topic="topic" :key="question.id"></module-question-topic>
        </td>
        <td>{{ question.created_at | localDate }}</td>
        <td>{{ question.updated_at | localDate }}</td>
        <td>{{ question.automaticity | questionType(question.amount) }}</td>
        <td>¥{{ question.amount / 100 | money }}</td>
        <td v-if="isTrash">
          <ui-process-button type="button" class="btn btn-warning btn-sm" @click="handleRestore($event, question)">
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
            
          <!-- 精选 -->
          <template v-if="question.excellent">
            <ui-process-button type="button" class="btn btn-primary btn-sm" @click="handleExcellent($event, question, 0)">
              <template slot-scope="{ processing }">
                <template v-if="processing">
                  <ui-loading></ui-loading>
                  撤销精选中...
                </template>
                <template v-else>撤销精选</template>
              </template>
            </ui-process-button>
          </template>
          <template v-else>
            <ui-process-button type="button" class="btn btn-primary btn-sm" @click="handleExcellent($event, question, 1)">
              <template slot-scope="{ processing }">
                <template v-if="processing">
                  <ui-loading></ui-loading>
                  设置精选中...
                </template>
                <template v-else>精选</template>
              </template>
            </ui-process-button>
          </template>

          <!-- 编辑 -->
          <router-link append :to="`${question.id}`" tag="button" type="button" class="btn btn-info btn-sm">编辑</router-link>

          <!-- 删除 -->
          <ui-process-button type="button" class="btn btn-danger btn-sm" @click="handleDelete($event, question)">
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
import Topic from './Topic';
export default {
  name: 'module-question-list',
  components: { 'module-question-topic': Topic },
  filters: {
    questionType (automaticity, amount) {
      if (automaticity) {
        return '邀请悬赏';
      }

      if (amount) {
        return '公开悬赏';
      }

      return '普通';
    }
  },
  props: {
    questions: { type: Array, required: true },
    updateQuestion: { type: Function, required: true },
    publishMessage: { type: Function, required: true }
  },
  computed: {
    isTrash() {
      const { trash } = this.$route.query;

      return !! trash;
    }
  },
  methods: {
    /**
     * 设置精选／撤销精选。
     *
     * @param {Object} event
     * @param {Object} question
     * @param {Number} excellent
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleExcellent(event, question, excellent) {

      const { stopProcessing = () => {} } = event;
      excellent = parseInt(excellent);

      admin.patch(`/questions/${question.id}`, { excellent }, {
        validateStatus: status => status === 204
      }).then(() => {
        this.updateQuestion(question.id, { ...question, excellent });
        stopProcessing();
      }).catch(({ response: { data = {} } = {} } = {}) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    },

    /**
     * 删除问题.
     *
     * @param {Object} { stopProcessing }
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleDelete({ stopProcessing = () => {} }, question) {
      if (confirm('确定要删除吗？')) {
        admin.delete(`/questions/${question.id}`, {
          validateStatus: status => status === 204
        }).then(() => {
          stopProcessing();
          this.updateQuestion(question.id, {}, true);
        }).catch(({ response: { data } = {} } = {}) => {
          stopProcessing();
          this.publishMessage(data, 'danger');
        });
      }
    },

    /**
     * 还原一个问题.
     *
     * @param {Object} { stopProcessing }
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleRestore({ stopProcessing = () => {} }, question) {
      admin.put(`/questions/${question.id}`, {}, {
        validateStatus: status => status === 204
      }).then(() => {
        stopProcessing();
        this.updateQuestion(question.id, {}, true);
      }).catch(({ response: { data } = {} } = {}) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    }
  }
};
</script>
