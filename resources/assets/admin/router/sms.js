//
// The file is defined "/sms" route.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
//

import Main from "../component/sms/Main";
import Home from "../component/sms/Home";
import Gateway from "../component/sms/Gateway";
import Alidayu from "../component/sms/Alidayu";
import Aliyun from "../component/sms/Aliyun";
import Yunpian from "../component/sms/Yunpian";
import Huyi from "../component/sms/Huyi";

const smsRouter = {
  path: "sms",
  component: Main,
  children: [
    { path: "", component: Gateway },
    { path: "alidayu", component: Alidayu },
    { path: "aliyun", component: Aliyun },
    { path: "yunpian", component: Yunpian },
    { path: "huyi", component: Huyi }
  ]
};

export default smsRouter;
