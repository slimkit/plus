<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      分类管理
      <a href="javascript:;" class="pull-right" data-toggle="modal" data-target="#myModal" @click="addCate">
        <span class="glyphicon glyphicon-plus"></span> 添加分类
      </a>
    </div>
    <div class="panel-body">
      <table class="table table-hover table-bordered table-responsive table-middle">
        <thead>
        <tr>
          <th># ID</th>
          <th>分类名称</th>
          <th>分类权重</th>
          <th>资讯数目</th>
          <th>推荐数目</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="cate in cates" :key="cate.id">
          <td>{{cate.id}}</td>
          <td>{{cate.name}}</td>
          <td>{{cate.rank}}</td>
          <td>{{cate.news_count}}</td>
          <td>{{cate.rank}}</td>
          <td>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" @click="cur_cate = cate">
              编辑
            </button>
            <button class="btn btn-danger btn-sm" @click="delCate(cate.id)">删除</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <ui-alert :type="message.type" v-show="message.open">
      {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
    </ui-alert>
    <ui-modal :title="pop_title" :doSave="doSave" :onClose="updateCates">
      <div class="form-horizontal">
        <div class="row">
          <div class="col-sm-12">
            <!-- 名称 -->
            <div class="form-group">
              <label for="title" class="col-sm-2 control-label">名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="title" placeholder="请输入名称" v-model="cur_cate.name"/>
              </div>
            </div>
            <!-- /名称 -->
            <!-- 权重 -->
            <div class="form-group">
              <label for="rank" class="col-sm-2 control-label">权重</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="rank" placeholder="请输入分类权重, 默认为0"
                       v-model.number="cur_cate.rank"/>
              </div>
            </div>
            <!-- /权重 -->
          </div>
        </div>
      </div>
    </ui-modal>
  </div>
</template>
<script>
  import { admin } from '../../axios'

  export default {
    name: 'cates-list',
    data () {
      return ({
        cates: [],
        message: {
          open: false,
          type: '',
          data: {}
        },

        cur_cate: {
          id: null,
          name: '',
          rank: null
        }
      })
    },
    computed: {
      pop_title () {
        return this.cur_cate.id > 0 ? '编辑分类' : '添加分类'
      }
    },
    methods: {
      publishMessage (data, type, ms = 5000) {
        this.message = { open: true, data, type }
        setTimeout(() => {
          this.message.open = false
        }, ms)
      },

      getCates (cb) {
        cb = cb || function() {}
        admin.get(`/news/cates`, {
          validateStatus: status => status === 200
        }).then(({ data = [] }) => {
          this.cates = [...data]
          cb()
        }).catch(err => {
          console.log(err)
          this.publishMessage(err, 'danger')
        })
      },

      updateCates () {
        this.getCates()
        this.$refs.modal.modal('hide')
      },

      manageCate ({ id, name, rank }) {
        this.cur_cate = { id, name, rank }
      },

      addCate () {
        this.cur_cate = { id: null, name: '', rank: 0 }
      },

      delCate (id) {
        admin.delete(`/news/del/${id}/cate`, {
          validateStatus: status => status === 204
        }).then(() => {
          this.publishMessage({ message: '操作成功！' }, 'success')
          this.updateCates()
        }).catch(({ response: { data = { message: '操作失败！' } } }) => {
          this.publishMessage(data, 'danger')
          this.updateCates()
        })
      },

      doSave () {
        const {
          id,
          name,
          rank
        } = this.cur_cate
        if (name) {
          if (name.length > 6) {
            this.publishMessage({ message: '分类名称不能超过6个字' }, 'danger')
            return false
          }
          let param = {
            name,
            cate_id: id,
            rank
          }
          if (!id) { delete param.cate_id }


          admin.post('/news/handle_cate', {
            ...param,
            validateStatus: status => status === 200
          }).then(() => {
            this.publishMessage({ message: '操作成功！' }, 'success')
            this.updateCates()
          }).catch(({ response: { data = { message: '操作失败！' } } = {} }) => {
            this.publishMessage(data, 'danger')
            this.updateCates()
          })
        } else {
          this.publishMessage({ message: '请输入分类名称' }, 'danger')
          return false
        }
      }
    },
    created () {
      this.getCates()
    }
  }
</script>
