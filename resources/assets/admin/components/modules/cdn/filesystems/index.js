import Disk from './Disk';
import Local from './Local';
import Public from './Public';
import S3 from './S3';

export default {
  [Disk.name]: Disk,
  [Local.name]: Local,
  [Public.name]: Public,
  [S3.name]: S3,
};
