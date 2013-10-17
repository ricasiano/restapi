<?php

interface iRestserver {
    public function get($id = '');
    public function post();
    public function put($id);
    public function delete($id);
}