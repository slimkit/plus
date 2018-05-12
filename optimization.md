### ThinkSNS+性能优化方案
> 既然使用了Laravel框架，我们就无需在考虑其他的因素， 专心做针对Laravel的优化，
为什么是针对Laravel呢，因为ThinkSNS+完全遵从Laravel的扩展包开发方式

#### 部署方式
1. 在进行各种优化之前，首先第一步请设置.env文件中
    1. ```APP_DEBUG=false``` 关闭debug模式，防止隐私信息泄漏「程序出现错误的情况下」
    2. ```APP_ENV=production``` 设置为生产模式
    3. ```APP_LOG_LEVEL=warning``` 日志设置为警告模式，记录警告及以上等级的日志信息
    4. ```APP_LOG=daily``` 日志按天分割
    5. ```CACHE_DRIVER=redis/memcached``` 使用专业缓存

2. 缓存配置文件
    ThinkSNS+根目录执行 ```php artisan config:cache```, 此命令将所有的配置文件写入到bootstrap/config.php中，
    从而减少读取众多配置文件时的磁盘IO开销
3. 缓存路由文件
    ThinkSNS+根目录执行 ```php artisan route:cache```, 此命令将所有的配置文件写入到bootstrap/routes.php中，
    从而减少读取众多配置文件时的磁盘IO开销
4. 1-5中的缓存驱动，建议使用redis，瘦死的骆驼比马大，内存再不济，性能也不可能比磁盘差
5. 数据库建议使用阿里云旗下的RDS，程序主要的性能瓶颈其实是在对数据库的操作上，做到数据分离，用专业的数据服务，获得更好的sql执行性能「要相信大厂的实力」
6. 文件存储同上一条，推荐使用阿里云OSS
7. 非常重要的一点，为什么要放在第七条，因为我们**强制**使用`php7`, 而且`php7`的性能较之前的php版本，真的是质的飞跃，
用户开真是体验，换了`php7`之后，服务器费用降低了一半「较`php5`而言」，还有更重要的一点，生产环境上开启Opcache
    1. opcache.enable=1 开启opcache
    2. opcache.memory_consumption=128 共享内存
    3. opcache.validate_timestamps=0 设置opcache定时检查文件更新，如果需要检查请设置为你想要的数字， 单位是：秒
    4. zend_extension="opcache.so" 请注意，一定是 **zend_extension**
    5. opcache.max_accelerated_files=8000 缓存的文件数量
    6. opcache.memory_consumption=192
    7. opcache.interned_strings_buffer=16
    8. opcache.fast_shutdown=1
8. 服务器磁盘选用SSD，别相信所谓的高新能云盘，都是骗人的，绝壁是骗人的！就选SSD，绝对没错
9. 好了，优化就这么多，composer和laravel自身已经做了好些优化，省去了我们好些工作，
祝大家运维开心 😳

    
    