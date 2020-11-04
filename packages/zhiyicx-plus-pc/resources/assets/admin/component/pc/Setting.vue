<template>
    <div class="component-container container-fluid">
        <div class="panel panel-default">
            <!-- Title -->
            <div class="panel-heading">基础配置</div>
            <!-- Loading -->
            <div v-if="loadding.state === 0" class="panel-body text-center">
                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                加载中...
            </div>
            <!-- Body -->
            <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="url">站点logo</label>
                    <div class="col-sm-4">
                        <Upload :imgs='site.logo' @getTask_id="getlogo_id" @updata='updataLogo'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="position">登录背景图</label>
                    <div class="col-sm-4">
                        <Upload :imgs='site.loginbg' @getTask_id="getloginbg_id" @updata='updataIoginbg'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="position">PC端 </label>
                    <div class="col-md-4">
                        <label class="radio-inline">
                            <input type="radio" value="1" v-model="site.status"> 开启
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="0" v-model="site.status"> 关闭
                        </label>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label" for="position">站点名称</label>
                    <div class="col-sm-4">
                        <input type="text" name="site_name" class="form-control" id="site_name"
                               v-model="site.site_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="position">版权信息</label>
                    <div class="col-sm-4">
                        <input type="text" name="site_copyright" class="form-control" id="site_copyright"
                               v-model="site.site_copyright">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="position">技术支持</label>
                    <div class="col-sm-4">
                        <input type="text" name="site_technical" class="form-control" id="site_technical"
                               v-model="site.site_technical">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="position">统计代码</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" id="stats_code" name="stats_code"
                                  v-model="site.stats_code"></textarea>
                    </div>
                </div>

                <!-- button -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-2">
                        <button v-if="submit.state === true" class="btn btn-primary" type="submit" disabled="disabled">
                            <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                            提交...
                        </button>
                        <button v-else type="button" class="btn btn-primary" @click.stop.prevent="submitHandle">提交
                        </button>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-primary" @click="cacheclear()">清除站点缓存</button>
                    </div>
                    <div class="col-sm-6 help-block">
                        <span :class="`text-${submit.type}`">{{ submit.message }}</span>
                    </div>
                </div>
            </div>
            <!-- Loading Error -->
            <div v-else class="panel-body">
                <div class="alert alert-danger" role="alert">{{ loadding.message }}</div>
                <button type="button" class="btn btn-primary" @click.stop.prevent="request">刷新</button>
            </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request'
import Upload from '../Upload_v2'

const NavmanageComponent = {
    components: {
        Upload
    },
    data: () => ({
        loadding: {
            state: 0,
            message: ''
        },
        submit: {
            state: false,
            type: 'muted',
            message: ''
        },
        site: {
            logo: '',
            loginbg: '',
            status: 1,
            stats_code: '',
            site_name: 'ThinkSNS',
            site_copyright: 'Powered by ThinkSNS ©2017 ZhishiSoft All Rights Reserved.',
            site_technical: 'ThinkSNS+'
        }
    }),
    methods: {
        cacheclear () {
            request.put(createRequestURI('site/cacheclear'), {
                validateStatus: status => status === 200
            }).then(({ data = {} }) => {
                this.submit.state = false
                this.submit.type = 'success'
                this.submit.message = data.message
            })
        },
        requestSiteInfo () {
            request.get(createRequestURI('site/baseinfo'), {
                validateStatus: status => status === 200
            }).then(({ data = {} }) => {
                this.site = data
                this.loadding.state = 1
            }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
                this.loadding.state = 0
                window.alert(message)
            })
        },
        submitHandle () {
            this.submit.state = true
            request.patch(
                createRequestURI('site/baseinfo'),
                { site: this.site },
                { validateStatus: status => status === 201 }
            ).then(({ message = '操作成功' }) => {
                this.submit.state = false
                this.submit.type = 'success'
                this.submit.message = message
            }).catch(({ response: { message: [message = '提交失败'] = [] } = {} }) => {
                this.submit.state = false
                this.submit.type = 'danger'
                this.submit.message = message
            })
        },
        // 获取图片ID || 图片上传任务ID
        getlogo_id (task_id) {
            this.site.logo = task_id
        },
        getloginbg_id (task_id) {
            this.site.loginbg = task_id
        },
        // 清除图片ID || 任务ID
        updataLogo () {
            this.site.logo = null
        },
        updataIoginbg () {
            this.site.loginbg = null
        }
    },
    created () {
        this.requestSiteInfo()
    }
}

export default NavmanageComponent
</script>
