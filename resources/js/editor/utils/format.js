export const beautifyHTML = (code) => {
    return html_beautify(code, {
        indent_size: 4,
        indent_char: ' ',
        max_preserve_newlines: 2,
        preserve_newlines: true,
        keep_array_indentation: false,
        break_chained_methods: false,
        indent_scripts: 'normal',
        brace_style: 'collapse',
        space_before_conditional: true,
        unescape_strings: false,
        jslint_happy: false,
        end_with_newline: true,
        wrap_line_length: 0,
        indent_inner_html: true,
        comma_first: false,
        e4x: false,
        indent_empty_lines: false
    });
};

export const beautifyCSS = (code) => {
    return css_beautify(code, {
        indent_size: 4,
        indent_char: ' ',
        max_preserve_newlines: 2,
        preserve_newlines: true,
        indent_with_tabs: false,
        selector_separator_newline: true,
        end_with_newline: true,
        newline_between_rules: true
    });
};

export const formatPluginName = (name) => {
    return name.split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join('');
};
