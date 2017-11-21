<template>
    <div class="container-fluid" style="margin:15px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="form-inline">
                <div class="form-group">
                    <label>类型： </label>
                    <select class="form-control" v-model="filterQueryParams.type">
                        <option v-for="type in types" :value="type.alias">{{ type.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>关键词： </label>
                    <input type="text" class="form-control" v-model="filterQueryParams.keyword" placeholder="内容">
                </div>
                <div class="form-group">
                  <router-link class="btn btn-default" tag="button" :to="{ path: '/conversations', query: filterQueryParams }">
                    搜索
                  </router-link>
                </div>
            </div>
          </div>
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>会话类型</th>
                        <th>会话用户</th>
                        <th>被会话用户</th>
                        <th>会话内容</th>
                        <th>会话时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 加载 -->
                    <table-loading :loadding="loadding" :colspan-num="7"></table-loading>
                    <template v-if="conversations.length">
                        <tr v-for="conversation in conversations">
                            <td>{{ conversation.id }}</td>
                            <td>{{ conversionTypeDisplay(conversation.type) }}</td>
                            <td>{{ conversation.user ? conversation.user.name : '' }}</td>
                            <td>{{ conversation.target ? conversation.target.name : '' }}</td>
                            <td>{{ conversation.content }}</td>
                            <td>{{ conversation.created_at | localDate }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" 
                                @click.prevent="delConversation(conversation.id)">删除</button>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr class="text-center" v-show="!loadding"><td colspan="6">无数据</td></tr>
                    </template>
                </tbody>
            </table>
            <!-- 分页 -->
            <div class="text-center">
              <offset-paginator class="pagination" :total="total" :offset="offset" :limit="15">
                <template slot-scope="pagination">
                  <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
                    <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
                    <router-link v-else :to="offsetPage(pagination.offset)">{{ pagination.page }}</router-link>
                  </li>
                </template>
              </offset-paginator>
            </div>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';

const FeedbackComponent = {
    data: () => ({
      loadding: true,
      conversations: [],
      total: 0,
      types: [
        { name: '全部', alias: '' },
        { name: '意见反馈', alias: 'feedback' },
        { name: '系统消息', alias: 'system' },
      ],
      filterQueryParams: {
        type: '',
        keyword: '',
      },
      message: {
        error: null,
        success: null,
      }
    }),
    computed: {
      offset () {
        const { query: { offset = 0 } } = this.$route;
        return parseInt(offset);
      },
      searchQuery () {
        return { ...this.filterQueryParams, offset: 0 };
      },
    },
    watch: {
      '$route' ($route) {
        this.total = 0;
        this.getConversations({ ...$route.query });
      },
    },
    methods: {
      getConversations (query = {}) {
        this.loadding = true;
        this.conversations = [];
        request.get(
          createRequestURI('conversations'),
          { 
            validateStatus: status => status === 200,
            params: { ...query, limit: 15 }, 
          }
        ).then(({ data = [], headers: { 'x-conversation-total': total } }) => {
          this.loadding = false;
          this.conversations = data;
          this.total = parseInt(total);
        }).catch(({ response: { data: { errors = ['加载会话列表失败'] } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = plusMessageFirst(errors);
        });
      },
      conversionTypeDisplay (type) {
        for (let i=0; i < this.types.length; i++) {
           if (type == this.types[i].alias) {
             return this.types[i].name;
           }
        }
      },
      delConversation (id) {
        let bool = confirm('是否确认删除？');
        if (bool) {
          request.delete(
            createRequestURI(`conversations/${id}`),
            { validateStatus: status => status === 204 }
          ).then(response => {
            this.message.success = '删除成功';
            this.byIdDeleteConversation(id);
          }).catch(({ response: { data: { errors = ['删除失败'] } = {} } = {} }) => {
            this.message.error = plusMessageFirst(errors);
          });
        }
      },
      byIdDeleteConversation(id) {
        this.conversations.forEach((item, index) => {
          if (item.id == id) {
            this.conversations.splice(index, 1)
          }
        });
      },
      offsetPage(offset) {
        return { path: '/conversations', query: { ...this.filterQueryParams, offset } };
      },
    },
    created () {
      this.getConversations(this.$route.query);
    },
};
export default FeedbackComponent;
</script>
