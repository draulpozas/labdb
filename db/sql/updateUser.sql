UPDATE `labdb`.`user` 
SET 
    `username` = '{{username}}',
    `passwd` = '{{passwd}}',
    `role` = '{{role}}',
    `lang` = '{{lang}}'
WHERE
    (`id` = '{{id}}');
