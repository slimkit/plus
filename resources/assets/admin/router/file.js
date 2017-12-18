import File from '../components/pages/File';
import FileSetting from '../components/modules/file/Setting'

export default {
  path: 'file',
  component: File,
    children: [
    { path: '', component: FileSetting }
  ]
};
