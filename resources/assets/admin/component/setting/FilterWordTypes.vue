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
</style>

<template>
    <div :class="$style.container">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div v-show="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" @click.prevent="offAlert">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ errorMessage }}
            </div>
            <div v-show="successMessage" class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" @click.prevent="offAlert">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ successMessage }}
            </div>
          </div>
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" @click="selectAll"></th>
                        <th>类型</th>
                        <th>状态</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-show="loadding">
                        <!-- 加载动画 -->
                        <td :class="$style.loadding" colspan="4">
                            <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                        </td>
                    </tr>
                    <tr v-for="type in types">
                        <td><input type="checkbox" :value="type.id" :checked="checked"></td>
                        <td>{{ type.name }}</td>
                        <td>
                          <a class="label" 
                             :class="type.status ? 'label-success' : 'label-danger'" 
                             href="javascript:;" @click="changeStatus(type.id)">{{ type.status ? '开启' : '关闭' }}</a>
                        </td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const FilterWordCategory = {
    data: () => ({
      loadding: true,
      types: {},
      checked: false,
      errorMessage: '',
      successMessage: '',
    }),
    methods: {
      getTypes () {
        request.get(
          createRequestURI('filter-word-types'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.types = response.data;
        }).catch(({ response: { data = {} } = {} }) => {
          let {name = []} = data;
          let [ errorMessage ] = [...name];
          this.errorMessage = errorMessage;
        });
      },
      changeStatus (id) {
        request.patch(
          createRequestURI('filter-word-types/' + id + '/status'),
          { validateStatus: status => status === 200 }
        ).then(({ data: { message: [ message ] = [] } }) => {
            this.successMessage = message;
            this.getTypes();
        }).catch(({ response: { data = {} } = {} }) => {
          let {name = []} = data;
          let [ errorMessage ] = [...name];
          this.errorMessage = errorMessage;
        });
      },
      selectAll () {
        this.checked = this.checked ? false : true;
      },
      offAlert () {
        this.errorMessage = this.successMessage = '';
      },
    },
    created () {
      this.getTypes();
    },
};
export default FilterWordCategory;
</script>
