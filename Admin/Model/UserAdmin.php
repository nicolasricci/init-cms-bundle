<?php
/**
 * This file is part of the Networking package.
 *
 * (c) net working AG <info@networking.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Networking\InitCmsBundle\Admin\Model;

use Sonata\UserBundle\Admin\Entity\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class UserAdmin
 * @package Networking\InitCmsBundle\Admin\Model
 * @author Yorkie Chadwick <y.chadwick@networking.ch>
 */
abstract class UserAdmin extends SonataUserAdmin
{

    /**
     * @var string
     */
    protected $baseRoutePattern = 'cms/users';

    /**
     * @var string
     */
    protected $baseRouteName = 'admin_networking_initcms_user';

    /**
     * @var array
     */
    protected $trackedActions = array('list');

    /**
     * @param $trackedActions
     * @return $this
     */
    public function setTrackedActions($trackedActions)
    {
        $this->trackedActions = $trackedActions;

        return $this;
    }

    /**
     * @return Array
     */
    public function getTrackedActions()
    {
        return $this->trackedActions;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'icon-user';
    }

    /**
     * @return array
     */
    public function getSubNavLinks()
    {
        $pool = $this->getConfigurationPool();
        $groupAdmin = $pool->getAdminByAdminCode('sonata.user.admin.group');

        $links = array(
            $this->trans($this->getLabel()) => $this,
            $groupAdmin->trans($groupAdmin->getLabel()) => $groupAdmin

        );

        return $links;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('groups')
            ->add(
                'enabled',
                null,
                array('editable' => true)
            );
        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add(
                    'impersonating',
                    'string',
                    array('template' => 'NetworkingInitCmsBundle:Admin:Field/impersonating.html.twig')
                );
        }

        $listMapper->add(
            '_action',
            'actions',
            array(
                'label' => ' ',
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            )
        );
    }


    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('username')
            ->add('locked', null, array('hidden' => true))
            ->add('email', null, array('hidden' => true))
            ->add('groups', null, array('hidden' => true));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'text', array('required' => false))
            ->end()
            ->with('Groups')
            ->add('groups', 'sonata_type_model', array('required' => false, 'expanded' => true, 'multiple' => true))
            ->end()
            ->with('Profile')
//            ->add('dateOfBirth', 'birthday', array('required' => false))
            ->add('firstname', null, array('required' => false))
            ->add('lastname', null, array('required' => false))
//            ->add('website', 'url', array('required' => false))
//            ->add('biography', 'text', array('required' => false))
//            ->add(
//                'gender',
//                'sonata_user_gender',
//                array(
//                    'required' => false,
//                    'translation_domain' => $this->getTranslationDomain()
//                )
//            )
            ->add('locale', 'locale', array('required' => false))
//            ->add('timezone', 'timezone', array('required' => false))
//            ->add('phone', null, array('required' => false))
            ->end();
//            ->with('Social')
//            ->add('facebookUid', null, array('required' => false))
//            ->add('facebookName', null, array('required' => false))
//            ->add('twitterUid', null, array('required' => false))
//            ->add('twitterName', null, array('required' => false))
//            ->add('gplusUid', null, array('required' => false))
//            ->add('gplusName', null, array('required' => false))
//            ->end();

        if (!$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {

        $formMapper
            ->with('Management')
            ->add(
                'realRoles',
                'sonata_security_roles',
                array(
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'label_render' => false,
                )
            )
            ->add('locked', null, array('required' => false), array('inline_block' => true))
            ->add('expired', null, array('required' => false), array('inline_block' => true))
            ->add('enabled', null, array('required' => false), array('inline_block' => true))
            ->add('credentialsExpired', null, array('required' => false), array('inline_block' => true))
            ->end();
        }

//        $formMapper
//            ->with('Security')
//            ->add('token', null, array('required' => false))
//            ->add('twoStepVerificationCode', null, array('required' => false))
//            ->end();
    }
}
