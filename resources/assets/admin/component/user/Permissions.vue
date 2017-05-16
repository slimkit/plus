<style lang="css" module>
.container {
  padding-top: 15px;
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
  <div class="container-fluid" :class="$style.container">
    <!-- 提示 -->
    <div class="alert alert-success" role="alert">
      权限节点，用于各个位置标示用户权限的配置～配置需要配合程序。尽量不要删除权限节点～以为节点name是在程序中赢编码的～
      这里提供管理，只是方便技术人员对节点进行管理。
      <p>编辑节点内容，修改完成后可直接回车或者留任不管～失去焦点后会自动保存。</p>
    </div>

    <!-- 表格列表 -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th>节点名称</th>
          <th>显示名称</th>
          <th>描述</th>
          <th>更新时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="perm in perms" :key="perm.id">
          <td>{{ perm.name }}</td>
          <td>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="输入名称" :value="perm.display_name" @change.lazy="updatePerm(perm.id, 'display_name', $event.target.value)">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="输入名称" :value="perm.description" @change.lazy="updatePerm(perm.id, 'description', $event.target.value)">
            </div>
          </td>
          <td>{{ perm.updated_at }}</td>
          <td>
            <button v-if="deleteIds.hasOwnProperty(perm.id)" type="button" class="btn btn-danger btn-sm" disabled="disabled">
              <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </button>
            <button v-else type="button" class="btn btn-danger btn-sm" @click.prevent="deletePerm(perm.id)">删除</button>
          </td>
        </tr>

        <tr>
          <td>
            <div class="input-group">
              <input v-model="add.name" type="text" class="form-control" placeholder="输入节点名称">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input v-model="add.display_name" type="text" class="form-control" placeholder="输入显示名称">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input v-model="add.description" type="text" class="form-control" placeholder="输入节点描述">
            </div>
          </td>
          <td></td>
          <td>
            <button v-if="add.adding" class="btn btn-primary btn-sm" disabled="disabled">
              <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </button>
            <button v-else type="button" class="btn btn-primary btn-sm" @click.pervent="postPerm">添加</button>
          </td>
        </tr>

      </tbody>
    </table>

    <div v-show="loadding" :class="$style.loadding">
      <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>

    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';

const PermissionsComponent = {
  data: () => ({
    perms: [],
    deleteIds: {},
    add: {
      name: '',
      display_name: '',
      description: '',
      adding: false
    },
    loadding: true,
    error: null
  }),
  methods: {
    postPerm () {
      console.log(2);
      const { name, display_name, description } = this.add;
      this.add.adding = true;
      request.post(
        createRequestURI('perms'),
        { name, display_name, description },
        { validateStatus: status => status === 201 }
      ).then(({ data }) => {
        this.perms = [
          ...this.perms,
          data
        ];
        this.add = {
          name: '',
          display_name: '',
          description: '',
          adding: false
        };
      }).catch(({ response: { data = {} } = {} }) => {
        const { errors = ['更新失败'] } = data;
        const errorMessage = lodash.values(errors).pop();
        this.error = errorMessage;
        this.add.adding = false;
      });
    },
    updatePerm (id, key, value) {
      request.patch(
        createRequestURI(`perms/${id}`),
        { key, value },
        { validateStatus: status => status === 201 }
      ).then(() => {
        // todo
        // 因为没有用到状态管理～无序重新设置！
      }).catch(({ response: { data = {} } = {} }) => {
        const { errors = ['更新失败'] } = data;
        const errorMessage = lodash.values(errors).pop();
        this.error = errorMessage;
      });
    },
    deletePerm (id) {
      if (window.confirm('确认删除节点？')) {
        this.deleteIds = {
          ...this.deleteIds,
          [id]: id
        };

        const deleteId = (id) => {
          let ids = {};
          for (let _id in this.deleteIds) {
            if (parseInt(_id) !== parseInt(id)) {
              ids = {
                ...ids,
                [_id]: _id
              };
            }
          }
          this.deleteIds = ids;
        };

        request.delete(
          createRequestURI(`perms/${id}`),
          { validateStatus: status => status === 204 }
        ).then(() => {
          deleteId(id);
          this.deletePermToState(id);
        }).catch(({ response: { data = {} } = {} }) => {
          const { errors = ['删除失败'] } = data;
          const errorMessage = lodash.values(errors).pop();
          deleteId(id);
          this.error = errorMessage;
        });
      }
    },
    deletePermToState (id) {
      let perms = [];
      this.perms.forEach(perm => {
        if (parseInt(perm.id) !== parseInt(id)) {
          perms.push(perm);
        }
      });
      this.perms = perms;
    },
    dismisError () {
      this.error = null;
    }
  },
  created () {
    request.get(
      createRequestURI('perms'),
      { validateStatus: status => status === 200 }
    ).then(({ data }) => {
      this.perms = data;
      this.loadding = false;
    }).catch(({ response: { data: { errors = ['获取失败，请刷新重试！'] } = {} } = {} }) => {
      this.error = lodash.values(errors).pop();
      this.loadding = false;
    });
  }
};

export default PermissionsComponent;
</script>
