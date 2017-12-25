<template>
  <table class="table table-hover table-responsive">

    <thead>
      <tr>
        <th># ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>状态</th>
        <th>备注</th>
        <th>操作</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="item in items">
        <td>{{ item.id }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.status == 0 ? '待审核' : (item.status == 1 ? '已审核' : '已驳回') }}</td>
        <td>
          <template v-if="item.status == 0">
            <div class="input-group" v-show="id == item.id">
              <input type="text" class="form-control" placeholder="请填写驳回理由" v-model="remark">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" @click="handleAuditReject">确认</button>
              </span>
            </div>
          </template>
          <template v-else>
            <span>{{ item.remarks }}</span>
          </template>
        </td>
        <td>
          <button class="btn btn-primary btn-sm" :disabled="item.status == 0 ? false : true" @click="handleAccept(item.id)">通过</button>
          <button class="btn btn-danger btn-sm" @click="handleAudit(item.id)" :disabled="item.status == 0 ? false : true">驳回</button>
          <button class="btn btn-danger btn-sm" @click="handleDelete(item.id)" :disabled="item.status == 0 ? false : true">删除</button>
        </td>
      </tr>
    </tbody>

  </table>
</template>

<script>
import { admin } from '../../../../axios';

export default {
  name: 'module-topic-application-list',
  props: {
    items: { type: Array, required: true },
    publishMessage: { type: Function, required: true }
  },
  data: () => ({
    id: null,
    remark: null,
  }),
  methods: {
    /**
     * 处理审核处理.
     * @param  {int} type 审核类型
     */
    handleAudit(itemId) {
      this.remark = null;
      if (this.id == itemId) {
        this.id = null;
      } else {
        this.id = itemId;
      }
    },
    /**
     * 处理驳回申请.
     */
    handleAuditReject() {
      const { id, remark } = this;

      if (!remark) return alert('请填写驳回理由');

      admin.put(`/topic-application-records/${id}/reject`,{ remarks: remark }, 
      {
        validateStatus: status => status === 201,
      }).then(( { data = {} }) => {
        this.updateItem(id, data);
      }).catch(({ response: { data = {} } = {} } = {}) => {

      });
    },
    /**
     * 处理通过申请.
     */
    handleAccept(id) {
      admin.put(`/topic-application-records/${id}/accept`, {
        validateStatus: status => status === 201,
      }).then(({ data = {} }) => {
        this.updateItem(id, data);
      }).catch(({ response: { data = {} } = {} } = {}) => {

      });
    },
    /**
     * 处理删除申请.
     * @param  {[int]} id [申请话题id]
     */
    handleDelete(id) {
      admin.delete(`/topic-application-records/${id}`, {
        validateStatus: status => status === 204,
      }).then(({ data = {} }) => {
        this.deleteItem(id);
        this.publishMessage({message: '删除成功'}, 'success');
      }).catch(({ response: { data = {} } = {} } = {}) => {

      });
    },
    /**
     * 更新节点数据
     * @param  {[int]} id
     * @param  {[type]} data 
     */
    updateItem(id, data) {
      this.items.forEach(function(item){
        if (id == item.id) {
          item.status = data.status;
          item.remarks = data.remarks;
        }
      });
    },
    /**
     * 删除节点
     * @param  {[type]} id [description]
     * @return {[type]}    [description]
     */
    deleteItem(id) {
      if (confirm('确认要删除嘛')) {
        let _this = this;
        this.items.forEach(function(item, index){
          if (id == item.id) {
              _this.items.splice(index, 1)
          }
        });
      }
    },
  }
};
</script>
