<template>
    <div>
        <module-pinneds-filters></module-pinneds-filters>
        <div class="panel panel-default">
            <module-pinneds-list :datas="pinneds" :update="updatePinneds" :publishMessage="publishMessage"></module-pinneds-list>
        </div>
        <ui-alert :type="message.type" v-show="message.open">
            {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
        </ui-alert>
    </div>
</template>
<script>
import components from "../modules/newspinneds/";
import { admin } from "../../axios/";
export default {
    name: "news-pinneds",
    components,
    data() {
        return({
            URI: "/news/pinneds",
            pinneds: [],
            message: {
                open: false,
                type: '',
                data: {}
            }
        });
    },
    watch: {
        "$route": function({ query = {} }) {
            this.fetchPinneds(query);
        }
    },
    methods: {
        publishMessage(data, type, ms = 5000) {
            this.message = { open: true, data, type };
            setTimeout(() => {
                this.message.open = false;
            }, ms);
        },
        fetchPinneds(query = {}) {
            admin.get(this.URI, {
                validateStatus: status => status === 200,
                params: { ...query, limit: 15 }
            }).then(({ data = [] }) => {
                this.pinneds = data;
            }).catch((err) => {
                this.publishMessage(err, 'danger');
            });
        },
        updatePinneds() {
            this.fetchPinneds(this.$route.query);
        }
    },
    created() {
        this.updatePinneds();
    }
}
</script>