<style lang="css" module>
    .container {
        padding-top: 15px;
    }
    .loadding {
        text-align: center;
        font-size: 42px;
        padding-top: 100px;
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
            <!-- 加载动画 -->
            <div v-show="loadding" :class="$style.loadding">
                <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </div>

            <div class="col-md-6 col-md-offset-3" v-show="!loadding">
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
                <div class="form-group">
                    <label for="">类型名：</label>
                    <input type="text" class="form-control" v-model="category.name" disabled>
                </div>
                <div class="form-group">
                    <label for="">显示名：</label>
                    <input type="text" class="form-control" v-model="category.display_name">
                </div>
                <div class="form-group">
                    <label for="">描述：</label>
                    <textarea class="form-control" v-model="category.description"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit"
                           value="提交"
                           class="btn btn-primary btn-sm"
                           @click.prevent="updateCertificationCategory(category.name)">
                </div>
            </div>
        </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const CategoryEditComponent = {
    data: () => ({
      loadding: true,
      errorMessage: '',
      successMessage:'',
      category:{},
    }),
    methods: {
        getCertificationCategory (name) {
          this.loadding = true;
          request.get(
            createRequestURI('certification/categories/'+name),
            {validateStatus: status => status === 200}
          ).then(response => {
            this.loadding = false;
            this.category = response.data;
          }).catch(({ response: { data: { errors = ['加载认证详情失败'] } = {} } = {} }) => {
            this.loadding = false;
          });
        },
        updateCertificationCategory (name) {
          request.put(
            createRequestURI('certification/categories/' + name),
            { ...this.category },
            { validateStatus: status => status === 201 }
          ).then(({ data: { message: [ message ] = [] } }) => {
            this.successMessage = message;
          }).catch(({ response: { data = {} } = {} }) => {
            const { display_name = [] } = data;
            const [ errorMessage ] = [...display_name];
            this.errorMessage = errorMessage;
            this.adding = false;
          });
        },
        offAlert() {
          this.errorMessage = this.successMessage = '';
        }
    },
    created () {
      this.getCertificationCategory(this.$route.params.name);
    },

};
export default CategoryEditComponent;
</script>
