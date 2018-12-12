---
title: 安装 SPA 应用（H5）
---

首先纠正一下你们所谓的 `H5`，H5 是 HTML 5 的缩写，也代表新浏览器技术和标准，例如一些先进浏览所支持的一些 HTML 5 标准 APIs，所以请大家认清事实别出去说 H5，在程序员眼里，听到这么叫真的觉得很 Low B 的！

> 主要是不知道概念，很容易装逼失败，尴尬的是你😂

## 什么是 SPA

单页 Web 应用（single page web application，SPA），就是只有一张 Web 页面的应用。单页应用程序 (SPA) 是加载单个 HTML 页面并在用户与应用程序交互时动态更新该页面的Web应用程序。浏览器一开始会加载必需的 HTML、CSS 和 JavaScript，所有的操作都在这张页面上完成，都由 JavaScript 来控制。因此，对单页应用来说模块化的开发和设计显得相当重要。

说明参考：
* [SPA · 百度百科](https://baike.baidu.com/item/SPA/17536313)
* [单页应用 · 维基百科](https://zh.wikipedia.org/wiki/%E5%8D%95%E9%A1%B5%E5%BA%94%E7%94%A8)

## 简介

Plus 小屏网页客户端（以下简称 Plus SPA）是使用 Vue.js 及 ES+ 等现代 Web 技术进行构建编写的 Web 单页应用。其纯前端的代码，允许将 Plus SPA 分发到网络上的各个地方，甚至你可以进行设备嵌入本地运行，只要你有一个可以渲染 JS + HTML + CSS 的应用即可。

## 下载程序

在前面的教程中，我们在 **CentOS** `7.4` 的服务器中已经安装可 Git 软件，如果你是直接跳过来看 SPA 安装教程的，请在 CentOS 中运行下面的命令安装，如果你不是 CentOS 系统，请自行从 Git 官网安装，命令如下：

```bash
yum install -y git
```

我们的 SPA 代码存放在👉「[slimkit/plus-small-screen-client](https://github.com/slimkit/plus-small-screen-client)」。

现在，我们进入 CentOS 中，然后按照之前安装 Plus 的惯例，我们将程序代码存放在 `/usr/local/src` 目录中，所以我们执行 `cd /usr/local/src` 即可进入该目录，然后我们执行下面的命令：

```bash
git clone https://github.com/slimkit/plus-small-screen-client spa && cd spa
```

执行完成你，你可以执行 `pwd` 你可以看到你当前所处的位置为 `/usr/local/src/spa`，这就是我们的代码位置了。

## 安装 Node.js

我们打开 [Node.js 官网下载页面](https://nodejs.org/en/download/) 默认会选择 LTS 版本，目前我这里最新的是 `8.12.0` 版本，我们在页面找到 ·Linux Binaries (x86/x64)· 这一行，我这里系统是 `65 Bit` 所以我点击这一行的 `64-bit` 按钮，你更具你系统选择。如果你不清楚，请看下图：

<img :src="$withBase('/assets/img/guide/installation/node-js-download-page.png')" />

通过上图可知，我这里需要 `64-bit` 右键后选择「复制链接地址」得到 `https://nodejs.org/dist/v8.12.0/node-v8.12.0-linux-x64.tar.xz` 这样的地址。我们现在回到服务器家目录（执行 `cd ~`）然后使用下面的命令进行下载：

```bash
curl -L https://nodejs.org/dist/v8.12.0/node-v8.12.0-linux-x64.tar.xz > node-v8.12.0-linux-x64.tar.xz
```

执行完成后，我们输入 `ls -al` 命令，你会看到有一个 `node-v8.12.0-linux-x64.tar.xz` 的归档文件。

现在我们下载解压工具对归档进行解压：

```bash
yum install -y xz tar
```

执行完成后，我们开始解压吧！我们先执行 `xz -d node-v8.12.0-linux-x64.tar.xz` 命令，然后之前的 `.xz` 归档消失了，会多处一个 `node-v8.12.0-linux-x64.tar` 的归档文件，我们继续执行 `tar -xvf node-v8.12.0-linux-x64.tar` 就会出现一个 `node-v8.12.0-linux-x64` 的文件夹，这就是我们的 Node.js 程序了！

接下来，我们为了统一管理，我们将 Node 软件放到指定位置（并不是必须，而是推荐），之前我们的软件都放在 `/usr/local` 下，所以这次也一样，我们执行：

```bash
mv -f ~/node-v8.12.0-linux-x64 /usr/local/node
```

然后为了方便我们全局使用 `node` 命令进行调用，我们需要将 `/usr/local/node/bin` 加入到环境变量，素以我们使用 Vim 打开 `/etc/profile` 文件，在结尾追加一行：

```bash
export PATH=$PATH:/usr/local/node/bin/
```

加入后，我们执行 `source /etc/profile` 使其生效，最后我们输入 `node -v` 你会看到下面的信息，表示已经成功：

```
v8.12.0
```

## 安装 Yarn 依赖管理工具

我们并不推荐使用 NPM 进行依赖管理，因为开发人员在开发过程中也是重度依赖 Yarn 进行依赖管理，我们并不知道开发人员会搞出什么幺蛾子，所以我们和开发人员一样使用 Yarn 吧！常规安装 Yarn 较为麻烦，但是我们可以利用 NPM **让媳妇把小三抱进家里**☺️：

```bash
npm -g i yarn
```

接下来，我们运行 `yarn --version` 就会输出 Yarn 的版本信息，说明我们已经安装完成了。

## 配置 Plus SPA

好了，工具和环境都安装完成了，我们输入 `cd /usr/local/src/spa` 回到 Plus SPA 的代码目录，运行下面的命令，创建我们所需要的 `.env` 文件：

```bash
cat .env.example > .env
```

运行完成后，我们使用 Vim 工具进行编辑该文件，运行 `vim .env` 命令，找到并编辑以下内容：

```ini
BASE_URL=/ # 我们假设你部署在一个域名下，所以默认 `/`, 例如你部署在子目录下，请设置子目录，必须以 `/` 结尾！
VUE_APP_API_HOST=http://127.0.0.1 # 将内容修改为我们服务器的 IP 地址（上一章我们使用 NPM + FPM 发布了站点的）
```

> 各个参数描述详情清查看 [plus/resource/spa/.env.example](https://github.com/slimkit/plus/blob/master/resources/spa/.env.example) 的描述

## 安装依赖

我们使用 `cd /usr/local/src/spa` 进入 Plus SPA 程序目录，这里我们使用 Yarn 工具进行程序的依赖安装：

```bash
yarn install
```

> 这个过程会从国外的服务器上下载依赖包的元数据和依赖包数据，所以速度会很慢，因为 `vue-cli` 工具本身的依赖原因，过程中会出现一些 `warning` 开头的警告，这不是错误，所以无需理会！

## 子目录发布 SPA

子目录发布 H5 有一个不好的地方，就是我们只能使用 `hash` 模式理由，但是我们可以规避**跨域请求**的安全限制问题，当然，想使用 `history` 需要对 Nginx 做特殊处理，这里教程中并不会给出例子，因为我们后面有独立部署的章节，所以我们先来看如何子目录安装吧。

首先**我们拟定我们希望放置在 Plus 程序域名下的 `spa` 目录下**，所以，我们重新打开 Plus SPA 的 .env 文件，然后将下面的 `BASE_URL` 修改为 `/spa/` 这个值，修改后应该是这样的：

```
BASE_URL=/spa/
```

修改成功后，我们使用 `cd /usr/local/src/spa` 命令进入 Plus SPA 程序目录，然后执行虾米啊的命令进行打包编译：

```bash
yarn build
```

这个过程也是比较慢的，速度取决于你的磁盘速度。运行成功后会有类似下面的输出：

<img :src="$withBase('/assets/img/guide/installation/build-spa-console-output.png')" />

接下来我们执行下面的命令，将其编译好的输出软链到我们的 Plus 程序的 public 下：

```
ln -s /usr/local/src/spa/dist /usr/local/src/plus/public/spa
```

> 如果你不用软链，你可以将 `/usr/local/src/spa/dist` 里面的内容全部复制到 `/usr/local/src/plus/public/spa` 目录下。

然后我们现在打开你的网站，后面输入 `/spa` 查看 H5 吧！大概的样子如下：

<img :src="$withBase('/assets/img/guide/installation/local-dir-publish-spa-review.png')" />

注意点：

1. 为防止在 History 模式下刷新页面出现 `404` 错误，需要在 nginx.conf 的相应配置地方追加对于 SPA 的 URL 重写机制。其中 `/spa` 是你的相对域名根目录的路径，工作目录以 `/usr/local/src/plus/public/spa` 为例，相应配置类似于：

  ```nginx
  location /spa {
     alias /usr/local/src/plus/public/spa;
     index index.html;
     try_files $uri $uri/ /index.html?$query_string;
     if (!-e $request_filename){
         rewrite ^/spa/(.*)$ /spa/index.html?s=$1 last;
     }
  }
  ```

## 独立域名发布 SPA

我们在前面的教程中安装了 Nginx 这一节教程将指导如何在独立域名（或者端口）进行程序的发布，因为这里是教程，我们就换一个网络端口（因为 `80` 端口已经被 Plus 程序占用）进行发布。

我们使用 `touch /usr/local/nginx/vhost/spa.conf` 命令创建配置文件，然后编辑该文件，内容如下：

```nginx
server {
    listen 8080; # 因为 80 端口被占用了，所以我们使用 8080 端口
    server_name localhost; # 如果你已经为 SPA 分配了域名，那么将这路的 `localhost` 替换为域名，然后 listen 可以继续使用 80 端口！
    root /usr/local/src/spa/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html$is_args$args;
    }
}
```

接下来，我们使用 `cd /usr/local/src/spa` 进入 Plus SPA 目录，编辑下面的 `.env` 文件，将 `BASE_URL` 的值修改为 `/` 修改后的样子：`BASE_URL=/` 这样子。将 `VUE_APP_ROUTER_MODE` 的值替换为 `history` 修改后的样子：`VUE_APP_ROUTER_MODE=history`。

然后我们执行 `yarn build` 命令，等待命令执行完成。命令执行完成后，我们使用 `nginx -s reload` 命令重新加载 Nginx 配置。

然后我们在浏览器打开 `http://你的ip:8080` 看到类似于「子目录发布的站点了」。

## GitHub pages 发布 SPA

首先，你需要一个 GitHub 帐号，你可以任意创建一个空白仓库（打开网址 [https://github.com/new](https://github.com/new)），创建仓库如下截图：

<img :src="$withBase('/assets/img/guide/installation/new-a-github-repo.png')" />

> 「Initialize this repository with a README」 一定要勾选上，因为看这个教程按照零基础进行，如果不勾选，会多处很多后续步骤，这个你们就自己学了 Git 命令自己探索吧！

接下来，我们使用 `cd /usr/local/src/spa` 进入 Plus SPA 目录，编辑下面的 `.env` 文件，将 `BASE_URL` 的值修改为 `仓库名字` 修改后的样子：`BASE_URL=/spa/` (这里的值之所以是 `/spa/` 是因为我们仓库名字叫做 `spa`) 这样子。将 `VUE_APP_ROUTER_MODE` 的值替换为 `history` 修改后的样子：`VUE_APP_ROUTER_MODE=history`。

然后我们执行 `yarn build` 命令，执行完成后，我们得到了 `dist` 的文件，然后将里面的内容上传到你创建仓库的 `gh-pages` 分支中。访问 `https://<USERNAME>.github.io/<REPO>`。

> `<USERNAME>` 是你的 GitHub 用户名，`<REPO>` 是你的仓库名称。

## Plus 程序开启页面跳转

我们现在打开 Plus 程序的后台页面，然后点击「系统设置」在「基本信息」蓝中下拉，找到 「Web 终端」的设置版。首先我们在第二项的的地方输入 SPA 地址，输入后开关按钮变为可操作状态，然后点击开启，最后点击提交即可！截图如下：

<img :src="$withBase('/assets/img/guide/installation/spa-set-web-client-url.png')" />
