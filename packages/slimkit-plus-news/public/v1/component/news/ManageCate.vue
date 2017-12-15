<template>
    <div class="component-container container-fluid">
        <div class="panel panel-primary">
            <!-- header -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-12">{{ title }}</div>
                </div>
            </div>
            <!-- /header -->
            <!-- body -->
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- 名称 -->
                            <div class="form-group">
                                <label for="title" class="col-sm-1 control-label">名称</label>
                                <div class="col-sm-11">
                                    <input ref='Input' type="text" class="form-control" id="title" aria-describedby="title-help-block" placeholder="请输入名称" v-model="cateName" />
                                </div>
                            </div>
                            <!-- /名称 -->
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-11">
                            <button v-if="adding" type="button" class="btn btn-primary" disabled="disabled">
                                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                            </button>
                            <button v-else type="button" class="btn btn-primary" @click="ManageCates">{{title}}</button>
                        </div>
                    </div>
                    <!-- /Button -->
                </div>
            </div>
            <!-- /body -->
        </div>
        <!-- 错误提示 -->
        <div class="row" style="margin:0; min-height:76px;">
            <div class="alert alert-dismissible" v-show="tips.open" :class="`alert-${tips.type}`">
                <button type="button" class="close" @click.prevent="clearTips">
                    <span aria-hidden="true">&times;</span>
                </button> {{ tips.message }}
            </div>
        </div>
        <!-- /错误提示 -->
    </div>
</template>
<script>
import request, {
    createRequestURI
} from '../../util/request';


const INFO = "info",
    SUCCESS = "success",
    ERROR = "danger",
    WARN = "warning";
export default {
    name: 'ManageCate',
    props: {
        curId: [Number, String],
        curName: String,
    },
    data() {
        return ({
            cateId: this.curId || null,
            cateName: this.curName || "",
            loadding: false,
            adding: false,
            tips: {
                open: false,
                type: "info",
                message: "",
            },
            timer: null,
        });
    },
    watch: {
        curName(val) {
            this.cateName = val;
        },
        curId(val) {
            console.log(123);
            this.cateId = val;
        }
    },
    computed: {
        title() { return this.curId ? "编辑分类" : "添加分类"; }
    },
    updated() {this.$refs.Input.focus();},
    methods: {

        // 显示提示信息
        showTips({ type = "info", message = "" }) {
            if (this.timer) {
                clearTimeOut(this.timer);
                this.timer = null
            };
            if (message) {
                this.tips = {
                    ...this.tips,
                    type,
                    message,
                    open: true,
                }

                this.timer = setTimeout(() => {
                    this.clearTips();
                }, 4000);
            }
            return;
        },

        // 清除提示信息
        clearTips() {
            this.timer = null;
            this.tips = {
                open: false,
                message: "",
                type: "info",
            }
        },
        ManageCates() {
            if (this.cateName) {
                if (this.cateName.length > 6) {
                    this.showTips({ type: WARN, message: "分类名称不能超过6个字" });
                    return;
                }
                let param = {
                    cate_id: this.cateId,
                    name: this.cateName,
                }
                if (!this.cateId) { delete param.cate_id };

                request.post('/news/admin/news/handle_cate', param)
                    .then(({ data: { status, message } = {} }) => {
                        let type = status ? SUCCESS : ERROR;
                        this.showTips({ type, message });
                    })
                    .catch(({ response: { data: { message } } = {} }) => {
                        console.log(message);
                    })
            } else {
                this.showTips({ type: WARN, message: "请输入分类名称" });
                this.$refs.Input.focus();
            }
        }
    },
    created() {},
};
</script>