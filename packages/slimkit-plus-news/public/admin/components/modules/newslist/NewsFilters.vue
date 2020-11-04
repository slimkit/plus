<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            条件筛选
            <a href="javascript:;" @click="isTrash = ! isTrash" v-if="isTrash" class="pull-right">
                <span class="glyphicon glyphicon-list"></span> 资讯列表
            </a>
            <a href="javascript:;" @click="isTrash = ! isTrash" v-else class="pull-right">
                <span class="glyphicon glyphicon-trash"></span> 回收站
            </a>
            <router-link :to="{ path: '/manage' }" class="pull-right" style="margin-right: 20px;" replace>
                <span class="glyphicon glyphicon-plus"></span> 添加资讯
            </router-link>
            <div class="form-group flex-row">
                <!-- 文章状态 0-正常 1-待审核 2-草稿 3-驳回  -->
                <label class="radio-inline">
                    <input type="radio" name="state" v-model='cur_state' value=""> 全部
                </label>
                <label class="radio-inline">
                    <input type="radio" name="state" v-model='cur_state' :value="0"> 正常
                </label>
                <label class="radio-inline">
                    <input type="radio" name="state" v-model='cur_state' :value="1"> 待审核
                </label>
                <label class="radio-inline">
                    <input type="radio" name="state" v-model='cur_state' :value="2"> 草稿
                </label>
                <label class="radio-inline">
                    <input type="radio" name="state" v-model='cur_state' :value="3"> 驳回
                </label>
                <label class="checkbox-inline" style="margin-left: 10px">
                    <input type="checkbox" name="recommend" v-model='recommend'> 只显示推荐
                </label>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="标题" v-model="key_words">
                        <span class="input-group-btn">
                            <input class="btn btn-default" type="button" @click="formatQuery" value='搜索' />
                        </span>
                    </div>
                </div>
                <div class="col-xs-3">
                    <select class="form-control" v-model='cur_cate'>
                        <option value="">全部分类</option>
                        <option v-for="item in cates" :value="item.id">{{item.name}}</option>
                    </select>
                </div>
                <div class="col-xs-6">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { admin } from "../../../axios";

export default {
    name: "news-filters",
    data() {
        return({
            cates: [],
            cur_cate: "",
            cur_state: "",
            key_words: "",
            isTrash: false,
            recommend: false
        });
    },
    watch: {
        value(val) {
            this.isTrash = val;
        },
        isTrash(val) {
            this.$emit('input', val);
        },
        cur_cate(val) {
            this.formatQuery();
        },
        cur_state(val) {
            this.formatQuery();
        },
        recommend(val){
            this.formatQuery();
        }
    },
    methods: {
        initQuery() {
            const {
                key = "",
                    state = "",
                    cate_id = "",
                    recommend = "",
            } = this.$route.query;

            this.key_words = key;
            this.cur_state = state;
            this.cur_cate = cate_id;
            this.recommend = recommend;
        },
        
        formatQuery() {
            const query = {
                key: this.key_words || "",
                state: this.cur_state >= 0 ? this.cur_state : '',
                cate_id: this.cur_cate >= 0 ? this.cur_cate : '',
                recommend: this.recommend
            };

            this.$router.push({ path: '/newslist', query: { ...query } });
        },

        getCates(cb = () => {}) {
            admin.get(`/news/cates`, {
                validateStatus: status => status === 200,
            }).then(({ data = [] }) => {
                this.cates = [...data];
                cb();
            }).catch(err => {
                this.publishMessage(err, "danger");
            });
        },
    },
    created() {
        this.getCates(this.initQuery());
    }
}
</script>

<style lang='scss'>
    .flex-row{
        display: inline-flex;
        align-items: center;
        margin-left: 10px;
        margin-bottom: 0 !important;
        > div[class*='col']{
            height: 36px;
        }
    }
</style>