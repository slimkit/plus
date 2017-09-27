<template>
    <div style="padding: 15px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="form-inline">
                <div class="form-group">
                    <label>类型： </label>
                    <select class="form-control" v-model="queryParams.type">
                        <option v-for="type in types" :value="type.alias">{{ type.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-default" @click="handleSearch">搜索</button>
                </div>
                <div class="form-group pull-right">
                    <ul class="pagination" style="margin: 0;">
                      <li :class="paginate.currentPage <= 1 ? 'disabled' : null">
                        <a href="javascript:;" aria-label="Previous" @click.stop.prevent="prevPage">
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>
                      <li :class="paginate.currentPage >= paginate.lastPage ? 'disabled' : null">
                        <a href="javascript:;" aria-label="Next" @click.stop.prevent="nextPage">
                          <span aria-hidden="true">&raquo;</span>
                        </a>
                      </li>
                    </ul>
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
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 加载 -->
                    <table-loading :loadding="loadding" colspanNum="6"></table-loading>
                    <template v-if="conversations.length">
                        <tr v-for="conversation in conversations">
                            <td>{{ conversation.id }}</td>
                            <td>{{ conversionTypeDisplay(conversation.type) }}</td>
                            <td>{{ conversation.user.name }}</td>
                            <td>{{ conversation.to_user_id }}</td>
                            <td>{{ conversation.content }}</td>
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
          </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import plusMessageBundle from 'plus-message-bundle';
import tableLoading from '../common/TableLoading';
const FeedbackComponent = {
    components: {
      tableLoading
    },
    data: () => ({
      loadding: true,
      conversations: {},
      paginate: {
        perPage: 20,
        lastPage: 10,
        currentPage:1,
      },
      types: [
         {name: '全部', alias: ''},
         {name: '意见反馈', alias: 'feedback'},
      ],
      queryParams: {
        type: '',
        keyword: '',
      },
      message: {
        error: null,
        success: null,
      }
    }),
    watch: {
      'queryParams.type' () {
        this.getConversations();
      },
      'paginate.currentPage' () {
        this.getConversations();
      }
    },
    methods: {
      getConversations () {
        this.loadding = true;
        this.conversations = [];
        request.get(
          createRequestURI('conversations' + this.getQueryParams()),
          { validateStatus: status => status === 200 }
        ).then(response => {
            this.loadding = false;
            this.conversations = {}; 
          
            const { data: data, current_page: currentPage, last_page: lastPage, total: total } = response.data;
            this.paginate.currentPage = currentPage;
            this.paginate.lastPage = lastPage;
            this.paginate.total = total;
            this.conversations = data;

        }).catch(({ response: { data: { errors = ['加载会话列表失败'] } = {} } = {} }) => {
          this.loadding = false;
          let Message = new plusMessageBundle(errors);
          this.message.error = Message.getMessage();
        });
      },
      conversionTypeDisplay (type) {
        for (let i=0; i < this.types.length; i++) {
           if (type == this.types[i].alias) {
             return this.types[i].name;
           }
        }
      },
      handleSearch () {
        this.paginate.currentPage = 1;
        this.getConversations();
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
            let Message = new plusMessageBundle(errors);
            this.message.error = Message.getMessage();
          });
        }
      },
      getQueryParams () {
        let query = '?';
        query += 'type=' + this.queryParams.type;
        query += '&perPage=' + this.paginate.perPage;
        query += '&page=' + this.paginate.currentPage;
        return query;
      },
      byIdDeleteConversation(id) {
        this.conversations.forEach((item, index) => {
          if (item.id == id) {
            this.conversations.splice(index, 1)
          }
        });
      },
      nextPage () {
        if (this.paginate.lastPage > this.paginate.currentPage) {
          this.paginate.currentPage += 1; 
        }
      },
      prevPage () {
        if (this.paginate.currentPage > 1) {
          this.paginate.currentPage -= 1; 
        }
      },
      offAlert () {
        this.message.error = null;
        this.message.success = null;
      }
    },
    created () {
      this.getConversations();
    },

};
export default FeedbackComponent;
</script>
