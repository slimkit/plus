<template>
    <div class="container-fluid" style="margin-top:10px;">
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>认证类型</th>
                        <th>显示标题</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 加载 -->
                    <table-loading :loadding="loadding" :colspan-num="3"></table-loading>
                    <tr v-for="category in categories" :key="category.name">
                        <td>{{ category.name }}</td>
                        <td>{{ category.display_name }}</td>
                        <td>
                            <!-- 编辑 -->
                            <router-link type="button"
                            class="btn btn-primary btn-sm"
                            :to="{ name: 'certification:category:edit', params:{name:category.name}}" >编辑</router-link>
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
const ManageComponent = {
    data: () => ({
      loadding: true,
      categories:{}
    }),
    methods: {
      getCertificationCategories () {
        this.loadding = true;
        request.get(
          createRequestURI('certification/categories'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.categories = response.data || [];
        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
          this.loadding = false;
        });
      }
    },
    created () {
      this.getCertificationCategories();
    },
};
export default ManageComponent;
</script>
