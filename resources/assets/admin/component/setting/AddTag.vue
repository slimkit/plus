<template>
  <div class="container-fluid">
    <div class="page-header">
      <h4>添加标签</h4>
    </div>
    <form style="margin-bottom: 16px;">
      <div class="form-group">
        <label for="exampleInputEmail1">标签名字</label>
        <input v-model="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="标签名称">
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1">权重<small>(越大越靠前)</small></label>
        <input v-model="weight" type="text" class="form-control" id="exampleInputEmail1" placeholder="标签权重">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">标签分类</label>
        <div  class="btn-toolbar" role="group" aria-label="cate">
          <button
            type="button" 
            @click="setCategory(cate.id)" 
            :class="{ 'btn-info': category === cate.id}"
            v-for="cate in categories" 
            aria-label="cate" 
            :key="cate.id" 
            class=" btn btn-group btn-group-sm btn-default" 
            role="group" 
          >
            {{cate.name}}
          </button>
        </div>
      </div>
      <button type="submit" @click="send()" id="myButton" data-complete-text="添加成功" data-loading-text="提交中..." class="btn btn-default" autocomplete="off" :disabled="!canSend">
        添加
      </button>
    </form>
    <div v-show="add.error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisAddAreaError">
        <span aria-hidden="true">&times;</span>
      </button>
      <strong>Error:</strong>
      <p>{{ add.error_message }}</p>
    </div>
  </div>
</template>
<style scoped lang="scss">
  .btn-group {
    margin-bottom: 8px;
  }
</style>
<script>
  import request, { createRequestURI } from '../../util/request';

  const AddTag = {
    data: () => ({
      name: '',
      category: 0,
      weight: 0,
      categories: [],
      add: {
        loadding: false,
        error: false,
        error_message: ''
      }
    }),

    methods: {
      send () {
        const {
          name = '',
          category = 0,
          weight = 0
        } = this;
        if(!name || !category) {
          this.add.error = true;
          this.add.error_message = '参数不完整';
          return false;
        }
        let btn = $("#myButton").button('loading');

        request.post(createRequestURI('site/tags'), {
          name,category,weight
        }, {
          validateStatus: status => status === 201
        })
        .then ( response => {
          this.sendComplate(btn);
        })
        .catch(({ response: { data = {} } = {} }) => {
          btn.button('reset');

          let error = '添加标签失败';
          if(data.name) {
            error = data.name[0];
          }
          if(data.category) {
            error = data.category[0];
          }
          this.add.loadding = false;
          this.add.error = true;
          this.add.error_message = error;
        });
      },

      sendComplate(btn) {
        btn.button('complete');
        setTimeout(() => {
          btn.button('reset');
          this.name = '',
          this.category = 0;
          this.weight = 0;
        }, 1500);
      },

      dismisAddAreaError () {
        this.add.error = false;
      },

      setCategory (id) {
        this.category = id;
      },

      // 获取标签分类
      getCategories () {
        request.get(createRequestURI('site/tags/categories'),{
          validateStatus: status => status === 200
        })
        .then(({ data = [] }) => {
          this.categories = data;
        })
        .catch( () => {

        });
      }
    },

    computed: {
      // 按钮是否处于激活状态
      canSend() {
        return (this.name != '') && (this.category != 0);
      }
    },
    created () {
      this.getCategories();
    }
  };

  export default AddTag;
</script>