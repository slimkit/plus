<?php

return [

    /*
     * The TS+ example.
     */
    'zhiyicx/plus-component-example' => new class() {
        public function install()
        {
        }

        public function router()
        {
            return storage_path('example/router.php');
        }

        public function resource()
        {
            return storage_path('example/res');
        }
    },

];
