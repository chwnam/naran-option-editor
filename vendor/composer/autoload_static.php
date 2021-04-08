<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit894c8a53e25fd99ad167e02080f72659
{
    public static $files = array (
        'b3dc2bfcb36160d127729bba1255a68b' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'NOE_Admin' => __DIR__ . '/../..' . '/includes/modules/class-noe-admin.php',
        'NOE_Admin_Menu' => __DIR__ . '/../..' . '/includes/modules/class-noe-admin-menu.php',
        'NOE_Admin_Module' => __DIR__ . '/../..' . '/includes/interface-noe-admin-module.php',
        'NOE_Admin_Option_Editor' => __DIR__ . '/../..' . '/includes/modules/class-noe-admin-option-editor.php',
        'NOE_Admin_Prefix_Inspector' => __DIR__ . '/../..' . '/includes/modules/class-noe-admin-prefix-inspector.php',
        'NOE_Admin_Subpage_Blank' => __DIR__ . '/../..' . '/includes/modules/class-noe-admin-subpage-blank.php',
        'NOE_Ajax' => __DIR__ . '/../..' . '/includes/modules/registrables/class-noe-ajax.php',
        'NOE_Container' => __DIR__ . '/../..' . '/includes/class-noe-container.php',
        'NOE_Desc_Table' => __DIR__ . '/../..' . '/includes/modules/class-noe-desc-table.php',
        'NOE_Meta' => __DIR__ . '/../..' . '/includes/modules/registrables/class-noe-meta.php',
        'NOE_Mockup' => __DIR__ . '/../..' . '/includes/modules/class-noe-mockup.php',
        'NOE_Module' => __DIR__ . '/../..' . '/includes/interface-noe-module.php',
        'NOE_Options_List_Table' => __DIR__ . '/../..' . '/includes/class-noe-options-list-table.php',
        'NOE_Prefix_Filter' => __DIR__ . '/../..' . '/includes/class-noe-prefix-filter.php',
        'NOE_Registerer' => __DIR__ . '/../..' . '/includes/interface-noe-registerer.php',
        'NOE_Registerer_Admin_Ajax' => __DIR__ . '/../..' . '/includes/modules/registerers/class-noe-registerer-admin-ajax.php',
        'NOE_Registerer_Admin_Post' => __DIR__ . '/../..' . '/includes/modules/registerers/class-noe-registerer-admin-post.php',
        'NOE_Registerer_Admin_Script' => __DIR__ . '/../..' . '/includes/modules/registerers/class-noe-registerer-admin-script.php',
        'NOE_Registerer_Admin_Style' => __DIR__ . '/../..' . '/includes/modules/registerers/class-noe-registerer-admin-style.php',
        'NOE_Registerer_Meta' => __DIR__ . '/../..' . '/includes/modules/registerers/class-noe-registerer-meta.php',
        'NOE_Registerer_Module' => __DIR__ . '/../..' . '/includes/modules/class-noe-registerer-module.php',
        'NOE_Registrable' => __DIR__ . '/../..' . '/includes/interface-noe-registrable.php',
        'NOE_Script' => __DIR__ . '/../..' . '/includes/modules/registrables/class-noe-script.php',
        'NOE_Style' => __DIR__ . '/../..' . '/includes/modules/registrables/class-noe-style.php',
        'NOE_Submenu_Page' => __DIR__ . '/../..' . '/includes/interface-noe-submeu-page.php',
        'NOE_Submit' => __DIR__ . '/../..' . '/includes/modules/registrables/class-noe-submit.php',
        'NOE_Submodule_Impl' => __DIR__ . '/../..' . '/includes/trait-noe-submodule-impl.php',
        'NOE_Template_Impl' => __DIR__ . '/../..' . '/includes/trait-noe-template-impl.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit894c8a53e25fd99ad167e02080f72659::$classMap;

        }, null, ClassLoader::class);
    }
}
