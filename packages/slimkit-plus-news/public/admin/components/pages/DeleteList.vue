<template>
    <div class="panel panel-default delete-list">
        <div class="panel-heading">删除管理</div>
        <div class="panel-body">
            <table class="table table-hover table-bordered table-responsive table-middle">
                <thead>
                <tr>
                    <th># ID</th>
                    <th>申请人</th>
                    <th>相关资讯</th>
                    <th>申请时间</th>
                    <th>处理状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="apl in page.data" :key="`delete_apl_${apl.id}`" :class="statusToStyle(apl.status).cls">
                    <td>{{apl.id}}</td>
                    <td>{{apl.user.name}}</td>
                    <td>
                        <a class="td-title" :href="`/news/${apl.news.id}`"
                           :title='apl.news.title'>{{apl.news.title}}</a>
                    </td>
                    <td>{{apl.created_at}}</td>
                    <td>{{ statusToStyle(apl.status).label }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" :disabled='apl.status !== 0'
                                @click="putRequire(apl.id)('accept')">删除
                        </button>
                        <button class="btn btn-primary btn-sm" :disabled='apl.status !== 0'
                                @click="putRequire(apl.id)('reject')">拒绝
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="delete-list panel-footer" v-if='page.last_page > 1 '>
            <div class="row">
                <ui-page class='col-sm-12 text-center' v-model="current_page" :last="page.last_page"></ui-page>
            </div>
        </div>
        <ui-alert :type="message.type" v-show="message.open">
            {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
        </ui-alert>
    </div>
</template>
<script>
  import { admin, api } from '../../axios/'

  export default {
    name: 'news-delete-list',
    data() {
        return({
            page: {
              data: []
            },
            message: {
                type: 'error',
                open: false,
                data: ''
            },
            query: {
              limit: 15,
              page: 1
            },
            current_page: 1,
        })
    },
    watch: {
      current_page (val) {
        this.$set(this, 'query', {
          ...this.query,
          page: val
        })
        this.getList()
      },
    },
    methods: {
      publishMessage (data, type, ms = 5000) {
        this.message = { open: true, data, type }
        setTimeout(() => {
          this.message.open = false
        }, ms)
      },
      getList () {
        const { query } = this
        admin.get(`/news/applylogs`, {
          params: query,
          validataStatus: s => (s === 200)
        }).then(({ data }) => {
          this.page = data
        }).catch(({ response: { data = { message: '获取资讯失败' } } }) => {
          this.publishMessage(data, 'error')
        })
      },
      putRequire (id) {
        return (type) => {
          admin.put(`/news/applylogs/${id}/${type}`).then(data => {
            this.publishMessage({ success: '操作成功' }, 'success')
            this.getList()
          }).catch(err => {
            this.publishMessage({ error: '操作失败' }, 'error')
            this.getList()
          })
        }
      },
      statusToStyle (status) {
        switch (status) {
          case 0:
            return { label: '待审核', cls: 'info' }
          case 1:
            return { label: '已处理', cls: 'success' }
          case 2:
            return { label: '已驳回', cls: 'danger' }
        }
      }
    },
    created () {
      this.getList()
    }
  }
</script>
<style lang='scss'>
    a.td-title {
        display: block;
        max-width: 240px;
        width: auto;
    }

    .delete-list {
        .table {
            margin-bottom: 0;
        }

        .panel-footer {
            background-color: #fff;
        }
    }
</style>