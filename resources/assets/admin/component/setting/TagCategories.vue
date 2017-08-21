<template>
  <div class="container-fluid">
    <!-- 标签列表 -->
      <table class="table table-striped" v-if="!empty">
        <thead>
          <tr>
            <th>分类ID</th>
            <th>分类</th>
            <th>拥有标签数量</th>
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
          <tr v-for="category in tag_categories" :key="category.id">
            <td>{{ category.id }}</td>
            <td>{{ category.name }}</td>
            <td>{{ category.tags_count }}</td>
            <td>
              <!-- 编辑 -->
              <router-link type="button" class="btn btn-primary btn-sm" :to="`/users/manage/${category.id}`" >编辑</router-link>
              <!-- 删除 -->
              <button type="button" class="btn btn-danger btn-sm" @click="deleteUser(category.id)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>
      <p v-else style="text-align: center; padding: 8px;">还没有添加分类</p>
      <ul class="pager" v-show="page >= 1 && last_page > 1">
        <li class="previous" :class="page <= 1 ? 'disabled' : ''">
          <router-link :to="{ path: '/setting/tag-categories', query: prevQuery }">
            <span aria-hidden="true">&larr;</span>
            上一页
          </router-link>
        </li>
        <li>
          共 {{ total }}个标签，总共{{ last_page }}页，当前为第{{ page }}页
        </li>
        <li class="next" :class="page >= last_page ? 'disabled': ''">
          <router-link :to="{ path: '/setting/tag-categories', query: nextQuery }">
            下一页
            <span aria-hidden="true">&rarr;</span>
          </router-link>
        </li>
      </ul>
    </div>
</template>

<style lang="scss" module>
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

<script>
  import request, { createRequestURI } from '../../util/request';

  const TagCategories = {
    data: () => ({
      tag_categories: [],
      last_page: 1,
      per_page: 20,
      page: 1,
      total: 1,
      loadding: true
    }),

    methods: {
      getTagCategories () {
        request.get(createRequestURI(`site/tag_categories`) , {
          params: { ...this.queryParams }
        }, {
          validateStatus: status => status === 200
        }).then(({ data = {} }) => {
          this.tag_categories = data.data;
          this.last_page = data.last_page;
          this.page = data.current_page;
          this.total = data.total;
          this.loadding = false;
        }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
          this.loadding = false;
          // this.error = true;
          // window.alert(message);
        });
      }
    },

    watch: {
      '$route' (to) {
        const {
          last_page = 1,
          per_page = 20,
          page = 1
        } = to.query;

        this.last_page = parseInt(last_page);
        this.per_page = parseInt(per_page);
        this.page = parseInt(page);

        this.getTagCategories();
      }
    },

    computed: {
      empty () {
        return !(this.tag_categories.length > 0);
      },
      queryParams () {
        const { per_page, page } = this;
        return { per_page, page };
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
      }
    },

    created () {
      const {
        per_page = 20,
        last_page = 1,
        page = 1
      } = this.$route.query;
      // set state.
      this.last_page = last_page;
      this.current_page = page;
      this.per_page = per_page;

      this.getTagCategories();
    }
  }

  export default TagCategories;
</script>