<template>
  <div>
    <ui-table :loading="loading" :colspanNum="10">
        <!-- thead -->
        <tr slot="table-thead-tr">
          <th>#ID</th>
          <th>标题</th>
          <th>内容</th>
          <th>圈子</th>
          <th>评论数</th>
          <th>点赞数</th>
          <th>发布人</th>
          <th>置顶状态</th>
          <th>发布时间</th>
          <th>操作</th>
        </tr>

        <!-- tbody -->
        <tr slot="table-tbody-tr" v-for="item in items">
          <td>{{ item.id }}</td>
          <td>{{ item.title }}</td>
          <td>{{ item.summary }}</td>
          <td>
            <router-link :to="`/groups/${item.group.id}`">
              {{ item.group.name }}
            </router-link>
          </td>
          <td>
            <router-link :to="`/comments?post_id=${item.id}`">
              {{ item.comments_count }}
            </router-link>
          </td>
          <td>{{ item.likes_count }}</td>
          <td>{{ item.user.name }}</td>
          <td>
            {{ getStatus(item.latest_pinned) }}
            <br>
            <small v-if="item.latest_pinned">
              天数：{{ item.latest_pinned.day }} 
              金额：{{ item.latest_pinned.amount | money }}
              <span v-if="item.latest_pinned.expires_at">
                过期时间：{{ item.latest_pinned.expires_at | localDate }}
              </span>
            </small>
          </td>
          <td>{{ item.created_at | localDate }}</td>
          <td>
            <template v-if="!item.deleted_at">
              <!-- 撤销帖子置顶 -->
              <button 
              v-if="item.latest_pinned && item.latest_pinned.status==1 && item.latest_pinned.expires_state" 
              class="btn btn-primary btn-sm" 
              @click="handlePinnedRevocation(item.id)">撤销</button>

              <!-- 置顶帖子 -->
              <button class="btn btn-primary btn-sm" 
              v-if="!item.latest_pinned || (item.latest_pinned && item.latest_pinned.status == 1 && !item.latest_pinned.expires_state) || (item.latest_pinned && item.latest_pinned.status == 2)" 
              @click="handlePinned(item.id)" data-toggle="modal" data-target="#myModal">置顶</button>

              <!-- 审核置顶 -->
              <template v-if="item.latest_pinned && item.latest_pinned.status == 0">
                <button class="btn btn-primary btn-sm" @click="handlePinnedAccept(item.id)">置顶通过</button>
                <button class="btn btn-danger btn-sm" @click="handlePinnedReject(item.id)">置顶驳回</button>
              </template>

              <!-- 帖子删除 -->
              <button class="btn btn-danger btn-sm" @click="handleDelete(item.group_id, item.id)">删除</button>
            </template>
            <template v-else>
              <!-- 还原 -->
              <button class="btn btn-primary btn-sm" @click="hanldeRestore(item.id)">还原</button>
            </template>
          </td>
        </tr>
    </ui-table>

    <!-- 帖子置顶弹框 -->
    <ui-modal title="帖子置顶" :save="handlePinnedSubmit" :cancel="handlePinnedCancel">
        <div class="row form-horizontal" slot="modal-body"> 
          <div class="col-xs-12">
            <div class="form-group">
              <label class="control-label col-xs-2">置顶天数</label>
              <div class="col-xs-5">
                <input type="number" class="form-control" placeholder="置顶天数" value="1" v-model="day">
              </div>
              <div class="help-block">
                帖子需要置顶的天数
              </div>
            </div>
          </div>
        </div>
    </ui-modal>

  </div>
</template>
<script>
import { admin } from '../../../axios';
export default({
  props: {
    items: {
      type: Array,
    }
  },

  data:() => ({
  	groupId: null,
    loading: true,
    postId: null,
    day: 1,
  }),

  watch: {
    items() { this.loading = false; }
  },

  methods: {

    handlePinned(id) { this.postId = id; },
    /**
     * 帖子驳回置顶.
     * @param  {int} id
     * @return {void}
     */
  	handlePinnedReject(id) {
      admin.patch(`pinned/posts/${id}/reject`, {
        validateStatus: status => status === 201,
      }).then(({ data })=>{
        this.items.forEach((item)=>{
           if(item.id == id)  item.latest_pinned = data.pinned;
        });
        this.$store.dispatch('alert-open', { type: 'success', message: data });
      }).catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
        })
  	},
    /**
     * 帖子通过置顶.
     * @param  {int} id
     * @return {void}
     */
  	handlePinnedAccept(id) {
      admin.patch(`pinned/posts/${id}/accept`, {
        validateStatus: status => status === 201,
      }).then(({ data })=>{
        this.items.forEach((item)=>{
           if(item.id == id)  item.latest_pinned = data.pinned;
        });
        this.$store.dispatch('alert-open', { type: 'success', message: data });
      }).catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
        })
  	},
    /**
     * 处理置顶撤销.
     * @param  {[type]} id [description]
     * @return {[type]}    [description]
     */
  	handlePinnedRevocation(id) {
      admin.patch(`pinned/posts/${id}/revocation`, {
        validateStatus: status => status === 201,
      }).then(({ data })=>{
        this.items.forEach((item)=>{
           if(item.id == id)  item.latest_pinned = data.pinned;
		    });
        this.$store.dispatch('alert-open', { type: 'success', message: data });
      }).catch(({ response: { data } })=> {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      })
  	},
    /**
     * 删除帖子
     * @param  {int} id
     * @return {void}
     */
  	handleDelete(gid, id) {
  	  if (confirm('确定要删除吗？')) {
        admin.delete(`groups/${gid}/posts/${id}`, {
          validateStatus: status => status === 204,
        }).then(response=>{
          this.items.forEach((item, index)=>{
             if(item.id == id)  this.items.splice(index, 1);
		      });
          this.$store.dispatch('alert-open', { type: 'success', message: { message: '删除成功' } });
        }).catch(({ response: { data = { message: '操作失败' } } = {} }) => {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
        });
  	  }
  	},

    /**
     * 帖子置顶提交
     * @return {void}
     */
    handlePinnedSubmit() {
      if (this.day <= 0) {
        return this.$store.dispatch('alert-open', { type: 'danger', message: { message: '请填写置顶天数' } });
      }
      admin.post(`pinned/posts/${this.postId}`,{day: this.day}, 
      { validateStatus: status => status === 201 }).then(({ data })=>{
        this.items.forEach((item)=>{
           if(item.id == this.postId)  item.latest_pinned = data.pinned;
        });
        this.$store.dispatch('alert-open', { type: 'success', message: data });
      }).catch(({ response: { data } })=> {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      });
    },
    /**
     * 帖子置顶取消
     * @return {void}
     */
    handlePinnedCancel() {
      this.postId = null;
    },
    /**
     * 获取置顶状态.
     * 
     * @param  {Object} pinned
     * @return {void}
     */
    getStatus(pinned) {
      if (! pinned) return '未置顶';
      if (pinned.status == 0) {
      	return '待审核';
      } else if (pinned.status == 1) {
      	return '已审核'+(pinned.expires_state ? '(置顶中)' : '(已过期)');
      } else if(pinned.status == 2){
      	return '已驳回';
      }
    },

    hanldeRestore(id) {
      if (confirm('确定要还原吗？')) {
        admin.patch(`posts/${id}/restore`, {
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
  	this.groupId = this.$route.params.id;
  }
});
</script>