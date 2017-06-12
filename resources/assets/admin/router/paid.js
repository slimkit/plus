/**
 * The file defuned /paid route.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import Main from '../component/paid/Main';
import Home from '../component/paid/Home';

export default {
  path: 'paid',
  component: Main,
  children: [
    { path: '', component: Home }
  ]
};
