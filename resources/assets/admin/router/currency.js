import Main from '../components/modules/Currency/Main';
import Currency from '../components//pages/Currency';

export default {
	path: 'currency',
	component: Main,
	children: [
		{ path: '', component: Currency },
	]
}