<style lang="css" module>
    .container {
        padding: 15px;
    }
    .loadding {
        text-align: center;
        font-size: 42px;
    }
    .loaddingIcon {
        animation-name: "TurnAround";
        animation-duration: 1.4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
</style>

<template>
    <div :class="$style.container">
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
                    <tr v-show="loadding">
                        <!-- 加载动画 -->
                        <td :class="$style.loadding" colspan="6">
                            <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                        </td>
                    </tr>
                    <template v-if="conversations.length">
                        <tr v-for="conversation in conversations">
                            <td>{{ conversation.id }}</td>
                            <td>{{ conversionTypeDisplay(conversation.type) }}</td>
                            <td>{{ conversation.user.name }}</td>
                            <td>{{ conversation.to_user_id }}</td>
                            <td>{{ conversation.content }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm">删除</button>
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
const FeedbackComponent = {
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
      }
    }),
    methods: {
      getConversations () {
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

        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
          this.loadding = false;
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
      getQueryParams () {
        let query = '?';
        query += 'type=' + this.queryParams.type;
        query += '&perPage=' + this.paginate.perPage;
        query += '&page=' + this.paginate.currentPage;
        return query;
      },
      nextPage () {
        if (this.paginate.lastPage > this.paginate.currentPage) {
          this.paginate.currentPage += 1; 
          this.getConversations();
        }
      },
      prevPage () {
        if (this.paginate.currentPage > 1) {
          this.paginate.currentPage -= 1; 
          this.getConversations();
        }
      },
    },
    created () {
      this.getConversations();
    },

};
export default FeedbackComponent;
</script>
