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
        docsBranch: 'docs',
        sidebar: 'auto',
        search: true,
        lastUpdated: '更新时间',
        nav: [
            {
                text: '学习指南',
                items: [
                    { text: '安装教程', link: '/guide/installation/' },
                    { text: '升级指南', link: '/guide/upgrade/' },
                    {
                        text: '开发教程',
                        items: [
                            { text: '应用开发', link: '/guide/dev/blog/' }
                        ]
                    }
                ]
            },
            {
                text: 'HTTP APIs',
                items: [
                    { text: '核心', link: '/core/api/v2/system' },
                    { text: '用户', link: '/api-v2/user/' },
                ]
            }
        ],
        sidebar: {
            '/guide/installation/': [
                '',
                'build-install-php',
                'build-install-mysql',
                'build-install-nginx',
                'install-plus',
                'using-nginx-and-fpm-publish-website',
                'install-spa',
            ],
            '/guide/upgrade/': [
                '',
                '1.9-to-2.0',
                '2.0-to-2.1',
                '2.1-to-2.2',
            ],
            '/guide/dev/blog/': [
                '',
                'create-package',
                'create-pages-layout',
                'create-pages-my-blog',
                'blog-profile',
            ],
            '/api-v2/user/': [
                '',
            ]
        }
    },
};
