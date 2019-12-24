<?php

namespace App\Repositories\Contracts;

interface IGroupGameLink {
    public function getGroupGameLinks();
    public function getGroupGameLink($id);
    public function getLinksOfGroupGame($groupGameId);

    public function create(Array $data);
    public function update(Array $data, $id);
    public function delete($Id);
}
