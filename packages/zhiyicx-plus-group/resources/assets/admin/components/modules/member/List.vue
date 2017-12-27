<template>
<div>
  <ui-table :loading="loading" :colspanNum="8">
    <!-- thead -->
    <tr slot="table-thead-tr">
      <th>#ID</th>
      <th>用户名</th>
      <th>职位</th>
      <th>类型</th>
      <th>状态</th>
      <th>拉黑</th>
      <th>加入时间</th>
      <th>操作</th>
    </tr>
    <!-- tbody -->
    <tr slot="table-tbody-tr" v-for="item in items">
        <td>{{ item.id }}</td>
        <td>{{ item.user.name }}</td>
        <td>
          <span v-if="item.role == 'founder'" class="label label-success">{{ item.role | memberRole }}</span>
          <span class="label label-primary" v-else>{{ item.role | memberRole }}</span>
        </td>
        <td>{{ item.group.mode | groupType }}</td>
        <td>{{ item.audit | memberAudit }}</td>
        <td>{{ item.disabled ? '是' : '否' }}</td>
        <td>{{ item.user.updated_at | localDate }}</td>
        <td>
            <template v-if="item.audit == 1">
                <button class="btn btn-primary btn-sm" v-if="item.role != 'founder'"
                        @click="handleRoleSetting(item.id, 'founder')">设置圈主</button>

                <button class="btn btn-primary btn-sm"
                        v-if="item.role != 'founder' && item.role != 'administrator'"
                        @click="handleRoleSetting(item.id, 'administrator')">设置为管理员</button>

                <button class="btn btn-primary btn-sm"
                        v-if="item.role == 'administrator'"
                        @click="handleRoleSetting(item.id, 'member')">撤销管理员</button>

                <button class="btn btn-primary btn-sm"
                        v-if="item.role != 'founder' && item.disabled == 0"
                        @click="handleMemberDisabled(item.id, 1)">加入黑名单</button>

                <button class="btn btn-primary btn-sm"
                        v-if="item.disabled == 1"
                        @click="handleMemberDisabled(item.id, 0)">解除黑名单</button>

                <button class="btn btn-danger btn-sm"
                        v-if="item.role != 'founder' && item.audit==1"
                        @click="handleMemberRemove(item.id)">踢出成员</button>
            </template>
        </td>
    </tr>
  </ui-table>
</div>
</template>
<script>
import plueMessageBundle from 'plus-message-bundle';
import { admin } from '../../../axios';

export default({
  props: {
    items: {
      type: Array,
    }
  },

  data:()=>({
    loading: true,
    message: {
      open: false,
      type: '',
      data: {},
    },
  }),

  filters: {

    memberRole(val) {
      let role = null;

      switch (val) {
        case 'founder':
          role = '圈主';
          break;
        case 'member':
          role = '成员';
          break;
        default:
          role = '管理员';
          break;
      }

      return role;
    },

    memberAudit(val) {
      let audit = null;

      switch (val) {
        case 0:
          audit = '待审核';
          break;
        case 1:
          audit = '已审核';
          break;
        default:
          audit = '已驳回';
          break;
      }

      return audit;
    },

    groupType(val) {
      let type = null;

      switch (val) {
        case 'public':
          type = '公开';
          break;
        case 'private':
          type = '私密';
          break;
        default:
          type = '收费';
          break;
      }

      return type;
    }
  },

  watch: {
    items() { this.loading = false; },
  },

  methods: {
    publishMessage (data, type, ms = 3000) {
      clearInterval(this.interval);

      this.message = { open: true, type, data };
      this.interval = setInterval(() => {
        this.message.open = false;
      }, ms);
    },
    /**
     * 设置职位
     * @param  {[int]} id
     * @param  {[string]} role
     * @return {[mix]}
     */
    handleRoleSetting(id, role) {
        if (confirm('是否要进行该操作？')) {
          admin.patch(`members/${id}/role`,{ role: role },{
              validateStatus: status => status === 201,
          }).then(({ data }) => {
              this.items.forEach((item) => {
                  if (item.id == id) {
                      item.role = role;
                  } else {
                    if (role == 'founder' && item.role == 'founder') item.role = 'member';
                  }
              });
              this.$store.dispatch('alert-open', { type: 'success', message: data });
          }).catch(({ response: { data = { message: '设置失败' } } = {} }) => {
              this.$store.dispatch('alert-open', { type: 'danger', message: data });
          });
        }
    },
    /**
     * 成员拉黑.
     * @param  {[int]} id
     * @param  {[int]} disable
     * @return {[mix]}
     */
    handleMemberDisabled(id, disable) {
        if (confirm('是否要进行该操作？')) {
          admin.patch(`members/${id}/disable`,{ disable: disable },{
              validateStatus: status => status === 201,
          }).then(({ data }) => {
              this.items.forEach((item) => {
                if (item.id == id) return item.disabled = disable;
              });
              this.$store.dispatch('alert-open', { type: 'success', message: data });
          }).catch(({ response: { data = { message: '操作失败' } } = {} }) => {
              this.$store.dispatch('alert-open', { type: 'danger', message: data });
          });
        }
    },
    /**
     * 成员踢出.
     * @param  {[int]} id
     * @return {[mix]}
     */
    handleMemberRemove(id) {
        if (confirm('是否要进行该操作？')) {
          admin.delete(`members/${id}`,{
              validateStatus: status => status === 204,
          }).then(({ data }) => {
              this.items.forEach((item, index) => {
                  if (item.id == id) this.items.splice(index, 1);
              });
              this.$store.dispatch('alert-open', { type: 'success', message: data });
          }).catch(({ response: { data = { message: '设置失败' } } = {} }) => {
              this.$store.dispatch('alert-open', { type: 'danger', message: data });
          });
        }
    }
  },

  created() {
  
  }
});
</script>