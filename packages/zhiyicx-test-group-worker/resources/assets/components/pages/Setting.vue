<template>
  <el-card class="box-card">

    <div slot="header" class="clearfix">
      <span>GitHub 账号</span>
      <el-tooltip placement="left">
        <div slot="content">
          Q: 为什么这里需要输入 GitHub 账号？<br>
          A: 因为管理平台是深度与 GitHub 进行整合，一些权限操作 GitHub 必须要求使用 Basic 方式认证。<br><br>
          Q: 我可以添加多少账号？<br>
          A: 管理平台不限制，你可以添加很多个，但是建议不要添加太多，以免选择账号的时候太多增加难度。<br><br>
          Q: 为什么可以增加多个账号？<br>
          A: 因为在不同 Issues 任务中，可能需要用到不同的账号操作。
        </div>
        <el-button style="float: right; padding: 3px 0" type="text">
          <span class="el-icon-question"></span>
        </el-button>
      </el-tooltip>
    </div>

    <el-table :data="data">
      <el-table-column
        prop="username"
        label="用户名"
      >
        <template slot-scope="scope">
          <el-input v-model="scope.row.username" v-if="scope.row.isNew || scope.row.isEdit" placeholder="请输入用户名" type="text"></el-input>
          <template v-else>{{ scope.row.username }}</template>
        </template>
      </el-table-column>
      <el-table-column
        prop="password"
        label="密码"
      >
        <template slot-scope="scope">
          <el-input v-model="scope.row.password" v-if="scope.row.isNew || scope.row.isEdit" placeholder="请输入密码" type="password"></el-input>
          <template v-else>{{ scope.row.password }}</template>
        </template>
      </el-table-column>
      <el-table-column
        prop="updated_at"
        label="上次更新"
      >
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button v-if="scope.row.isNew" size="mini" type="primary" @click="handleAddAccess(scope.row)">添加</el-button>
          <template v-else-if="scope.row.isEdit">
            <el-button type="primary" size="mini" @click="handleEditSave(scope.$index, scope.row)">保存</el-button>
            <el-button size="mini" @click="handleEditStatus(scope.$index, false)">取消</el-button>
          </template>
          <template v-else>
            <el-button size="mini" @click="handleEditStatus(scope.$index, true)">编辑</el-button>
            <el-button size="mini" type="danger">删除</el-button>
          </template>
        </template>
      </el-table-column>
    </el-table>
  </el-card>
</template>

<script>
export default {
  name: 'page-setting',
  data: () => ({
    accesses: [
      {
        username: 'medz',
        password: 'Zycx2pwd',
        updated_at: '222'
      }
    ]
  }),
  computed: {
    data() {
      return [
        ...this.accesses,
        { isNew: true }
      ];
    }
  },
  methods: {
    handleAddAccess({ username, password }) {
      console.log(username, password);
    },
    handleEditStatus(index, edit = false) {
      this.accesses = this.accesses.map((access, key) => {
        if (key === index) {
          access.isEdit = edit;
        }

        return access;
      });
    },
    handleEditSave(index, { username, password }) {
      console.log(index, this.accesses);
    }
  },
};
</script>
