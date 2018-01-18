import axios from 'axios';

const basename = document.head.querySelector('meta[name="admin-api-basename"]');
if (! basename) {
  console.error('后台根地址没有设置，请设置 "<meta name="admin-api-basename" content="url">"');
}

export default axios.create({ baseURL: basename.content });
