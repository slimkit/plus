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
                    <label><span class="text-danger">*</span>名称：</label>
                    <input type="text" class="form-control" v-model="sensitive.name">
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>类型：</label>
                    <select class="form-control" v-model="sensitive.filter_word_category_id">
                       <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>分类：</label>
                   <select class="form-control" v-model="sensitive.filter_word_type_id">
                       <option v-for="type in types" :value="type.id">{{ type.name }}</option>
                   </select>
                </div>
                <div class="form-group">
                      <button type="submit" 
                      @click="add" id="ok-btn" 
                      data-loading-text="提交中..." 
                      class="btn btn-primary" 
                      autocomplete="off" 
                      :disabled="disabled">确认</button>
                </div>
            </div>
        </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const FilterWordCategoryAdd = {
    data: () => ({
        loadding: true,
        errorMessage: '',
        successMessage: '',
        categories: {},
        types: {},
        sensitive:{
            name: '',
            filter_word_category_id: '',
            filter_word_type_id: '',
        },
        disabled: true,
    }),
    methods: {
      add () {
        let error = this.validateForm();
        if ( error ) {
            this.errorMessage = error;
            return;
        }
        let btn = $("#ok-btn").button('loading')
        request.post(
          createRequestURI('sensitive-words'),
          { ...this.sensitive },
          { validateStatus: status => status === 201 }
        ).then(({ data: { message: [ message ] = [] } }) => {
          this.successMessage = message;
          btn.button('reset');
        }).catch(({ response: { data = {} } = {} }) => {
          let {name = [], filter_word_category_id = [], filter_word_type_id = []} = data.errors;
          let [ errorMessage ] = [...name, ...filter_word_category_id, ...filter_word_type_id];
          btn.button('reset');
          this.errorMessage = errorMessage;
        });
      },
      getCategories () {
        return new Promise((resolve, reject) => {
            request.get(
              createRequestURI('filter-word-categories'),
              { validateStatus: status => status === 200 }
            ).then(response => {
              this.loadding = false;
              resolve(response.data);
            }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
                this.loadding = false;
                reject(response.data);
            });
        });
      },
      getTypes () {
        return new Promise((resolve, reject) => {
            request.get(
              createRequestURI('filter-word-types'),
              { validateStatus: status => status === 200 }
            ).then(response => {
              this.loadding = false;
              resolve(response.data);
            }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
                this.loadding = false;
                reject(message);
            });
        });
      },
      offAlert () {
        this.errorMessage = this.successMessage = '';
      },
      validateForm () {
        let { name, filter_word_category_id, filter_word_type_id } = this.sensitive;
        let errorMessage = '';
        if ( !name ) errorMessage += "名称必须填写,";
        if ( !filter_word_category_id ) errorMessage += "类型必须选择,";
        if ( !filter_word_type_id ) errorMessage += "分类必须选择";
        return errorMessage;
      }
    },
    created () {
      let promise = this.getTypes();
      promise.then(data => {
        if (data.length) {
          this.types = data;

          let promise = this.getCategories();
          promise.then(data => {
            if (data.length) {
                this.categories = data;
                this.disabled = false;
            } else {
                this.errorMessage = '请填充过滤词类型';
            }
          }, error => {
            this.errorMessage = error; 
          });

        } else {
          this.errorMessage = '请添加过滤词类别';
        }
      }, error => {
        this.errorMessage = error; 
      });
    },

};
export default FilterWordCategoryAdd;
</script>