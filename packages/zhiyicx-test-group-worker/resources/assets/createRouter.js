import Vue from 'vue';
import VueRouter from 'vue-router';
import Projects from './components/pages/Projects';
import Settings from './components/pages/Settings';
import NewProject from './components/pages/NewProject';
import Project from './components/pages/Project';

import ProjectGeneral from './components/modules/project/General';
import ProjectSettings from './components/modules/project/Settings';

Vue.use(VueRouter);

export default new VueRouter({
  base: '/test-group-worker/',
  mode: 'history',
  routes: [
    { path: '/', redirect: '/projects' },
    { path: '/projects', component: Projects },
    { path: '/settings', component: Settings },
    { path: '/new-project', component: NewProject },
    {
      path: '/projects/:id',
      component: Project,
      children: [
        { path: '', component: ProjectGeneral },
        { path: 'tasks', component: ProjectGeneral },
        { path: 'settings', component: ProjectSettings },
      ]
    },
  ]
});
