import axios from "axios";

const basename = document.head.querySelector('meta[name="api-basename"]');
if (!basename) {
  console.error(
    '后台根地址没有设置，请设置 "<meta name="api-basename" content="url">"'
  );
}

const api = axios.create({ baseURL: basename.content });

const token = document.head.querySelector('meta[name="api-token"]');
if (token) {
  api.defaults.headers.common["Authorization"] = `Bearer ${token.content}`;
}

export default api;
