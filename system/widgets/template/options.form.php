<?php

class formWidgetTemplateOptions extends cmsForm {

    public function init($options, $template_name) {

        return array(

            array(
                'type' => 'fieldset',
                'title' => LANG_OPTIONS,
                'childs' => array(

                    new fieldList('options:type', array(
                        'title' => LANG_WD_T_TYPE,
                        'items' => [
                            'body' => LANG_PAGE_BODY,
                            'breadcrumbs' => LANG_PAGE_BREADCRUMB,
                            'smessages' => LANG_WD_T_SMESSAGES
                        ]
                    )),

                    new fieldList('options:session_type', array(
                        'title' => LANG_WD_T_SESSION_TYPE,
                        'items' => [
                            'on_position' => LANG_WD_T_SESSION_TYPE1,
                            'toastr' => LANG_WD_T_SESSION_TYPE2
                        ],
                        'visible_depend' => array('options:type' => array('show' => array('smessages')))
                    )),

                    new fieldList('options:breadcrumbs:template', array(
                        'title' => LANG_WD_T_BTEMPLATE,
                        'hint'  => LANG_WD_T_BTEMPLATE_HINT,
                        'generator' => function($item) use ($template_name) {
                            return cmsTemplate::getInstance()->getAvailableTemplatesFiles('assets/ui', 'breadcrumbs*.tpl.php', $template_name);
                        },
                        'visible_depend' => array('options:type' => array('show' => array('breadcrumbs')))
                    )),

                    new fieldCheckbox('options:breadcrumbs:strip_last', array(
                        'title' => LANG_WD_T_STRIP_LAST,
                        'visible_depend' => array('options:type' => array('show' => array('breadcrumbs')))
                    ))

                )
            )

        );

    }

}
