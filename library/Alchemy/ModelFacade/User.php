<?php
namespace Alchemy\ModelFacade;

use Alchemy\ModelFacade;

class User extends ModelFacade
{
    /**
     * Return name of related model
     *
     * @return	string
     */
    protected function getModelName()
    {
        return 'User';
    }

}
