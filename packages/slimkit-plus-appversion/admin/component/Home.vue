<template>
  <div>
  	<h4>App版本管理</h4>
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="开关设置" name="status">
        <el-row :gutter="10">
          <el-col :span="2"><div class="grid-content bg-purple">是否开启版本控制</div></el-col>
          <el-col :span="8"><div class="grid-content bg-purple-light">
            <el-tooltip :content="'开启状态: ' + (open === true ? '开启' : '关闭')" placement="top">
              <el-switch
                v-model="open"
                on-color="#13ce66"
                off-color="#ff4949"
                :on-value="true"
                :off-value="false">
              </el-switch>
            </el-tooltip>
          </div> 设置版本控制在移动端的开启与关闭
        </el-col>
        </el-row>
        <el-row :class="$style.textinput">
          <el-col :offset="2"><el-button type="primary" @click="setStatus">提交</el-button></el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="当前版本" name="current">
        <el-radio class="radio" v-model="type" label="android">安卓客户端</el-radio>
        <el-radio class="radio" v-model="type"  label="ios">苹果客户端</el-radio>
        <el-input :class="$style.textinput" placeholder="上传Apk后自动生成，无需填写" :disabled="true" v-model="nowType.link">
          <template slot="prepend">下载链接</template>
        </el-input>
        <el-input :class="$style.textinput" placeholder="上传Apk后自动获取，无需填写" :disabled="true" v-model="nowType.version">
          <template slot="prepend">App版本号</template>
        </el-input>
        <el-input :class="$style.textinput" placeholder="上传Apk后自动获取，无需填写" :disabled="true" v-model="nowType.version_code">
          <template slot="prepend">App内部版本号</template>
        </el-input>
        <el-radio class="radio" v-model="nowType.is_forced" :disabled="true" :label="1">强制更新</el-radio>
        <el-radio class="radio" v-model="nowType.is_forced" :disabled="true" :label="0">可选更新</el-radio>
        <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 6}" :class="$style.textinput" placeholder="更新内容" :disabled="true" v-model="nowType.description">
          <template slot="prepend">更新内容</template>
        </el-input>
        <el-input :class="$style.textinput" placeholder="创建时间" :disabled="true" v-model="localTime">
          <template slot="prepend">版本创建时间</template>
        </el-input>
      </el-tab-pane>
      <el-tab-pane label="历史版本" name="versions">
        <el-table
          :data="formateVersions"
          stripe
          style="width: 100%">
          <el-table-column
            prop="id"
            label="版本ID"
            width="80">
          </el-table-column>
          <el-table-column
            prop="version"
            label="版本号"
            width="80">
          </el-table-column>
          <el-table-column
            prop="version_code"
            label="内部版本号"
            width="80">
          </el-table-column>
          <el-table-column
            prop="link"
            label="下载地址"
            width="180">
          </el-table-column>
          <el-table-column
            prop="type"
            label="客户端类型">
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="提交时间"
          >
          </el-table-column>
          <el-table-column
            fixed="right"
            label="操作"
            width="100">
            <template scope="scope">
              <el-button type="text" @click.native="remove(scope.row.id, scope.$index)" size="small">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :class="$style.pagination"
          small
          layout="prev, pager, next"
          :total="total"
          :page-size="20"
          @current-change="changePage"
        >
        </el-pagination>
      </el-tab-pane>
      <el-tab-pane label="添加版本" name="add">
        <el-radio class="radio" v-model="news.type" label="android">安卓客户端</el-radio>
        <el-radio class="radio" v-model="news.type"  label="ios">苹果客户端</el-radio>
        <el-input 
          :class="$style.textinput" 
          :placeholder="isAndroid ? '自动生成，无需填写' : 'App Store 链接' " 
          :disabled="isAndroid" 
          v-model="news.link"
        >
          <template slot="prepend">下载链接</template>
        </el-input>
        <el-input 
          :class="$style.textinput" 
          placeholder="App版本号" 
          v-model="news.version"
        >
          <template slot="prepend">App版本号</template>
        </el-input>
        <el-input 
          :class="$style.textinput" 
          placeholder="App内部版本号" 
          v-model="news.version_code"
        >
          <template slot="prepend">App内部版本号</template>
        </el-input>
        <el-upload
          :class="$style.upload"
          :action="action"
          ref="upload"
          :disabled="!isAndroid"
          :on-success="handleSuccess"
          :on-preview="handlePreview"
          :on-remove="removeFile"
          :auto-upload="true"
          :file-list="fileList"
          :data="data"
        >
          <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
          <div slot="tip" class="el-upload__tip">只能上传APK文件</div>
        </el-upload>
        <el-radio class="radio" v-model="news.is_forced" :label="1">强制更新</el-radio>
        <el-radio class="radio" v-model="news.is_forced" :label="0">可选更新</el-radio>
        <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 6}" :class="$style.textinput" placeholder="更新内容" v-model="news.description">
          <template slot="prepend">更新内容</template>
        </el-input>
        <el-button
          :class="$style.textinput" 
          type="primary"
          @click.native="post"
        >
            <i class="el-icon-left el-icon-plus"></i>添加
        </el-button>
      </el-tab-pane>
      <el-tab-pane label="下载页管理" name="download-manage">
        <download-manage />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<style lang="less" module>
  .textinput, .upload {
    padding: 8px 0;
  }
  .pagination {
    margin: 8px 0;
  }
</style>
<script>
  import request, { createRequestURI } from '../utils/request.js';
  import PlusMessageBundle from 'plus-message-bundle/es.js';
  import _ from 'lodash';
  import DownloadManage from './download-manage.vue';

	const Home = {
    components: { DownloadManage },
    data: () => ({
      action: createRequestURI("admin/plus-appversion/upload"),
      data: {
        _token: window.Version.csrf_token
      },
      activeName: 'status',
      type: 'android',
      current:{},
      versions: [],
      news: {
        link: '',
        version: '',
        version_code: 0,
        type: '',
        is_forced: 0,
        storage: 0,
        description: ''
      },
      total: 0,
      currentPage: 1,
      open: false
    }),
    methods: {
      post () {
        const {link, version_code, version, type, is_forced, storage, description} = this.news;
        request.post(createRequestURI('admin/plus-appversion'), {
          link,
          version,
          version_code,
          type,
          is_forced,
          storage,
          description
        }, {
          validateStatus: status => status === 201
        })
        .then( () => {
          this.$message({
            message: '添加成功',
            type: 'success'
          });
          this.news = {
            link: '',
            version: '',
            version_code: 0,
            type: '',
            is_forced: 0,
            storage: 0,
            description: ''
          };
          this.$refs.upload.clearFiles();
        })
        .catch( ({ response: { data = {} } = {} }) => {
          let message = PlusMessageBundle(data);
          this.$message.error(message.message);
        })
      },
      setStatus () {
        const { open } = this;
        request.patch(createRequestURI('admin/plus-appversion/status'), {
          open
        }, {
          validateStatus: status => status === 201
        })
        .then( () => {
          this.$message({
            message: '添加成功',
            type: 'success'
          });
        })
        .catch( ({ response: { data = {} } = {} }) => {
          console.log(data);
        })
      },
      changePage (page) {
        this.currentPage = page;
        this.getVersions();
      },
      handleClick(tab, event) {
        this.getVersions();
      },
      handleSuccess (response, file, fileList) {
        this.news.storage = response.id;
        this.news.link = createRequestURI(`api/v2/files/${response.id}`);
      },
      removeFile () {
        this.news.storage = 0;
      },
      toAdd () {
        this.activeName = 'add';
      },
      remove (id, index) {
        request.delete(createRequestURI(`admin/plus-appversion/${id}`),{
          validateStatus: status => status === 204
        })
        .then( () => {
          let rows = this.versions;
          rows.splice(index, 1);
        })
        .catch( ({ response = {}}) => {
          console.log(response);
          // PlusMessageBundle();
        });
      },
      getVersions () {
        // 获取当前版本
        request.get(createRequestURI('admin/plus-appversion/current'), {
          validateStatus: status => status === 200
        })
        .then(({ data = {} }) => {
          this.current = { ... data };
        });

        // 获取版本记录
        request.get(createRequestURI(`admin/plus-appversion/versions?page=${this.currentPage}`), {
          validateStatus: status => status === 200
        })
        .then(({ data = {} }) => {
          this.versions = data.data;
          this.total = data.total;
        });

        // 获取当前扩展的状态
        request.get(createRequestURI('admin/plus-appversion/status'), {
          validateStatus: status => status === 200
        })
        .then(({ data: { open = false } = {} }) => {
          this.open = open;
        })
      },
      formateDate (dateString) {
        const ts = Date.parse(dateString);
        const offset = (new Date()).getTimezoneOffset();
        return new Date(ts - (offset * 60 * 1000)).toLocaleString();
      }
    },
    computed: {
      disabled () {
        return !(_.keys(this.current).length > 0);
      },
      isAndroid () {
        return this.news.type === 'android';
      },
      nowType () {
        return this.current[this.type] || {};
      },
      localTime () {
        if (this.current[this.type] && this.current[this.type].created_at) {
          return this.formateDate(this.current[this.type].created_at);
        } 
        return '';
      },
      formateVersions () {
        const { versions } = this;
        return versions.map( (version) => {
          version.created_at = this.formateDate(version.created_at);
          return version;
        });
      }
    },
    created () {
      this.getVersions();
    }
	}

	export default Home;
</script>
