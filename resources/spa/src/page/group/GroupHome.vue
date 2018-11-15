<template>
  <div class="p-group-home">

    <common-header>
      圈子
      <div slot="right" @click.capture.stop.prevent="popupBuyTS">
        <svg class="m-style-svg m-svg-def" @click="onSearchClick">
          <use xlink:href="#icon-search"/>
        </svg>
        <svg class="m-style-svg m-svg-def" @click="beforeCreateGroup">
          <use xlink:href="#icon-group-create"/>
        </svg>
      </div>
    </common-header>

    <main @click.capture.stop.prevent="popupBuyTS">

      <div class="group-label" @click="$router.push({ name: 'groups', query: { type: 'recommend' } })">
        <h2><strong>{{ groupTotalNumber }}</strong>个兴趣小组，等待你的加入！</h2>
        <svg class="m-style-svg m-svg-def m-entry-append">
          <use xlink:href="#icon-arrow-right"/>
        </svg>
      </div>

      <!-- 我加入的 -->
      <div class="m-box-model">
        <router-link
          :to="`/users/${user.id}/group`"
          tag="div"
          class="group-label">
          <span>我加入的</span>
          <div class="m-box m-aln-center m-justify-end">
            <span v-if="!myGroupsCount">查看全部</span>
            <span v-else-if="myGroupsCount > 5">查看更多</span>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </div>
        </router-link>

        <ul class="group-list">
          <li v-for="group in groups" :key="`mygroup-${group.id}`">
            <group-item :group="group" />
          </li>
        </ul>
      </div>

      <!-- 推荐圈子 -->
      <div v-if="recGroups.length > 0" class="m-box-model">
        <div class="group-label">
          <span>热门推荐</span>
          <div class="m-box m-aln-center m-justify-end" @click="fetchRecGroups">
            <svg :style="{ transform: `rotate(${clickCount}turn)` }" class="m-style-svg m-svg-small">
              <use xlink:href="#icon-refresh"/>
            </svg>
            <span style="margin-left: 0.05rem">换一批</span>
          </div>
        </div>
        <ul class="group-list">
          <li v-for="group in recGroups" :key="`recgroup-${group.id}`">
            <group-item :group="group" />
          </li>
        </ul>
      </div>
    </main>
  </div>
</template>

<script>
import { mapState } from "vuex";
import GroupItem from "./components/GroupItem.vue";
import * as api from "@/api/group.js";

export default {
  name: "GroupHome",
  components: { GroupItem },
  data() {
    return {
      myGroups: new Map(),
      recGroups: [],
      clickCount: 0,
      groupTotalNumber: 0,

      fetchRecing: false,
      myGroupChangeTracker: 0,
      myGroupsCount: 0
    };
  },
  computed: {
    ...mapState({
      CONFIG: "CONFIG",
      user: "CURRENTUSER"
    }),
    groups() {
      return this.myGroupChangeTracker && [...this.myGroups.values()];
    }
  },
  created() {
    this.fetchMyGroups();
    this.fetchRecGroups();
    api.getGroupTotalNumber().then(count => {
      this.groupTotalNumber = count;
    });
  },
  methods: {
    formateGroups(groups) {
      groups.forEach(group => {
        this.myGroups.set(group.id, group);
        this.myGroupChangeTracker += 1;
      });
    },
    async fetchMyGroups() {
      const groups = await this.$store.dispatch("group/getMyGroups", {
        limit: 6 // 获取 6 条数据，判断是否大于5个圈子
      });
      this.myGroupsCount = groups.length;
      this.formateGroups(groups.slice(0, 5));
    },
    async fetchRecGroups() {
      if (this.fetchRecing) return;
      this.fetchRecing = true;
      const groups = await this.$store.dispatch("group/getGroups", {
        type: "random",
        limit: 5
      });
      this.recGroups = groups;
      this.clickCount += 1;
      this.fetchRecing = false;
    },
    onSearchClick() {
      this.$router.push({ name: "groupSearch" });
    },
    /**
     * 创建圈子前检查
     */
    beforeCreateGroup() {
      const { need_verified: needVerified } = this.CONFIG["group:create"];
      const { verified } = this.user;

      // 如果不需要认证或已经认证
      if (!needVerified || verified)
        return this.$router.push({ name: "groupCreate" });

      const actions = [
        {
          text: "去认证",
          method: () => this.$router.push({ name: "ProfileCertificate" })
        }
      ];
      this.$bus.$emit(
        "actionSheet",
        actions,
        "取消",
        "认证用户才能创建圈子, 去认证?"
      );
    }
  }
};
</script>

<style lang="less" scoped>
.p-group-home {
  .group-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
    background-color: #fff;
    font-size: 24px;
    color: @text-color3;
    padding: 20px;

    strong {
      font-size: 40px;
      color: @error;
    }

    .m-svg-small {
      transition: transform 0.5s ease;
    }
  }

  .group-list {
    li {
      border-top: 1px solid @border-color; /*no*/
    }
  }
}
</style>
