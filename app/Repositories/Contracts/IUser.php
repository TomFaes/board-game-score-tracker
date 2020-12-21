<?php

namespace App\Repositories\Contracts;

interface IUser {
    public function getAllUsers($itemsPerPage = 10);
    public function getUser($id);

    public function create(Array $data);
    public function update(Array $data, $userId);
    public function delete($userId);

    public function changeFavoriteGroup($userId, $groupId);
    public function createSocialUser($socialUser);
    public function existingUser(string $socialUserEmail);
}
