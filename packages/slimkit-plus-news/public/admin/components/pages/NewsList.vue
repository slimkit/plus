<template>
    <div>
        <module-news-filters v-model="trash">
            <slot>
                <ui-page v-model="current_page" :last="total"></ui-page>
            </slot>
        </module-news-filters>
        <div class="panel panel-default">
            <div class="panel-body">
                <module-news-list :datas="newsArray" :update="updateNews" :publishMessage="publishMessage"></module-news-list>
            </div>
        </div>
        <ui-alert :type="message.type" v-show="message.open">
            {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
        </ui-alert>
    </div>
</template>
<script>
import { admin } from '../../axios';
import components from "../modules/newslist/";
export default {
    name: "news-list",
    components,
    data() {
        return({
            URI: "/news",
            newsArray: [],
            trash: false,
            current_page: 1,
            loading: false,
            total: 0,
            message: {
                open: false,
                type: '',
                data: {}
            }
        });
    },
    watch: {
        '$route': function({ query = {} }) {
            this.fetchNews({ ...query });
        },
        current_page(val) {
            this.fetchNews(this.$route.query);
        },
        trash(val) {
            this.URI = val ? '/news/recycle' : '/news';
            this.fetchNews(this.$route.query);
        }
    },
    methods: {
        updateNews() {
            this.fetchNews(this.$route.query);
        },
        fetchNews(query = {}) {
            this.loading = true;
            const params = { ...query, limit: 15, page: this.current_page };
            admin.get(this.URI, {
                validateStatus: status => status === 200,
                params
            }).then(({ data: { data = [], current_page = 1, last_page = 0 } }) => {
                this.loading = false;
                this.total = last_page;
                this.newsArray = data;
                this.current_page = current_page;
            }).catch(({ response: { data = {} } = {} } = {}) => {
                this.loading = false;
                this.publishMessage(data, 'danger');
            });
        },
        publishMessage(data, type, ms = 5000) {
            this.message = { open: true, data, type };
            setTimeout(() => {
                this.message.open = false;
            }, ms);
        },
    },
    created() {
        this.updateNews();
    }
}
</script>