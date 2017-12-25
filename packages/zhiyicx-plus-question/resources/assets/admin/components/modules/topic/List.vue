<template>
  <table class="table table-hover table-responsive">
      
    <thead>
      <tr>
        <th># ID</th>
        <th>名称</th>
        <th>描述</th>
        <th>成员数</th>
        <th>专家数</th>
        <th>问题数</th>
        <th>状态</th>
        <th>权重排序</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>操作</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="topic in topics" :key="topic.id">
        <td>{{ topic.id }}</td>
        <td>{{ topic.name }}</td>
        <td>{{ topic.description }}</td>
        <td>{{ topic.follows_count }}</td>
        <td>{{ topic.experts_count }}</td>
        <td>{{ topic.questions_count }}</td>
        <td>
          <span class="text-success" v-if="!topic.status">开启</span>
          <span class="text-danger" v-else>关闭</span>
        </td> 
        <th class="col-md-1"><input type="number" :value="topic.sort" class="form-control" @change="handleTopicSort($event.target.value, topic.id)" style="text-align:center;"></th>
        <td>{{ topic.created_at | localDate }}</td>
        <td>{{ topic.updated_at | localDate }}</td>
        <td class="col-md-2">
          
          <!-- 查看 -->
          <router-link tag="button" class="btn btn-warning btn-sm" :to="`/topics/${topic.id}`">
            查看
          </router-link>

          <!-- 编辑 -->
          <router-link tag="button" class="btn btn-primary btn-sm" :to="`/topics/${topic.id}/edit`">
            编辑
          </router-link>

          <ui-process-button type="button" class="btn btn-sm" :class="{ 'btn-primary': topic.status, 'btn-danger': !topic.status }" @click="handleOpen($event, topic.id)">
            <template slot-scope="{ processing }">
              <template v-if="processing">
                <ui-loading></ui-loading>
                提交中...
              </template>
              <template v-else>{{ !topic.status ? '关闭' : '开启' }}</template>
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
  name: 'module-topic-list',
  props: {
    topics: { type: Array, required: true },
    updateTopic: { type: Function, required: true },
    publishMessage: { type: Function, required: true }
  },
  methods: {
    /**
     * 话题排序.
     * 
     * @param  {int} sort
     * @param  {int} topicId
     * @return {void}
     */
    handleTopicSort (sort, topicId) {

      admin.patch(`topics/${topicId}/sort`,
        { sort: parseInt(sort) }, 
        { validateStatus: status => status === 204 }
      ).then(response => {
        this.updateTopic(topicId, sort);
      }).catch(({ response: { data } = {} }) => {
        this.loading = false;
        this.publishMessage(data, 'danger');
      });
    },
    /**
     * 关闭和开启话题.
     * 
     * @param  {Object} event
     * @param  {int} topicId
     * @return {void}
     */
    handleOpen(event, topicId) {

      const { stopProcessing = () => {} } = event;

      admin.patch(`topics/${topicId}/status`, {
        validateStatus: status => status === 204
      }).then(() => {
        stopProcessing();
        this.updateTopic(topicId, '', true);
      }).catch(({ response: { data = {} } = {} } = {}) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    },
  }
};
</script>
