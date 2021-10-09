<?php

namespace App\Repository\Contracts;

use App\Repository;

interface UserInterface extends RepositoryInterface
{
    public function searchUserAutoComplete($searchWord = '');
    public function findAllAdministrator($searchWord, $start, $limit, $order, $orderBy);
    public function findAllMember($searchWord, $start, $limit, $order, $orderBy, $filter = []);
}
