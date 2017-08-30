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
    .image {
        max-width:200px;
        margin-bottom: 10px;
    }
</style>

<template>
        <div :class="$style.container">
            <!-- 加载动画 -->
            <div v-show="loadding" :class="$style.loadding">
                <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </div>
            <div class="col-md-6 col-md-offset-3" v-show="!loadding">
                <div v-show="errorMessage" class="alert alert-danger alert-dismissible affix-top" role="alert">
                    <button type="button" class="close" @click.prevent="offAlert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ errorMessage }}
                </div>
                <div v-show="successMessage" class="alert alert-success alert-dismissible affix-top" role="alert">
                    <button type="button" class="close" @click.prevent="offAlert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ successMessage }}
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>分类名称：</label>
                    <input type="text" class="form-control" v-model="category.name">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" 
                    @click.prevent="update">添加分类</button>
                </div>
            </div>
        </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const FilterWordCategoryUpdate = {
    data: () => ({
        loadding: true,
        errorMessage: '',
        successMessage: '',
        category: {
          id: '',
          name: '',
        }
    }),
    methods: {
      getCategory () {
        request.get(
          createRequestURI('filter-word-categories/' + this.category.id ),
          { validateStatus: status => status === 200 }
        ).then(response => {
            this.loadding = false;
            this.category = response.data;
        }).catch(({ response: { data = {} } = {} }) => {
            this.loadding = false;
        });
      },
      update () {
        if (!this.category.name) {
          this.errorMessage = '分类名称不能为空';
          return;
        }
        request.patch(
          createRequestURI('filter-word-categories/' + this.category.id ),
          { ...this.category },
          { validateStatus: status => status === 201 }
        ).then(({ data: { message: [ message ] = [] } }) => {
          this.successMessage = message;
        }).catch(({ response: { data = {} } = {} }) => {
          let {name = []} = data;
          let [ errorMessage ] = [...name];
          this.errorMessage = errorMessage;
        });
      },
      offAlert () {
        this.errorMessage = this.successMessage = '';
      }
    },
    created () {
      this.category.id = this.$route.params.id;
      this.getCategory();
    },

};
export default FilterWordCategoryUpdate;
</script>
