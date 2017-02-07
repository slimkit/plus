<?php

return [

    /*
     * The TS+ example.
     */
    'zhiyicx/plus-component-example' => new class() {
        public function install()
        {
            var_dump('example-install');
        }

        public function router()
        {
        }

        public function resource()
        {
            return [];
        }
    },

];
