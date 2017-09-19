<style lang="css" module>
    .container {
        padding: 15px;
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
          <div class="panel panel-default">
            <div class="panel-heading">
              过滤词分类-添加
            </div>
            <div class="panel-body">
                <div class="col-md-6 col-md-offset-3" v-show="!loadding">
                    <div class="form-group">
                        <label><span class="text-danger">*</span>分类名称：</label>
                        <input type="text" class="form-control" v-model="category.name">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" 
                        @click.prevent="add" data-loading-text="提交" id="add-btn">确认</button>
                        <div class="pull-right">
                            <span class="text-danger" v-show="message.error">{{ message.error }}</span>
                            <span class="text-success" v-show="message.success">{{ message.success }}</span>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import plusMessageBundle from 'plus-message-bundle';
const FilterWordCategoryAdd = {
    data: () => ({
        loadding: true,
        message: {
          error: null,
          success: null,
        },
        category: {
          name: '',
        }
    }),
    methods: {
      add () {
        if (!this.category.name) {
          this.message.error = '请填写分类名称';
          return;
        }
        $('#add-btn').button('loading');
        request.post(
          createRequestURI('filter-word-categories'),
          { ...this.category },
          { validateStatus: status => status === 201 }
        ).then(({ data: { message: [ message ] = [] } }) => {
          $('#add-btn').button('reset');
          this.message.success = message;
        }).catch(({ response: { data = {} } = {} }) => {
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
        });
      },
    },
    created () {
      this.loadding = false;
    },
};
export default FilterWordCategoryAdd;
</script>
