<?php

namespace App\Http\Controllers;

use App\Models\ImUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ImUserController extends Controller
{
    /**
     * [imUsercreate description].
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-05T16:09:03+080
     *
     * @version  [version]
     *
     * @return [type] [description]
     */
    public function imUsercreate()
    {
        dump($_POST);
        exit;
        $data = [
            'user_id' => 1,
            'im_user_id' => 1,
            'im_password' => 'sun951024',
            'username' => '测试的im用户',
        ];
        $imuser = ImUser::create($data);

        dump($imuser);
        exit;
    }

    /**
     * [imUserDelete description].
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-05T17:11:44+080
     *
     * @version  [version]
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function imUserDelete()
    {
        echo '1111';
        exit;
    }
    public function testDelete()
    {
        $str = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHg6TmV0d29ya1JlcXVlc3Qg
eG1sbnM6eD0iaHR0cDovL3d3dy5wYXllY28uY29tIiB4bWxuczp4c2k9Imh0dHA6Ly93d3cudzMu
b3JnIj4KCTxWZXJzaW9uPjIuMC4wPC9WZXJzaW9uPgoJPFByb2NDb2RlPjAyMTA8L1Byb2NDb2Rl
PgoJPFByb2Nlc3NDb2RlPjE5MDAxMTwvUHJvY2Vzc0NvZGU Cgk8QWNjb3VudE5vPjwvQWNjb3Vu
dE5vPgoJPEFjY291bnRUeXBlPjAxPC9BY2NvdW50VHlwZT4KCTxNb2JpbGVObz48L01vYmlsZU5v
PgoJPEFtb3VudD4wLjAxPC9BbW91bnQ Cgk8Q3VycmVuY3k Q05ZPC9DdXJyZW5jeT4KCTxTeW5B
ZGRyZXNzPjwvU3luQWRkcmVzcz4KCTxBc3luQWRkcmVzcz5odHRwOi8vZGFveXVhbi41MWVkdWxp
bmUuY29tL3BheWVjb19hc3luPC9Bc3luQWRkcmVzcz4KCTxSZXNwQ29kZT4wMDAwPC9SZXNwQ29k
ZT4KCTxSZW1hcms 5Lqk5piT5oiQ5YqfPC9SZW1hcms Cgk8VGVybWluYWxObz48L1Rlcm1pbmFs
Tm8 Cgk8TWVyY2hhbnRObz4xNDcyMTgxNTQzMjM2PC9NZXJjaGFudE5vPgoJPE1lcmNoYW50T3Jk
ZXJObz4yMDE3MDExMTExNDU1NDY3MjI3PC9NZXJjaGFudE9yZGVyTm8 Cgk8T3JkZXJObz43MDIw
MTcwMTExMDAxMzQ1Mzg8L09yZGVyTm8 Cgk8T3JkZXJGcm9tPjwvT3JkZXJGcm9tPgoJPERlc2Ny
aXB0aW9uPui0reS5sOivvueoizwvRGVzY3JpcHRpb24 Cgk8T3JkZXJUeXBlPjAwPC9PcmRlclR5
cGU Cgk8T3JkZXJTdGF0ZT4wMjwvT3JkZXJTdGF0ZT4KCTxBY3FTc24 MjYyMzA4PC9BY3FTc24
Cgk8UmVmZXJlbmNlPjwvUmVmZXJlbmNlPgoJPFRyYW5zRGF0ZXRpbWU PC9UcmFuc0RhdGV0aW1l
PgoJPE1lcmNoYW50TmFtZT48L01lcmNoYW50TmFtZT4KCTxJRENhcmROYW1lPjwvSURDYXJkTmFt
ZT4KCTxJRENhcmRObz48L0lEQ2FyZE5vPgoJPEJhbmtBZGRyZXNzPjwvQmFua0FkZHJlc3M Cgk8
SURDYXJkVHlwZT48L0lEQ2FyZFR5cGU Cgk8VXBzTm8 NTQ2NTEwNjczNDYwPC9VcHNObz4KCTxU
c05vPjE2ODQ1OTwvVHNObz4KCTxTZXR0bGVEYXRlPjAxMTE8L1NldHRsZURhdGU Cgk8VHJhbnNE
YXRhPjwvVHJhbnNEYXRhPgoJPE1ldGhvZD5QT1NUPC9NZXRob2Q Cgk8VXNlclBheWVkVGltZT4y
MDE3MDExMTExNDgyOTwvVXNlclBheWVkVGltZT4KCTxNQUM OEVEQkQyOTlCMzQzRkIyRDAxNDUx
MkQyNkU3MjNDRjU8L01BQz4KPC94Ok5ldHdvcmtSZXF1ZXN0Pgo=';
        $res = base64_decode($str);
        dump($res);
        exit;
        header('content-type:text/xml');
        echo $res;
        exit;
        // $client = new Client(['base_uri' => 'http://192.168.10.222:9900']);
        // $res = $client->request('get', '', [
        // //$res = $client->request('post', '/test.php', [
        //     'form_params' => [
        //         'field_name' => 'abc',
        //         'other_field' => '123',
        //         'nested_field' => [
        //             'nested' => 'hello',
        //         ],
        //     ],
        // ]);
        // echo $res->getStatusCode();
        // $body = $res->getBody();
        // echo $body->getContents();
        // exit;
        $model = new ImUser();
        echo $model->initImUser(1);
    }
}
