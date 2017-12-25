<template>
  <table class="table table-hover table-responsive">

    <thead>
      <tr>
        <th># ID</th>
        <th>评论者</th>
        <th>资源类型</th>
        <th>内容</th>
        <th>时间</th>
        <th>操作</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="comment in comments">
        <td>{{ comment.id }}</td>
        <td>{{ comment.user.name }} ({{ comment.user_id }})</td>
        <td>{{ comment.commentable_type | commentable }}</td>
        <td><ui-show-more :content="comment.body"></ui-show-more></td>
        <td>{{ comment.created_at | localDate }}</td>
        <td>
          <!-- 删除 -->
          <ui-process-button type="button" class="btn btn-danger btn-sm" @click="handleDelete($event, comment)">
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
  name: 'module-comment-list',
  props: {
    comments: { type: Array, required: true },
    handleRemove: { type: Function, required: true },
    handleMessage: { type: Function, required: true },
  },
  filters: {
    commentable(type) {
      if (type === 'questions') {
        return '问题';
      }

      if (type === 'question-answers') {
        return '回答';
      }

      return '未知';
    }
  },
  methods: {
    handleDelete({ stopProcessing = () => {} }, comment) {
      if (confirm('确定要删除吗？')) {
        admin.delete(`/comments/${comment.id}`, {
          validateStatus: status => status === 204,
        }).then(() => {
          this.handleRemove(comment.id);
          this.handleMessage({ message: '删除成功！' }, 'success');
          stopProcessing();
        }).catch(({ response: { data = { message: '删除失败' } } = {} }) => {
          this.handleMessage(data, 'danger');
          stopProcessing();
        });
      }
    },
    test() {
      $('#element').popover('show');
    }
  },
  created () {
  
  } 
};
</script>
