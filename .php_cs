<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'align_double_arrow' => true,
        'align_equals' => true,
    ],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_return' => true,
    'blank_line_before_statement' => true,
    'braces' => true,
    'cast_spaces' => true,
    'class_attributes_separation' => true,
    'class_definition' => true,
    'combine_consecutive_unsets' => true,
    'concat_space' => true,
    'declare_equal_normalize' => true,
    'elseif' => true,
    'encoding' => true,
    'full_opening_tag' => true,
    'fully_qualified_strict_types' => true, // added by Shift
    'function_declaration' => true,
    'function_typehint_space' => true,
    'heredoc_to_nowdoc' => true,
    'include' => true,
    'increment_style' => ['style' => 'post'],
    'indentation_type' => true,
    'line_ending' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => ['syntax' => 'short'],
    'lowercase_cast' => true,
    'lowercase_constants' => true,
    'lowercase_keywords' => true,
    'lowercase_static_reference' => true, // added from Symfony
    'magic_constant_casing' => true,
    'magic_method_casing' => true, // added from Symfony
    'method_argument_space' => true,
    'method_separation' => true,
    'multiline_whitespace_before_semicolons' => true,
    'native_function_casing' => true,
    'new_with_braces' => true,
    'no_alias_functions' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_closing_tag' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => true,
    'no_extra_consecutive_blank_lines' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_mixed_echo_print' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_bool_cast' => true,
    'no_short_echo_tag' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_around_offset' => true,
    'no_spaces_inside_parenthesis' => true,
    'no_trailing_comma_in_list_call' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_trailing_whitespace_in_comment' => true,
    'no_trailing_whitespace' => true,
    'no_unneeded_control_parentheses' => true,
    'no_unreachable_default_argument_value' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'normalize_index_brace' => true,
    'not_operator_with_successor_space' => true,
    'object_operator_without_whitespace' => true,
    'ordered_imports' => ['sortAlgorithm' => 'alpha'],
    'php_unit_strict' => true,
    'php_unit_test_class_requires_covers' => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_align' => true,
    'phpdoc_indent' => true,
    'phpdoc_inline_tag' => true,
    'phpdoc_no_access' => true,
    'phpdoc_no_package' => true,
    'phpdoc_no_useless_inheritdoc' => true,
    'phpdoc_order' => true,
    'phpdoc_scalar' => true,
    'phpdoc_separation' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_var_without_name' => true,
    'psr4' => true,
    'self_accessor' => true,
    'semicolon_after_instruction' => true,
    'short_scalar_cast' => true,
    'simplified_null_return' => true,
    'single_blank_line_at_eof' => true,
    'single_blank_line_before_namespace' => true,
    'single_class_element_per_statement' => true,
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'single_line_comment_style' => [
        'comment_types' => ['hash']
    ],
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'strict_comparison' => true,
    'strict_param' => true,
    'switch_case_semicolon_to_colon' => true,
    'switch_case_space' => true,
    'ternary_operator_spaces' => true,
    'trailing_comma_in_multiline_array' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'visibility_required' => true,
    'whitespace_after_comma_in_array' => true,
];

$finder = Finder::create()
    ->notPath('bootstrap')
    ->notPath('storage')
    ->notPath('vendor')
    ->in(getcwd())
    ->name('*.php')
    ->notName('*.blade.php')
    ->notName('index.php')
    ->notName('server.php')
    ->notName('_ide_helper.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Config::create()
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
