<?php

namespace SlimKit\PlusID\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plus_id_clients';

    /**
     * make sign.
     *
     * @param array $action
     * @param string $key
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function sign(array $action, string $key = ''): string
    {
        ksort($action);
        $action = json_encode($action);

        return md5(hash_hmac('sha256', $action, $key ?: $this->key, true));
    }
}
