(function (blocks, element, i18n) {
    var config = window.CFSBlockEditor || {};
    var groups = config.groups || [];
    var createElement = element.createElement;
    var __ = i18n.__;

    groups.forEach(function (group) {
        blocks.registerBlockType(group.name, {
            apiVersion: 3,
            title: group.blockTitle,
            description: config.description || __('Displays a CFS field group.', 'cfs'),
            category: 'cfs',
            icon: 'feedback',
            attributes: {
                groupId: {
                    type: 'number',
                    default: group.id
                }
            },
            supports: {
                html: false,
                reusable: false
            },
            edit: function () {
                return createElement(
                    'div',
                    { className: 'cfs-block-editor-placeholder' },
                    createElement('strong', {}, group.title),
                    createElement(
                        'span',
                        {},
                        (config.fieldGroup || __('Field Group', 'cfs')) + ' / ' +
                            (config.fieldCount || __('Fields', 'cfs')) + ': ' + group.fieldCount
                    ),
                    0 === group.fieldCount ? createElement('em', {}, config.noFields || __('No fields in this group.', 'cfs')) : null
                );
            },
            save: function () {
                return null;
            }
        });
    });
})(window.wp.blocks, window.wp.element, window.wp.i18n);
