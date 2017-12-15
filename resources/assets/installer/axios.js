import axios from 'axios';

const basename = document.head.querySelector('meta[name="api-base"]');
if (! basename) {
  console.error('"<meta name="admin-api-basename content="URL" Is not found.');
}

export default axios.create({
  baseURL: basename.content,
});
