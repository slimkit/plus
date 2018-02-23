<template>
    <div class="form-inline">
        <div class="form-inline">
            <div class="form-group">
                <input type="text"
                       class="form-control"
                       placeholder="用户名"
                       v-model="filters.name">
            </div>
            <div class="form-group">
                <input type="text"
                       class="form-control"
                       placeholder="用户ID"
                       v-model="filters.user">
            </div>
            <div class="form-group">
                <select class="form-control" v-model="filters.action">
                    <option value="">全部</option>
                    <option value="1">增加</option>
                    <option value="-1">减少</option>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" v-model="filters.state">
                    <option value="">全部</option>
                    <option value="0">等待</option>
                    <option value="1">成功</option>
                    <option value="-1">失败</option>
                </select>
            </div>
            <div class="form-group">
                <router-link :to="{ path: '', query: queries }" class="btn btn-default">确认</router-link>
            </div>
        </div>
    </div>
</template>
<script>
    import lodash from 'lodash';

    export default {
        name: 'water-search',
        data() {
            return {
                filters: {
                    name: '',
                    user: '',
                    action: '',
                    state: '',
                }
            }
        },
        computed: {
            queries() {
                return lodash.mapValues(this.filters, p => {
                    if (p) return p;
                })
            }
        }
    }
</script>