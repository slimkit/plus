<template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <div class="panel-heading">检索</div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>#ID</th>
            <th>举报人</th>
            <th>被举报人</th>
            <th>举报内容</th>
            <th>举报资源类型</th>
            <th>状态</th>
            <th>举报理由</th>
            <th>处理备注</th>
            <th>举报时间</th>
            <th>操作</th>
          </tr>
          </thead>
          <tbody>
          <table-loading :loadding="loading" :colspan-num="10"></table-loading>
          <tr v-for="item in items">
            <td>{{ item.id }}</td>
            <td>{{ item.user ? item.user.name : '未知' }}</td>
            <td>{{ item.target ? item.target.name : '未知' }}</td>
            <td>{{ item.subject }}</td>
            <td>{{ item.reportable_type | moduleName }}</td>
            <td>{{ item.status | status }}</td>
            <td>{{ item.reason ? item.reason : '无' }}</td>
            <td>
              <input type="text" class="form-control" v-if="!item.status" placeholder="审核需要填写备注"
                     :ref="`mark${item.id}`">
              <span v-else>{{ item.mark }}</span>
            </td>
            <td>{{ item.created_at | localDate }}</td>
            <td>
              <a v-if="item.view" class="btn btn-primary btn-sm" :href="item.view">查看</a>
              <template v-if="item.status == 0">
                <button class="btn btn-primary btn-sm" @click="handleDeal(item.id)">通过</button>
                <button class="btn btn-primary btn-sm" @click="handleReject(item.id)">驳回</button>
              </template>
            </td>
          </tr>
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
  import request, { createRequestURI } from '../../util/request'
  import { plusMessageFirst } from '../../filters'

  export default {
    data: () => ({
      items: [],
      loading: true,
      total: 0
    }),
    watch: {
      '$route': function($route) {
        this.total = 0
        this.getReports({ ...$route.query })
      }
    },
    computed: {
      offset () {
        const { query: { offset = 0 } } = this.$route
        return parseInt(offset)
      }
    },
    filters: {
      status (val) {
        let title = '待审核'
        if (val == 1) {
          title = '已处理'
        } else if (val == 2) {
          title = '已驳回'
        }
        return title
      },
      moduleName (val) {
        switch (val) {
          case 'news':
            return '资讯'
          case 'feeds':
            return '动态'
          case 'users':
            return '用户'
          case 'groups':
            return '圈子'
          case 'comments':
            return '评论'
          case 'group-posts':
            return '圈子帖子'
          default:
            return '未知'
        }
      }
    },

    methods: {
      getReports (query = {}) {
        this.items = []
        this.loading = true
        request.get(
          createRequestURI('reports'),
          {
            validateStatus: status => status === 200,
            params: { ...query, limit: 15 }
          }
        ).then(({ data = [], headers: { 'x-total': total } }) => {
          this.loading = false
          this.total = parseInt(total)
          this.items = data
        }).catch(({ response: { data: { errors = ['加载失败'] } = {} } = {} }) => {
          this.loading = false
          window.alert(plusMessageFirst(errors))
        })
      },
      handleDeal (id) {
        let mark = this.$refs[`mark${id}`][0].value
        if (!mark) return window.alert('请填写通过备注')
        request.patch(
          createRequestURI(`reports/${id}/deal`),
          { mark: mark },
          { validateStatus: status => status === 201 }
        ).then(response => {
          window.alert('操作成功')
          this.items.forEach((item) => {
            if (id == item.id) item.status = 1, item.mark = mark
          })
        }).catch(({ response: { data: { errors = ['审核失败'] } = {} } = {} }) => {
          window.alert(errors)
        })
      },
      handleReject (id) {
        let mark = this.$refs[`mark${id}`][0].value
        if (!mark) return window.alert('请填写驳回备注')
        request.patch(
          createRequestURI(`reports/${id}/reject`),
          { mark: mark },
          { validateStatus: status => status === 201 }
        ).then(response => {
          window.alert('操作成功')
          this.items.forEach((item) => {
            if (id == item.id) item.status = 1, item.mark = mark
          })
        }).catch(({ response: { data: { errors = ['审核失败'] } = {} } = {} }) => {
          window.alert(errors)
        })
      },
      offsetPage (offset) {
        return { path: '/reports', query: { offset } }
      }
    },

    created () {
      this.getReports(this.$route.query)
    }
  }
</script>
