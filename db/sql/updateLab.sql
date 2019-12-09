UPDATE `labdb`.`lab` 
SET 
    `name` = '{{name}}',
    `manager` = '{{manager}}',
    `manager_email` = '{{manager_email}}'
WHERE
    (`id` = '{{id}}');
