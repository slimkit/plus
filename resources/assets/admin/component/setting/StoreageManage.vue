<style lang="css" module>
.checkboxAndRadioInput {
  margin-right: 10px;
}
</style>

<template>
  <div class="component-container container-fluid form-horizontal">
    <!-- select engine -->
    <div class="form-group">
      <label for="storage-engine" class="col-sm-2 control-label">选择储存</label>
      <div class="col-sm-6">
        <select class="form-control" id="storage-engine" aria-describedby="storages-engine-help-block" v-model="selected">
          <option v-for="(engine, value) in engines" :key="value" :value="value">{{ engine.name }}</option>
        </select>
      </div>
      <span class="col-sm-4 help-block" id="storages-engine-help-block">
        选择 ThinkSNS+ 中所使用的资源储存引擎。
      </span>
    </div>

    <!-- for - option -->
    <div class="form-group" v-if="showOption" v-show="!loadingOption" :key="`engine-option-${selected}`" v-for="{name, type, tip, items, value, option} in options" >
      <label :for="`engine-option-${selected}-${name}`" class="col-sm-2 control-label">{{ name }}</label>
      <div class="col-sm-6" :class="type">
        <!-- text -->
        <input v-bind="option" v-if="type === 'text'" type="text" :name="name" class="form-control" :id="`engine-option-${selected}-${name}`" :ariaDescribedby="`storages-option-${selected}-${name}-help-block`" :value="value" v-model="optionsValues[name]" />

        <!-- checkbox -->
        <template v-else-if="type === 'checkbox'">
          <label v-for="(display_name, value) in items" :key="`${selected}-${name}-${display_name}-{$value}`" :class="$style.checkboxAndRadioInput">
            <input v-bind="option" type="checkbox" :name="name" :id="`engine-option-${selected}-${name}`" :ariaDescribedby="`storages-option-${selected}-${name}-help-block`" :value="value" v-model="optionsValues[name]" /> {{ display_name }}
          </label>
        </template>

        <!-- radio -->
        <template v-else-if="type === 'radio'">
          <label v-for="(display_name, value) in items" :key="`${selected}-${name}-${display_name}-{$value}`" :class="$style.checkboxAndRadioInput">
            <input v-bind="option" type="radio" :name="name" :id="`engine-option-${selected}-${name}`" :ariaDescribedby="`storages-option-${selected}-${name}-help-block`" :value="value" v-model="optionsValues[name]" /> {{ display_name }}
          </label>
        </template>

        <!-- select -->
        <select v-bind="option" v-else-if="type === 'select'" class="form-control" :id="`engine-option-${selected}-${name}`" :ariaDescribedby="`storages-option-${selected}-${name}-help-block`" :value="value" :name="name"  v-model="optionsValues[name]" >
          <option v-for="(display_name, value) in items" :key="`${selected}-${name}-${display_name}-{$value}`" :value="value">{{ display_name }}</option>
        </select>

      </div>
      <span class="col-sm-4 help-block" :id="`storages-option-${selected}-${name}-help-block`">{{ tip }}</span>
    </div>

    <!-- engine option loadding -->
    <div v-show="loadingOption" class="component-loadding">
      <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
    </div>

    <!-- Button -->
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button v-if="changeIn" type="button" class="btn btn-primary" disabled="disabled">
          <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        </button>
        <button v-else type="button" class="btn btn-primary" @click="updateEngineOption">提交</button>
      </div>
    </div>

    <!-- error -->
    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      {{ error }}
    </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';

const StorageManageComponent = {
  data: () => ({
    engines: {},
    selected: 'local',
    allOptionsValues: {},
    changeIn: false,
    error: null,
    loadingOption: false
  }),
  computed: {
    showOption () {
      return !!this.options.length;
    },
    options () {
      const selected = this.selected;
      const { [selected]: { option = [] } = {} } = this.engines;

      return this.eventOptions(option);
    },
    optionsValues () {
      const selected = this.selected;
      let { [selected]: optionsValues } = this.allOptionsValues;

      if (optionsValues === undefined) {
        optionsValues = {};
        this.options.forEach(({ name, value }) => {
          optionsValues[name] = value;
        });
        this.allOptionsValues = {
          ...this.allOptionsValues,
          [selected]: optionsValues
        };
      }

      return optionsValues;
    }
  },
  watch: {
    selected (engine) {
      const { [engine]: optionsValues } = this.allOptionsValues;
      if (optionsValues === undefined && this.showOption) {
        this.requestEngineOption(engine);
      }
    }
  },
  methods: {
    eventOptions (options) {
      return options.map(({ name, type, tip, value = '', items = {}, ...option }) => {
        if (type === 'checkbox' || (type === 'select' && !!option.multiple)) {
          value = [value];
        }

        return { name, type, tip, items, value, option };
      });
    },
    updateEngineOption () {
      const engine = this.selected;
      const options = this.optionsValues;
      this.changeIn = true;
      request.patch(
        createRequestURI(`storages/engines/${engine}`),
        { options },
        { validateStatus: status => status === 201 }
      ).then(() => {
        this.changeIn = false;
      }).catch(({ response: { data = ['更新失败'] } = {} }) => {
        this.error = lodash.values(data).pop();
        this.changeIn = false;
      });
    },
    requestEngineOption (engine) {
      this.loadingOption = true;
      request.get(
        createRequestURI(`storages/engines/${engine}`),
        { validateStatus: status => status === 200 }
      ).then(({ data }) => {
        this.allOptionsValues = {
          ...this.allOptionsValues,
          [engine]: {
            ...this.optionsValues,
            ...data
          }
        };
        this.loadingOption = false;
      }).catch(() => {
        this.loadingOption = true;
        this.error = '加载储存引擎配置失败，请刷新网页重现尝试。如果此时忽略本条警告强行提交，可能会造成数据错误。';
      });
    }
  },
  created () {
    request.get(
      createRequestURI('storages/engines'),
      { validateStatus: status => status === 200 }
    ).then(({ data }) => {
      this.engines = data.engines;
      this.allOptionsValues = { [this.selected]: data.validateStatus };
      this.selected = data.selected;
    }).catch(({ response: { data: { message = '加载失败，请刷新重试' } = {} } = {} }) => {
      this.error = message;
    });
  }
};

export default StorageManageComponent;
</script>
