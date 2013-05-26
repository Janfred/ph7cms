<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2013, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Field / Form / Processing
 */
namespace PH7;
defined('PH7') or exit('Restricted access');

use
PH7\Framework\Cache\Cache,
PH7\Framework\Mvc\Router\UriRoute,
PH7\Framework\Url\HeaderUrl;

class AddFieldFormProcessing extends Form
{

    public function __construct()
    {
        parent::__construct();

        $sMod = $this->httpRequest->get('mod');
        $sName = $this->httpRequest->post('name');
        $sType = $this->httpRequest->post('type');
        $iLength = $this->httpRequest->post('length');
        $sDefVal = $this->httpRequest->post('value');

        if (Field::isExists($sMod, $sName))
        {
            \PFBC\Form::setError('form_add_field', t('Oops! The field already exists!'));
        }
        else
        {
            $bRet = ( new FieldModel(Field::getTable($sMod), $sName, $sType, $iLength, $sDefVal) )->insert();

            if ($bRet)
            {
                /* Clean UserCoreModel Cache */
                (new Cache)->start(UserCoreModel::CACHE_GROUP, null, null)->clear();
                HeaderUrl::redirect(UriRoute::get('field', 'field', 'all', $sMod), t('The field has been added.'));
            }
            else
                \PFBC\Form::setError('form_add_field', t('Oops! An error occurred while adding the field, please try again.'));
        }
    }

}
