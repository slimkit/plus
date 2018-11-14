import request, { createRequestURI } from '../util/request';

export class easemob {
    static uri = createRequestURI('setting/vendor/easemob');
    static get() {
        return request.get(this.uri, {
            validateStatus: status => status === 200,
        });
    }
    static update(settings = {}) {
        return request.put(this.uri, settings, {
            validateStatus: status => status === 204,
        });
    }
};
