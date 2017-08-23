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
                    <label>状态：</label>
                    <select class="form-control" v-model="statuss.selected">
                        <option :value="item.value" v-for="item in statuss.data">{{ item.status }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <span><label>类型：</label></span>
                    <select class="form-control" v-model="categories.selected">
                       <option value="">全部</option>
                       <option :value="item.name" v-for="item in categories.data">{{ item.display_name }}</option>
                    </select>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" v-model="keyword">
                  <button class="btn btn-default" type="button" @click="handleSearch">搜索</button>
                </div>
                <div class="form-group pull-right">
                    <router-link type="button" class="btn btn-success btn-sm" :to="{ name: 'certification:add' }">添加认证用户</router-link>
                </div>
            </div>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
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
                            <td :class="$style.loadding" colspan="11">
                                <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                            </td>
                        </tr>
                        <tr v-for="certification in certifications">
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
                                <button class="btn btn-primary btn-sm" v-show="certification.status === 0" @click.prevent="passCertification(certification.id)">通过</button>
                                <button class="btn btn-primary btn-sm" 
                                    v-show="certification.status !== 2" 
                                    data-toggle="modal" @click="openRejectModal(certification.id)">驳回</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 驳回modal start -->
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
                    <textarea class="form-control" v-model="reject.reject_content" @input="watchInput"></textarea>
                    <span class="text-danger" v-show="reject.reject_message">{{ reject.reject_message }}</span>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" @click="rejectCertification">确认</button>
              </div>
            </div>
          </div>
        </div>
        <!-- 驳回modal end-->
    </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const UserComponent = {
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
            page: 1,
            lastPage: 10,
        },
        reject: {
            certification_id: '',
            reject_content:'',
            reject_message: '',
        },
        keyword: '',
        successMessage:'',
        errorMessage: '',
    }),
    methods: {
        /**
         * 获取认证类型
         */
        getCertificationCategories () {
            request.get(
                createRequestURI('certification/categories'),
                {validateStatus: status => status === 200}
            ).then(response => {
                const {data: data} = response.data;
                this.categories.data = data;
            }).catch(({ response: { data: { errors = ['加载认证详情失败'] } = {} } = {} }) => {
            }); 
        },
        /**
         * 获取认证列表
         */
        getCertifications () {
            this.loadding = true;
            request.get(
                createRequestURI('certifications' + this.getQueryParams() ),
                { validateStatus: status => status === 200 }
            ).then(response => {
                this.loadding = false;
                const {data: data} = response.data;
                this.certifications = data;
            }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
                this.loadding = false;
            });
        },
        /**
         * 通过认证
         */
        passCertification (id) {
            request.patch(
                createRequestURI('certifications/' + id + '/pass'),
                {validateStatus: status => status === 201}
            ).then(({ data: { message: [ message ] = [] } }) => {
                this.successMessage = message;
                this.updateCertificationStatus(id, 1);
            }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
                this.errorMessage = message;
            });
        },
        /**
         * 驳回认证
         */
        rejectCertification () {
            if ( !this.reject.reject_content ) {
                this.reject.reject_message = '请填写驳回原因';
                return;
            }
            let id = this.reject.certification_id;
            request.patch(
                createRequestURI('certifications/' + id + '/reject'),
                {reject_content: this.reject.reject_content},
                {validateStatus: status => status === 201}
            ).then(({ data: { message: [ message ] = [] } }) => {
                this.successMessage = message;
                this.updateCertificationStatus(id, 2);
                $('#rejectModal').modal('hide')
            }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
                this.errorMessage = message;
                $('#rejectModal').modal('hide')
            });
        },
        /**
         * 监听输入
         */
        watchInput(e) {
            if ( e.data ) {
                this.reject.reject_message = '';
            }
        },
        /**
         * 打开驳回弹层
         */
        openRejectModal (id) {
            this.reject.certification_id = id;
            this.reject.reject_content = '';
            this.reject.reject_message = ''
            $('#rejectModal').modal('show');
        },
        /**
         * 更新认证状态
         */
        updateCertificationStatus (id, status) {
            for (let i=0; i < this.certifications.length; i++) {
                if ( this.certifications[i].id == id ) {
                     this.certifications[i].status = status;
                }
            }
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
            query += '&page=' + this.paginate.page;

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
            this.paginate.page = 1;
            this.certifications = {}; 
        },
        /**
         * 关闭提示
         */
        offAlert() {
            this.errorMessage = this.successMessage = '';
        }
    },
    created () {
        this.getCertificationCategories();
        this.getCertifications();
    },

};
export default UserComponent;
</script>
