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
  <div class="container-fluid" style="margin-top:10px;">
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
        标签列表
        <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/setting/addtag" role="button">
          <span class="glyphicon glyphicon-plus"></span>
          添加
        </router-link>
      </div>
      <div class="panel-heading">
        <div class="form-inline">
          <div class="form-group">
            <label for="">搜索：</label>
            <input type="text" class="form-control" placeholder="标签名检索" v-model="keyword">
          </div>
          <div class="form-group">
            <select class="form-control" v-model="category" v-show="categories.length">
              <option value="">全部</option>
              <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
            </select>
          </div>
          <div class="form-group">
              <router-link class="btn btn-default" tag="button" :to="{ path: '/setting/tags', query: searchQuery }">
                搜索
              </router-link>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>标签ID</th>
              <th>标签</th>
              <th>所属分类</th>
              <th>热度</th>
              <th>权重(越大越靠前)</th>
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
            <template v-if="!empty">
              <tr v-for="tag in tags" :key="tag.id">
                <td>{{ tag.id }}</td>
                <td>{{ tag.name }}</td>
                <td>{{ tag.category.name }}</td>
                <td>{{ tag.taggable_count }}</td>
                <td>{{ tag.weight }}</td>
                <td>
                  <!-- 编辑 -->
                  <router-link type="button" class="btn btn-primary btn-sm" :to="`/setting/updatetag/${tag.id}`">编辑</router-link>
                  <!-- 删除 -->
                  <button type="button" class="btn btn-danger btn-sm" @click="deleteTag(tag.id)">删除</button>
                </td>
              </tr>    
            </template>
            <template v-else>
              <tr><td colspan="6" class="text-center">无相关记录</td></tr>
            </template>

          </tbody>
        </table>
        <ul class="pager" v-show="page >= 1 && last_page > 1">
          <li class="previous" :class="page <= 1 ? 'disabled' : ''">
            <router-link :to="{ path: '/setting/tags', query: prevQuery }">
              <span aria-hidden="true">&larr;</span>
              上一页
            </router-link>
          </li>
          <li>
            共 {{ total }}个标签，总共{{ last_page }}页，当前为第{{ page }}页
          </li>
          <li class="next" :class="page >= last_page ? 'disabled': ''">
            <router-link :to="{ path: '/setting/tags', query: nextQuery }">
              下一页
              <span aria-hidden="true">&rarr;</span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
  import _ from 'lodash';
  import { mapGetters } from 'vuex';
  import request, { createRequestURI } from '../../util/request';
  import plusMessageBundle from 'plus-message-bundle';
  const Tags = {
    data: () => ({
      tags: [],
      page: 1,
      from: 1,
      last_page: 0,
      prev_page_url: null,
      total: 0,
      per_page: 20,
      loadding: true,
      cate: 0,
      keyword: '',
      message: {
        error: null,
        success: null,
      },
      categories: [],
      category: '',
    }),
    methods: {
      getTags () {
        request.get(createRequestURI(`site/tags`) , {
          params: { ...this.queryParams }
        }, {
          validateStatus: status => status === 200
        }).then(({ data = {} }) => {
          this.tags = data.data;
          this.last_page = data.last_page;
          this.page = data.current_page;
          this.total = data.total;
          this.loadding = false;
        }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
          this.loadding = false;
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
        });
      },

      getTagCategories () {
        request.get(createRequestURI(`site/tags/categories`) , {
        }, {
          validateStatus: status => status === 200
        }).then(({ data = {} }) => {
          this.categories = data;
        }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
        });
      },

      dismisAddAreaError () {
        this.error = false;
      },

      deleteTag(id) {
        if(!id) {
          return false;
        }
        request.delete(createRequestURI(`site/tags/${id}`), {
          validateStatus: status => status === 204
        })
        .then( () => {
          // 删除数据
          let index = _.findIndex(this.tags, (tag) => {
            return tag.id === id;
          });

          this.tags.splice(index, 1);
        })
        .catch(({ response: { data: { message  = '加载失败' } = {} } = {} }) => {
          this.error = true;
          this.message.error = message;
        })
      }
      
    },

    watch: {
      '$route' (to) {
        const {
          last_page = 1,
          per_page = 20,
          page = 1,
          cate = 0
        } = to.query;

        this.last_page = parseInt(last_page);
        this.per_page = parseInt(per_page);
        this.page = parseInt(page);
        this.cate = parseInt(cate);

        this.getTags();
      }
    },

    computed: {
      empty () {
        return !(this.tags.length > 0);
      },
      queryParams () {
        const { per_page, page, keyword, category } = this;
        return { per_page, page, keyword, category };
      },
      prevQuery () {
        const page = parseInt(this.page);
        return {
          ...this.queryParams,
          last_page: this.last_page,
          page: page > 1 ? page - 1 : page
        };
      },
      nextQuery () {
        const page = parseInt(this.page);
        const last_page = parseInt(this.last_page);
        return {
          ...this.queryParams,
          last_page: last_page,
          page: page < last_page ? page + 1 : last_page
        };
      },
      searchQuery () {
        this.page = 1;
        const { per_page, page, category, keyword } = this;
        return { per_page, page, category, keyword };
      }
    },

    created () {
      const {
        last_page = 1,
        page = 1,
        per_page = 20,
        keyword = '',
        category = '',
      } = this.$route.query;
      // set state.
      this.last_page = last_page;
      this.current_page = page;
      this.per_page = per_page;
      this.keyword = keyword;
      this.category = category;
      this.getTags();
      this.getTagCategories();
    }
  }

  export default Tags;
</script>