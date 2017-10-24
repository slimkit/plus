import Filesystem from './Filesystem';
import Qiniu from './Qiniu';

export default {
  [Filesystem.name]: Filesystem,
  [Qiniu.name]: Qiniu,
};
