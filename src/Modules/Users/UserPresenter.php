<?php

namespace Nhitrort90\CMS\Modules\Users;

use Nhitrort90\CMS\Modules\Lib\Presenter;

class UserPresenter extends Presenter
{
    public function typeTitle()
    {
        if (\Lang::has('CMS::users.user_types.' . $this->type))
        {

            return trans('CMS::users.user_types.' . $this->type);
        }
        return ucwords($this->type);
    }

    public function Photo()
    {
        if($this->avatar == null)
        {
            return cms_asset_path('img/pug.png');
        }

        return asset($this->avatar);
    }

}