<template>
    <div class="container-fluid" style="margin-top:10px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
              认证列表
              <router-link tag="a" class="btn btn-link pull-right btn-xs" :to="{ name: 'certification:add' }" role="button">
                <span class="glyphicon glyphicon-plus"></span>
                添加
              </router-link>
            </div>
            <div class="panel-heading">
                <!-- 数据过滤 -->
                <div class="form-inline">
                    <div class="form-group">
                        <label>状态：</label>
                        <select class="form-control" v-model="filter.status">
                            <option :value="item.value" :key="item.value" v-for="item in statuss.data">{{ item.status }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>类型：</label>
                        <select class="form-control" v-model="filter.certification_name">
                           <option value="">全部</option>
                           <option :value="item.name" :key="item.name" v-for="item in categories.data">{{ item.display_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" v-model="filter.keyword" placeholder="用户名/支持模糊匹配">
                      <router-link class="btn btn-default" tag="button" :to="{ path: '/certifications/', query: searchQuery }">
                        搜索
                      </router-link>
                    </div>
                </div>
            </div>
            <div class="panel-heading">
              <b>统计：</b>
              <span class="text-primary" :key="index" v-for="(count, index) in counts">{{ `${index}${count}` }} </span>  
            </div>
            <div class="panel-body">
                <!-- 认证列表 -->
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th width="5%">用户名</th>
                        <th width="6%">真实姓名</th>
                        <th width="8%">机构名称</th>
                        <th width="8%">机构地址</th>
                        <th width="7%">手机号码</th>
                        <th width="10%">身份证号码</th>
                        <th width="6%">认证类型</th>
                        <th width="20%">认证描述</th>
                        <th width="8%">认证资料</th>
                        <th width="8%">认证状态</th>
                        <th width="8%">认证时间</th>
                        <th width="5%">操作</th>
                      </tr>
                    </thead>
                    <tbody>
                        <!-- 加载 -->
                        <table-loading :loadding="loadding" :colspan-num="12"></table-loading>
                        <template v-if="certifications.length">
                          <tr v-for="(certification, index) in certifications" :key="certification.id">
                              <td>{{ certification.user.name }}</td>
                              <td>{{ certification.data.name }}</td>
                              <td>{{ certification.data.org_name }}</td>
                              <td>{{ certification.data.org_address }}</td>
                              <td>{{ certification.data.phone }}</td>
                              <td>{{ certification.data.number }}</td>
                              <td>{{ certification.category.display_name }}</td>
                              <td>{{ certification.data.desc }}</td>
                              <td>
                                <a :href="attachmentPath+file" 
                                target="__blank" 
                                v-for="(file,index) in certification.data.files" :key="file"> 附件[{{ index+1 }}]</a>
                              </td>
                              <td>{{ statuss.display[certification.status] }}</td>
                              <td>{{ certification.updated_at | localDate }}</td>
                              <td>
                                  <router-link type="button"
                                  class="btn btn-primary btn-sm"
                                  v-show="certification.status === 1 || certification.status == 2"
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
            <!-- 分页 -->
            <div class="text-center">
              <offset-paginator class="pagination" :total="total" :offset="offset" :limit="15">
                <template slot-scope="pagination">
                  <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
                    <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
                    <router-link v-else :to="offsetPage(pagination.offset)">{{ pagination.page }}</router-link>
                  </li>
                </template>
              </offset-paginator>
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
import request, { createRequestURI } from "../../util/request";

const certificationComponent = {
  data: () => ({
    loadding: true,
    total: 0,
    counts: {},
    categories: [],
    certifications: [],
    attachmentPath: "/api/v2/files/",
    reject: {
      id: "",
      content: "",
      message: ""
    },
    pass: {
      id: "",
      desc: "",
      message: ""
    },
    filter: {
      keyword: "",
      status: "",
      certification_name: ""
    },
    message: {
      error: null,
      success: null
    },
    statuss: {
      display: ["待审核", "已审核", "已驳回"],
      data: [
        { status: "全部", value: "" },
        { status: "待审核", value: 0 },
        { status: "已审核", value: 1 },
        { status: "已驳回", value: 2 }
      ]
    }
  }),

  watch: {
    $route: function($route) {
      this.total = 0;
      this.getCertifications({ ...$route.query });
    }
  },

  computed: {
    offset() {
      const {
        query: { offset = 0 }
      } = this.$route;
      return parseInt(offset);
    },
    searchQuery() {
      return { ...this.filter, offset: 0 };
    }
  },

  methods: {
    /**
     * Update certifcation status.
     *
     * @param {Integer} id
     * @param {Integer} status
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateItemStatus(id, status) {
      this.certifications = this.certifications.map(certification => {
        if (parseInt(id) === parseInt(certification.id)) {
          return { ...certification, status };
        }

        return certification;
      });
    },

    /**
     * 获取认证类型
     */
    getCertificationCategories() {
      return new Promise((resolve, reject) => {
        request
          .get(createRequestURI("certification/categories"), {
            validateStatus: status => status === 200
          })
          .then(response => {
            resolve(response.data);
          })
          .catch(
            ({
              response: {
                data: { errors = ["加载认证栏目失败详情失败"] } = {}
              } = {}
            }) => {
              reject(errors);
            }
          );
      });
    },
    /**
     * 获取认证列表
     */
    getCertifications(query = {}) {
      this.loadding = true;
      this.certifications = [];
      request
        .get(createRequestURI(`certifications`), {
          validateStatus: status => status === 200,
          params: { ...query, limit: 15 }
        })
        .then(({ data, headers: { "x-certifications-total": total } }) => {
          this.loadding = false;
          this.total = parseInt(total);
          this.certifications = data.items;
          this.counts = data.counts;
        })
        .catch(
          ({ response: { data: { errors = ["加载失败"] } = {} } = {} }) => {
            this.loadding = false;
            let Message = new plusMessageBundle(errors);
            this.message.error = Message.getMessage();
          }
        );
    },
    /**
     * 打开通过认证模态框
     */
    openPassModal(index) {
      let certification = this.certifications[index];

      this.pass.id = certification.id;
      this.pass.desc = certification.data.desc;

      $("#passModal").modal("show");
    },
    /**
     * 处理通过认证
     */
    passCertification() {
      let { desc: desc, id: id } = this.pass;
      request
        .patch(
          createRequestURI("certifications/" + id + "/pass"),
          { desc: desc },
          { validateStatus: status => status === 201 }
        )
        .then(({ data: { message: [message] = [] } }) => {
          $("#passModal").modal("hide");
          this.message.success = message;
          this.updateItemStatus(id, 1);
          // this.getCertifications(this.$route.query);
        })
        .catch(
          ({ response: { data: { message: [message] = [] } = {} } = {} }) => {
            this.pass.message = message;
          }
        );
    },
    /**
     * 驳回认证
     */
    rejectCertification() {
      if (!this.reject.content) {
        this.reject.message = "请填写驳回原因";
        return;
      }
      let { id: id, content: content } = this.reject;
      request
        .patch(
          createRequestURI("certifications/" + id + "/reject"),
          { reject_content: content },
          { validateStatus: status => status === 201 }
        )
        .then(({ data: { message: [message] = [] } }) => {
          this.message.success = message;
          this.updateItemStatus(id, 2);
          // this.getCertifications(this.$route.query);
          $("#rejectModal").modal("hide");
        })
        .catch(
          ({ response: { data: { message: [message] = [] } = {} } = {} }) => {
            this.message.error = message;
            $("#rejectModal").modal("hide");
          }
        );
    },
    /**
     * 打开驳回弹层
     */
    openRejectModal(id) {
      this.reject.id = id;
      this.reject.content = "";
      this.reject.message = "";
      $("#rejectModal").modal("show");
    },
    offsetPage(offset) {
      return { path: "/certifications", query: { ...this.filter, offset } };
    }
  },
  created() {
    let promise = this.getCertificationCategories();
    promise.then(
      data => {
        this.loadding = false;
        if (data.length) {
          this.categories.data = data;
          this.getCertifications(this.$route.query);
        } else {
          this.message.error = "认证类型加载失败";
        }
      },
      error => {
        this.message.error = error;
      }
    );
  }
};
export default certificationComponent;
</script>
