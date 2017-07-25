<?php
/**
 * Created by PhpStorm.
 * User: phper
 * Date: 2017/7/14
 * Time: 1:10
 */

namespace App\Traits;

use Zizaco\Entrust\Traits\EntrustUserTrait;


trait MyEntrustUserTrait
{

    use EntrustUserTrait;


    public function saveRoles($inputRoles)
    {
        if (!empty($inputRoles)) {
            $this->roles()->sync($inputRoles);
        } else {
            $this->roles()->detach();
        }
    }

}