import Filesystem from './Filesystem';
import Qiniu from './Qiniu';
import AliOss from './AliOss';

export default {
  [Filesystem.name]: Filesystem,
  [Qiniu.name]: Qiniu,
  [AliOss.name]: AliOss,
};
