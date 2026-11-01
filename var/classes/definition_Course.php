<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- title [input]
 * -- teaser [textarea]
 * -- description [wysiwyg]
 * - slug [input]
 * - category [select]
 * - format [select]
 * - durationSemesters [numeric]
 * - price [numeric]
 * - startDate [date]
 * - featured [checkbox]
 * - image [image]
 * - locations [manyToManyObjectRelation]
 */

return \OpenDxp\Model\DataObject\ClassDefinition::__set_state(array(
   'dao' => NULL,
   'id' => 'course',
   'name' => 'Course',
   'title' => '',
   'description' => '',
   'creationDate' => NULL,
   'modificationDate' => 1793534464,
   'userOwner' => NULL,
   'userModification' => 0,
   'parentClass' => '',
   'implementsInterfaces' => NULL,
   'listingParentClass' => '',
   'useTraits' => '',
   'listingUseTraits' => '',
   'encryption' => false,
   'encryptedTables' => 
  array (
  ),
   'allowInherit' => false,
   'allowVariants' => false,
   'showVariants' => false,
   'layoutDefinitions' => 
  \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'name' => 'root',
     'type' => NULL,
     'region' => NULL,
     'title' => NULL,
     'width' => 0,
     'height' => 0,
     'collapsible' => false,
     'collapsed' => false,
     'bodyStyle' => NULL,
     'datatype' => 'layout',
     'permissions' => NULL,
     'children' => 
    array (
      0 => 
      \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
         'name' => 'main',
         'type' => NULL,
         'region' => NULL,
         'title' => 'Daten',
         'width' => 0,
         'height' => 0,
         'collapsible' => false,
         'collapsed' => false,
         'bodyStyle' => NULL,
         'datatype' => 'layout',
         'permissions' => NULL,
         'children' => 
        array (
          0 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
             'name' => 'localizedfields',
             'title' => NULL,
             'tooltip' => NULL,
             'mandatory' => false,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'children' => 
            array (
              0 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'title',
                 'title' => 'Titel',
                 'tooltip' => NULL,
                 'mandatory' => true,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => NULL,
                 'permissions' => NULL,
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => true,
                 'visibleSearch' => true,
                 'blockedVarsForExport' => 
                array (
                ),
                 'defaultValue' => NULL,
                 'columnLength' => 190,
                 'regex' => '',
                 'regexFlags' => 
                array (
                ),
                 'unique' => false,
                 'showCharCount' => false,
                 'width' => NULL,
                 'defaultValueGenerator' => '',
              )),
              1 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                 'name' => 'teaser',
                 'title' => 'Kurzbeschreibung',
                 'tooltip' => NULL,
                 'mandatory' => true,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => NULL,
                 'permissions' => NULL,
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => true,
                 'visibleSearch' => true,
                 'blockedVarsForExport' => 
                array (
                ),
                 'maxLength' => 240,
                 'showCharCount' => false,
                 'excludeFromSearchIndex' => false,
                 'height' => NULL,
                 'width' => NULL,
              )),
              2 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Wysiwyg::__set_state(array(
                 'name' => 'description',
                 'title' => 'Beschreibung',
                 'tooltip' => NULL,
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => NULL,
                 'permissions' => NULL,
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => true,
                 'visibleSearch' => true,
                 'blockedVarsForExport' => 
                array (
                ),
                 'toolbarConfig' => '',
                 'excludeFromSearchIndex' => false,
                 'maxCharacters' => 0,
                 'height' => NULL,
                 'width' => NULL,
              )),
            ),
             'region' => NULL,
             'layout' => NULL,
             'maxTabs' => NULL,
             'border' => false,
             'provideSplitView' => false,
             'tabPosition' => 'top',
             'hideLabelsWhenTabsReached' => NULL,
             'referencedFields' => 
            array (
            ),
             'permissionView' => NULL,
             'permissionEdit' => NULL,
             'labelWidth' => 170,
             'labelAlign' => 'left',
             'width' => NULL,
             'height' => NULL,
             'fieldDefinitionsCache' => NULL,
          )),
          1 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
             'name' => 'slug',
             'title' => 'URL-Slug',
             'tooltip' => 'Kleinbuchstaben, Zahlen und Bindestriche, z. B. web-entwicklung',
             'mandatory' => true,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'defaultValue' => NULL,
             'columnLength' => 190,
             'regex' => '^[a-z0-9]+(-[a-z0-9]+)*$',
             'regexFlags' => 
            array (
            ),
             'unique' => true,
             'showCharCount' => false,
             'width' => NULL,
             'defaultValueGenerator' => '',
          )),
          2 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'category',
             'title' => 'Kategorie',
             'tooltip' => NULL,
             'mandatory' => true,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'options' => 
            array (
              0 => 
              array (
                'key' => 'Informatik',
                'value' => 'it',
              ),
              1 => 
              array (
                'key' => 'Technik',
                'value' => 'engineering',
              ),
              2 => 
              array (
                'key' => 'Wirtschaft',
                'value' => 'business',
              ),
              3 => 
              array (
                'key' => 'Gestaltung',
                'value' => 'design',
              ),
            ),
             'defaultValue' => NULL,
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => NULL,
             'optionsProviderType' => NULL,
             'optionsProviderClass' => NULL,
             'optionsProviderData' => NULL,
          )),
          3 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'format',
             'title' => 'Form',
             'tooltip' => NULL,
             'mandatory' => true,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'options' => 
            array (
              0 => 
              array (
                'key' => 'Berufsbegleitend',
                'value' => 'part-time',
              ),
              1 => 
              array (
                'key' => 'Vollzeit',
                'value' => 'full-time',
              ),
            ),
             'defaultValue' => NULL,
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => NULL,
             'optionsProviderType' => NULL,
             'optionsProviderClass' => NULL,
             'optionsProviderData' => NULL,
          )),
          4 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
             'name' => 'durationSemesters',
             'title' => 'Dauer (Semester)',
             'tooltip' => NULL,
             'mandatory' => true,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'defaultValue' => NULL,
             'integer' => true,
             'unsigned' => true,
             'minValue' => NULL,
             'maxValue' => NULL,
             'unique' => false,
             'decimalSize' => NULL,
             'decimalPrecision' => NULL,
             'width' => NULL,
             'defaultValueGenerator' => '',
          )),
          5 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
             'name' => 'price',
             'title' => 'Preis (CHF)',
             'tooltip' => NULL,
             'mandatory' => false,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'defaultValue' => NULL,
             'integer' => true,
             'unsigned' => true,
             'minValue' => NULL,
             'maxValue' => NULL,
             'unique' => false,
             'decimalSize' => NULL,
             'decimalPrecision' => NULL,
             'width' => NULL,
             'defaultValueGenerator' => '',
          )),
          6 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Date::__set_state(array(
             'name' => 'startDate',
             'title' => 'Nächster Start',
             'tooltip' => NULL,
             'mandatory' => true,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'defaultValue' => NULL,
             'useCurrentDate' => false,
             'columnType' => 'bigint(20)',
             'defaultValueGenerator' => '',
          )),
          7 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
             'name' => 'featured',
             'title' => 'Auf der Startseite hervorheben',
             'tooltip' => NULL,
             'mandatory' => false,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'defaultValue' => NULL,
             'defaultValueGenerator' => '',
          )),
          8 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
             'name' => 'image',
             'title' => 'Bild',
             'tooltip' => NULL,
             'mandatory' => false,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => false,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'uploadPath' => '/Kurse',
             'width' => NULL,
             'height' => NULL,
          )),
          9 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation::__set_state(array(
             'name' => 'locations',
             'title' => 'Standorte',
             'tooltip' => NULL,
             'mandatory' => true,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => NULL,
             'permissions' => NULL,
             'relationType' => true,
             'invisible' => false,
             'visibleGridView' => true,
             'visibleSearch' => true,
             'blockedVarsForExport' => 
            array (
            ),
             'classes' => 
            array (
              0 => 
              array (
                'classes' => 'Location',
              ),
            ),
             'displayMode' => NULL,
             'pathFormatterClass' => NULL,
             'maxItems' => NULL,
             'visibleFields' => NULL,
             'allowToCreateNewObject' => true,
             'allowToClearRelation' => true,
             'optimizedAdminLoading' => false,
             'enableTextSelection' => false,
             'visibleFieldDefinitions' => 
            array (
            ),
             'width' => NULL,
             'height' => NULL,
          )),
        ),
         'locked' => false,
         'blockedVarsForExport' => 
        array (
        ),
         'fieldtype' => 'panel',
         'layout' => NULL,
         'border' => false,
         'icon' => NULL,
         'labelWidth' => 170,
         'labelAlign' => 'left',
      )),
    ),
     'locked' => false,
     'blockedVarsForExport' => 
    array (
    ),
     'fieldtype' => 'panel',
     'layout' => NULL,
     'border' => false,
     'icon' => NULL,
     'labelWidth' => 100,
     'labelAlign' => 'left',
  )),
   'icon' => '/bundles/opendxpadmin/img/flat-color-icons/graduation_cap.svg',
   'group' => NULL,
   'showAppLoggerTab' => false,
   'linkGeneratorReference' => NULL,
   'previewGeneratorReference' => NULL,
   'compositeIndices' => 
  array (
  ),
   'showFieldLookup' => false,
   'propertyVisibility' => 
  array (
    'grid' => 
    array (
      'id' => true,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
    'search' => 
    array (
      'id' => true,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
  ),
   'enableGridLocking' => false,
   'deletedDataComponents' => 
  array (
  ),
   'blockedVarsForExport' => 
  array (
  ),
   'fieldDefinitionsCache' => 
  array (
  ),
   'activeDispatchingEvents' => 
  array (
  ),
));
