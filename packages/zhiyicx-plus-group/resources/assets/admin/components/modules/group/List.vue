<template>
<ui-table :loading="loading" :colspanNum="10">
    <!-- thead -->
    <tr slot="table-thead-tr">
      <th>#ID</th>
      <th>名称</th>
      <th>分类</th>
      <th>类型</th>
      <th>圈主</th>
      <th>成员数</th>
      <th>帖子数</th>
      <th>审核状态</th>
      <th>创建时间</th>
      <th>操作</th>
    </tr>
    <!-- tbody -->
    <tr slot="table-tbody-tr" v-for="item in items">
      <td>{{ item.id }}</td>  
      <td>{{ item.name }}</td>
      <td>{{ item.category.name }}</td>
      <td>
        {{ item.mode | type }}
        <span class="text-danger" v-show="item.mode == 'paid'">{{ item.money | money }}</span>
      </td>
      <td>{{ item.user.name }}</td>
      <td>{{ item.users_count }}</td>
      <td>{{ item.posts_count }}</td>
      <td>{{ item.audit | audit }}</td>
      <td>{{ item.created_at | localDate }}</td>
      <td>
        

        <template v-if="!item.deleted_at">
          <!-- 查看 -->
          <router-link tag="button" class="btn btn-warning btn-sm" :to="`/groups/${item.id}`">
            查看
          </router-link>

          <template v-if="item.audit == 0">

            <!-- 审核通过 -->
            <button class="btn btn-primary btn-sm" @click="handleAudit(item.id, 1)">通过</button>
            
            <!-- 驳回审核 -->
            <button class="btn btn-primary btn-sm" @click="handleAudit(item.id, 2)">驳回</button>

          </template>

          <template v-if="item.audit == 1 || item.audit == 3">
            <!-- 编辑 -->
            <router-link tag="button" class="btn btn-primary btn-sm" :to="`/groups/${item.id}/edit`">
              编辑
            </router-link>

            <!-- 推荐／不推荐 -->
            <button class="btn btn-sm" 
            :class="item.recommend ? 'btn-danger' : 'btn-primary'" @click="hanldeRecommend(item)">{{ item.recommend ? '取消推荐' : '推荐' }}</button>

            <!-- 圈子开启 -->
            <button class="btn btn-sm btn-primary"  @click="handleAudit(item.id, 1)" v-if="item.audit==3">启动</button>

            <!-- 圈子关闭 -->
            <button class="btn btn-sm btn-danger"  @click="handleAudit(item.id, 3)" v-if="item.audit==1">关闭</button>

            <!-- 解散 -->
            <button class="btn btn-danger btn-sm" @click="hanldeDelete(item.id)">解散</button>
          </template>  

        </template>
        <template v-else>
            <!-- 解散 -->
            <button class="btn btn-primary btn-sm" @click="hanldeRestore(item.id)">恢复</button>
        </template>
      </td>
    </tr>
</ui-table>
</template>
<script>
import { admin } from '../../../axios';
export default({
  props: {
    items: {
      type: Array,
    }
  },
  data:()=>({
    loading: true,
  }),
  watch: {
    items() {
      this.loading = false;
    }
  },
  filters: {  
    type(value) {  
      let type = '公开';
      if (value == 'private') {
        type = '私有';
      } else if (value == 'paid'){
        type = '收费';
      }
      return type;
    },
    audit(value) {
      let audit = '待通过';
      if (value == 1) {
        audit = '已通过';
      } else if (value == 2){
        audit = '未通过';
      } else if (value == 3) {
        audit = '已关闭';
      }
      return audit;
    }
  },
  methods: {
    hanldeDelete(id) {
      if (confirm('确定要解散圈子吗？')) {
        admin.delete(`groups/${id}`, {
          validateStatus: status => status === 204,
        }).then(response=>{
          this.items.forEach((item, index)=>{
            if (item.id == id) this.items.splice(index, 1);
          });
          this.$store.dispatch('alert-open', { type: 'success', message: { message: '解散成功' } });
        }).catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
        })
      }
    },
    hanldeRecommend(group) {
      admin.patch(`groups/${group.id}/recommend`, {
        validateStatus: status => status === 204,
      }).then(response=>{
        this.items.forEach((item, index)=>{
          if (item.id == group.id) item.recommend = !item.recommend;
        });
        this.$store.dispatch('alert-open', { type: 'success', message: { message: '操作成功' } });
      }).catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
      })
    },

    handleAudit(id, audit) {
      let tip = '确定要通过该申请嘛？';
      if (audit == 2) {
          tip = '确定要驳回该申请嘛？';
      } else if (audit == 3) {
          tip = '确定要关闭该圈子嘛？';
      }
      if (confirm(tip)) {
        admin.patch(`groups/${id}/audit`, { audit: audit }, {
          validateStatus: status => status === 201,
        }).then(({ data })=>{
          this.items.forEach((item)=>{
            if (item.id == id) {
              item.audit = audit;
              if (item.audit == 1) {
                item.users_count += 1;
              } 
            }
          });
          this.$store.dispatch('alert-open', { type: 'success', message: data });
        }).catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
        })
      }
    },

    hanldeRestore(id) {
      if (confirm('确定要恢复吗？')) {
        admin.patch(`groups/${id}/restore`, {
          validateStatus: status => status === 201,
        }).then(({ data })=>{
          this.items.forEach((item, index) => {
            if (item.id == id) this.items.splice(index, 1);
          });
          this.$store.dispatch('alert-open', { type: 'success', message: data });
        }).catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
        })
      }
    }
  },
  created() {
  }
});
</script>