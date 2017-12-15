<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            条件筛选
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
                        <option value="">全部</option>
                        <option v-for="item in cates" :value="item.id">{{item.name}}</option>
                    </select>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <!-- 审核状态 0: 待审核, 1 审核通过, 2 被拒 -->
                        <label class="radio-inline">
                            <input type="radio" name="state" v-model='cur_state' value=""> 全部
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="state" v-model='cur_state' :value="0"> 待审核
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="state" v-model='cur_state' :value="1"> 审核通过
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="state" v-model='cur_state' :value="2"> 被拒
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
<script>
import { admin } from "../../../axios/";
export default {
    name: "pinneds-filters",
    data() {
        return({
            cates: [],
            cur_cate: "",
            key_words: "",
            cur_state: "",
        });
    },
    watch: {
        cur_cate(val) {
            this.formatQuery();
        },
        cur_state(val) {
            this.formatQuery();
        }
    },
    methods: {
        initQuery() {
            const { key = "", state = "", cate_id = "", } = this.$route.query;
            this.key_words = key;
            this.cur_state = state;
            this.cur_cate = cate_id;
        },
        formatQuery() {
            const query = {
                key: this.key_words || "",
                state: this.cur_state >= 0 ? this.cur_state : '',
                cate_id: this.cur_cate >= 0 ? this.cur_cate : ''
            };

            this.$router.push({ path: '/pinneds', query: { ...query } });
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