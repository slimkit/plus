<template>
<div class="form-inline">
    <div class="form-group">
        <input type="text" 
        class="form-control" 
        placeholder="用户名" 
        v-model="filters.name">
    </div>
    <div class="form-group">
        <input type="number" 
        class="form-control" 
        placeholder="用户ID" 
        v-model="filters.user">
    </div>
    <div class="form-group">
        <input type="number"
                class="form-control"
                placeholder="用户名手机"
                v-model="filters.phone">
    </div>
    <div class="form-group">
        <input type="text"
                class="form-control"
                placeholder="用户邮箱"
                v-model="filters.email">
    </div>
    <div class="form-group">
        <router-link :to="{ path: '', query: queries }" class="btn btn-default">搜索</router-link>
    </div>
</div>
</template>
<script>
import lodash from 'lodash';

export default {
    name: 'currency-search',
    data() {
        return {
            filters: {
                user: '',
                name: '',
                phone: '',
                email: '',
            },
        }
    },
    computed: {
        queries() {
            return lodash.mapValues(this.filters, p => {
                if (p) return p;
            })
        },
    },
}
</script>