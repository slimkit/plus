<?php

return [

    /*
     * The TS+ example.
     */
    'zhiyicx/plus-component-example' => new class() {
        public function install()
        {
            // var_dump('example-install');
        }

        public function router()
        {
            return base_path('routes/web.php');
        }

        public function resource()
        {
            return base_path('.github');
        }
    },

];
