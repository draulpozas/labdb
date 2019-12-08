UPDATE `labdb0_0_4`.`laboratory` 
SET 
    `name` = '{{name}}',
    `manager` = '{{manager}}',
    `manager_email` = '{{manager_email}}'
WHERE
    (`id` = '{{id}}');
