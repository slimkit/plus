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
            <router-link to="/setting/sensitive-words/add" class="btn btn-success">添加敏感词</router-link>
          </div>
          <div class="panel-heading">
            <div class="form-inline">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Search for...">
                  <button class="btn btn-default" type="button" @click.prevent="search">搜索</button>
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
          <div class="panel-heading">
            <div v-show="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" @click.prevent="offAlert">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ errorMessage }}
            </div>
            <div v-show="successMessage" class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" @click.prevent="offAlert">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ successMessage }}
            </div>
          </div>
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" @click="selectAll"></th>
                        <th>敏感词</th>
                        <th>替换词</th>
                        <th>类型</th>
                        <th>分类</th>
                        <th>提交人</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-show="loadding">
                        <!-- 加载动画 -->
                        <td :class="$style.loadding" colspan="8">
                            <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                        </td>
                    </tr>
                    <tr v-for="sensitive in sensitives">
                        <td><input type="checkbox" :value="sensitive.id" :checked="checked"></td>
                        <td>{{ sensitive.name }}</td>
                        <td>{{ sensitive.replace_name ? sensitive.replace_name : '无'  }}</td>
                        <td>{{ sensitive.filter_word_category.name }}</td>
                        <td>{{ sensitive.filter_word_type.name }}</td>
                        <td>{{ sensitive.user.name }}</td>
                        <td>{{ sensitive.created_at }}</td>
                        <td>
                          <button class="btn btn-primary btn-sm">编辑</button>
                          <button class="btn btn-danger btn-sm">删除</button>
                        </td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const FilterWordCategory = {
    data: () => ({
      loadding: true,
      sensitives: {},
      types: {},
      categories: {},
      checked: false,
      paginate: {
        perPage: 20,
        lastPage: 1,
        currentPage: 1,
      },
      errorMessage: '',
      successMessage: '',
    }),
    methods: {
      getSensitives () {
        this.loadding = true;
        request.get(
          createRequestURI('sensitive-words' + this.getQueryParams()),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;

          let { 
            data: data, 
            current_page: 
            currentPage, 
            last_page: lastPage, 
            total: total 
          } = response.data;

          this.paginate.currentPage = currentPage;
          this.paginate.lastPage = lastPage;
          this.paginate.total = total;
          this.sensitives = data;

        }).catch(({ response: { data = {} } = {} }) => {
          let {name = []} = data;
          let [ errorMessage ] = [...name];
          this.errorMessage = errorMessage;
        });
      },
      getTypes () {
        request.get(
          createRequestURI('filter-word-types'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.types = response.data;
        }).catch(({ response: { data = {} } = {} }) => {
          let {name = []} = data;
          let [ errorMessage ] = [...name];
          this.errorMessage = errorMessage;
        });
      },
      getCategories () {
        request.get(
          createRequestURI('filter-word-categories'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.categories = response.data;
        }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
            this.loadding = false;
            this.errorMessage = message;
        });
      },
      search () {
        //todo
      },
      getQueryParams () {
        let query = '?';

        query += '&perPage=' + this.paginate.perPage;
        query += '&page=' + this.paginate.currentPage;

        return query;
      },
      nextPage () {
        if (this.paginate.lastPage > this.paginate.currentPage) {
          this.paginate.currentPage += 1;
          this.sensitives = {}; 
          this.getSensitives();
        } 
      },
      prevPage () {
        if (this.paginate.currentPage > 1) {
          this.paginate.currentPage -= 1;
          this.sensitives = {}; 
          this.getSensitives(); 
        } 
      },
      selectAll () {
        this.checked = this.checked ? false : true;
      },
      offAlert () {
        this.errorMessage = this.successMessage = '';
      },
    },
    created () {
      this.getTypes();
      this.getSensitives();
    },
};
export default FilterWordCategory;
</script>
