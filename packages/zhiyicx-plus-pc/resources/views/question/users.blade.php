<div class="search-user">
    <div class="search-user-input">
        <input type="text" name="name" id="input-name">
    </div>
    <div class="search-lists">
        <div class="lists-notice">专家列表</div>
        <div class="lists">

        </div>
    </div>
</div>
<script>
    // 关键字搜索用户
    var input = $('#input-name');
    var invitation = $('.invitation-a');
    var lists = $('.lists');
    var search = 0;

    input.on('keypress', function (event) {
        search = 1;
        event.keyCode == 13 ?
        getUsers() : "";
    });

    function getUsers() {
        var keyword = input.val();
        loader.init({
            container: '.lists',
            loading: '.lists',
            url: '/questions/users',
            params: {
                limit: 10,
                ajax: 1,
                keyword: keyword,
                topics: "{{ $topics }}",
                search: search,
                paramtype: 1
            }
        });
    }

    getUsers();

    // 点击邀请
    lists.on('click', '.invitation-a', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#invitation-add').text('已邀请：'+name);
        args.invitations_ = [id];
        ly.close();
    })
</script>