<template>
  <div class="container-fluid">
    <div class="page-header">
      <h4>编辑标签</h4>
    </div>
    <form style="margin-bottom: 16px;">
      <div class="form-group">
        <label for="exampleInputEmail1">标签名字</label>
        <input v-model="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="标签名称">
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1">标签权重<small>(越大越靠前)</small></label>
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
      <button type="button" @click="send()" id="myButton" data-complete-text="修改成功" data-loading-text="提交中..." class="btn btn-default" autocomplete="off" :disabled="!canSend">
        修改
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

  const UpdateTag = {
    data: () => ({
      tag: {},
      tag_id: 0,
      name: '',
      category: 0,
      weight: null,
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
          tag_id = 0,
          weight = null
        } = this;
        if(!name || !category || !tag_id) {
          this.add.error = true;
          this.add.error_message = '参数不完整';
          return false;
        }

        let data = {};
        if (name != this.tag.name) {
          data.name = name;
        }

        if (category != this.tag.tag_category_id) {
          data.category = category;
        }

        if (weight != this.tag.weight) {
          data.weight = parseInt(weight);
        }

        let btn = $("#myButton").button('loading');

        request.patch(createRequestURI(`site/tags/${tag_id}`), {
          ...data
        }, {
          validateStatus: status => status === 201
        })
        .then ( response => {
          this.sendComplate(btn);
        })
        .catch(({ response: { data = {} } = {} }) => {
          btn.button('reset');

          let error = '修改标签失败';
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
            this.tag.name = this.name;
            this.tag.tag_category_id = this.category;
            this.tag.weight = this.weight;
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
      },

      // 获取标签详情
      getTag () {
        request.get(createRequestURI(`site/tags/${this.tag_id}`), {
          validateStatus: status => status === 200
        })
        .then(({ data = {} }) => {
          this.tag = { ...data };
          this.name = data.name;
          this.category = data.tag_category_id;
          this.weight = data.weight;
        })
        .catch( () => {

        })
      }
    },

    computed: {
      // 按钮是否处于激活状态
      canSend() {
        let nameChanged = (this.name != '' && (this.name != this.tag.name));
        let cateChanged = (this.category !== 0 && (this.tag.tag_category_id !== this.category));
        let weightChanged = (this.weight !== null && (this.tag.weight !== this.weight));
        return (nameChanged || cateChanged || weightChanged);
      }
    },
    created () {
      const {
        tag_id = 0
      } = this.$route.params;

      if(!tag_id) {
        window.alert('参数错误');
        setTimeout( () => {
          this.$router.go(-1);
        }, 2000); 
      }
      this.tag_id = tag_id;
      this.getTag();
      this.getCategories();
    }
  };

  export default UpdateTag;
</script>