  <template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <div class="panel-heading">
        添加标签
      </div>
      <div class="panel-body form-horizontal">
        <loading :loadding="loadding"></loading>
        <div class="row" v-show="!loadding">
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
  const UpdateTag = {
    data: () => ({
      loadding: true,
      tag: {},
      tag_id: 0,
      name: '',
      category: 0,
      weight: null,
      categories: [],
      message: {
        success: null,
        error: null,
      }
    }),

    methods: {
      send () {

        this.resetMessage();

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
          this.message.success = '更新成功';
          this.$router.replace({ path: '/setting/tags' });
        })
        .catch(({ response: { data = {} } = {} }) => {
          btn.button('reset');
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
          if(data.name) {
            error = data.name[0];
          }
          if(data.category) {
            error = data.category[0];
          }
          this.add.loadding = false;
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
        this.loadding = true;
        request.get(createRequestURI('site/tags/categories'),{
          validateStatus: status => status === 200
        })
        .then(({ data = [] }) => {
          this.loadding = false;
          this.categories = data;
        })
        .catch( () => {
          this.loadding = false;
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
      },
      resetMessage () {
        let msg = this.message;
        msg.error = msg.success = null;
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