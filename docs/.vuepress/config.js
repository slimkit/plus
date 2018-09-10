module.exports = {
    base: '/plus/',
    title: 'Plus (ThinkSNS+)',
    description: 'Plus (ThinkSNS+) 是使用 Laravel 框架开发；一个功能强大、易于开发和动态拓展的社交系统。',
    head: require('./head'),
    dest: '.vuepress/dist',
    ga: undefined,
    serviceWorker: false,
    locales: require('./locales'),
    markdown: require('./markdown'),
    chainWebpack: require('./webpack'),
    evergreen: false,
    themeConfig: {
        repo: 'slimkit/plus',
        repoLabel: 'GitHub',
        editLinks: true,
        editLinkText: '协助改善此文档',
        sidebar: 'auto',
        search: true,
        lastUpdated: '更新时间',
        nav: [
            {
                text: '学习指南',
                items: [
                    { text: '安装教程', link: '/guide/installation/' },
                    { text: '应用开发', link: '/guide/app/' }
                ]
            },
            {
                text: 'HTTP APIs',
                items: [
                    { text: '核心', link: '/core/api/v2/system' },
                    { text: '用户', link: '/core/api/v2/users' },
                ]
            }
        ],
        sidebar: {
            '/guide/installation/': ['', 'build-install-php']
        }
    },
};
