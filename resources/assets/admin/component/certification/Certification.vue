<style lang="css" module>
    .container {
        padding: 15px;
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
    .img {
       width: 60px;
       height: 60px;
    }
</style>

<template>
    <div :class="$style.container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div v-show="successMessage" class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" @click.prevent="offAlert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ successMessage }}
                </div>
                <div v-show="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" @click.prevent="offAlert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ errorMessage }}
                </div>
                <div class="form-inline">
                    <div class="form-group">
                        <router-link type="button" class="btn btn-success" :to="{ name: 'certification:add' }">添加认证用户</router-link>
                    </div>
                </div>
            </div>
            <div class="panel-heading">
                <!-- 数据过滤 -->
                <div class="form-inline">
                    <div class="form-group">
                        <label>状态：</label>
                        <select class="form-control" v-model="statuss.selected" @change="watchChange">
                            <option :value="item.value" v-for="item in statuss.data">{{ item.status }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>类型：</label>
                        <select class="form-control" v-model="categories.selected" @change="watchChange">
                           <option value="">全部</option>
                           <option :value="item.name" v-for="item in categories.data">{{ item.display_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" v-model="keyword">
                      <button class="btn btn-default" type="button" @click="handleSearch">搜索</button>
                    </div>
                    <div class="form-group pull-right">
                        <ul class="pagination" style="margin: 0;">
                          <li :class="paginate.currentPage <= 1 ? 'disabled' : null">
                            <a href="javascript:;" aria-label="Previous" @click.stop.prevent="prevPage">
                              <span aria-hidden="true">&laquo;</span>
                            </a>
                          </li>
                          <li :class="paginate.currentPage >= paginate.lastPage ? 'disabled' : null">
                            <a href="javascript:;" aria-label="Next" @click.stop.prevent="nextPage">
                              <span aria-hidden="true">&raquo;</span>
                            </a>
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <!-- 认证列表 -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>用户名</th>
                            <th>真实姓名</th>
                            <th>机构名称</th>
                            <th>手机号码</th>
                            <th>身份证号码</th>
                            <th>认证类型</th>
                            <th>认证描述</th>
                            <th>认证资料</th>
                            <th>认证状态</th>
                            <th>认证时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 加载动画 -->
                        <tr v-show="loadding">
                            <td :class="$style.loadding" colspan="12">
                                <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                            </td>
                        </tr>
                        <template v-if="certifications.length">
                          <tr v-for="(certification, index) in certifications">
                              <td><input type="checkbox" :value="certification.id"></td>
                              <td>{{ certification.user.name }}</td>
                              <td>{{ certification.data.name }}</td>
                              <td>{{ certification.data.hasOwnProperty('org_name') ? certification.data.org_name : '' }}</td>
                              <td>{{ certification.data.phone }}</td>
                              <td>{{ certification.data.number }}</td>
                              <td>{{ certification.category.display_name }}</td>
                              <td>{{ certification.data.desc }}</td>
                              <td><a :href="attachmentPath+certification.data.files[0]" target="__blank">下载</a></td>
                              <td>{{ statuss.display[certification.status] }}</td>
                              <td>{{ certification.updated_at }}</td>
                              <td>
                                  <router-link type="button"
                                  class="btn btn-primary btn-sm"
                                  v-show="certification.status === 1"
                                  :to="{ name: 'certification:edit', params:{certification:certification.id}}">编辑</router-link>
                                  <button class="btn btn-primary btn-sm" 
                                  v-show="certification.status === 0" 
                                  @click.prevent="openPassModal(index)">通过</button>
                                  <button class="btn btn-primary btn-sm" 
                                      v-show="certification.status !== 2" 
                                      @click="openRejectModal(certification.id)">驳回</button>  
                              </td>
                          </tr> 
                        </template>
                        <template v-else>
                            <tr class="text-center" v-show="!loadding"><td colspan="12">无数据</td></tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 驳回认证modal start -->
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">认证驳回</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <label>驳回理由</label>
                    <textarea class="form-control" v-model="reject.content" @input="reject.message = ''"></textarea>
                    <span class="text-danger" v-show="reject.message">{{ reject.message }}</span>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" @click="rejectCertification">确认</button>
              </div>
            </div>
          </div>
        </div>
        <!-- 驳回认证modal end-->
        <!-- 通过认证modal start -->
        <div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">认证通过</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <label>通过描述</label>
                    <textarea class="form-control" v-model="pass.desc" @input="pass.message = ''"></textarea>
                    <span class="text-danger" v-show="pass.message ">{{ pass.message }}</span>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" @click="passCertification">确认</button>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const certificationComponent = {
    data: () => ({
        loadding: true,
        certifications: {},
        attachmentPath: '/api/v2/files/',
        categories: {
          selected: '',
          data: {}
        },
        statuss: {
          selected: '',
          display: ['待审核', '通过' , '拒绝'],
          data:[
            {status: '全部', value: ''},
            {status: '待审核', value: 0},
            {status: '通过', value: 1},
            {status: '拒绝', value: 2},
          ]
        },
        paginate: {
          perPage: 20,
          lastPage: 10,
          currentPage:1,
        },
        reject: {
          id: '',
          content:'',
          message: '',
        },
        pass: {
          id: '',
          desc: '',
          message: '',
        },
        keyword: '',
        successMessage: '',
        errorMessage: '',
    }),
    methods: {
        /**
         * 获取认证类型
         */
        getCertificationCategories () {
          return new Promise((resolve, reject) => {
            request.get(
              createRequestURI('certification/categories'),
              {validateStatus: status => status === 200}
            ).then(response => {
              resolve(response.data);
            }).catch(({ response: { data: { errors = ['加载认证栏目失败详情失败'] } = {} } = {} }) => {
              reject(errors); 
            }); 
          });
        },
        /**
         * 获取认证列表
         */
        getCertifications () {
          this.loadding = true;

          let params = this.getQueryParams();

          request.get(
            createRequestURI(`certifications${params}`),
            { validateStatus: status => status === 200 }
          ).then(response => {
            this.loadding = false;

            let { 
              data: data, 
              current_page: 
              currentPage, 
              last_page: lastPage, 
              total: total 
            } = response.data;

            this.paginate.currentPage = currentPage;
            this.paginate.lastPage = lastPage;
            this.paginate.total = total;
            this.certifications = data;

          }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
            this.loadding = false;
          });
        },
        /**
         * 打开通过认证模态框
         */
        openPassModal (index) {
          let certification = this.certifications[index];

          this.pass.id = certification.id;
          this.pass.desc = certification.data.desc;

          $('#passModal').modal('show');
        },
        /**
         * 处理通过认证
         */
        passCertification () {
          let {desc: desc, id: id} = this.pass;
          request.patch(
            createRequestURI('certifications/' + id + '/pass'),
            {desc: desc},
            {validateStatus: status => status === 201}
          ).then(({ data: { message: [ message ] = [] } }) => {
            $('#passModal').modal('hide');
            this.successMessage = message;
            this.getCertifications();
          }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
            this.pass.message = message;
          });
        },
        /**
         * 驳回认证
         */
        rejectCertification () {
          if ( !this.reject.content ) {
            this.reject.message = '请填写驳回原因';
            return;
          }
          let {id: id, content: content} = this.reject;
          request.patch(
            createRequestURI('certifications/' + id + '/reject'),
            {reject_content: content},
            {validateStatus: status => status === 201}
          ).then(({ data: { message: [ message ] = [] } }) => {
            this.successMessage = message;
            this.getCertifications();
            $('#rejectModal').modal('hide')
          }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
            this.errorMessage = message;
            $('#rejectModal').modal('hide')
          });
        },
        /**
         * 打开驳回弹层
         */
        openRejectModal (id) {
          this.reject.id = id;
          this.reject.content = '';
          this.reject.message = '';
          $('#rejectModal').modal('show');
        },
        /**
         * 获取参数
         */
        getQueryParams () {
          let query = '?';

          query += 'certification_name=' + this.categories.selected;
          query += '&status=' + this.statuss.selected;
          query += '&keyword=' + this.keyword;
          query += '&perPage=' + this.paginate.perPage;
          query += '&page=' + this.paginate.currentPage;

          return query;
        },
        /**
         * 处理过滤
         */
        handleSearch () {
          this.initializationData();
          this.getCertifications();
        },
        /**
         * 初始化数据
         */
        initializationData () {
          this.paginate.currentPage = 1;
          this.certifications = {}; 
        },
        /**
         * 关闭提示
         */
        offAlert () {
          this.errorMessage = this.successMessage = '';
        },
        watchChange () {
          this.initializationData();
          this.getCertifications();    
        },
        nextPage () {
          if (this.paginate.lastPage > this.paginate.currentPage) {
            this.paginate.currentPage += 1;
            this.certifications = {}; 
            this.getCertifications();
          } 
        },
        prevPage () {
          if (this.paginate.currentPage > 1) {
            this.paginate.currentPage -= 1;
            this.certifications = {}; 
            this.getCertifications(); 
          } 
        },
    },
    created () {
      let promise = this.getCertificationCategories();

      promise.then(data => {
        this.loadding = false;
        if (data.length) {
          
          this.categories.data = data;
          this.getCertifications();
        } else {
          this.errorMessage = '认证类型加载失败';
        }
      }, error => {
        this.errorMessage = error;
      });

    },
};
export default certificationComponent;
</script>
