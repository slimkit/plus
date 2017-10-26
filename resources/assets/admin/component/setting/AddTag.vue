  <template>
  <div class="container-fluid" style="margin-top:15px;">
    <div class="panel panel-default">
      <div class="panel-heading">
        添加标签
      </div>
      <div class="panel-body form-horizontal">
        <div class="row">
          <div class="col-md-11 col-md-offset-1">
            <div class="form-group">
              <label for="exampleInputEmail1" class="control-label col-md-2">标签名字</label>
              <div class="col-md-5">
                <input v-model="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="标签名称">
              </div>
              <div class="col-md-5">
                <div class="help-block">输入标签名字</div>
              </div>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1" class="control-label col-md-2">标签权重</label>
              <div class="col-md-5">
                <input v-model="weight" type="text" class="form-control" placeholder="标签权重">
              </div>
              <div class="col-md-5">
                <div class="help-block">输入标签权重<small>（越大越靠前）</small></div>
              </div>
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1" class="control-label col-md-2">标签分类</label>
              <div  class="btn-toolbar col-md-5" role="group" aria-label="cate">
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
              <div class="col-md-5">
                <div class="help-block">请选择标签分类</div>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="control-label col-md-2"></label>
              <div class="col-md-5">
                <button type="submit" @click="send()" id="myButton" data-complete-text="添加成功" data-loading-text="提交中..." class="btn btn-primary" autocomplete="off">
                 确认
                </button>
              </div>
              <div class="col-md-5">
                 <span class="text-success"  v-show="message.success">{{ message.success }}</span>
                 <span class="text-danger" v-show="message.error">{{ message.error }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
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
  import plusMessageBundle from 'plus-message-bundle';
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
      },
      message: {
        success: null,
        error: null,
      },
    }),
    watch: {
      'categories'() {
        if (this.categories.length <= 0) {
          this.message.error = '请先添加标签分类，在进行添加标签';
        }
      }
    },
    methods: {
      send () {
        this.resetMessage();
        if (!this.validate())  return;
        const { name = '', category = 0, weight = 0 } = this;
        let btn = $("#myButton").button('loading');
        request.post(createRequestURI('site/tags'), {
          name,category,weight
        }, {
          validateStatus: status => status === 201
        })
        .then ( response => {
          this.sendComplate(btn);
          this.message.success = '添加成功';
          this.$router.replace({ path: '/setting/tags' });
        })
        .catch(({ response: { data = {} } = {} }) => {
          btn.button('reset');
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
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
      validate () {
        const { name = '', category = 0, weight = 0 } = this;
        if (!name) {
          this.message.error = '请输入标签名字';
          return false;
        }
        if (!category) {
          this.message.error = '请选择标签分类';
          return false;
        }
        return true;
      },
      resetMessage () {
        let msg = this.message;
        msg.error = msg.success = null;
      }
    },
    created () {
      this.getCategories();
    }
  };

  export default AddTag;
</script>