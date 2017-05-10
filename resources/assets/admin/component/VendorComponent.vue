<style lang="css" module>
.nav {
  padding: 12px 12px 0;
}

.checkboxAndRadioInput {
  margin-right: 10px;
}

</style>

<template>
  <div>
    <ul class="nav nav-tabs" :class="$style.nav">
      <router-link
        tag="li"
        v-for="name in munes"
        :key="name"
        :to="`/vendor/${root}/${name}`"
        :class="name === children ? 'active' : null"
      >
        <a href="#">{{ name }}</a>
      </router-link>
    </ul>
    <div class="component-container container-fluid form-horizontal">
      <div
        class="form-group"
        v-for="{ type, display, name, tip, items, options } in inputs"
        :key="`/${root}/${children}/${name}`"
      >
        <label :for="`${root}-${children}-${name}`" class="col-sm-2 control-label">{{ display }}</label>
        <div class="col-sm-6" :class="type">
          <!-- text -->
          <input v-bind="options" v-if="type === 'text'" type="text" :name="name" class="form-control" :id="`${root}-${children}-${name}`" :ariaDescribedby="`${root}-${children}-${name}-help-block`" />

           <!-- password -->
          <input v-bind="options" v-if="type === 'password'" type="password" :name="name" class="form-control" :id="`${root}-${children}-${name}`" :ariaDescribedby="`${root}-${children}-${name}-help-block`" />

          <!-- checkbox -->
          <template v-else-if="type === 'checkbox'">
            <label
              v-for="(display_name, value) in items"
              :key="`/${root}/${children}/${name}`"
              :class="$style.checkboxAndRadioInput"
            >
              <input v-bind="options" type="checkbox" :name="name" :id="`engine-option-${selected}-${name}`" ::ariaDescribedby="`${root}-${children}-${name}-help-block`" /> {{ display_name }}
            </label>
          </template>

          <!-- radio -->
          <template v-else-if="type === 'radio'">
            <label
              v-for="(display_name, value) in items"
              :key="`/${root}/${children}/${name}`"
              :class="$style.checkboxAndRadioInput"
            >
              <input v-bind="options" type="radio" :name="name" :id="`${root}-${children}-${name}`" ariaDescribedby="`${root}-${children}-${name}-help-block`" /> {{ display_name }}
            </label>
          </template>

        </div>
        <span class="col-sm-4 help-block" :id="`${root}-${children}-${name}-help-block`">{{ tip }}</span>
      </div>

      <!-- Button -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="button" class="btn btn-primary">提交</button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import lodash from 'lodash';
import { mapGetters } from 'vuex';
import { FORM_ALL } from '../store/getter-types';

const VendorComponent = {
  data: () => ({
    loadding: false,
    error: null,
    message: null,
  }),
  computed: {
    ...mapGetters({
      forms: FORM_ALL,
    }),
    root() {
      return this.$route.params.root;
    },
    children() {
      const { children } = this.$route.params;

      if (! children) {
        const [ last ] = this.munes;

        return last;
      }

      return children;
    },
    munes() {
      const { [this.root]: items = {} } = this.forms;
      return lodash.keys(items);
    },
    currentData() {
      const { [this.root]: { [this.children]: form = {} } = {} } = this.forms;

      return form;
    },
    type() {
      const { type } = this.currentData;

      return type;
    },
    data() {
      const { data } = this.currentData;

      return data;
    },
    save() {
      const { save } = this.currentData;

      return save;
    },
    inputs() {
      const { form = [] } = this.currentData;

      let inputs = [];

      lodash.forEach(form, input => {
        const { display, name, type, tip, items, ...options } = input;

        inputs.push({
          display, name, type, tip, items, options
        });
      });

      return inputs;
    }
  },
  created() {
    // console.log(this.$store);
  }
};

export default VendorComponent;
</script>
