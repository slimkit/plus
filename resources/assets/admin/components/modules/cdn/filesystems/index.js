import Disk from './Disk';
import Local from './Local';
import Public from './Public';

export default {
  [Disk.name]: Disk,
  [Local.name]: Local,
  [Public.name]: Public,
};
