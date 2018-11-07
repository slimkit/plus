import PropTypes from 'prop-types';
export default {
    classes: PropTypes.object.isRequired,
    onClose: PropTypes.func.isRequired,
    payload: PropTypes.object.isRequired,
    message: PropTypes.shape({
        onShow: PropTypes.func.isRequired,
        onClose: PropTypes.func.isRequired,
    }),
    onRefresh: PropTypes.func.isRequired,
};
